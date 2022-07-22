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
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (User::where('student_id', '=', $input->student_id)->count() == 0)
                {
                    $user = new User;
                    $user->name=$input->name;
                    $user->address=$input->address;
                    $user->gender=$input->gender;
                    $user->city=$input->city;
                    $user->phone=$input->phone;
                    $user->student_id=$input->student_id;
                    $user->email=$input->email;
                    $user->dob=date('Y-m-d',strtotime($input->dob));
                    $user->parent_or_guardian=$input->parent_or_guardian;
                    $user->password=$input->password;
                    $user->parent_or_guardian_phone=$input->parent_or_guardian_phone;

                    if($user->save())
                    {
                       $userId=$this->encryptData($user->id);
                       User::where('id',$user->id)->update(array('encrypt_id' => $userId));
                        $output['status']=true;
                        $output['message']='student Successfully Added';
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
    public function deleteStudent($studentId)
    {
        $studentId=$this->decrypt($studentId);
        $user=Auth::User();
        if($user->hasPermissionTo('deleteStudents'))
        {
            if (User::where('id', '=', $studentId)->count() == 1)
            {
                User::where('id',$studentId)->update(array('deleteStatus' => 1));
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
}
