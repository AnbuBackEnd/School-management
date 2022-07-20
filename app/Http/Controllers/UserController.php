<?php
//composer require doctrine/dbal install package for column modification
namespace App\Http\Controllers;
use Auth;
use Hash;
use Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\user;
use App\Http\Traits\StudentTrait;
class UserController extends Controller
{
    use StudentTrait;
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'admin_name' => 'required',
            'address' => 'required',
            'phone' => 'required|min:10',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ];
        $input=(array)$this->decrypt($request->input('input'));
        $validator = Validator::make($input, $rules);
        if(!$validator->fails())
        {
            if (User::where('email', '=', $input['email'])->count() == 0)
            {
                $output=array();
                $user = new User;
                $user->name = $input['name'];
                $user->admin_name = $input['admin_name'];
                $user->address = $input['address'];
                $user->phone =  $input['phone'];
                $user->email = $input['email'];
                $user->password = Hash::make($input['password']);
                $user->otp_code= random_int(100000, 999999);
                $user->role=2;
                $user->role_text='Admin';
                if ($user->save())
                {
                    $user->assignRole('admin');
                    $output['status']=true;
                    $output['message']='Successfully Added';
                    $output['userData']=$user;
                    $response['data']=$this->encryptData($output);
                    //$response=$this->encrypt($output);
                    $code = 200;
                }
                else
                {
                    $output['status']=true;
                    $output['message']='Something went wrong. Please try again later.';
                    $response['data']=$this->encryptData($output);
                    // $response=$this->encrypt($output);
                    $code = 400;
                }
            }
            else
            {
                $output['status']=false;
                $output['message']='Already Exists';
                $response['data']=$this->encryptData($output);
            // $response=$this->encrypt($output);
                $code = 409;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']=[$validator->errors()->first()];
            //$response=$this->encrypt($output);
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function accountVerification(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'otpCode' => 'required|min:6',
        ];
        $input=$this->decrypt($request->input('input'));
        $validator = Validator::make((array)$input, $rules);
        if(!$validator->fails())
        {
            $userData=User::where('email',$input->email)->where('otp_code',$input->otpCode)->where('account_verification_status',0)->first();
            if(isset($userData->id) == 1)
            {
                User::where('email',$input->email)->update(array('account_verification_status' => 1));
                $output['status']=true;
                $output['message']='Successfully Verified';
                $response['data']=$this->encryptData($output);
                $code = 200;
            }
            else
            {

                $output['status']=false;
                $output['message']='Invalid Otp Code';
                $response['data']=$this->encryptData($output);
                $code = 409;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']=[$validator->errors()->first()];
            //$response=$this->encrypt($output);
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $input=$this->decrypt($request->input('input'));
        $validator = Validator::make((array)$input, $rules);
        if(!$validator->fails())
        {
            $isExist = User::where('email',$input->email)->where('account_verification_status', 1)->first();

            if (isset($isExist->id))
            {
                if (Hash::check($input->password, $isExist->password))
                {
                    $output['status']=true;
                    $output['token'] = $this->getAccessToken($isExist);
                    $output['message']='Successfully Logined';
                    $response['data']=$this->encryptData($output);
                    $code = 200;
                }
                else{
                    $output['status']=false;
                    $output['message'] = "Please check the password.";
                    $response['data']=$this->encryptData($output);
                    $code = 400;
                }
            }
            else{
                $output['status']=false;
                $output['message'] = "Please check the username.";
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
        return response($response, $code);
    }

    public function unauthenticated()
    {
        $output['status'] = false;
        $output['message'] = 'You are not authenticated to access this url.';
        $response['data']=$this->encryptData($output);
        return response($response, 401);
    }
    public function getStaffs()
    {
        $user=Auth::user();
        if($user->hasRole('Admin'))
        {
            $roles=Role::where('id','!=',1)->where('id','!=',2)->get('name');
            $output['status'] = true;
            $output['message'] = 'Sucessfully Retrieved';
            $output['staffsList'] = $roles;
            $response['data']=$this->encryptData($output);
            return response($response, 200);
        }
        else
        {
            $output['status'] = false;
            $output['message'] = 'Unauthorized Access';
            $response['data']=$this->encryptData($output);
            return response($response, 400);
        }



    }
    public function addStaff(Request $request)
    {
        $user=Auth::user();
        if($user->hasRole('Admin'))
        {
            $rules = [
                'name' => 'required',
                'gender' => 'required',
                'address' => 'required',
                'city' => 'required',
                'dob' => 'required | date',
                'phone' => 'required|min:10',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (User::where('email', '=', $input->email)->count() == 0)
                {
                    $output=array();
                    $user = new User;
                    $user->name = $input->name;
                    $user->address = $input->address;
                    $user->gender = $input->gender;
                    $user->city = $input->city;
                    $user->dob = $input->dob;
                    $user->phone = $input->phone;
                    $user->email = $input->email;
                    $user->password = Hash::make($input->password);

                    $role_id=Role::where('name',$input->role)->get('id');


                    $user->role=$role_id[0]['id'];
                    $user->role_text=$input->role;
                    $user->assignRole($input->role);

                    if ($user->save())
                    {

                        $output['status']=true;
                        $output['message']='Successfully Added';
                        $output['userData']=$user;
                        $response['data']=$this->encryptData($output);
                        //$response=$this->encrypt($output);
                        $code = 200;
                    }
                    else
                    {
                        $output['status']=true;
                        $output['message']='Something went wrong. Please try again later.';
                        $response['data']=$this->encryptData($output);
                        // $response=$this->encrypt($output);
                        $code = 400;
                    }
                }
                else
                {
                    $output['status']=false;
                    $output['message']='Already Exists';
                    $response['data']=$this->encryptData($output);
                // $response=$this->encrypt($output);
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
            return response($response, $code);
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
        // $response=$this->encrypt($output);
            $code = 400221;
        }
    }
    public function decrypt_user(Request $request){
        $key='s#Jv6ejUxs7MKcgyTkC3X9zZLjslGw2f';
		$iv='K10Djpm7%9On%q7K';
		$result=openssl_decrypt(base64_decode($request->input('input')), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        echo $result;
	}
    // public function register_school(request $request)
    // {
    //     $validator=Validator::make($request->all(),
    //     [
    //         'name' => 'required',
    //         'email' => 'required|email',
    //         'password' => 'required',
    //         'address' => 'required',
    //         'mobile_number' => 'required',
    //     ]);
    //     if($validator->fails())
    //     {
    //         return response()->json(['message' => $validator->errors()->first()],401);
    //     }
    //     $data=$request->all();
    //     $users=$this->store();
    //   //  $response['token']=$users->createToken('Registration Data');
    //     $response['status']=true;
    //     $response['message']='Successfully Added';
    //     return response()->json($response,200);
    // }
    // public function login(request $request)
    // {
    //    // dd($request);

    //     Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')]);
    //     if(Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')]))
    //     {
    //         $school_user=Auth::user();
    //         $response['token']=$school_user->createToken('Registration Data');
    //         $response['message']='Successfully Logined';
    //         return response()->json($response,200);
    //     }
    //     else
    //     {
    //         return response()->json(['message' => 'error'],401);
    //     }
    // }
}
