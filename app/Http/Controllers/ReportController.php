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
use App\Models\StaffSalary;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Traits\StudentTrait;
class ReportController extends Controller
{
    use StudentTrait;
    public function feesAllDetails($feesId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('feesAllDetails'))
        {
            $getRecords = Pay_fee::where('fees_id',$feesId)->where('admin_id',$user->id)
                        ->join('students','students.id','=','pay_fees.student_id')
                        ->join('classes','classes.id','=','pay_fees.class_id')
                        ->join('intiate_fees','intiate_fees.id','=','pay_fees.fees_id')
                        ->select('id as pay_fees_id','intiate_fees.fees_name as fees_name','intiate_fees.id as fees_id','students.name as student_name','students.id as student_id','classes.id as class_id','classes.class_name as class_name')
                        ->get();
                        $output['status'] = true;
                        $output['response'] = $getRecords;
                        $output['message'] = 'Successfully Retrieved';
                        $response['data']=$this->encryptData($output);
                        $code=200;
        }
        else
        {
            $output['status'] = false;
            $output['message'] = 'Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=200;
        }
        return response($response, $code);
    }

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
    public function PersonalInformation()
    {
        $user=Auth::User();
        if($user)
        {
            if($user->hasRole('Admin'))
            {
                $personalDetails = User::where('id',$user->id)->select('name as school_name','address','city','phone','admin_name','email',\DB::raw('(CASE WHEN (students.gender = 1) THEN "Male" ELSE "Female" END) AS gender'));
                $output['status'] = true;
                $output['response'] = $personalDetails;
                $output['message'] = 'Successfully Retrieved';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $personalDetails = User::where('id',$user->id)->select('name','address','city','phone','email',\DB::raw('(CASE WHEN (students.gender = 1) THEN "Male" ELSE "Female" END) AS gender'));
                $output['status'] = true;
                $output['response'] = $personalDetails;
                $output['message'] = 'Successfully Retrieved';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            return response($response, $code);

        }
        // if($user->hasRole('Admin'))
        // {
        //    //

        // }
        // else
        // {

        // }
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
    public function OverAllStudentList()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('overAllStudentList-Admin'))
        {
            $studentList = Student::join('classes', 'students.id', '=', 'students.class_id')
            ->where('admin_id','>',$user->admin_id)
            ->select('class_id','id','name','address',\DB::raw('(CASE WHEN (gender == 1) THEN "MALE" ELSE "FEMALE" END) AS gender'),'city','phone','student_id as studentRollNo','classes.class_name as class_name')->get();
            $output['status'] = true;
            $output['response'] = $studentList;
            $output['message'] = 'Successfully Retrieved';
            $response['data']=$this->encryptData($output);
            $code=200;
        }
        else
        {
            $output['status'] = false;
            $output['message'] = 'Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=200;
        }
    }
    public function overAllStaffList()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('overAllStaffList-Admin'))
        {
            $staffList = User::where('admin_id','>',$user->admin_id)
            ->select('name',\DB::raw('(CASE WHEN (gender == 1) THEN "MALE" ELSE "FEMALE" END) AS gender'),'address','city','phone','email',\DB::raw('(CASE WHEN (role == 2) THEN "Teaching Staff" WHEN (role == 3) THEN "Non Teaching Staff" ELSE "Librarian" END) AS role'))->get();
            $output['status'] = true;
            $output['response'] = $staffList;
            $output['message'] = 'Successfully Retrieved';
            $response['data']=$this->encryptData($output);
            $code=200;
        }
        else
        {
            $output['status'] = false;
            $output['message'] = 'Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=200;
        }
    }
    public function staffSalarycalculationRecords()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('staffSalaryReport-Total'))
        {
            $staffSalary = StaffSalary::join('users', 'users.id', '=', 'staff_salaries.staff_id')
            ->where('confirm_status',1)
            ->where('admin_id',$user->admin_id)
            ->where('staff_id',$user->id)
            ->select('staff_id','users.name as staff_name','from_date','to_date','salary_amount as salary','miscellaneous_amount','confirm_status')->get();
            $output['status'] = true;
            $output['response'] = $staffSalary;
            $output['message'] = 'Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=200;
        }
        else
        {
            $output['status'] = false;
            $output['message'] = 'Successfully Retrieved';
            $response['data']=$this->encryptData($output);
            $code=200;
        }
    }

    public function staffSalaryCalculationAdmin($fromDate,$toDate)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('staffSalaryReportAdmin-Particular'))
        {
            $staffSalary = StaffSalary::join('users', 'users.id', '=', 'staff_salaries.staff_id')
                            ->where('confirm_status',1)
                            ->where('admin_id',$user->admin_id)
                            ->select('staff_id','users.name as staff_name','from_date','to_date','salary_amount as salary','miscellaneous_amount','confirm_status')->get();
            $output['status'] = true;
            $output['response'] = $staffSalary;
            $output['message'] = 'Successfully Retrieved';
            $response['data']=$this->encryptData($output);
            $code=200;

        }
        else
        {
            $output['status'] = false;
            $output['message'] = 'Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=200;
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
