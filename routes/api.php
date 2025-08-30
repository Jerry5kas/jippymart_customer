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

// Mart API Routes
Route::prefix('marts')->group(function () {
    // Public routes (no authentication required)
    Route::get('/categories', [App\Http\Controllers\Api\MartController::class, 'getMartCategories']);
    Route::get('/items', [App\Http\Controllers\Api\MartController::class, 'getMartItems']);
    Route::post('/item-details', [App\Http\Controllers\Api\MartController::class, 'getItemDetails']);
    Route::post('/search-items', [App\Http\Controllers\Api\MartController::class, 'searchItems']);
    Route::get('/vendors', [App\Http\Controllers\Api\MartController::class, 'getAllMartVendors']);
    Route::get('/vendor-details/{vendor_id}', [App\Http\Controllers\Api\MartController::class, 'getVendorDetails']);
    Route::post('/nearby-vendors', [App\Http\Controllers\Api\MartController::class, 'getNearbyVendors']);
    Route::post('/vendor-working-hours', [App\Http\Controllers\Api\MartController::class, 'getVendorWorkingHours']);
    Route::post('/vendor-special-discounts', [App\Http\Controllers\Api\MartController::class, 'getVendorSpecialDiscounts']);
    Route::post('/vendor-items-by-category', [App\Http\Controllers\Api\MartController::class, 'getVendorItemsByCategory']);

    // Protected routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user-profile', [App\Http\Controllers\Api\MartController::class, 'getUserProfile']);
        Route::post('/update-user-profile', [App\Http\Controllers\Api\MartController::class, 'updateUserProfile']);
    });
});

// Mart Categories API Routes (Enhanced)
Route::prefix('marts/categories')->group(function () {
    // Public routes (no authentication required)
    Route::get('/', [App\Http\Controllers\Api\Mart\MartCategoryController::class, 'index']);
    Route::get('/homepage', [App\Http\Controllers\Api\Mart\MartCategoryController::class, 'getHomepageCategories']);
    Route::get('/with-subcategories', [App\Http\Controllers\Api\Mart\MartCategoryController::class, 'getCategoriesWithSubcategories']);
    Route::post('/search', [App\Http\Controllers\Api\Mart\MartCategoryController::class, 'search']);
    Route::get('/{category_id}', [App\Http\Controllers\Api\Mart\MartCategoryController::class, 'show']);

    // Protected routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [App\Http\Controllers\Api\Mart\MartCategoryController::class, 'store']);
        Route::put('/{category_id}', [App\Http\Controllers\Api\Mart\MartCategoryController::class, 'update']);
        Route::delete('/{category_id}', [App\Http\Controllers\Api\Mart\MartCategoryController::class, 'destroy']);
        Route::post('/bulk-update', [App\Http\Controllers\Api\Mart\MartCategoryController::class, 'bulkUpdate']);
    });
});

// Mart Sub Categories API Routes (Enhanced)
Route::prefix('marts/subcategories')->group(function () {
    // Public routes (no authentication required)
    Route::get('/', [App\Http\Controllers\Api\Mart\MartSubCategoryController::class, 'index']);
    Route::get('/homepage', [App\Http\Controllers\Api\Mart\MartSubCategoryController::class, 'getHomepageSubcategories']);
    Route::get('/by-parent/{parent_category_id}', [App\Http\Controllers\Api\Mart\MartSubCategoryController::class, 'getByParentCategory']);
    Route::post('/search', [App\Http\Controllers\Api\Mart\MartSubCategoryController::class, 'search']);
    Route::get('/{subcategory_id}', [App\Http\Controllers\Api\Mart\MartSubCategoryController::class, 'show']);

    // Protected routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [App\Http\Controllers\Api\Mart\MartSubCategoryController::class, 'store']);
        Route::put('/{subcategory_id}', [App\Http\Controllers\Api\Mart\MartSubCategoryController::class, 'update']);
        Route::delete('/{subcategory_id}', [App\Http\Controllers\Api\Mart\MartSubCategoryController::class, 'destroy']);
        Route::post('/bulk-update', [App\Http\Controllers\Api\Mart\MartSubCategoryController::class, 'bulkUpdate']);
    });
});

// Mart Items API Routes (Enhanced)
Route::prefix('marts/items')->group(function () {
    // Public routes (no authentication required)
    Route::get('/', [App\Http\Controllers\Api\Mart\MartItemController::class, 'index']);
    Route::get('/featured', [App\Http\Controllers\Api\Mart\MartItemController::class, 'getFeaturedItems']);
    Route::get('/by-vendor/{vendor_id}', [App\Http\Controllers\Api\Mart\MartItemController::class, 'getByVendor']);
    Route::get('/by-category/{category_id}', [App\Http\Controllers\Api\Mart\MartItemController::class, 'getByCategory']);
    Route::get('/by-subcategory/{subcategory_id}', [App\Http\Controllers\Api\Mart\MartItemController::class, 'getBySubCategory']);
    Route::get('/best-sellers', [App\Http\Controllers\Api\Mart\MartItemController::class, 'getBestSellers']);
    Route::get('/featured-items', [App\Http\Controllers\Api\Mart\MartItemController::class, 'getFeatured']);
    Route::get('/new-items', [App\Http\Controllers\Api\Mart\MartItemController::class, 'getNewItems']);
    Route::get('/seasonal', [App\Http\Controllers\Api\Mart\MartItemController::class, 'getSeasonal']);
    Route::get('/spotlight', [App\Http\Controllers\Api\Mart\MartItemController::class, 'getSpotlight']);
    Route::get('/steal-of-moment', [App\Http\Controllers\Api\Mart\MartItemController::class, 'getStealOfMoment']);
    Route::get('/trending', [App\Http\Controllers\Api\Mart\MartItemController::class, 'getTrending']);
    Route::post('/search', [App\Http\Controllers\Api\Mart\MartItemController::class, 'search']);
    Route::get('/{item_id}', [App\Http\Controllers\Api\Mart\MartItemController::class, 'show']);

    // Protected routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [App\Http\Controllers\Api\Mart\MartItemController::class, 'store']);
        Route::put('/{item_id}', [App\Http\Controllers\Api\Mart\MartItemController::class, 'update']);
        Route::delete('/{item_id}', [App\Http\Controllers\Api\Mart\MartItemController::class, 'destroy']);
        Route::post('/bulk-update', [App\Http\Controllers\Api\Mart\MartItemController::class, 'bulkUpdate']);
    });
});
