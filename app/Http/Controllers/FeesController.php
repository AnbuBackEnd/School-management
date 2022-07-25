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

class FeesController extends Controller
{
    public function addFeesStructureCatagory(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('addFeesStructureCatagory'))
        {
            $rules = [
                'feesStrutureCatagory' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (FeesStructureCatagory::where('feesStrutureCatagory', '=', $input->feesStrutureCatagory)->where('deleteStatus',0)->count() == 0)
                {
                    $fees = new FeesStructureCatagory;
                    $fees->feesStrutureCatagory=$input->feesStrutureCatagory;
                    $fees->duration=$input->duration;
                    $fees->class_id=$input->classId;
                    $fees->amount=$input->amount;
                    $fees->user_id=$user->id;
                    if($fees->save())
                    {
                       $feesId=$this->encryptData($fees->id);
                       FeesStructureCatagory::where('id',$fees->id)->update(array('encrypt_id' => $feesId));
                       $feesObject = FeesStructureCatagory::where('id',$fees->id)->first(['encrypt_id AS feesStructureCatagory_id', 'feesStrutureCatagory AS catagory_name']);
                        $output['status']=true;
                        $output['message']='Section Successfully Added';
                        $output['response']=$feesObject;
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
    public function deleteFeesStructureCatagory($FeesId)
    {
        $FeesId=$this->decrypt($FeesId);
        $user=Auth::User();
        if($user->hasPermissionTo('deleteFeesStrutureCatagory'))
        {
            if (FeesStructureCatagory::where('id', '=', $FeesId)->where('deleteStatus',0)->count() == 1)
            {
                FeesStructureCatagory::where('id',$FeesId)->update(array('deleteStatus' => 1));
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
    public function getFeesStructureCatagory($feesId)
    {
        $feesId=$this->decrypt($feesId);
        $user=Auth::User();
        if($user->hasPermissionTo('editFeesStructureCatagory'))
        {
            $Fees = FeesStructureCatagory::where('id',$feesId)->where('deleteStatus',0)->first(['encrypt_id AS feesStructureCatagory_id', 'feesStrutureCatagory AS catagory_name']);
            if (isset($Fees->feesStructureCatagory_id)) {
                $output['status'] = true;
                $output['response'] = $section;
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
    public function getAllFeesStructurecatagory()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('viewFeesStructureCatagory'))
        {
           // $section = Section::all(['encrypt_id AS section_id', 'section_name']);
            $Fees = FeesStructureCatagory::where('deleteStatus',0)->where('user_id',$user->id)->paginate(10,['encrypt_id AS feesStructureCatagory_id', 'feesStrutureCatagory AS catagory_name']);
            if (isset($Fees->feesStructureCatagory_id)) {

                $output['status'] = true;
                $output['response'] = $Fees;
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
    public function listAllFeesStructureCatagory()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('listFeesStrutureCatagory'))
        {
            $Fees = FeesStructureCatagory::where('deleteStatus',0)->where('user_id',$user->id)->get(['encrypt_id AS feesStructureCatagory_id', 'feesStrutureCatagory AS catagory_name']);
            if (isset($Fees->feesStructureCatagory_id)) {
                $output['status'] = true;
                $output['response'] = $Fees;
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
    public function updateFeesStructureCatagory(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('editFeesStructureCatagory'))
        {
            $rules = [
                'feesStrutureCatagory' => 'required',
                'editId' => 'required|integer',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (FeesStructureCatagory::where('id', '=', $this->decrypt($input->editId))->where('user_id',$user->id)->where('deleteStatus',0)->count() == 1)
                {
                    FeesStructureCatagory::where('id',$this->decrypt($input->editId))->update(array('feesStructureCatagory' => $input->feesStrutureCatagory));
                    $output['status']=true;
                    $output['message']='Section Successfully Updated';
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


    //Curd Operations of Fees
    public function addFees(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('addFees'))
        {
            $rules = [
                'FeesCatagoryId' => 'required',
                'classId' => 'required',
                'amount' => 'required|digits_between:1,99999999999999',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Fee::where('fees_catagory_id', '=', $this->decrypt($input->FeesCatagoryId))->where('user_id',$user->id)->where('class_id',$this->decrypt($input->classId))->count() == 0)
                {
                    $fees = new Fee;
                    $fees->fees_catagory_id=$this->decrypt($input->FeesCatagoryId);
                    $fees->encrypt_class_id=$this->classId;
                    $fees->encrypt_fees_catagory_id=$this->FeesCatagoryId;
                    $fees->amount=$input->amount;
                    $fees->class_id=$this->decrypt($input->classId);
                    $fees->user_id=$user->id;

                    if($fees->save())
                    {
                       $feesId=$this->encryptData($fees->id);
                       Fee::where('id',$fees->id)->update(array('encrypt_id' => $feesId));
                        $output['status']=true;
                        $output['message']='Fees Successfully Added';
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
    public function deleteFees($FeesId)
    {
        $FeesId=$this->decrypt($FeesId);
        $user=Auth::User();
        if($user->hasPermissionTo('deleteFees'))
        {
            if (Fee::where('id', '=', $FeesId)->where('deleteStatus',0)->count() == 1)
            {
                Fee::where('id',$FeesId)->update(array('deleteStatus' => 1));
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
    public function getFees($feesId)
    {
        $feesId=$this->decrypt($feesId);
        $user=Auth::User();
        if($user->hasPermissionTo('editFees'))
        {
            $Fees = Fee::with(['classes','FeesCatagory'])->where('id','=',$feesId)->get(['class_name','feesStructureCatagory','encrypt_id AS fees_id','encrypt_class_id AS class_id','encrypt_fees_catagory_id AS fees_catagory_id']);
            if (isset($Fees->feesStructureCatagory_id)) {
                $output['status'] = true;
                $output['response'] = $section;
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
    public function getAllFees()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('viewFees'))
        {
           // $section = Section::all(['encrypt_id AS section_id', 'section_name']);
           $Fees = Fee::with(['classesMany','FeesCatagoryMany'])->where('deleteStatus','=',0)->get(['class_name','feesStructureCatagory','encrypt_id AS fees_id','encrypt_class_id AS class_id','encrypt_fees_catagory_id AS fees_catagory_id']);
            if (isset($Fees->feesStructureCatagory)) {

                $output['status'] = true;
                $output['response'] = $Fees;
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
    public function updateFees(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('editFees'))
        {
            $rules = [
                'FeesCatagoryId' => 'required',
                'classId' => 'required',
                'editId' => 'required',
                'amount' => 'required|digits_between:1,99999999999999',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Fee::where('id', '=', $this->decrypt($input->editId))->where('user_id',$user->id)->where('deleteStatus',0)->count() == 1)
                {
                    Fee::where('id',$this->decrypt($input->editId))->update(array('fees_catagory_id' => $this>decrypt($input->FeesCatagoryId),'class_id' => $this->decrypt($input->classId),'encrypt_class_id' => $input->classId,'encrypt_fees_catagory_id' => $input->FeesCatagoryId,'amount' => $input->amount));
                    $output['status']=true;
                    $output['message']='Fees Successfully Updated';
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
}
