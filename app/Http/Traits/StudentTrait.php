<?php
namespace App\Http\Traits;
use App\Models\Student;
trait StudentTrait
{
    public function encrypt($input)
    {
        $output=new stdClass();
        $output->data=$input;
        $jsonvalue=json_encode($ouput);

        return base64_encode(openssl_encrypt($jsonvalue, 'AES-256-CBC', $key, OPENSSL_RAW_DATA,$iv));
    }
    public function decrypt($input){
        $key=env('RESPONSE_ENCRYPTION_KEY');
		$iv=env('RESPONSE_ENCRYPTION_IV');
		$result=openssl_decrypt(base64_decode($input), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return json_decode($result);
	}
    public function getAccessToken($user)
    {
        $token = $user->createToken('API Token')->accessToken;
        $result['user'] = $user;
        $result['accessToken'] = $token;

        return $result;
    }
}
