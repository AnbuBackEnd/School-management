<?php
//composer require doctrine/dbal install package for column modification
namespace App\Http\Controllers;
use Auth;
use Hash;
use Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
                if ($user->save())
                {
                    $output['status']=true;
                    $output['message']='Successfully Added';
                    $output['userData']=$user;
                    $response['data']=$output;
                    //$response=$this->encrypt($output);
                    $code = 200;
                }
                else
                {
                    $output['status']=true;
                    $output['message']='Something went wrong. Please try again later.';
                    $response['data']=$output;
                    // $response=$this->encrypt($output);
                    $code = 400;
                }
            }
            else
            {
                $output['status']=false;
                $output['message']='Already Exists';
                $response['data']=$output;
            // $response=$this->encrypt($output);
                $code = 409;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']=[$validator->errors()->first()];
            //$response=$this->encrypt($output);
            $response['data']=$output;
            $code=400;
        }
        return response($response, $code);
    }
    public function account_confirmation(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'otp_code' => 'required|min:6',

        ];
        $input=(array)$this->decrypt($request->input('input'));
        $validator = Validator::make($input, $rules);
        if(!$validator->fails())
        {
            if (User::where(['email' => $input['email'],'otp_code' => $input['otp_code'],'account_verification_status',0])->count() == 1)
            {
                User::where('email',$input['email'])->update(array('account_verification_status' => 1));
                $output['status']=true;
                $output['message']='Successfully Updated';
                $response['data']=$output;
            // $response=$this->encrypt($output);
                $code = 200;
            }
            else
            {
                $output['status']=false;
                $output['message']='Invalid Otp Code';
                $response['data']=$output;
            // $response=$this->encrypt($output);
                $code = 409;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']=[$validator->errors()->first()];
            //$response=$this->encrypt($output);
            $response['data']=$output;
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
        $input=(array)$this->decrypt($request->input('input'));
        $validator = Validator::make($input, $rules);
        if(!$validator->fails())
        {
            $isExist = User::where(['email' => $input['email'],'account_verification_status' => 1])->first();

            if (isset($isExist->id))
            {
                if (Hash::check($input['password'], $isExist->password))
                {
                    $output['status']=true;
                    $output['token'] = $this->getAccessToken($isExist);
                    $output['message']='Successfully Logined';
                    $response['data']=$output;
                    $code = 200;
                }
                else{
                    $output['status']=false;
                    $output['message'] = "Please check the password.";
                    $response['data']=$output;
                    $code = 400;
                }
            }
            else{
                $output['status']=false;
                $output['message'] = "Please check the username.";
                $response['data']=$output;
                $code = 400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']=[$validator->errors()->first()];
            //$response=$this->encrypt($output);
            $response['data']=$output;
            $code=400;
        }
        return response($response, $code);
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
