<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Standard;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subjectmapping;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Traits\StudentTrait;
class ClassController extends Controller
{
    use StudentTrait;
    public function listAllStandards()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('listStandards'))
        {
            $standard = Standard::all(['id', 'standard_name']);
            if ($standard) {
                $output['status'] = true;
                $output['response'] = $standard;
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
    public function addClassesEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sectionId' => 'required',
            'standardId' => 'required',
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
    public function assignClassesEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subjectId' => 'required',
            'classId' => 'required',
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

    public function assignClasses(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('assignClasses'))
        {
            $rules = [
                'subjectId' => 'required',
                'classId' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Subjectmapping::where('subject_id', '=', $input->subjectId)->where('class_id', '=', $input->classId)->where('admin_id','=',$user->id)->count() == 0)
                {
                    $subjectmapping = new Subjectmapping;
                    $subjectmapping->user_id=$user->id;
                    $subjectmapping->admin_id=$user->id;
                    $subjectmapping->subject_id=$user->subjectId;
                    $subjectmapping->class_id=$user->classId;
                    if($subjectmapping->save())
                    {
                        $output['status']=true;
                        $output['message']='Assigned';
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
            $code = 400;
        }
        return response($response, $code);
    }
    public function deleteAssignClasses($Id)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('deleteAssignClasses'))
        {
            if (Subjectmapping::where('id', '=', $Id)->where('admin_id','=',$user->id)->count() == 1)
            {

                Subjectmapping::where('id',$Id)->delete();
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
                if (Classes::where('section_id', '=', $input->sectionId)->where('standard_id', '=', $input->standardId)->where('user_id','=',$user->id)->count() == 0)
                {
                    $section = Section::where('deleteStatus',0)->where('id',$input->sectionId)->get(['section_name']);
                    $standard = Standard::where('id',$input->standardId)->get(['standard_name']);
                    $section_name=$section[0]['section_name'];
                    $standard_name=$standard[0]['standard_name'];
                    $classes = new Classes;
                    $classes->section_id=$input->sectionId;
                    $classes->standard_id=$input->standardId;
                    $classes->class_name=$standard_name.'-'.$section_name;
                    $classes->user_id=$user->id;
                    if($classes->save())
                    {

                        $output['status']=true;
                        $output['message']='Class Successfully Added';;
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
    public function updateClassesEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sectionId' => 'required',
            'standardId' => 'required',
            'classId' => 'required',
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
    public function updateClasses(Request $request)
    {

        $user=Auth::User();
        if($user->hasPermissionTo('editClasses'))
        {
            $rules = [
                'sectionId' => 'required',
                'standardId' => 'required',
                'classId' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {

                if (Classes::where('id', '=', $input->classId)->count() == 1)
                {
                    if(Student::where('class_id', '=', $input->classId)->count() == 0)
                    {
                        $section = Section::where('deleteStatus',0)->where('user_id',$user->id)->where('id',$input->sectionId)->get(['section_name']);
                        $standard = Standard::where('id',$input->standardId)->get(['standard_name']);

                        $section_name=$section[0]['section_name'];
                        $standard_name=$standard[0]['standard_name'];
                        Classes::where('id',$input->classId)->update(array('section_id' => $input->sectionId,'standard_id' => $input->standardId,'class_name' => $standard_name.'-'.$section_name));
                        $output['status']=true;
                        $output['message']='Class Successfully Updated';
                        $response['data']=$this->encryptData($output);
                        $code = 200;
                    }
                    else
                    {
                        $output['status']=true;
                        $output['message']='Not Allowed to edit';
                        $response['data']=$this->encryptData($output);
                        $code = 400;
                    }

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
        $user=Auth::User();
        if($user->hasPermissionTo('deleteClasses'))
        {
            if (Classes::where('id', '=', $ClassesId)->where('user_id',$user->id)->where('deleteStatus',0)->count() == 1)
            {
                if(Student::where('class_id', '=', $ClassesId)->count() == 0)
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
                    $output['message'] = 'Not Allowed to Delete';
                    $response['data']=$this->encryptData($output);
                    $code=400;
                }

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

    public function getClassesRecord($ClassesId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('editClasses'))
        {
            $classes = Classes::with(['section','standard'])->where('id','=',$ClassesId)->where('deleteStatus',0)->where('user_id',$user->id)->get();
            if (isset($classes)) {
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
            $classes =Classes::with(['section','standard'])->where('deleteStatus',0)->where('user_id',$user->id)->paginate(10);
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
            $classes = Classes::with(['section','standard'])->where('deleteStatus',0)->where('user_id',$user->id)->get();
            if (isset($classes)) {
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
