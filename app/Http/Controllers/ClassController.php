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
class ClassController extends Controller
{
    public function listAllStandards()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('listStandards'))
        {
            $standard = Standard::all(['encrypt_id AS standard_id', 'standard_name']);
            if ($section) {
                $output['status'] = true;
                $output['standards'] = $standard;
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
    public function addClasses(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('addClasses'))
        {
            $rules = [
                'sectionId' => 'required',
                'standardId' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Classes::where('section_id', '=', $this->decrypt($input->sectionId))->where('standard_id', '=', $this->decrypt($input->standardId))->where('user_id','=',$user->id)->count() == 0)
                {
                    $section_name = Classes::with(['section'])->where('section_id','=',$this->decrypt($input->sectionId))->get(['section_name'])[0]['section_name'];
                    $standard_name = Classes::with(['standard'])->where('standard_id','=',$this->decrypt($input->standardId))->get(['standard_name'])[0]['standard_name'];
                    $classes = new Classes;
                    $classes->section_id=$this->decrypt($input->sectionId);
                    $classes->encrypt_section_id=$input->sectionId;
                    $classes->standard_id=$this->decrypt($input->standardId);
                    $classes->encrypt_standard_id=$input->standardId;
                    $classes->class_name=$standard_name.'-'.$section_name;
                    $classes->user_id=$user->id;
                    if($classes->save())
                    {
                        $classes_id=$this->encryptData($classes->id);
                        Classes::where('id',$classes->id)->update(array('encrypt_id' => $classes_id));
                        $output['status']=true;
                        $output['message']='Section Successfully Added';
                        $output['userData']=$section;
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

        }
    }
    public function updateClasses(Request $request)
    {

        $user=Auth::User();
        if($user->hasPermissionTo('editSections'))
        {
            $rules = [
                'sectionId' => 'required',
                'standardId' => 'required',
                'ClassId' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Classes::where('id', '=', $this->decrypt($input->ClassId))->count() == 1)
                {
                    Classes::where('id',$this->decrypt($input->ClassId))->update(array('section_id' => $this->decrypt($input->sectionId),'standard_id' => $this->decrypt($input->standardId),'encrypt_section_id' => $input->sectionId,'encrypt_standard_id' => $input->standardId));
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
    public function deleteClasses($ClassesId)
    {
        $ClassesId=$this->decrypt($ClassesId);
        $user=Auth::User();
        if($user->hasPermissionTo('deleteSections'))
        {
            if (Classes::where('id', '=', $ClassesId)->count() == 1)
            {
                Classes::where('id',$ClassesId)->update(array('deleteStatus' => 1));
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

    public function getClassesRecord($ClassesId)
    {
        $ClassesId=$this->decrypt($ClassesId);
        $user=Auth::User();
        if($user->hasPermissionTo('editClasses'))
        {
            $classes = Classes::with(['section','standard'])->where('id','=',$ClassesId)->get(['section_name','encrypt_section_id','standard_name','encrypt_standard_id']);

            if ($classes) {
                $output['status'] = true;
                $output['classes'] = $classes;
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
    public function getAllClasses()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('viewClasses'))
        {
            $classes =Classes::with(['section','standard'])->paginate(10,['section_name','encrypt_section_id','standard_name','encrypt_standard_id']);
            if ($classes) {
                $output['status'] = true;
                $output['classes'] = $classes;
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
    public function listAllClasses()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('listClasses'))
        {
            $classes = Classes::with(['section','standard'])->get(['section_name','encrypt_section_id','standard_name','encrypt_standard_id']);
            if ($classes) {
                $output['status'] = true;
                $output['classes'] = $classes;
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
