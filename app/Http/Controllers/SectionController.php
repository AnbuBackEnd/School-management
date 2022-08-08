<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Section;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;
use Hash;
use Input;
use App\Http\Traits\StudentTrait;
class SectionController extends Controller
{
    use StudentTrait;
    // public function addSection(Request $request)
    // {
    //     $user=Auth::User();

    //     if($user->hasPermissionTo('addSections'))
    //     {
    //         $rules = [
    //             'sectionName' => 'required',
    //             'active' => 'required',
    //         ];
    //         $input=$this->decrypt($request->input('input'));
    //         $validator = Validator::make((array)$input, $rules);

    //         if(!$validator->fails())
    //         {
    //             if (Section::where('section_name', '=', $input->sectionName)->count() == 0)
    //             {
    //                 $section = new Section;
    //                 $section->section_name=$input->sectionName;
    //                 $section->active=$input->active;
    //                 $section->user_id=$user->id;
    //                 $section->admin_id=$user->id;
    //                 if($section->save())
    //                 {
    //                     $output['status']=true;
    //                     $output['message']='Section Successfully Added';
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
    // public function deleteSection($sectionId)
    // {
    //     $user=Auth::User();
    //     if($user->hasPermissionTo('deleteSections'))
    //     {
    //         if (Section::where('id', '=', $sectionId)->where('deleteStatus',0)->count() == 1)
    //         {
    //             Section::where('id',$sectionId)->update(array('deleteStatus' => 1));
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
    // public function getSectionRecord($sectionId)
    // {
    //     $user=Auth::User();
    //     if($user->hasPermissionTo('editSections'))
    //     {
    //         $section = Section::where('id',$sectionId)->where('deleteStatus',0)->first(['id', 'section_name']);
    //         if (isset($section)) {
    //             $output['status'] = true;
    //             $output['response'] = $section;
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

    // public function getAllSections()
    // {
    //     $user=Auth::User();
    //     if($user->hasPermissionTo('viewSections'))
    //     {
    //        // $section = Section::all(['encrypt_id AS section_id', 'section_name']);
    //         $section = Section::where('deleteStatus',0)->where('user_id',$user->id)->paginate(10,['id', 'section_name']);
    //         if (isset($section)) {

    //             $output['status'] = true;
    //             $output['response'] = $section;
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
    public function listAllSections()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('listSections'))
        {
            $section = Section::all(['id', 'section_name']);
            if (isset($section)) {
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
                if (Section::where('id', '=', $input->sectionId)->where('user_id',$user->id)->where('deleteStatus',0)->count() == 1)
                {
                    Section::where('id',$input->sectionId)->update(array('section_name' => $input->sectionName,'active' => $input->active));
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
