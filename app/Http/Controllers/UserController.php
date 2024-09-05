<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
    public function sentOtpCode(Request $request){
        $email = $request->input('email');
        $otp = rand(1000,9999);
        $count = User::where('email', '=', $email)->count();
        
        if($count == 1){
            // otp send to email 
            Mail::to($email)->send(new OTPMail($otp));
            //otp code update in database table
            User::where('email','=', $email)->update(['otp'=>$otp]);
            return response()->json([
                'status'  =>  'success',
                'message' => '4 digit otp code has been sent to your mail!'
            ],200);
        }else{
            return response()->json([
                'status'  =>  'failed',
                'message' => 'Unauthorized'
            ],200);
        }
    }
}
