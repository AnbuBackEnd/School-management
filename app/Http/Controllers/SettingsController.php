<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\user;
use App\Models\section;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;
use Hash;
use Input;
use App\Http\Traits\StudentTrait;
class SettingsController extends Controller
{
    use StudentTrait;
    public function addSection(Request $request)
    {
        $user=Auth::User();
        if($user->hasRole('Admin'))
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
                    if($section->save())
                    {
                       //$section->id=$this->encryptData($section->id);
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

    }

    public function getSectionRecord($sectionId)
    {
        //$sectionId=$this->decrypt($sectionId);
        $section = Section::where('id',$sectionId)->first();
        if (isset($section->id)) {
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

        return response($response, $code);
    }


}
