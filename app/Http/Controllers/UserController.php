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

    public function loginPage(){
        return view('pages.auth.login-page');
    }
    public function registrationPage(){
        return view('pages.auth.registration-page');
    }
    public function sentOTPPage(){
        return view('pages.auth.sent-otp-page');
    }
    public function verifyOTPPage(){
        return view('pages.auth.verify-otp-page');
    }
    public function resetPasswordPage(){
        return view('pages.auth.reset-password-page');
    }
    public function dashboardPage(){
        return view('pages.dashboard.profile-page');
    }

    
    /**
     * backend  function start from here
     * 
     *  user registration function
     */
    public function userRegistration(Request $request){
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
        ],401);
       }

    }
     /**
     * user login function
     */
    public function userLogin(Request $request){
        $count = User::where('email', '=', $request->input('email'))
            ->where('password', '=', $request->input('password') )
            ->count();

        if($count == 0){
           $token = JWTToken::createToken($request->input('email'));
           return response()->json([
            'status'  =>  'success',
            'message' => "User login successful"
        ],200)->cookie( 'token',$token,60*24*30);
        }else{
            return response()->json([
                'status'  =>  'failed',
                'message' => 'Unauthorized'
            ],401);
        }
    
    }
      /**
     * otp send function
     */
    public function sentOTP(Request $request){
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
            ],401);
        }
    }
       /**
     * otp verification function
     */

    public function verifyOTP(Request $request){
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email','=',$email)->where('otp','=',$otp)->count();

        if($count == 1){
             //otp update in database
             User::where('email','=',$email)->update(['otp'=>0]);
             //user password reset token issue
             $token = JWTToken::createTokenForSetPassword($request->input('email'));
             return response()->json([
              'status'  =>  'success',
              'message' => "OTP verification successful",
              'token' => $token
          ],200);
        }else{
            return response()->json([
                'status'  =>  'failed',
                'message' => 'Unauthorized'
            ],401);
        }
    }
      /**
     * Reset password function
     */
    function resetPassword(Request $request){
        try{
            $email = $request->header('email');
            $password = $request->input('password');
            User::where('email','=',$email)->update(['password'=>$password]);
            return response()->json([
            'status'  =>  'success',
            'message' => 'Password reset request successfully'
        ],200);
        }catch(Exception $e){
            return response()->json([
                'status'  =>  'fail',
                'message' => 'Something went wrong'
            ],401);
        }
    }

  
    

}
