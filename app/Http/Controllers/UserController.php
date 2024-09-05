<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * user registration by create()
     */
    public function userRegistration(Request $request)
    {
       try {
        User::create([
            'first_name'       => $request->input('first_name'),
            'last_name'        => $request->input('last_name'),
            'email'            => $request->input('email'),
            'mobile'           => $request->input('mobile'),
            'password'         => $request->input('password')
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'User Registration Successfully'
        ],200);
       } catch (Exception $e) {
        return response()->json([
            'status'  =>  'failed',
            'message' => 'User Registration Failed'
        ]);
       }

    }
     /**
     * user login by create()
     */
    public function userLogin(Request $request){
        $count = User::where('email', '=', $request->input('email'))
            ->where('password', '=', $request->input('password') )
            ->count();

        if($count == 0){
           $token = JWTToken::createToken($request->input('email'));
           return response()->json([
            'status'  =>  'success',
            'message' => "User login successful",
            'token' => $token
        ],200);
        }else{
            return response()->json([
                'status'  =>  'failed',
                'message' => 'Unauthorized'
            ],200);
        }

    }
}
