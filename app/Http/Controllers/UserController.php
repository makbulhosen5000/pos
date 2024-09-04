<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * user registration by create()
     */
    public function userRegistration(Request $request)
    {
       try {
        User::created([
            'first_name'       => $request->input('first_name'),
            'last_name'        => $request->input('last_name'),
            'email'            => $request->input('email'),
            'mobile'           => $request->input('mobile'),
            'password'         => $request->input('password'),
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

   
}
