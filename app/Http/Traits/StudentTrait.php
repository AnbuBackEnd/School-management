<?php
namespace App\Http\Traits;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
trait StudentTrait
{
    public function encrypt($input)
    {
        $output=new stdClass();
        $output->data=$input;
        $jsonvalue=json_encode($ouput);

        return base64_encode(openssl_encrypt($jsonvalue, 'AES-256-CBC', $key, OPENSSL_RAW_DATA,$iv));
    }
    public function getUserName($user_id)
    {
        $users = DB::table('users')->where('id','='.$user_id)->get('name');
        if($users)
        {
            return $users[0]['name'];
        }
    }

    public function encrypt_sample(){
		$str=new stdClass;
		 $str->name='bristo Marticulation School';
         $str->admin_name='Anbarasu';
         $str->address='fdskgh klsjgldsj klsdgjkl dsjlj lhjsdljhlsdjl';
         $str->phone='9787522164';
         $str->email='anbu@gmail.com';
         $str->password='dfklhjdfklj';
		$jsonvalue=json_encode($str);
        $key='s#Jv6ejUxs7MKcgyTkC3X9zZLjslGw2f';
		$iv='K10Djpm7%9On%q7K';
      echo base64_encode(openssl_encrypt($jsonvalue, 'AES-256-CBC', $key, OPENSSL_RAW_DATA,$iv));
  	}

    public function encryptData($content)
    {
        $key='s#Jv6ejUxs7MKcgyTkC3X9zZLjslGw2f';
		$iv='K10Djpm7%9On%q7K';
        if (gettype($content) == 'string') {
            $encrypted = base64_encode(openssl_encrypt($content, 'AES-256-CBC', $key, OPENSSL_RAW_DATA,$iv));
        }
        else{
            $encrypted = base64_encode(openssl_encrypt(json_encode($content), 'AES-256-CBC', $key, OPENSSL_RAW_DATA,$iv));
        }
        return $encrypted;

    }
    public function decrypt($input){
        $key='s#Jv6ejUxs7MKcgyTkC3X9zZLjslGw2f';
		$iv='K10Djpm7%9On%q7K';
		$result=openssl_decrypt(base64_decode($input), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return json_decode($result);
	}
    public function getAccessToken($user)
    {
        $token = $user->createToken('API Token')->accessToken;
        $user->accessToken=$token;




        return $user;
    }
}
