<?php
namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

 class JWTToken
 {
    public static function createToken($userEmail):string{
        $key = env('JWT_KEY');
        $payload = [
            'iss'       => 'laravel-token', // issue name
            'iat'       => time(), // issue time
            'exp'       => time() + 60*60, // expire time and 60*60=3600second/1h
            'userEmail' => $userEmail,
        ];

       return JWT::encode($payload, $key, 'HS256'); //HS256 algorithm
           
    }

    public static function createTokenForSetPassword($userEmail):string{
        $key = env('JWT_KEY');
        $payload = [
            'iss'       => 'laravel-token', // issue name
            'iat'       => time(), // issue time
            'exp'       => time() + 60*20, // expire time 20 minutes
            'userEmail' => $userEmail,
        ];

       return JWT::encode($payload, $key, 'HS256'); //HS256 algorithm
           

    }

    public static function verifyToken($token):string{
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