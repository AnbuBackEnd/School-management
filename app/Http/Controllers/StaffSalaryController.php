<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;
use Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\StaffSalary;
use App\Models\user;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Traits\StudentTrait;

class StaffSalaryController extends Controller
{
    public function addStaffSalaryEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'staffId' => 'required | numeric',
            'fromDate' => 'required | date',
            'toDate' => 'required | date',
            'salaryAmount' => 'required',
            'misAmount' => 'required',
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
    public function addStaffSalary(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('addSalary'))
        {
            $rules = [
                'staffId' => 'required | numeric',
                'fromDate' => 'required | date',
                'toDate' => 'required | date',
                'salaryAmount' => 'required',
                'misAmount' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (StaffSalary::where('staff_id', '=', $input->staffId)->where('from_date', '=',date('Y-m-d',strtotime($input->fromDate)))->where('to_date',date('Y-m-d',strtotime($input->toDate)))->where('user_id','=',$user->id)->count() == 0)
                {


                    $staffsalary = new StaffSalary;
                    $staffsalary->admin_id=$user->admin_id;
                    $staffsalary->staff_id=$input->staffId;
                    $staffsalary->salary_amount=$input->salaryAmount;
                    $staffsalary->miscellaneous_amount=$input->misAmount;
                    $staffsalary->from_date=date('Y-m-d',strtotime($input->fromDate));
                    $staffsalary->to_date=date('Y-m-d',strtotime($input->toDate));
                    if($staffsalary->save())
                    {

                        $output['status']=true;
                        $output['message']='Staff Salary Successfully Added';;
                        $response['data']=$this->encryptData($output);
                        $code = 200;
                    }
                    else
                    {
                        $output['status']=false;
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
    public function deleteStaffSalary($recordId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('deleteSalary'))
        {
            $recordCount=StaffSalary::where('id',$recordId)->where('admin_id',$user->admin_id)->where('confirm_status',0)->count();
            if ($recordCount == 1)
            {
                DB::table('staff_salaries')->where('id',$recordId)->where('admin_id',$user->admin_id)->where('confirm_status',0)->delete();
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
    public function getStaffSalary($recordId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('editSalary'))
        {
            $staffRecord = StaffSalary::with(['getStaff'])->where('id','=',$recordId)->where('admin_id',$user->admin_id)->get();

            if (isset($staffRecord)) {
                $output['status'] = true;
                $output['message'] = 'Successfully Retrieved';
                $output['response'] = $staffRecord;
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
    public function getStaffSalaries()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('viewSalary'))
        {
            $staffRecord = StaffSalary::with(['getStaff'])->where('admin_id',$user->admin_id)->get();
            if (isset($staffRecord)) {

                $output['status'] = true;
                $output['response'] = $staffRecord;
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
    public function updatestaffSalaryEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'staffId' => 'required | numeric',
            'fromDate' => 'required | date',
            'toDate' => 'required | date',
            'salaryAmount' => 'required',
            'misAmount' => 'required',
            'editId' => 'required',
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


}
