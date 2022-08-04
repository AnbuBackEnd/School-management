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
use App\Models\FeesStructureCatagory;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Traits\StudentTrait;

class StudentController extends Controller
{
    use StudentTrait;
    public function addStudentEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'gender' => 'required | numeric',
            'city' => 'required',
            'phone' => 'required',
            'studentNo' => 'required',
            'email' => 'required|email|unique:users,email',
            'dob' => 'date_format:Y-m-d',
            'password' => 'required',
            'parent_or_guardian' => 'required',
            'classId' => 'required',
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
    public function addStudent(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('addStudents'))
        {

            $rules = [
                'name' => 'required',
                'address' => 'required',
                'gender' => 'required | numeric',
                'city' => 'required',
                'phone' => 'required',
                'studentNo' => 'required',
                'email' => 'required|email|unique:users,email',
                'dob' => 'date_format:Y-m-d',
                'password' => 'required',
                'parent_or_guardian' => 'required',
                'classId' => 'required',
            ];


            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);

            if(!$validator->fails())
            {
                if (Student::where('email', '=', $input->email)->count() == 0)
                {
                    $student = new Student;
                    $student->name=$input->name;
                    $student->address=$input->address;
                    $student->gender=$input->gender;
                    $student->city=$input->city;
                    $student->phone=$input->phone;
                    $student->student_id=$input->studentNo;
                    $student->email=$input->email;
                    $student->dob=date('Y-m-d',strtotime($input->dob));
                    $student->parent_or_guardian=$input->parent_or_guardian;
                    $student->password=$input->password;
                    //$student->parent_or_guardian_phone=$input->parent_or_guardian_phone;
                    $student->user_id=$user->id;
                    $student->admin_id=$user->admin_id;
                    $student->class_id=$input->classId;

                    if($student->save())
                    {
                        $output['status']=true;
                        $output['message']='student Successfully Added';
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
    public function deleteStudent($studentId)
    {

        $user=Auth::User();
        if($user->hasPermissionTo('deleteStudents'))
        {

                $recordCount=Student::where('id', '=', $studentId)->where('user_id','=',$user->id)->count();
            if ($recordCount == 1)
            {
                Student::where('id',$studentId)->update(array('deleteStatus' => 1));
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
    public function getStudentRecord($studentId)
    {

        $user=Auth::User();
        if($user->hasPermissionTo('editStudents'))
        {
                $student = Student::where('id',$studentId)->where('user_id',$user->id)->where('admin_id',$user->admin_id)->where('deleteStatus',0)->first(['id','name','address','gender','city','phone','student_id AS student_no','email','dob','parent_or_guardian','class_id','admin_id','user_id']);
            if (isset($student)) {
                $output['status'] = true;
                $output['student'] = $student;

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
    public function getAllStudents()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('viewStudents'))
        {
            $student = Student::where('user_id','=',$user->id)->where('admin_id',$user->admin_id)->paginate(10);
            if (isset($student)) {
                $output['status'] = true;
                $output['student'] = $student;
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
    public function listAllStudents()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('viewStudents'))
        {
            $student = Student::where('user_id','=',$user->id)->where('admin_id',$user->admin_id)->get();
            if (isset($student)) {
                $output['status'] = true;
                $output['student'] = $student;
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

}
