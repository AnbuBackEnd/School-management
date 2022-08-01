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
use App\Models\pay_see;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Traits\StudentTrait;
class ReportController extends Controller
{
    use StudentTrait;
    public function feesNotPaidStudents_admin($feesId,$classId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('feesNotPaidStudentsAdmin'))
        {
            $getRecords = DB::table('pay_fees')->where('fees_id',$feesId)->where('class_id',$classId)->get(['student_id']);
            $data=array();
            json_encode($getRecords);
            if($getRecords != false)
            {
                foreach($getRecords as $row)
                {
                    array_push($data,$row->student_id);
                }
            }
            if(count($data) > 0)
            {
                $result=Student::whereNotIn('id',$data)->where('class_id',$classId)->get();
                $output['status'] = true;
                $output['response'] = $result;
                $output['message'] = 'Successfully Retrieved';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'Data Not Found';
                $response['data']=$this->encryptData($output);
                $code=200;
            }



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
        if($user->hasPermissionTo('feesNotPaidStudentsTeacher'))
        {
            $getRecords = DB::table('pay_fees')->where('fees_id',$feesId)->where('class_id',$user->class_id)->get(['student_id']);
            $data=array();
            json_encode($getRecords);
            if($getRecords != false)
            {
                foreach($getRecords as $row)
                {
                    array_push($data,$row->student_id);
                }
            }
            if(count($data) > 0)
            {
                $result=Student::whereNotIn('id',$data)->where('class_id',$user->class_id)->get();
                $output['status'] = true;
                $output['response'] = $result;
                $output['message'] = 'Successfully Retrieved';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'Data Not Found';
                $response['data']=$this->encryptData($output);
                $code=200;
            }

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
        if($user->hasPermissionTo('gradeCalculationReportAdmin'))
        {
            $students = Result::join('students', 'students.id', '=', 'results.student_id')
            ->where('results.class_id',$classId)
            ->join('classes', 'classes.id', '=', 'results.class_id')
            ->join('exams', 'exams.id', '=', 'results.exam_id')
            ->join('subjects', 'subjects.id', '=', 'results.subject_id')
            ->select(
                'students.name as student_name',
                'classes.class_name as class_name',
                'students.city as city',
                'students.phone as phone',
                DB::raw('SUM(results.mark) AS total_marks'),
                \DB::raw('(CASE WHEN (SUM(results.mark) > 300 and sum(results.mark) < 400) THEN "C" WHEN (sum(results.mark) > 400 and sum(results.mark) < 450) THEN "B" WHEN (sum(results.mark) > 450) THEN "A" ELSE "D" END) AS grade'),
                \DB::raw('(CASE WHEN (students.gender = 1) THEN "Male" ELSE "Female" END) AS gender'),
                )
                ->orderBy('results.student_id','ASC')
                ->groupby('results.student_id')
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
    public function gradeCalculation_teacher($examId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('gradeCalculationReportTeacher'))
        {
            $students = Result::join('students', 'students.id', '=', 'results.student_id')
            ->where('results.class_id',$user->class_id)
            ->join('classes', 'classes.id', '=', 'results.class_id')
            ->join('exams', 'exams.id', '=', 'results.exam_id')
            ->join('subjects', 'subjects.id', '=', 'results.subject_id')

            ->select(
                'students.name as student_name',
                'classes.class_name as class_name',
                'students.city as city',
                'students.phone as phone',
                DB::raw('SUM(results.mark) AS total_marks'),
                \DB::raw('(CASE WHEN (SUM(results.mark) > 300 and sum(results.mark) < 400) THEN "C" WHEN (sum(results.mark) > 400 and sum(results.mark) < 450) THEN "B" WHEN (sum(results.mark) > 450) THEN "A" ELSE "D" END) AS grade'),
                \DB::raw('(CASE WHEN (students.gender = 1) THEN "Male" ELSE "Female" END) AS gender'),
                )
                ->orderBy('results.student_id','ASC')
                ->groupby('results.student_id')
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
        if($user->hasPermissionTo('studentAttendanceReportAdmin'))
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
                ->orderBy('student_attendances.student_id','ASC')
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
        if($user->hasPermissionTo('studentAttendanceReportTeacher'))
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
                ->orderBy('student_attendances.student_id','ASC')
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

}
