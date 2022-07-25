<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\user;
use App\Models\section;
use App\Models\Fee;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;
use Hash;
use Input;
use App\Http\Traits\StudentTrait;
class StudentAttendance extends Controller
{
     use StudentTrait;
    public function putAttendance()
    {
        $user=Auth::user();
        if($user->hasPermissionTo('putAttendance'))
        {
            $this->check_attendance($user->class_id);
            $status=0;
            $input=$this->decrypt($request->input('input'));
            if(count((array)$input) > 0)
            {
                for($i=0;$i<count($input);$i++)
                {
                    $validate_status=$this->validate_attendance($input[$i]);
                    if($validate_status == 0)
                    {
                        $status=1;
                    }
                    $input[$i]['class_id']=$user->class_id;
                    $input[$i]['date']=date('Y-m-d');
                    $input[$i]['student_encrypt_id']=$this->encrypt($input[$i]['student_id']);
                    $input[$i]['encrypt_class_id']=$this->encrypt($input[$i]['class_id']);
                }
            }
            if($validate_status == 1)
            {
                StudentAttendance::insert($input);
                $output['status'] = true;
                $output['message'] = 'Attendance Recorded';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'Invalid Data';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
        }
        else
        {
            $output['status'] = false;
            $output['message'] = 'UnAuthorized Access';
            $response['data']=$this->encryptData($output);
            $code=200;
        }
    }
    public function validate_attendance($input)
    {
        $rules = [
            'studentId' => 'required',
            'present' => 'required|boolean',
        ];
        $validator = Validator::make((array)$input, $rules);
        if(!$validator->fails())
        {
            return 1;
        }
        else if($validator->fails())
        {
            return 0;
        }

    }
    public function check_attendance($class_id)
    {
        if (StudentAttendance::where('date', '=', date('Y-m-d'))->where('class_id',$class_id)->count() == 1)
        {
            DB::table('student_attendances')->where('class_id', $class_id)->where('date',date('Y-m-d'))->delete();
        }
        return 0;
    }


}
