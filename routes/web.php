<?php

use App\Http\Controllers\DemoController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//web api routes
Route::post('/user-registration',[UserController::class,'userRegistration']);
Route::post('/user-login',[UserController::class,'userLogin']);
Route::post('/send-otp',[UserController::class,'sentOTP']);
Route::post('/verify-otp',[UserController::class,'verifyOTP']);
Route::post('/reset-password',[UserController::class,'resetPassword'])->middleware([TokenVerificationMiddleware::class]);

//page route
Route::get('/userLogin',[UserController::class,'loginPage']);
Route::get('/userRegistration',[UserController::class,'registrationPage']);
Route::get('/sendOtp',[UserController::class,'sentOTPPage']);
Route::get('/verifyOtp',[UserController::class,'verifyOTPPage']);
Route::get('/resetPassword',[UserController::class,'resetPasswordPage']);
Route::get('/dashboard',[UserController::class,'dashboardPage']);
