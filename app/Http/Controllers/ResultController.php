<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;
use Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\user;
use App\Models\standard;
use App\Models\student;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Traits\StudentTrait;

class ResultController extends Controller
{
    public function addResultRecord(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('addResults'))
        {
            $rules = [
                'studentId' => 'required',
                'subjectId' => 'required',
                'examId' => 'required',
                'mark' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Result::where('id', '=', $this->decrypt($input->studentId))->where('class_id','=',$user->class_id)->count() == 1)
                {
                    $result = new Result;
                    $result->student_id=$this->decrypt($input->studentId);
                    $result->encrypt_student_id=$input->studentId;
                    $result->encrypt_class_id=$input->classId;
                    $result->encrypt_subject_id=$input->subjectId;
                    $result->exam_id=$this->decrypt($input->examId);
                    $result->mark=$input->examId;
                    $result->subject_id=$this->decrypt($input->subjectId);
                    $result->class_id=$user->class_id;
                    $result->active=$input->active;
                    $result->user_id=$user->id;
                    if($result->save())
                    {
                       $resultId=$this->encryptData($result->id);
                       Result::where('id',$result->id)->update(array('encrypt_id' => $resultId));
                        $output['status']=true;
                        $output['message']='Result Successfully Updated';
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
    public function deleteResultRecord($resultId)
    {
        $resultId=$this->decrypt($resultId);
        $user=Auth::User();
        if($user->hasPermissionTo('deleteResults'))
        {
            if (Result::where('id', '=', $resultId)->where('deleteStatus',0)->where('user_id','=',$user->id)->count() == 1)
            {
                Result::where('id',$resultId)->where('user_id','=',$user->id)->update(array('deleteStatus' => 1));
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
    public function getResultRecord($resultId)
    {
        $resultId=$this->decrypt($resultId);
        $user=Auth::User();
        if($user->hasPermissionTo('editResults'))
        {
            $result = Result::with(['_class','student','exam','subject'])->where('id','=',$resultId)->where('user_id','=',$user->id)->get();

            if (isset($result->id)) {
                $output['status'] = true;
                $output['response'] = $result;
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
    public function getAllResults($examId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('viewSections'))
        {
            $result = Result::with(['_class','student','exam','subject'])->where('exam_id','=',$examId)->where('user_id','=',$user->id)->get();

            if (isset($result->id)) {

                $output['status'] = true;
                $output['response'] = $result;
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
    public function updateResult(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('editResults'))
        {
            $rules = [
                'studentId' => 'required',
                'subjectId' => 'required',
                'examId' => 'required',
                'mark' => 'required',
                'editId' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Result::where('id', '=', $this->decrypt($input->editId))->where('user_id',$user->id)->where('deleteStatus',0)->count() == 1)
                {
                    Result::where('id',$this->decrypt($input->editId))->update(array('student_id' => $this->decrypt($input->studentId),'class_id' => $user->class_id,'subject_id' => $this->decrypt($input->subjectId),'subject_id' => $this->decrypt($input->subjectId),'exam_id' => $this->decrypt($input->examId),'mark' => $input->mark,'student_encrypt_id' => $input->student_id,'class_encrypt_id' => $this->encrypt($user->class_id),'exam_encrypt_id' => $input->exam_id,'subject_encrypt_id' => $input->subject_id));
                    $output['status']=true;
                    $output['message']='Result Successfully Updated';
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
