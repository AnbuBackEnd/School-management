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
use App\Models\classes;
use App\Models\section;
use App\Models\Student;
use App\Models\result;
use App\Models\pay_fee;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Traits\StudentTrait;
class ReportController extends Controller
{

    public function feesNotPaidStudents_admin($feesId,$classId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('feesNotPaidStudents-Admin'))
        {
            $getRecords=Pay_see::where('fees_id',$feesId)->where('class_id',$classId)->get(['student_id']);
            $data=array();
            if($getRecords != false)
            {
                foreach($getRecords as $row)
                {
                    array_push($data,$row['student_id']);
                }
            }
            $result=$students::whereNotIn('id',$data)->where('class_id',$classId)->get();
            $output['status'] = true;
            $output['response'] = $result;
            $output['message'] = 'Successfully Retrieved';
            $response['data']=$this->encryptData($output);
            $code=200;

        }
        else
        {
            $output['status'] = false;
            $output['message'] = 'Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function feesNotPaidStudents_teacher($feesId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('feesNotPaidStudents-Teacher'))
        {
            $getRecords=Pay_see::where('fees_id',$feesId)->where('class_id',$user->class_id)->get(['student_id']);
            $data=array();
            if($getRecords != false)
            {
                foreach($getRecords as $row)
                {
                    array_push($data,$row['student_id']);
                }
            }
            $result=$students::whereNotIn('id',$data)->where('class_id',$user->class_id)->get();
            $output['status'] = true;
            $output['response'] = $result;
            $output['message'] = 'Successfully Retrieved';
            $response['data']=$this->encryptData($output);
            $code=200;

        }
        else
        {
            $output['status'] = false;
            $output['message'] = 'Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function gradeCalculation_admin($classId,$examId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('gradeCalculationReport-Admin'))
        {
            $students = Result::join('students', 'students.id', '=', 'results.student_id')
            ->join('classes', 'classes.id', '=', 'results.class_id')
            ->join('exams', 'exams.id', '=', 'results.exam_id')
            ->join('subjects', 'subjects.id', '=', 'results.subject_id')
            ->where('student_attendances.class_id',$classId)
            ->where('student_attendances.date',date('Y-m-d',strtotime($date)))
            ->select(
                'students.name as student_name',
                'students.student_id as student_roll_no',
                'classes.class_name as class_name',
                'students.city as city',
                'students.phone as phone',
                'sum(mark) as total_marks',
                \DB::raw('(CASE WHEN (sum(mark) > 300 and sum(mark) < 400) THEN "C" WHEN (sum(mark) > 400 and sum(mark) < 450) THEN "B" WHEN (sum(mark) > 450) THEN "C" ELSE "D" END) AS grade'),
                \DB::raw('(CASE WHEN (students.gender = 1) THEN "Male" ELSE "Female" END) AS gender'),
                )
                ->orderBy('student_attendances.student_id','desc')
                ->groupby('student_id')
            ->get();
            $output['status'] = true;
            $output['response'] = $students;
            $output['message'] = 'Successfully Retrieved';
            $response['data']=$this->encryptData($output);
            $code=200;
        }
        else
        {
            $output['status'] = false;
            $output['message'] = 'Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function gradeCalculation_teacher($classId,$examId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('gradeCalculationReport-Teacher'))
        {
            $students = Result::join('students', 'students.id', '=', 'results.student_id')
            ->join('classes', 'classes.id', '=', 'results.class_id')
            ->join('exams', 'exams.id', '=', 'results.exam_id')
            ->join('subjects', 'subjects.id', '=', 'results.subject_id')
            ->where('student_attendances.class_id',$user->class_id)
            ->where('student_attendances.date',date('Y-m-d',strtotime($date)))
            ->select(
                'students.name as student_name',
                'students.student_id as student_roll_no',
                'classes.class_name as class_name',
                'students.city as city',
                'students.phone as phone',
                'sum(mark) as total_marks',
                \DB::raw('(CASE WHEN (sum(mark) > 300 and sum(mark) < 400) THEN "C" WHEN (sum(mark) > 400 and sum(mark) < 450) THEN "B" WHEN (sum(mark) > 450) THEN "C" ELSE "D" END) AS grade'),
                \DB::raw('(CASE WHEN (students.gender = 1) THEN "Male" ELSE "Female" END) AS gender'),
                )
                ->orderBy('student_attendances.student_id','desc')
                ->groupby('student_id')
            ->get();
            $output['status'] = true;
            $output['response'] = $students;
            $output['message'] = 'Successfully Retrieved';
            $response['data']=$this->encryptData($output);
            $code=200;
        }
        else
        {
            $output['status'] = false;
            $output['message'] = 'Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function studentAttendanceReport_admin($classId,$date)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('studentAttendanceReport-Admin'))
        {
            $students = Student::join('student_attendances', 'student_attendances.student_id', '=', 'students.id')
            ->where('student_attendances.class_id',$classId)
            ->where('student_attendances.date',date('Y-m-d',strtotime($date)))
            ->select(
                'name',
                'address',
                'city',
                'phone',
                'student_attendances.date',
                \DB::raw('(CASE WHEN (gender = 1) THEN "Male" ELSE "Female" END) AS gender'),
                \DB::raw('(CASE WHEN (present = 1) THEN "Present" ELSE "Absent" END) AS status')
                )
                ->orderBy('student_attendances.student_id','desc')
            ->get();

            $output['status'] = true;
            $output['response'] = $students;
            $output['message'] = 'Successfully Retrieved';
            $response['data']=$this->encryptData($output);
            $code=200;
        }
        else
        {
            $output['status'] = false;
            $output['message'] = 'Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function studentAttendanceReport_teacher($date)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('studentAttendanceReport-Teacher'))
        {
            $students = Student::join('student_attendances', 'student_attendances.student_id', '=', 'students.id')
            ->where('student_attendances.class_id',$user->class_id)
            ->where('student_attendances.date',date('Y-m-d',strtotime($date)))
            ->select(
                'name',
                'address',
                'city',
                'phone',
                'student_attendances.date',
                \DB::raw('(CASE WHEN (gender = 1) THEN "Male" ELSE "Female" END) AS gender'),
                \DB::raw('(CASE WHEN (present = 1) THEN "Present" ELSE "Absent" END) AS status')
                )
                ->orderBy('student_attendances.student_id','desc')
            ->get();

            $output['status'] = true;
            $output['response'] = $students;
            $output['message'] = 'Successfully Retrieved';
            $response['data']=$this->encryptData($output);
            $code=200;
        }
        else
        {
            $output['status'] = false;
            $output['message'] = 'Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function attendanceReport($classId,$from_date,$to_date)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('AttendanceReport-Admin'))
        {
            $salaries = DB::table('student_attendances')
            ->selectRaw('students.name as student_name,(SELECT count(*) FROM student_attendances WHERE present == 1) as present,(SELECT count(*) FROM student_attendances WHERE present == 0) as Absent')
            ->join('students', 'student_attendances.student_id', '=', 'students.id')
            ->groupBy('students.id')
            ->get();
            $subject = Subject::where('user_id','=',$user->id)->where('deleteStatus',0)->paginate(10,['encrypt_id AS subject_id', 'subject_name','total_marks']);
            if (isset($subject)) {

                $output['status'] = true;
                $output['response'] = $subject;
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
