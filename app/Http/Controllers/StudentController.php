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

class StudentController extends Controller
{
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
                'student_id' => 'required',
                'email' => 'required|email|unique:users,email',
                'dob' => 'date_format:Y-m-d',
                'password' => 'required',
                'parent_or_guardian' => 'required',
                'parent_or_guardian_phone' => 'required',
                'classId' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Student::where('student_id', '=', $input->student_id)->count() == 0)
                {
                    $student = new Student;
                    $student->name=$input->name;
                    $student->address=$input->address;
                    $student->gender=$input->gender;
                    $student->city=$input->city;
                    $student->phone=$input->phone;
                    $student->student_id=$input->student_id;
                    $student->email=$input->email;
                    $student->dob=date('Y-m-d',strtotime($input->dob));
                    $student->parent_or_guardian=$input->parent_or_guardian;
                    $student->password=$input->password;
                    $student->parent_or_guardian_phone=$input->parent_or_guardian_phone;
                    $student->user_id=$user->id;
                    $student->admin_id=$user->admin_id;
                    $student->class_id=$this->decrypt($input->classId);
                    if($student->save())
                    {
                       $userId=$this->encryptData($student->id);
                       Student::where('id',$student->id)->update(array('encrypt_id' => $userId));
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
        $studentId=$this->decrypt($studentId);
        $user=Auth::User();
        if($user->hasPermissionTo('deleteStudents'))
        {
            if($user->hasRole('TeachingStaff'))
            {
                $recordCount=Student::where('id', '=', $studentId)->where('user_id','=',$user->id)->count();
            }

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
    }
    public function getStudentRecord($studentId)
    {
        $studentId=$this->decrypt($studentId);
        $user=Auth::User();
        if($user->hasPermissionTo('editStudents'))
        {
            if($user->hasRole('TeachingStaff'))
            {
                $student = Student::where('id',$studentId)->where('user_id',$user->id)->where('deleteStatus',0)->first();
            }

            if ($student) {
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
            if($user->hasRole('Admin'))
            {
                $student = Student::where('admin_id','=',$user->id)->paginate(10);
            }
            if($user->hasRole('TeachingStaff'))
            {
                $student = Student::where('user_id','=',$user->id)->paginate(10);
            }

            if ($student) {
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
