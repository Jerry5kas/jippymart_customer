<?php

use App\Http\Controllers\PlayIntegrityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/delete-user', [App\Http\Controllers\ApiController::class, 'deleteUserFromDb'])->name('deleteUserFromDb');

Route::post('/verify-integrity', [PlayIntegrityController::class, 'verifyToken']);

// OTP Authentication Routes
Route::post('/send-otp', [App\Http\Controllers\OTPController::class, 'sendOtp']);
Route::post('/verify-otp', [App\Http\Controllers\OTPController::class, 'verifyOtp']);
Route::post('/resend-otp', [App\Http\Controllers\OTPController::class, 'resendOtp']);
Route::post('/sms-delivery-status', [App\Http\Controllers\OTPController::class, 'smsDeliveryStatus']);

Route::post('/test', function () {
    return response()->json(['success' => true]);
});

// Route::post('/verify-integrity', [PlayIntegrityController::class, 'verifyToken']);
Route::middleware('auth:sanctum')->post('/refresh-firebase-token', [App\Http\Controllers\Auth\AjaxController::class, 'refreshFirebaseToken']);
