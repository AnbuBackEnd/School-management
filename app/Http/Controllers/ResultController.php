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
use App\Models\result;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Traits\StudentTrait;

class ResultController extends Controller
{
    use StudentTrait;
public function addResultRecordEncrypt(Request $request)
{
    $validator = Validator::make($request->all(), [
        'studentId' => 'required',
        'subjectId' => 'required',
        'examId' => 'required',
        'mark' => 'required',
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
                if (Result::where('student_id', '=', $input->studentId)->where('subject_id',$input->subjectId)->where('class_id','=',$user->class_id)->where('exam_id',$input->examId)->count() == 0)
                {
                    $result = new Result;
                    $result->student_id=$input->studentId;
                    $result->exam_id=$input->examId;
                    $result->mark=$input->mark;
                    $result->subject_id=$input->subjectId;
                    $result->class_id=$user->class_id;
                    $result->user_id=$user->id;
                    if($result->save())
                    {

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
    // public function getResultRecord($resultId)
    // {
    //     $user=Auth::User();
    //     if($user->hasPermissionTo('editResults'))
    //     {
    //         $result = Result::with(['_class','student','exam','subject'])->where('id','=',$resultId)->where('user_id','=',$user->id)->get();

    //         if (isset($result)) {
    //             $output['status'] = true;
    //             $output['response'] = $result;
    //             $output['message'] = 'Successfully Retrieved';
    //             $response['data']=$this->encryptData($output);
    //             $code=200;
    //         }
    //         else
    //         {
    //             $output['status'] = false;
    //             $output['message'] = 'No Records Found';
    //             $response['data']=$this->encryptData($output);
    //             $code=400;
    //         }
    //     }
    //     else
    //     {
    //         $output['status']=false;
    //         $output['message']='Unauthorized Access';
    //         $response['data']=$this->encryptData($output);
    //         $code=400;
    //     }
    //     return response($response, $code);
    // }
    // public function getAllResults($examId)
    // {
    //     $user=Auth::User();
    //     if($user->hasPermissionTo('viewSections'))
    //     {
    //         $result = Result::with(['_class','student','exam','subject'])->where('exam_id','=',$examId)->where('user_id','=',$user->id)->get();

    //         if (isset($result->id)) {

    //             $output['status'] = true;
    //             $output['response'] = $result;
    //             $output['message'] = 'Successfully Retrieved';
    //             $response['data']=$this->encryptData($output);
    //             $code=200;
    //         }
    //         else
    //         {
    //             $output['status'] = false;
    //             $output['message'] = 'No Records Found';
    //             $response['data']=$this->encryptData($output);
    //             $code=400;
    //         }
    //     }
    //     else
    //     {
    //         $output['status']=false;
    //         $output['message']='Unauthorized Access';
    //         $response['data']=$this->encryptData($output);
    //         $code=400;
    //     }
    //     return response($response, $code);
    // }
    // public function updateResult(Request $request)
    // {
    //     $user=Auth::User();
    //     if($user->hasPermissionTo('editResults'))
    //     {
    //         $rules = [
    //             'studentId' => 'required',
    //             'subjectId' => 'required',
    //             'examId' => 'required',
    //             'mark' => 'required',
    //             'editId' => 'required',
    //         ];
    //         $input=$this->decrypt($request->input('input'));
    //         $validator = Validator::make((array)$input, $rules);
    //         if(!$validator->fails())
    //         {
    //             if (Result::where('id', '=', $input->editId)->where('user_id',$user->id)->where('deleteStatus',0)->count() == 1)
    //             {
    //                 Result::where('id',$input->editId)->update(array('student_id' => $input->studentId,'class_id' => $user->class_id,'subject_id' => $input->subjectId,'exam_id' => $input->examId,'mark' => $input->mark));
    //                 $output['status']=true;
    //                 $output['message']='Result Successfully Updated';
    //                 $response['data']=$this->encryptData($output);
    //                 $code = 200;
    //             }
    //             else
    //             {
    //                 $output['status']=true;
    //                 $output['message']='Something went wrong. Please try again later.';
    //                 $response['data']=$this->encryptData($output);
    //                 $code = 400;
    //             }
    //         }
    //         else
    //         {
    //             $output['status']=false;
    //             $output['message']=[$validator->errors()->first()];
    //             $response['data']=$this->encryptData($output);
    //             $code=400;
    //         }
    //     }
    //     else
    //     {
    //         $output['status']=false;
    //         $output['message']='Unauthorized Access';
    //         $response['data']=$this->encryptData($output);
    //         $code=400;
    //     }
    //     return response($response, $code);

    // }
}
