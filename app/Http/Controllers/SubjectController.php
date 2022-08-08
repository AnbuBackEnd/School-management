<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Section;
use App\Models\Subject;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;
use Hash;
use Input;
use App\Http\Traits\StudentTrait;
class SubjectController extends Controller
{
    use StudentTrait;
    public function addSubjectEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subjectName' => 'required',
            'totalMarks' => 'required',
        ]);


        if(!$validator->fails())
        {
            $response['data']=$this->encryptData(json_encode($request->all()));
            //$response=$this->encrypt($output);
            $code = 200;
        }
        else
        {
            $response['message']=[$validator->errors()->first()];
        // $response=$this->encrypt($output);
            $code = 200;
        }
        return response($response, $code);
    }
    public function addSubject(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('addSubjects'))
        {
            $rules = [
                'subjectName' => 'required',
                'totalMarks' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if(Subject::where('subject_name', '=', $input->subjectName)->where('user_id','=',$user->id)->where('deleteStatus',0)->count() == 0)
                {
                    $subject = new Subject;
                    $subject->subject_name=$input->subjectName;
                    $subject->total_marks=$input->totalMarks;
                    $subject->user_id=$user->id;
                    $subject->admin_id=$user->id;
                    if($subject->save())
                    {
                        $output['status']=true;
                        $output['message']='Subject Successfully Added';
                        $response['data']=$this->encryptData($output);
                        $code = 200;
                    }
                    else
                    {
                        $output['status']=true;
                        $output['message']='Something went wrong. Please try again later.';
                        $response['data']=$this->encryptData($output);
                        $code = 400;
                    }
                }
                else
                {
                    $output['status']=false;
                    $output['message']='Already Exists';
                    $response['data']=$this->encryptData($output);
                    $code = 409;
                }
            }
            else
            {
                $output['status']=false;
                $output['message']=[$validator->errors()->first()];
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function deleteSubject($subjectId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('deleteSubjects'))
        {
            if (Subject::where('id', '=', $subjectId)->where('deleteStatus',0)->count() == 1)
            {
                Subject::where('id',$subjectId)->update(array('deleteStatus' => 1));
                $output['status'] = true;
                $output['message'] = 'Successfully Deleted';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function getSubjectRecord($subjectId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('editSubjects'))
        {
            $subject = Subject::where('id',$subjectId)->where('deleteStatus',0)->where('user_id',$user->id)->first(['id AS subject_id', 'subject_name','total_marks']);

            if (isset($subject)) {
                $output['status'] = true;
                $output['message'] = 'Successfully Retrieved';
                $output['response'] = $subject;
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function getAllSubjects()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('viewSubjects'))
        {
            $subject = Subject::where('user_id','=',$user->id)->where('deleteStatus',0)->paginate(10,['id AS subject_id', 'subject_name','total_marks']);
            if (isset($subject)) {

                $output['status'] = true;
                $output['response'] = $subject;
                $output['message'] = 'Successfully Retrieved';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function listAllSubjects()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('listSubjects'))
        {
            $subject = Subject::where('user_id','=',$user->id)->where('deleteStatus',0)->get(['id AS subject_id', 'subject_name','total_marks']);
            if (isset($subject)) {
                $output['status'] = true;
                $output['response'] = $subject;
                $output['message'] = 'Successfully Retrieved';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }


        return response($response, $code);
    }
    public function updateSubjectsEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subjectName' => 'required',
            'subjectId' => 'required',
            'totalMarks' => 'required',
        ]);


        if(!$validator->fails())
        {
            $response['data']=$this->encryptData(json_encode($request->all()));
            //$response=$this->encrypt($output);
            $code = 200;
        }
        else
        {
            $response['message']=[$validator->errors()->first()];
        // $response=$this->encrypt($output);
            $code = 200;
        }
        return response($response, $code);
    }
    public function updateSubjects(Request $request)
    {

        $user=Auth::User();
        if($user->hasPermissionTo('editSubjects'))
        {
            $rules = [
                'subjectName' => 'required',
                'subjectId' => 'required',
                'totalMarks' => 'required',

            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Subject::where('id', '=', $this->decrypt($input->subjectId))->where('user_id','=',$user->id)->where('deleteStatus',0)->count() == 1)
                {
                    Subject::where('id',$this->decrypt($input->subjectId))->update(array('subject_name' => $input->subjectName,'total_marks' => $input->totalMarks));
                    $output['status']=true;
                    $output['message']='Subject Successfully Updated';
                    $response['data']=$this->encryptData($output);
                    $code = 200;
                }
                else
                {
                    $output['status']=true;
                    $output['message']='Something went wrong. Please try again later.';
                    $response['data']=$this->encryptData($output);
                    $code = 400;
                }
            }
            else
            {
                $output['status']=false;
                $output['message']=[$validator->errors()->first()];
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);

    }
}
