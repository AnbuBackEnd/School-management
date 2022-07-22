<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\user;
use App\Models\section;
use App\Models\subject;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;
use Hash;
use Input;
use App\Http\Traits\StudentTrait;
class SubjectController extends Controller
{
    public function addSubject(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('addSubjects'))
        {
            $rules = [
                'subjectName' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Subject::where('subject_name', '=', $input->subjectName)->where('user_id','=',$user->id)->count() == 0)
                {
                    $subject = new Subject;
                    $subject->subject_name=$input->subjectName;
                    $subject->user_id=$user->id;
                    if($subject->save())
                    {
                       $subId=$this->encryptData($subject->id);
                       Subject::where('id',$subject->id)->update(array('encrypt_id' => $subId));
                        $output['status']=true;
                        $output['message']='Subject Successfully Added';
                        $output['userData']=$section;
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
        $subjectId=$this->decrypt($subjectId);
        $user=Auth::User();
        if($user->hasPermissionTo('deleteSubjects'))
        {
            if (Subject::where('id', '=', $subjectId)->count() == 1)
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
    }
    public function getSubjectRecord($subjectId)
    {
        $subjectId=$this->decrypt($subjectId);
        $user=Auth::User();
        if($user->hasPermissionTo('editSections'))
        {
            $subject = Subject::where('id',$subjectId)->first(['encrypt_id AS subject_id', 'subject_name']);

            if (isset($section->section_id)) {
                $output['status'] = true;
                $output['subject'] = $subject;
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
    public function getAllSubjects()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('viewSubjects'))
        {
           // $section = Section::all(['encrypt_id AS section_id', 'section_name']);
            $subject = Subject::where('user_id','=',$user->id)->paginate(10,['encrypt_id AS subject_id', 'subject_name']);
            if ($subject) {

                $output['status'] = true;
                $output['subject'] = $subject;
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
            $subject = Subject::all(['encrypt_id AS subject_id', 'subject_name']);
            if ($subject) {
                $output['status'] = true;
                $output['subject'] = $subject;
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
    public function updateSubjects(Request $request)
    {

        $user=Auth::User();
        if($user->hasPermissionTo('editSubjects'))
        {
            $rules = [
                'subjectName' => 'required',
                'subjectId' => 'required',

            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Subject::where('id', '=', $this->decrypt($input->subjectId))->where('user_id','=',$user->id)->count() == 1)
                {
                    Subject::where('id',$this->decrypt($input->subjectId))->update(array('subject_name' => $input->subjectName));
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
