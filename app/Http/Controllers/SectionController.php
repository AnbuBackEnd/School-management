<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\user;
use App\Models\section;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;
use Hash;
use Input;
use App\Http\Traits\StudentTrait;
class SectionController extends Controller
{
    use StudentTrait;
    public function addSection(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('addSections'))
        {
            $rules = [
                'sectionName' => 'required',
                'active' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Section::where('section_name', '=', $input->sectionName)->count() == 0)
                {
                    $section = new Section;
                    $section->section_name=$input->sectionName;
                    $section->active=$input->active;
                    $section->user_id=$user->id;
                    if($section->save())
                    {
                       $secId=$this->encryptData($section->id);
                       Section::where('id',$section->id)->update(array('encrypt_id' => $secId));
                        $output['status']=true;
                        $output['message']='Section Successfully Added';
                        $output['userData']=$section;
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

    public function getSectionRecord($sectionId)
    {
        $sectionId=$this->decrypt($sectionId);
        $user=Auth::User();
        if($user->hasPermissionTo('editSections'))
        {
            $section = Section::where('id',$sectionId)->first(['encrypt_id AS section_id', 'section_name']);

            if (isset($section->section_id)) {
                $output['status'] = true;
                $output['section'] = $section;
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

    public function getAllSections()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('viewSections'))
        {
           // $section = Section::all(['encrypt_id AS section_id', 'section_name']);
            $section = Section::paginate(10,['encrypt_id AS section_id', 'section_name']);
            if ($section) {

                $output['status'] = true;
                $output['section'] = $section;
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
    public function listAllSections()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('listSections'))
        {
            $section = Section::all(['encrypt_id AS section_id', 'section_name']);
            if ($section) {
                $output['status'] = true;
                $output['section'] = $section;
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
    public function updateSection(Request $request)
    {

        $user=Auth::User();
        if($user->hasPermissionTo('editSections'))
        {
            $rules = [
                'sectionName' => 'required',
                'active' => 'required',
                'sectionId' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Section::where('id', '=', $this->decrypt($input->sectionId))->count() == 1)
                {
                    Section::where('id',$this->decrypt($input->sectionId))->update(array('section_name' => $input->sectionName,'active' => $input->active));
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
}
