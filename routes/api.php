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

// Delivery Charge API Routes
Route::post('/calculate-delivery-charge', [App\Http\Controllers\ApiController::class, 'calculateDeliveryCharge']);
Route::get('/delivery-settings', [App\Http\Controllers\ApiController::class, 'getDeliverySettings']);
Route::post('/update-delivery-settings', [App\Http\Controllers\ApiController::class, 'updateDeliverySettings']);

// Product Stock API Routes
Route::post('/product-stock-info', [App\Http\Controllers\ApiController::class, 'getProductStockInfo']);

// Search API Routes
Route::prefix('search')->group(function () {
    // Category search endpoints
    Route::get('/categories', [App\Http\Controllers\SearchController::class, 'searchCategories']);
    Route::get('/categories/published', [App\Http\Controllers\SearchController::class, 'getPublishedCategories']);

    // Mart items search endpoints
    Route::get('/items', [App\Http\Controllers\SearchController::class, 'searchMartItems']);
    Route::get('/items/featured', [App\Http\Controllers\SearchController::class, 'getFeaturedMartItems']);

    // Food items search endpoints
    Route::get('/food', [App\Http\Controllers\FoodSearchController::class, 'searchFoodItems']);

    // Health check endpoint
    Route::get('/health', [App\Http\Controllers\SearchController::class, 'healthCheck']);
});

// Mart API Routes
Route::prefix('mart')->group(function () {
    // Coupon endpoints
    Route::get('/coupons', [App\Http\Controllers\MartController::class, 'getMartCoupons']);
    Route::post('/coupons/apply', [App\Http\Controllers\MartController::class, 'applyMartCoupon']);
});

// Razorpay API Routes
Route::prefix('razorpay')->group(function () {
    // Public routes (no authentication required)
    Route::get('/settings', [App\Http\Controllers\Api\RazorpayController::class, 'getSettings']);

    // Protected routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/create-order', [App\Http\Controllers\Api\RazorpayController::class, 'createOrder']);
        Route::post('/verify-payment', [App\Http\Controllers\Api\RazorpayController::class, 'verifyPayment']);
        Route::post('/payment-details', [App\Http\Controllers\Api\RazorpayController::class, 'getPaymentDetails']);
        Route::post('/refund-payment', [App\Http\Controllers\Api\RazorpayController::class, 'refundPayment']);
        Route::post('/order-status', [App\Http\Controllers\Api\RazorpayController::class, 'getOrderStatus']);
    });
});

