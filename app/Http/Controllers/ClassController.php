<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;
use Input;
use Illuminate\Support\Facades\Validator;
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
                    $classes = new Classes;
                    $classes->section_id=$this->decrypt($input->sectionId);
                    $classes->encrypt_section_id=$input->sectionId;
                    $classes->standard_id=$this->decrypt($input->standardId);
                    $classes->encrypt_standard_id=$input->standardId;
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

}
