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
        $result['user'] = $user;
        $result['accessToken'] = $token;

        return $result;
    }
}
