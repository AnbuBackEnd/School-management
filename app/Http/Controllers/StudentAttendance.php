<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\user;
use App\Models\section;
use App\Models\Fee;
use App\Models\studentAttendance as studentAtt;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Auth;
use Hash;
use Input;
use App\Http\Traits\StudentTrait;
class StudentAttendance extends Controller
{
     use StudentTrait;
    public function putAttendance(Request $request)
    {
        $user=Auth::user();
        if($user->hasPermissionTo('putAttendance'))
        {
            $this->check_attendance($user->class_id);
            $status=0;
            $input=(array)$this->decrypt($request->input('input'));

            $datafinal=array();
            if(count($input) > 0)
            {
                for($i=0;$i<count($input);$i++)
                {
                    $datafinal[$i]['date']=date('Y-m-d');
                    $datafinal[$i]['student_id']=$input[$i]->student_id;
                    $datafinal[$i]['class_id']=$input[$i]->class_id;
                    $datafinal[$i]['present']=$input[$i]->present;

                }

                studentAtt::insert($datafinal);
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
        return response($response, $code);
    }
    public function validate_attendance($input)
    {
        $rules = [
            'classId' => 'required',
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
        if (studentAtt::where('date', '=', date('Y-m-d'))->where('class_id',$class_id)->count() > 0)
        {
            DB::table('student_attendances')->where('class_id', $class_id)->where('date',date('Y-m-d'))->delete();
        }
        return 0;
    }


}
