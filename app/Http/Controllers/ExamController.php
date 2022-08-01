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
use App\Models\ExamRecord;
use App\Models\Exam;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Traits\StudentTrait;

class ExamController extends Controller
{
    use StudentTrait;
    public function addExam(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('addExams'))
        {
            $rules = [
                'examName' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Exam::where('exam_name', '=', $input->examName)->count() == 0)
                {
                    $exam = new Exam;
                    $exam->exam_name=$input->examName;
                    $exam->user_id=$user->id;
                    $exam->admin_id=$user->admin_id;
                    if($exam->save())
                    {
                       $examId=$this->encryptData($exam->id);
                       Exam::where('id',$exam->id)->update(array('encrypt_id' => $examId));

                        $output['status']=true;
                        $output['message']='Exam Successfully Added';
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
    public function deleteExam($examId)
    {

        $user=Auth::User();
        if($user->hasPermissionTo('deleteExams'))
        {
            if (Exam::where('id', '=', $examId)->where('deleteStatus',0)->count() == 1)
            {
                Exam::where('id',$examId)->update(array('deleteStatus' => 1));
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
    public function getExam($examId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('editExams'))
        {
            $exam = Exam::where('id',$examId)->where('deleteStatus',0)->first(['id', 'exam_name']);
            if (isset($exam)) {
                $output['status'] = true;
                $output['response'] = $exam;
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
    public function getAllExams()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('viewExams'))
        {
           // $section = Section::all(['encrypt_id AS section_id', 'section_name']);
            $exam = Exam::where('deleteStatus',0)->where('user_id',$user->id)->where('admin_id',$user->admin_id)->paginate(10,['id', 'exam_name']);
            if (isset($exam))
            {
                $output['status'] = true;
                $output['response'] = $exam;
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
    public function listAllExams()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('listExams'))
        {
            $exam = Exam::where('deleteStatus',0)->where('user_id',$user->id)->get(['id', 'exam_name']);
            if (isset($exam)) {
                $output['status'] = true;
                $output['response'] = $exam;
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
    public function updateExam(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('editExams'))
        {
            $rules = [
                'examName' => 'required',
                'editId' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Exam::where('id', '=', $input->editId)->where('user_id',$user->id)->where('deleteStatus',0)->count() == 1)
                {
                    Exam::where('id',$input->editId)->update(array('exam_name' => $input->examName));
                    $output['status']=true;
                    $output['message']='Exam Successfully Updated';
                    $response['data']=$this->encryptData($output);
                    $code = 200;
                }
                else
                {
                    $output['statu']=strue;
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
    public function addExamRecords(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('addExams'))
        {
            $rules = [
                'examId' => 'required',
                'classId' => 'required',
                'subjectId' => 'required',
                'date' => 'required | date',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (ExamRecord::where('exam_id', '=', $input->examId)->where('subject_id','=',$input->subjectId)->where('class_id','=',$input->classId)->count() == 0)
                {
                    $exam = new ExamRecord;
                    $exam->exam_id=$input->examId;
                    $exam->class_id=$input->classId;
                    $exam->subject_id=$input->subjectId;
                    $exam->date=date('Y-m-d',strtotime($input->date));
                    $exam->user_id=$user->id;
                    $exam->admin_id=$user->admin_id;
                    if($exam->save())
                    {
                        $output['status']=true;
                        $output['message']='Exam Record Successfully Added';
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

    public function deleteExamRecord($examId)
    {

        $user=Auth::User();
        if($user->hasPermissionTo('deleteExams'))
        {
            if (ExamRecord::where('id', '=', $examId)->where('deleteStatus',0)->count() == 1)
            {
                ExamRecord::where('id',$examId)->update(array('deleteStatus' => 1));
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

    public function getExamRecord($examId)
    {

        $user=Auth::User();
        if($user->hasPermissionTo('editExams'))
        {
            $exam = ExamRecord::with(['subject','class_','exam'])->where('id','=',$examId)->where('user_id','=',$user->id)->where('deleteStatus',0)->where('admin_id',$user->admin_id)->get();
            if (isset($exam)) {
                $output['status'] = true;
                $output['response'] = $exam;
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
    public function getAllExamRecord()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('viewExams'))
        {
           // $section = Section::all(['encrypt_id AS section_id', 'section_name']);
            $exam = ExamRecord::where('deleteStatus',0)->where('user_id',$user->id)->where('admin_id',$user->admin_id)->paginate(10);
            if (isset($exam)) {

                $output['status'] = true;
                $output['response'] = $exam;
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
    public function listAllExamRecords()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('listExams'))
        {
            $exam = ExamRecord::where('deleteStatus',0)->where('user_id',$user->id)->get();
            if (isset($exam)) {
                $output['status'] = true;
                $output['response'] = $exam;
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
    public function updateExamRecord(Request $request)
    {

        $user=Auth::User();
        if($user->hasPermissionTo('editExams'))
        {
            $rules = [
                'examId' => 'required',
                'classId' => 'required',
                'subjectId' => 'required',
                'date' => 'required | date',
                'editId' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {

                if (ExamRecord::where('id', '=', $input->editId)->where('user_id',$user->id)->where('deleteStatus',0)->count() == 1)
                {
                    ExamRecord::where('id',$input->editId)->update(array('exam_id' => $input->examId,'class_id' =>  $input->classId,'subject_id' =>  $input->subjectId,'date' => date('Y-m-d',strtotime($input->date))));
                    $output['status']=true;
                    $output['message']='Exam Record Successfully Updated';
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
