<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\user;
use App\Models\section;
use App\Models\Fee;
use App\Models\Student;
use App\Models\studentAttendance as studentAtt;
use App\Models\StaffAttendance as staffAtt;
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
    public function confirmAttendanceStudent(Request $request)
    {
        $rules = [
            'classId' => 'required | integer',
            'staff' => 'required | boolean'
        ];
        $input=$this->decrypt($request->input('input'));
        $validator = Validator::make((array)$input, $rules);
        if(!$validator->fails())
        {
            if($input->staff == false)
            {
                if(StudentAtt::where('class_id',$input->classId)->where('date',date('Y-m-d'))->where('admin_id',$user->admin_id)->count() == 0)
                {
                    StudentAtt::where('admin_id',$user->admin_id)->where('class_id',$input->classId)->where('date',date('Y-m-d'))->update(array('confirm_status' => 1));
                    $output['status']=true;
                    $output['message']='Attendance Confirmed';
                    $response['data']=$this->encryptData($output);
                    $code=200;
                }
                else
                {
                    $output['status']=true;
                    $output['message']='No Record Found';
                    $response['data']=$this->encryptData($output);
                    $code=200;
                }
            }
            else
            {
                if(StaffAtt::where('date',date('Y-m-d'))->where('admin_id',$user->admin_id)->count() == 0)
                {
                    StaffAtt::where('date',date('Y-m-d'))->where('admin_id',$user->admin_id)->update(array('confirm_status' => 1));
                    $output['status']=true;
                    $output['message']='Attendance Confirmed';
                    $response['data']=$this->encryptData($output);
                    $code=200;
                }
                else
                {
                    $output['status']=true;
                    $output['message']='No Record Found';
                    $response['data']=$this->encryptData($output);
                    $code=200;
                }
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
    public function attendanceStudent(Request $request)
    {
        $user=Auth::user();
        if($user->hasPermissionTo('AttendanceStudent'))
        {
            $this->check_attendance($user->class_id);
            $rules = [
                'value' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                    if(count(explode(',',$input->value)) > 0)
                    {
                        $finalResult=explode(',',$input->value);
                        $presentRecords=$this->presentFunction($user,$finalResult,0);
                        if($presentRecords == 1)
                        {
                            $notPresentRecords=$this->notPresentFunction($user,$finalResult,0);
                            if($notPresentRecords == 1)
                            {
                                $output['status']=true;
                                $output['message']='Attendance Recorded';
                                $response['data']=$this->encryptData($output);
                                $code = 200;
                            }

                        }
                        else
                        {
                            $output['status']=false;
                            $output['message']='Something is Wrong';
                            $response['data']=$this->encryptData($output);
                            $code = 200;
                        }

                    }
                    else
                    {
                        $output['status']=false;
                        $output['message']='Something is Wrong';
                        $response['data']=$this->encryptData($output);
                        $code = 200;
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
            $output['status'] = false;
            $output['message'] = 'UnAuthorized Access';
            $response['data']=$this->encryptData($output);
            $code=200;
        }
        return response($response, $code);
    }
    public function attendanceStaff(Request $request)
    {
        $user=Auth::user();
        if($user->hasPermissionTo('AttendanceStaff'))
        {
            $this->check_attendance($user->class_id);
            $rules = [
                'value' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                    if(count(explode(',',$input->value)) > 0)
                    {
                        $finalResult=explode(',',$input->value);
                        $presentRecords=$this->presentFunction($user,$finalResult,1);
                        if($presentRecords == 1)
                        {
                            $notPresentRecords=$this->notPresentFunction($user,$finalResult,1);
                            if($notPresentRecords == 1)
                            {
                                $output['status']=true;
                                $output['message']='Attendance Recorded';
                                $response['data']=$this->encryptData($output);
                                $code = 200;
                            }

                        }
                        else
                        {
                            $output['status']=false;
                            $output['message']='Something is Wrong';
                            $response['data']=$this->encryptData($output);
                            $code = 200;
                        }

                    }
                    else
                    {
                        $output['status']=false;
                        $output['message']='Something is Wrong';
                        $response['data']=$this->encryptData($output);
                        $code = 200;
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
            $output['status'] = false;
            $output['message'] = 'UnAuthorized Access';
            $response['data']=$this->encryptData($output);
            $code=200;
        }
        return response($response, $code);
    }
    public function notPresentFunction($user,$requiredArray,$staff)
    {
        if(count($user) > 0)
        {
            if(count($requiredArray) > 0)
            {
                if($staff == 0)
                {
                    $notPresentRecords=Student::whereNotIn('id',$requiredArray)->where('class_id',$user->class_id)->get()->toArray();
                    if($notPresentRecords != false)
                    {
                        foreach($notPresentRecords as $row)
                        {
                            $studentAt=new studentAtt();
                            $studentAt->student_id=$row->id;
                            $studentAt->present=0;
                            $studentAt->admin_id=$user->admin_id;
                            $studentAt->user_id=$user->id;
                            $studentAt->date=date('Y-m-d');
                            $studentAt->class_id=$user->class_id;
                            $studentAt->save();
                        }
                    }
                }
                else
                {
                    $notPresentRecords=User::whereNotIn('id',$requiredArray)->where('admin_id','>',0)->get()->toArray();
                    if($notPresentRecords != false)
                    {
                        foreach($notPresentRecords as $row)
                        {
                            $staffAt=new staffAtt();
                            $staffAt->staff_id=$row->id;
                            $staffAt->present=0;
                            $staffAt->admin_id=$user->admin_id;
                            $staffAt->user_id=$user->id;
                            $staffAt->date=date('Y-m-d');
                            $staffAt->save();
                        }
                    }
                }


            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'Invalid Input is Occured';
                $response['data']=$this->encryptData($output);
                $code=200;
                return response($response, $code);
            }
        }
        else
        {
            $output['status'] = false;
            $output['message'] = 'User Not Occured';
            $response['data']=$this->encryptData($output);
            $code=200;
            return response($response, $code);
        }
        return 1;

    }
    public function presentFunction($user,$requiredArray,$staff)
    {
        if(count($user) > 0)
        {
            if(count($requiredArray) > 0)
            {
                if($staff == 0)
                {
                    for($i=0;$i<count($requiredArray);$i++)
                    {
                        if(Student::where('id',$requiredArray[$i])->where('class_id',$user->class_id)->count() == 1)
                        {
                            $studentAt=new studentAtt();
                            $studentAt->student_id=$requiredArray[$i];
                            $studentAt->present=1;
                            $studentAt->admin_id=$user->admin_id;
                            $studentAt->user_id=$user->id;
                            $studentAt->date=date('Y-m-d');
                            $studentAt->class_id=$user->class_id;
                            $studentAt->save();
                        }
                        else
                        {
                            $output['status'] = false;
                            $output['message'] = 'Student ID '.$requiredArray[$i].' Not Occured';
                            $response['data']=$this->encryptData($output);
                            $code=200;
                            return response($response, $code);
                        }

                    }
                }
                else
                {
                    for($i=0;$i<count($requiredArray);$i++)
                    {
                        if(User::where('id',$requiredArray[$i])->count() == 1)
                        {
                            $staffAt=new StaffAtt();
                            $staffAt->staff_id=$requiredArray[$i];
                            $staffAt->present=1;
                            $staffAt->admin_id=$user->admin_id;
                            $staffAt->user_id=$user->id;
                            $staffAt->date=date('Y-m-d');
                            $staffAt->save();
                        }
                        else
                        {
                            $output['status'] = false;
                            $output['message'] = 'Staff ID '.$requiredArray[$i].' Not Occured';
                            $response['data']=$this->encryptData($output);
                            $code=200;
                            return response($response, $code);
                        }

                    }
                }


            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'Invalid Input is Occured';
                $response['data']=$this->encryptData($output);
                $code=200;
                return response($response, $code);
            }
        }
        else
        {
            $output['status'] = false;
            $output['message'] = 'User Not Occured';
            $response['data']=$this->encryptData($output);
            $code=200;
            return response($response, $code);
        }
        return 1;

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
    public function check_attendance_staff()
    {
        if (studentAtt::where('date', '=', date('Y-m-d'))->count() > 0)
        {
            DB::table('staff_attendances')->where('date',date('Y-m-d'))->delete();
        }
        return 0;
    }


}
