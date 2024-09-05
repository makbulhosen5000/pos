<?php
namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PhpParser\Node\Stmt\TryCatch;

use function PHPSTORM_META\type;

 class JWTToken
 {
    function createToken($userEmail):string{
        $key = env('JWT_KEY');
        $payload = [
            'iss'       => 'laravel-token', // issue name
            'iat'       => time(), // issue time
            'exp'       => time() + 60*60, // expire time and 60*60=3600second/1h
            'userEmail' => $userEmail,
        ];

       return JWT::encode($payload, $key, 'HS256'); //HS256 algorithm
           

    }

    function verifyToken($token):string{
        try {
            $key = env('JWT_KEY');
            $decode = JWT::decode($token, new Key($key,'HS256'));
            return $decode->userEmail;
        } catch (Exception $e) {
            return "Unauthorized";
        }
    }
 }


?>