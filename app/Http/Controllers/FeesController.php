<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Section;
use App\Models\Fee;
use App\Models\Intiate_fee;
use App\Models\Pay_fee;
use App\Models\FeesStructureCatagory;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;
use Hash;
use Input;
use App\Http\Traits\StudentTrait;

class FeesController extends Controller
{
    use StudentTrait;
    // public function addFeesStructureCatagory(Request $request)
    // {
    //     $user=Auth::User();

    //     if($user->hasPermissionTo('addFeesStructureCatagory'))
    //     {
    //         $rules = [
    //             'feesCatagory' => 'required',
    //         ];
    //         $input=$this->decrypt($request->input('input'));
    //         $validator = Validator::make((array)$input, $rules);
    //         if(!$validator->fails())
    //         {
    //             if (FeesStructureCatagory::where('feesStructureCatagory', '=', $input->feesCatagory)->where('deleteStatus',0)->count() == 0)
    //             {

    //                 $fees = new FeesStructureCatagory;
    //                 $fees->feesStructureCatagory=$input->feesCatagory;
    //                 $fees->user_id=$user->id;
    //                 $fees->admin_id=$user->admin_id;

    //                 if($fees->save())
    //                 {

    //                     $output['status']=true;
    //                     $output['message']='Catagory Successfully Added';
    //                     $response['data']=$this->encryptData($output);
    //                     $code = 200;
    //                 }
    //                 else
    //                 {
    //                     $output['status']=true;
    //                     $output['message']='Something went wrong. Please try again later.';
    //                     $response['data']=$this->encryptData($output);
    //                     $code = 400;
    //                 }

    //             }
    //             else
    //             {
    //                 $output['status']=false;
    //                 $output['message']='Already Exists';
    //                 $response['data']=$this->encryptData($output);
    //                 $code = 409;
    //             }
    //         }
    //         else
    //         {
    //             $output['status']=false;
    //             $output['message']=[$validator->errors()->first()];
    //             $response['data']=$this->encryptData($output);
    //             $code=400;
    //         }
    //     }
    //     else
    //     {
    //         $output['status']=false;
    //         $output['message']='Unauthorized Access';
    //         $response['data']=$this->encryptData($output);
    //         $code=400;
    //     }
    //     return response($response, $code);
    // }
    public function deleteInitiateFees($deleteId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('deleteFees'))
        {
            if(Pay_fee::where('fees_id', '=', $deleteId)->count() == 0)
            {
                Intiate_fee::where('id',$deleteId)->update(array('deleteStatus' => 1));
                $output['status']=true;
                $output['message']='Deleted Successfully';
                $response['data']=$this->encryptData($output);
                $code = 200;
            }
            else
            {
                $output['status']=false;
                $output['message']='Not Allowed to Delete';
                $response['data']=$this->encryptData($output);
                $code = 400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='unAuthorized Access';
            $response['data']=$this->encryptData($output);
            $code = 400;
        }
    }
    public function deletePayFees($deleteId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('deletePayFees'))
        {
            if(Pay_fee::where('id', '=', $deleteId)->count() == 0)
            {
                DB::table('pay_fees')->where('id', $deleteId)->delete();
                $output['status']=true;
                $output['message']='Deleted Successfully';
                $response['data']=$this->encryptData($output);
                $code = 200;
            }
            else
            {
                $output['status']=false;
                $output['message']='Not Allowed to Delete';
                $response['data']=$this->encryptData($output);
                $code = 400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='unAuthorized Access';
            $response['data']=$this->encryptData($output);
            $code = 400;
        }
    }
    public function initiateFeesEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'feesName' => 'required',
            'amount' => 'required',
            'classId' => 'required',
            'lastDayToPay' => 'required | date',
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
    public function initiateFees(Request $request)
    {
        $user=Auth::User();

        if($user->hasPermissionTo('addFees'))
        {
            $rules = [
                'feesName' => 'required',
                'amount' => 'required',
                'classId' => 'required',
                'lastDayToPay' => 'required | date',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (intiate_fee::where('class_id', '=', $input->classId)->where('fees_name',$input->feesName)->where('deleteStatus',0)->count() == 0)
                {

                    $fees = new intiate_fee;
                    $fees->fees_name=$input->feesName;
                    $fees->amount=$input->amount;
                    $fees->class_id=$input->classId;
                    $fees->last_day_to_pay=$input->lastDayToPay;
                    $fees->user_id=$user->id;
                    $fees->admin_id=$user->admin_id;
                    if($fees->save())
                    {
                        $output['status']=true;
                        $output['message']='Successfully Initiated';
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
    public function pay_feesEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'studentId' => 'required',
            'feesId' => 'required',
            'classId' => 'required',
            'date' => 'required | date',
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
    public function pay_fees(Request $request)
    {
        $user=Auth::User();

        if($user->hasPermissionTo('addPayFees'))
        {
            $rules = [
                'studentId' => 'required',
                'feesId' => 'required',
                'classId' => 'required',
                'date' => 'required | date',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (intiate_fee::where('id', '=', $input->feesId)->where('deleteStatus',0)->where('class_id',$input->classId)->count() == 1)
                {

                    $fees = new Pay_fee;
                    $fees->student_id=$input->studentId;
                    $fees->fees_id=$input->feesId;
                    $fees->class_id=$input->classId;
                    $fees->date=date('Y-m-d');
                    $fees->user_id=$user->id;
                    $fees->admin_id=$user->admin_id;
                    if($fees->save())
                    {
                        $output['status']=true;
                        $output['message']='Successfully Paid';
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
                    $output['message']='Not Allowed to Pay';
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
    // public function deleteFeesStructureCatagory($FeesId)
    // {

    //     $user=Auth::User();

    //     if($user->hasPermissionTo('deleteFeesStrutureCatagory'))
    //     {
    //         if (FeesStructureCatagory::where('id', '=', $FeesId)->where('deleteStatus',0)->count() == 1)
    //         {
    //             FeesStructureCatagory::where('id',$FeesId)->update(array('deleteStatus' => 1));
    //             $output['status'] = true;
    //             $output['message'] = 'Successfully Deleted';
    //             $response['data']=$this->encryptData($output);
    //             $code=200;
    //         }
    //         else
    //         {
    //             $output['status'] = false;
    //             $output['message'] = 'No Records Found';
    //             $response['data']=$this->encryptData($output);
    //             $code=400;
    //         }
    //     }
    //     else
    //     {
    //         $output['status']=false;
    //         $output['message']='Unauthorized Access';
    //         $response['data']=$this->encryptData($output);
    //         $code=400;
    //     }
    //     return response($response, $code);
    // }
    // public function getFeesStructureCatagory($feesId)
    // {
    //     $user=Auth::User();
    //     if($user->hasPermissionTo('editFeesStructureCatagory'))
    //     {
    //         $Fees = FeesStructureCatagory::where('id',$feesId)->where('deleteStatus',0)->first(['id', 'feesStructureCatagory AS catagory_name']);
    //         if (isset($Fees)) {
    //             $output['status'] = true;
    //             $output['response'] = $Fees;
    //             $output['message'] = 'Successfully Retrieved';
    //             $response['data']=$this->encryptData($output);
    //             $code=200;
    //         }
    //         else
    //         {
    //             $output['status'] = false;
    //             $output['message'] = 'No Records Found';
    //             $response['data']=$this->encryptData($output);
    //             $code=400;
    //         }
    //     }
    //     else
    //     {
    //         $output['status']=false;
    //         $output['message']='Unauthorized Access';
    //         $response['data']=$this->encryptData($output);
    //         $code=400;
    //     }
    //     return response($response, $code);
    // }
    // public function getAllFeesStructurecatagory()
    // {
    //     $user=Auth::User();
    //     if($user->hasPermissionTo('viewFeesStructureCatagory'))
    //     {
    //        // $section = Section::all(['encrypt_id AS section_id', 'section_name']);
    //         $Fees = FeesStructureCatagory::where('deleteStatus',0)->where('user_id',$user->id)->paginate(10,['id', 'feesStructureCatagory AS catagory_name']);
    //         if (isset($Fees)) {

    //             $output['status'] = true;
    //             $output['response'] = $Fees;
    //             $output['message'] = 'Successfully Retrieved';
    //             $response['data']=$this->encryptData($output);
    //             $code=200;
    //         }
    //         else
    //         {
    //             $output['status'] = false;
    //             $output['message'] = 'No Records Found';
    //             $response['data']=$this->encryptData($output);
    //             $code=400;
    //         }
    //     }
    //     else
    //     {
    //         $output['status']=false;
    //         $output['message']='Unauthorized Access';
    //         $response['data']=$this->encryptData($output);
    //         $code=400;
    //     }
    //     return response($response, $code);
    // }
    // public function listAllFeesStructureCatagory()
    // {
    //     $user=Auth::User();
    //     if($user->hasPermissionTo('listFeesStrutureCatagory'))
    //     {
    //         $Fees = FeesStructureCatagory::where('deleteStatus',0)->where('user_id',$user->id)->get(['id', 'feesStructureCatagory AS catagory_name']);
    //         if (isset($Fees)) {
    //             $output['status'] = true;
    //             $output['response'] = $Fees;
    //             $output['message'] = 'Successfully Retrieved';
    //             $response['data']=$this->encryptData($output);
    //             $code=200;
    //         }
    //         else
    //         {
    //             $output['status'] = false;
    //             $output['message'] = 'No Records Found';
    //             $response['data']=$this->encryptData($output);
    //             $code=400;
    //         }
    //     }
    //     else
    //     {
    //         $output['status']=false;
    //         $output['message']='Unauthorized Access';
    //         $response['data']=$this->encryptData($output);
    //         $code=400;
    //     }


    //     return response($response, $code);
    // }
    // public function updateFeesStructureCatagory(Request $request)
    // {
    //     $user=Auth::User();
    //     if($user->hasPermissionTo('editFeesStructureCatagory'))
    //     {
    //         $rules = [
    //             'feesCatagory' => 'required',
    //             'editId' => 'required',
    //         ];
    //         $input=$this->decrypt($request->input('input'));
    //         $validator = Validator::make((array)$input, $rules);
    //         if(!$validator->fails())
    //         {
    //             if (FeesStructureCatagory::where('id', '=', $input->editId)->where('user_id',$user->id)->where('deleteStatus',0)->count() == 1)
    //             {
    //                 FeesStructureCatagory::where('id',$input->editId)->update(array('feesStructureCatagory' => $input->feesCatagory));
    //                 $output['status']=true;
    //                 $output['message']='Fees Catagory Successfully Updated';
    //                 $response['data']=$this->encryptData($output);
    //                 $code = 200;
    //             }
    //             else
    //             {
    //                 $output['status']=true;
    //                 $output['message']='Something went wrong. Please try again later.';
    //                 $response['data']=$this->encryptData($output);
    //                 $code = 400;
    //             }
    //         }
    //         else
    //         {
    //             $output['status']=false;
    //             $output['message']=[$validator->errors()->first()];
    //             $response['data']=$this->encryptData($output);
    //             $code=400;
    //         }
    //     }
    //     else
    //     {
    //         $output['status']=false;
    //         $output['message']='Unauthorized Access';
    //         $response['data']=$this->encryptData($output);
    //         $code=400;
    //     }
    //     return response($response, $code);

    // }


    // //Curd Operations of Fees
    // public function addFees(Request $request)
    // {
    //     $user=Auth::User();
    //     if($user->hasPermissionTo('addFees'))
    //     {
    //         $rules = [
    //             'FeesCatagoryId' => 'required',
    //             'classId' => 'required',
    //             'amount' => 'required',
    //         ];
    //         $input=$this->decrypt($request->input('input'));
    //         $validator = Validator::make((array)$input, $rules);
    //         if(!$validator->fails())
    //         {
    //             if (Fee::where('fees_catagory_id', '=', $input->FeesCatagoryId)->where('user_id',$user->id)->where('class_id',$input->classId)->count() == 0)
    //             {
    //                 $fees = new Fee;
    //                 $fees->fees_catagory_id=$input->FeesCatagoryId;
    //                 $fees->amount=$input->amount;
    //                 $fees->class_id=$input->classId;
    //                 $fees->user_id=$user->id;
    //                 if($fees->save())
    //                 {
    //                     $output['status']=true;
    //                     $output['message']='Fees Successfully Added';
    //                     $response['data']=$this->encryptData($output);
    //                     $code = 200;
    //                 }
    //                 else
    //                 {
    //                     $output['status']=true;
    //                     $output['message']='Something went wrong. Please try again later.';
    //                     $response['data']=$this->encryptData($output);
    //                     $code = 400;
    //                 }

    //             }
    //             else
    //             {
    //                 $output['status']=false;
    //                 $output['message']='Already Exists';
    //                 $response['data']=$this->encryptData($output);
    //                 $code = 409;
    //             }
    //         }
    //         else
    //         {
    //             $output['status']=false;
    //             $output['message']=[$validator->errors()->first()];
    //             $response['data']=$this->encryptData($output);
    //             $code=400;
    //         }
    //     }
    //     else
    //     {
    //         $output['status']=false;
    //         $output['message']='Unauthorized Access';
    //         $response['data']=$this->encryptData($output);
    //         $code=400;
    //     }
    //     return response($response, $code);
    // }
    // public function deleteFees($FeesId)
    // {
    //     // $FeesId=$this->decrypt($FeesId);
    //     $user=Auth::User();
    //     if($user->hasPermissionTo('deleteFees'))
    //     {
    //         if (Fee::where('id', '=', $FeesId)->where('deleteStatus',0)->count() == 1)
    //         {
    //             Fee::where('id',$FeesId)->update(array('deleteStatus' => 1));
    //             $output['status'] = true;
    //             $output['message'] = 'Successfully Deleted';
    //             $response['data']=$this->encryptData($output);
    //             $code=200;
    //         }
    //         else
    //         {
    //             $output['status'] = false;
    //             $output['message'] = 'No Records Found';
    //             $response['data']=$this->encryptData($output);
    //             $code=400;
    //         }
    //     }
    //     else
    //     {
    //         $output['status']=false;
    //         $output['message']='Unauthorized Access';
    //         $response['data']=$this->encryptData($output);
    //         $code=400;
    //     }
    //     return response($response, $code);
    // }
    // public function getFees($feesId)
    // {
    //    // $feesId=$this->decrypt($feesId);
    //     $user=Auth::User();
    //     if($user->hasPermissionTo('editFees'))
    //     {
    //         $Fees = Fee::with(['classes','FeesCatagory'])->where('id','=',$feesId)->get();
    //         if (isset($Fees)) {
    //             $output['status'] = true;
    //             $output['response'] = $Fees;
    //             $output['message'] = 'Successfully Retrieved';
    //             $response['data']=$this->encryptData($output);
    //             $code=200;
    //         }
    //         else
    //         {
    //             $output['status'] = false;
    //             $output['message'] = 'No Records Found';
    //             $response['data']=$this->encryptData($output);
    //             $code=400;
    //         }
    //     }
    //     else
    //     {
    //         $output['status']=false;
    //         $output['message']='Unauthorized Access';
    //         $response['data']=$this->encryptData($output);
    //         $code=400;
    //     }
    //     return response($response, $code);
    // }
    // public function getAllFees()
    // {
    //     $user=Auth::User();
    //     if($user->hasPermissionTo('viewFees'))
    //     {
    //        // $section = Section::all(['encrypt_id AS section_id', 'section_name']);
    //        $Fees = Fee::with(['classes','FeesCatagory'])->where('deleteStatus','=',0)->where('admin_id',$user->admin_id)->get();
    //         if (isset($Fees)) {

    //             $output['status'] = true;
    //             $output['response'] = $Fees;
    //             $output['message'] = 'Successfully Retrieved';
    //             $response['data']=$this->encryptData($output);
    //             $code=200;
    //         }
    //         else
    //         {
    //             $output['status'] = false;
    //             $output['message'] = 'No Records Found';
    //             $response['data']=$this->encryptData($output);
    //             $code=400;
    //         }
    //     }
    //     else
    //     {
    //         $output['status']=false;
    //         $output['message']='Unauthorized Access';
    //         $response['data']=$this->encryptData($output);
    //         $code=400;
    //     }
    //     return response($response, $code);
    // }
    // public function updateFees(Request $request)
    // {
    //     $user=Auth::User();
    //     if($user->hasPermissionTo('editFees'))
    //     {
    //         $rules = [
    //             'FeesCatagoryId' => 'required',
    //             'classId' => 'required',
    //             'editId' => 'required',
    //             'amount' => 'required',
    //         ];
    //         $input=$this->decrypt($request->input('input'));
    //         $validator = Validator::make((array)$input, $rules);
    //         if(!$validator->fails())
    //         {
    //             if (Fee::where('id', '=', $input->editId)->where('user_id',$user->id)->where('deleteStatus',0)->count() == 1)
    //             {
    //                 Fee::where('id',$input->editId)->update(array('fees_catagory_id' => $input->FeesCatagoryId,'class_id' => $input->classId,'amount' => $input->amount));

    //                 $output['status']=true;
    //                 $output['message']='Fees Successfully Updated';
    //                 $response['data']=$this->encryptData($output);
    //                 $code = 200;
    //             }
    //             else
    //             {
    //                 $output['status']=true;
    //                 $output['message']='Something went wrong. Please try again later.';
    //                 $response['data']=$this->encryptData($output);
    //                 $code = 400;
    //             }
    //         }
    //         else
    //         {
    //             $output['status']=false;
    //             $output['message']=[$validator->errors()->first()];
    //             $response['data']=$this->encryptData($output);
    //             $code=400;
    //         }
    //     }
    //     else
    //     {
    //         $output['status']=false;
    //         $output['message']='Unauthorized Access';
    //         $response['data']=$this->encryptData($output);
    //         $code=400;
    //     }
    //     return response($response, $code);

    // }
}
