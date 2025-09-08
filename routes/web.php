<?php


use App\Http\Controllers\AllRestaurantsController;

use App\Http\Controllers\FavoritesController;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\PageController;
use App\Http\Controllers\PlayIntegrityController;
use App\Http\Controllers\PrivacyController;

use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RestaurantStatusController;

use App\Http\Controllers\TermsController;

use Illuminate\Support\Facades\Route;


/*

|--------------------------------------------------------------------------

| Web Routes

|--------------------------------------------------------------------------

|

| Here is where you can register web routes for your application. These

| routes are loaded by the RouteServiceProvider within a group which

| contains the "web" middleware group. Now create something great!

|

*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('set-location', [App\Http\Controllers\HomeController::class, 'setLocation'])->name('set-location');

Route::get('login', [App\Http\Controllers\LoginController::class, 'login'])->name('login');

Route::get('signup', [App\Http\Controllers\LoginController::class, 'signup'])->name('signup');

Route::get('socialsignup', [App\Http\Controllers\LoginController::class, 'socialsignup'])->name('socialsignup');

Route::get('search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');

Route::get('lang/change', [App\Http\Controllers\LangController::class, 'change'])->name('changeLang');

Route::get('privacy', [App\Http\Controllers\CmsController::class, 'privacypolicy'])->name('privacy');

Route::get('privacyandpolicy', [App\Http\Controllers\PageController::class, 'staticprivacypolicy'])->name('staticprivacypolicy');

Route::get('privacypolicy', [App\Http\Controllers\PageController::class, 'staticprivacypolicy'])->name('staticprivacypolicy');

Route::get('terms', [App\Http\Controllers\CmsController::class, 'termsofuse'])->name('terms');

Route::get('deleteaccount', [App\Http\Controllers\PageController::class, 'deleteaccount'])->name('deleteaccount');

Route::get('deleteaccountdatarequestpage', [App\Http\Controllers\PageController::class, 'deletedatarequest'])->name('deletedatarequest');

Route::get('deletedriveraccountdata', [App\Http\Controllers\PageController::class, 'deletedriver'])->name('deletedriver');

Route::get('JippyMartQrcode', [PageController::class, 'qrcode'])->name('qrcode');

Route::get('deliveryofsupport', [App\Http\Controllers\CmsController::class, 'deliveryofsupport'])->name('deliveryofsupport');

Route::post('takeaway', [App\Http\Controllers\PaymentController::class, 'takeawayOption'])->name('takeaway');

Route::get('my_order', [App\Http\Controllers\OrderController::class, 'index'])->name('my_order');

Route::get('pay-wallet', [App\Http\Controllers\TransactionController::class, 'proccesstopaywallet'])->name('pay-wallet');
Route::post('wallet-proccessing', [App\Http\Controllers\TransactionController::class, 'walletProccessing'])->name('wallet-proccessing');
Route::post('wallet-process-stripe', [App\Http\Controllers\TransactionController::class, 'processStripePayment'])->name('wallet-process-stripe');
Route::post('wallet-process-paypal', [App\Http\Controllers\TransactionController::class, 'processPaypalPayment'])->name('wallet-process-paypal');
Route::post('razorpaywalletpayment', [App\Http\Controllers\TransactionController::class, 'razorpaypayment'])->name('razorpaywalletpayment');
Route::post('wallet-process-mercadopago', [App\Http\Controllers\TransactionController::class, 'processMercadoPagoPayment'])->name('wallet-process-mercadopago');
Route::get('wallet-success', [App\Http\Controllers\TransactionController::class, 'success'])->name('wallet-success');
Route::get('wallet-notify', [App\Http\Controllers\TransactionController::class, 'notify'])->name('wallet-notify');

Route::get('completed_order', [App\Http\Controllers\OrderController::class, 'completedOrders'])->name('completed_order');

Route::get('pending_order', [App\Http\Controllers\OrderController::class, 'pendingOrder'])->name('pending_order');

Route::get('cancelled_order', [App\Http\Controllers\OrderController::class, 'cancelledOrder'])->name('cancelled_order');

Route::get('my_dinein', [App\Http\Controllers\OrderController::class, 'myDinein'])->name('my_dinein');

Route::get('dinein', [App\Http\Controllers\OrderController::class, 'dinein'])->name('dinein');

Route::get('contact-us', [App\Http\Controllers\ContactUsController::class, 'index'])->name('contact_us');

Route::get('trending', [App\Http\Controllers\TrendingController::class, 'index'])->name('trending');

Route::get('categories', [App\Http\Controllers\RestaurantController::class, 'categoryList'])->name('categorylist');

// Test route for icon debugging
Route::get('icon-test', function () {
    return view('icon-test');
})->name('icon-test');

Route::get('category/{id}', [App\Http\Controllers\RestaurantController::class, 'categoryDetail'])->name('category_detail');

Route::get('restaurant', [App\Http\Controllers\RestaurantController::class, 'index'])->name('restaurant');
Route::get('restaurant/{id}/{restaurant_slug}/{zone_slug}', [App\Http\Controllers\RestaurantController::class, 'show'])->name('restaurant.show');

Route::get('cart', [App\Http\Controllers\ProductController::class, 'cart'])->name('cart');

Route::post('cart/sync', [App\Http\Controllers\ProductController::class, 'syncCart'])->name('cart.sync');

Route::post('add-to-cart', [App\Http\Controllers\ProductController::class, 'addToCart'])->name('add-to-cart');

Route::post('reorder-add-to-cart', [App\Http\Controllers\ProductController::class, 'reorderaddToCart'])->name('reorder-add-to-cart');

Route::get('products', [App\Http\Controllers\ProductController::class, 'productListAll'])->name('productlist.all');

// Route::get('product/{id}', [App\Http\Controllers\ProductController::class, 'productDetail'])->name('productDetail');
Route::get('product/{id}', function($id) {
    return redirect('/')->with('message', 'Product detail page is not available.');
})->name('productDetail');
Route::get('product/{id}/restaurant-info', [App\Http\Controllers\ProductController::class, 'getRestaurantInfo'])
    ->name('product.restaurant-info')
    ->middleware('cache.headers:public;max_age=3600;etag');

Route::get('products/{type}/{id}', [App\Http\Controllers\ProductController::class, 'productList'])->name('productList');

Route::post('update-cart', [App\Http\Controllers\ProductController::class, 'update'])->name('update-cart');

Route::post('remove-from-cart', [App\Http\Controllers\ProductController::class, 'remove'])->name('remove-from-cart');

Route::post('clear-cart', [App\Http\Controllers\ProductController::class, 'clearCart'])->name('clear-cart');

Route::post('change-quantity-cart', [App\Http\Controllers\ProductController::class, 'changeQuantityCart'])->name('change-quantity-cart');

Route::post('apply-coupon', [App\Http\Controllers\ProductController::class, 'applyCoupon'])->name('apply-coupon');

Route::get('checkout', [App\Http\Controllers\CheckoutController::class, 'checkout'])->name('checkout');

Route::post('order-complete', [App\Http\Controllers\ProductController::class, 'orderComplete'])->name('order-complete');

Route::post('order-tip-add', [App\Http\Controllers\ProductController::class, 'orderTipAdd'])->name('order-tip-add');

Route::post('order-delivery-option', [App\Http\Controllers\ProductController::class, 'orderDeliveryOption'])->name('order-delivery-option');

Route::get('pay', [App\Http\Controllers\CheckoutController::class, 'proccesstopay'])->name('pay');

Route::post('order-proccessing', [App\Http\Controllers\CheckoutController::class, 'orderProccessing'])->name('order-proccessing');
Route::post('store-order-session', [App\Http\Controllers\CheckoutController::class, 'storeOrderSession'])->name('store-order-session');

Route::post('stripepaymentcallback', [App\Http\Controllers\PaymentController::class, 'stripePaymentcallback'])->name('stripepaymentcallback');

Route::post('process-stripe', [App\Http\Controllers\CheckoutController::class, 'processStripePayment'])->name('process-stripe');

Route::post('process-paypal', [App\Http\Controllers\CheckoutController::class, 'processPaypalPayment'])->name('process-paypal');

Route::post('razorpaypayment', [App\Http\Controllers\CheckoutController::class, 'razorpaypayment'])->name('razorpaypayment');

Route::post('process-mercadopago', [App\Http\Controllers\CheckoutController::class, 'processMercadoPagoPayment'])->name('process-mercadopago');

Route::get('success', [App\Http\Controllers\CheckoutController::class, 'success'])->name('success');

Route::get('failed', [App\Http\Controllers\CheckoutController::class, 'failed'])->name('failed');

Route::get('notify', [App\Http\Controllers\CheckoutController::class, 'notify'])->name('notify');

Route::get('transactions', [App\Http\Controllers\TransactionController::class, 'index'])->name('transactions');

Route::get('/offers', [App\Http\Controllers\OffersController::class, 'index'])->name('offers');

Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');

Route::get('favorite-stores', [FavoritesController::class, 'index'])->name('favorites');

Route::get('favorite-products', [FavoritesController::class, 'favProduct'])->name('favorites.product');

Route::get('/faq', [App\Http\Controllers\FaqController::class, 'index'])->name('faq');

Route::get('restaurants/category/{id}', [App\Http\Controllers\AllRestaurantsController::class, 'RestaurantsbyCategory'])->name('RestaurantsbyCategory');

Route::get('/restaurants', [App\Http\Controllers\AllRestaurantsController::class, 'index'])->name('restaurants');

Route::get('/dineinRestaurants', [App\Http\Controllers\DiveinRestaurantController::class, 'index'])->name('dineinRestaurants');

Route::get('/dyiningrestaurant', [App\Http\Controllers\DiveinRestaurantController::class, 'dyiningrestaurant'])->name('dyiningrestaurant');

Route::post('/sendnotification', [App\Http\Controllers\RestaurantController::class, 'sendnotification'])->name('sendnotification');

// Restaurant Status Management Routes
Route::prefix('restaurant-status')->group(function () {
    Route::post('/get-status', [RestaurantStatusController::class, 'getStatus'])->name('restaurant.status.get');
    Route::post('/get-status-from-firestore', [RestaurantStatusController::class, 'getStatusFromFirestore'])->name('restaurant.status.firestore');
    Route::post('/update-status', [RestaurantStatusController::class, 'updateStatus'])->name('restaurant.status.update');
    Route::get('/history/{restaurant_id}', [RestaurantStatusController::class, 'getStatusHistory'])->name('restaurant.status.history');
});

Route::post('setToken', [App\Http\Controllers\Auth\AjaxController::class, 'setToken'])->name('setToken');

Route::post('logout', [App\Http\Controllers\Auth\AjaxController::class, 'logout'])->name('logout');

Route::post('newRegister', [App\Http\Controllers\Auth\AjaxController::class, 'newRegister'])->name('newRegister');

Route::post('checkEmail', [App\Http\Controllers\Auth\AjaxController::class, 'checkEmail'])->name('checkEmail');

Route::post('sendemail/send', [App\Http\Controllers\SendEmailController::class, 'send'])->name('sendContactUsMail');

Route::get('my_order/{id}', [App\Http\Controllers\OrderController::class, 'edit'])->name('orderDetails');

Route::post('add-cart-note', [App\Http\Controllers\OrderController::class, 'addCartNote'])->name('add-cart-note');

Route::get('proccesspaystack', [App\Http\Controllers\CheckoutController::class, 'proccesspaystack'])->name('proccesspaystack');

Route::get('page/{slug}', [App\Http\Controllers\CmsController::class, 'index'])->name('page');

Route::post('order-schedule-time-add', [App\Http\Controllers\ProductController::class, 'orderScheduleTimeAdd'])->name('order-schedule-time-add');

Route::post('send-email', [App\Http\Controllers\SendEmailController::class, 'sendMail'])->name('sendMail');

Route::get('lang/change', [App\Http\Controllers\LangController::class, 'change'])->name('changeLang');

Route::get('forgot-password', [App\Http\Controllers\LoginController::class, 'forgotPassword'])->name('forgot-password');

Route::get('buy-gift-card', [App\Http\Controllers\GiftCardController::class, 'index'])->name('customize.giftcard');

Route::post('gift-card-processing', [App\Http\Controllers\GiftCardController::class, 'giftCardProcessing'])->name('giftcard.processing');

Route::get('pay-giftcard', [App\Http\Controllers\GiftCardController::class, 'proccesstopay'])->name('giftcard.pay');

Route::get('gift-card-success', [App\Http\Controllers\GiftCardController::class, 'success'])->name('giftcard.success');

Route::get('giftcards', [App\Http\Controllers\GiftCardController::class, 'giftcards'])->name('giftcards');

Route::post('giftcard-razorpaypayment', [App\Http\Controllers\GiftCardController::class, 'razorpaypayment'])->name('giftcard.razorpaypayment');

Route::post('giftcard-stripepayment', [App\Http\Controllers\GiftCardController::class, 'processStripePayment'])->name('giftcard.stripepayment');

Route::post('giftcard-paypalpayment', [App\Http\Controllers\GiftCardController::class, 'processPaypalPayment'])->name('giftcard.paypalpayment');

Route::get('delivery-address', [App\Http\Controllers\DeliveryAddressController::class, 'index'])->name('delivery-address.index');

Route::post('store-firebase-service', [App\Http\Controllers\HomeController::class, 'storeFirebaseService'])->name('store-firebase-service');

Route::post('remove-coupon', [App\Http\Controllers\ProductController::class, 'removeCoupon'])->name('remove-coupon');

// Debug route for delivery charge
Route::get('/debug-delivery', function () {
    $cart = session()->get('cart', []);
    $deliveryChargeService = new \App\Services\DeliveryChargeService();

    $debug = [
        'new_system_enabled' => $deliveryChargeService->shouldUseNewDeliverySystem(),
        'cart_exists' => !empty($cart),
        'cart_items_count' => count($cart['item'] ?? []),
        'deliverykm' => $cart['deliverykm'] ?? 'NOT SET',
        'deliverycharge' => $cart['deliverycharge'] ?? 'NOT SET',
        'deliverychargemain' => $cart['deliverychargemain'] ?? 'NOT SET',
        'delivery_charge_calculation_exists' => isset($cart['delivery_charge_calculation']),
        'cookies' => [
            'deliveryChargemain' => $_COOKIE['deliveryChargemain'] ?? 'NOT SET',
            'address_lat' => $_COOKIE['address_lat'] ?? 'NOT SET',
            'address_lng' => $_COOKIE['address_lng'] ?? 'NOT SET',
            'restaurant_latitude' => $_COOKIE['restaurant_latitude'] ?? 'NOT SET',
            'restaurant_longitude' => $_COOKIE['restaurant_longitude'] ?? 'NOT SET',
        ]
    ];

    if (isset($cart['delivery_charge_calculation'])) {
        $calculation = $cart['delivery_charge_calculation'];
        $debug['calculation'] = [
            'original_fee' => $calculation['original_fee'],
            'actual_fee' => $calculation['actual_fee'],
            'is_free_delivery' => $calculation['is_free_delivery'],
            'savings' => $calculation['savings'],
            'ui_type' => $calculation['ui_components']['type'] ?? 'NOT SET',
            'ui_main_text' => $calculation['ui_components']['main_text'] ?? 'NOT SET'
        ];
    }

    // Calculate item total
    $itemTotal = 0;
    if (isset($cart['item']) && is_array($cart['item'])) {
        foreach ($cart['item'] as $restaurantItems) {
            if (is_array($restaurantItems)) {
                foreach ($restaurantItems as $item) {
                    $basePrice = floatval($item['item_price'] ?? 0);
                    $extraPrice = floatval($item['extra_price'] ?? 0);
                    $quantity = floatval($item['quantity'] ?? 1);
                    $itemTotal += ($basePrice + $extraPrice) * $quantity;
                }
            }
        }
    }
    $debug['item_total'] = $itemTotal;

    // Calculate distance if coordinates are available
    if (isset($_COOKIE['address_lat']) && isset($_COOKIE['address_lng']) && isset($_COOKIE['restaurant_latitude']) && isset($_COOKIE['restaurant_longitude']) &&
        $_COOKIE['address_lat'] && $_COOKIE['address_lng'] && $_COOKIE['restaurant_latitude'] && $_COOKIE['restaurant_longitude']) {
        $lat1 = floatval($_COOKIE['address_lat']);
        $lon1 = floatval($_COOKIE['address_lng']);
        $lat2 = floatval($_COOKIE['restaurant_latitude']);
        $lon2 = floatval($_COOKIE['restaurant_longitude']);

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $km = $miles * 1.609344;

        $debug['calculated_distance'] = round($km, 2);
        $debug['coordinates'] = [
            'address' => [$lat1, $lon1],
            'restaurant' => [$lat2, $lon2]
        ];

        // Test calculation
        $testCalculation = $deliveryChargeService->calculateDeliveryCharge($itemTotal, $km);
        $debug['test_calculation'] = [
            'original_fee' => $testCalculation['original_fee'],
            'actual_fee' => $testCalculation['actual_fee'],
            'is_free_delivery' => $testCalculation['is_free_delivery'],
            'ui_type' => $testCalculation['ui_components']['type'],
            'ui_main_text' => $testCalculation['ui_components']['main_text']
        ];

        // Business rules check
        $debug['business_rules'] = [
            'item_total_above_threshold' => $itemTotal >= 299,
            'distance_within_free_limit' => $km <= 7,
            'should_be_free_delivery' => ($itemTotal >= 299 && $km <= 7),
            'should_be_extra_distance' => ($itemTotal >= 299 && $km > 7),
            'should_be_normal_charge' => ($itemTotal < 299)
        ];
    }

    return response()->json($debug);
});

// Test route with real vendor ID from Firebase
Route::get('/test-real-vendor', function () {
    $realVendorId = '0QcKVUa4aqJVYQ0957kz'; // From your Firebase document

    return response()->json([
        'message' => 'Test with real vendor ID',
        'vendor_id' => $realVendorId,
        'expected_coordinates' => [
            'latitude' => 15.490739,
            'longitude' => 80.048471
        ],
            'test_urls' => [
        'debug_delivery' => "http://localhost:8000/debug-delivery"
    ],
    'instructions' => [
        '1. Product detail page is now hidden',
        '2. Focus on checkout functionality',
        '3. Test delivery charge calculation directly'
    ]
    ]);
});

// Test route to set coordinates and test delivery charge
Route::get('/test-coordinates-setup', function () {
    // Set test coordinates (different from restaurant to test distance calculation)
    setcookie('address_lat', '15.4865041', time() + 3600, '/');
    setcookie('address_lng', '80.0499408', time() + 3600, '/');
    setcookie('restaurant_latitude', '15.490739', time() + 3600, '/');
    setcookie('restaurant_longitude', '80.048471', time() + 3600, '/');

    // Calculate actual distance between coordinates
    $lat1 = 15.4865041; // User location
    $lon1 = 80.0499408;
    $lat2 = 15.490739;  // Restaurant location
    $lon2 = 80.048471;

    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $actualDistance = $miles * 1.609344;

    $deliveryChargeService = new \App\Services\DeliveryChargeService();

    // Test different item totals
    $testScenarios = [
        ['item_total' => 180, 'description' => 'Below threshold (< 299)'],
        ['item_total' => 350, 'description' => 'Above threshold (≥ 299)']
    ];

    $results = [];
    foreach ($testScenarios as $scenario) {
        $calculation = $deliveryChargeService->calculateDeliveryCharge($scenario['item_total'], $actualDistance);
        $results[] = [
            'scenario' => $scenario['description'],
            'item_total' => $scenario['item_total'],
            'distance' => round($actualDistance, 2),
            'calculation' => $calculation,
            'business_rule_applied' => [
                'below_threshold' => $scenario['item_total'] < 299,
                'above_threshold' => $scenario['item_total'] >= 299,
                'within_free_distance' => $actualDistance <= 7,
                'beyond_free_distance' => $actualDistance > 7
            ]
        ];
    }

    return response()->json([
        'message' => 'Coordinates setup and delivery charge test',
        'coordinates' => [
            'user_location' => [$lat1, $lon1],
            'restaurant_location' => [$lat2, $lon2],
            'calculated_distance_km' => round($actualDistance, 2)
        ],
        'test_scenarios' => $results,
        'cookies_set' => [
            'address_lat' => $_COOKIE['address_lat'] ?? 'NOT SET',
            'address_lng' => $_COOKIE['address_lng'] ?? 'NOT SET',
            'restaurant_latitude' => $_COOKIE['restaurant_latitude'] ?? 'NOT SET',
            'restaurant_longitude' => $_COOKIE['restaurant_longitude'] ?? 'NOT SET'
        ],
        'next_steps' => [
            '1. Visit product detail page: http://localhost:8000/product/0QcKVUa4aqJVYQ0957kz',
            '2. Check browser console for vendor data',
            '3. Add item to cart',
            '4. Check delivery charge calculation',
            '5. Verify business rules are applied correctly'
        ]
    ]);
});

// Comprehensive test route for delivery charge system with real coordinates
Route::get('/test-delivery-system-complete', function () {
    $deliveryChargeService = new \App\Services\DeliveryChargeService();

    // Test scenarios with different coordinates and distances
    $testScenarios = [
        [
            'name' => 'Same Location (0km) - Item < 299',
            'user_lat' => '15.4865041',
            'user_lng' => '80.0499408',
            'restaurant_lat' => '15.4865041',
            'restaurant_lng' => '80.0499408',
            'item_total' => 180,
            'expected_distance' => 0,
            'expected_fee' => 23
        ],
        [
            'name' => 'Same Location (0km) - Item >= 299',
            'user_lat' => '15.4865041',
            'user_lng' => '80.0499408',
            'restaurant_lat' => '15.4865041',
            'restaurant_lng' => '80.0499408',
            'item_total' => 350,
            'expected_distance' => 0,
            'expected_fee' => 0
        ],
        [
            'name' => '5km Distance - Item < 299',
            'user_lat' => '15.4865041',
            'user_lng' => '80.0499408',
            'restaurant_lat' => '15.4865041',
            'restaurant_lng' => '80.0499408',
            'item_total' => 250,
            'expected_distance' => 5,
            'expected_fee' => 23
        ],
        [
            'name' => '10km Distance - Item < 299',
            'user_lat' => '15.4865041',
            'user_lng' => '80.0499408',
            'restaurant_lat' => '15.4865041',
            'restaurant_lng' => '80.0499408',
            'item_total' => 250,
            'expected_distance' => 10,
            'expected_fee' => 47
        ],
        [
            'name' => '10km Distance - Item >= 299',
            'user_lat' => '15.4865041',
            'user_lng' => '80.0499408',
            'restaurant_lat' => '15.4865041',
            'restaurant_lng' => '80.0499408',
            'item_total' => 350,
            'expected_distance' => 10,
            'expected_fee' => 24
        ]
    ];

    $results = [];

    foreach ($testScenarios as $scenario) {
        // Calculate actual distance
        $lat1 = floatval($scenario['user_lat']);
        $lon1 = floatval($scenario['user_lng']);
        $lat2 = floatval($scenario['restaurant_lat']);
        $lon2 = floatval($scenario['restaurant_lng']);

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $actualDistance = $miles * 1.609344;

        // Calculate delivery charge
        $calculation = $deliveryChargeService->calculateDeliveryCharge($scenario['item_total'], $actualDistance);

        // Validate coordinates
        $validation = $deliveryChargeService->validateCoordinates(
            $scenario['user_lat'],
            $scenario['user_lng'],
            $scenario['restaurant_lat'],
            $scenario['restaurant_lng']
        );

        $results[$scenario['name']] = [
            'scenario' => $scenario,
            'actual_distance' => round($actualDistance, 2),
            'expected_distance' => $scenario['expected_distance'],
            'calculation' => $calculation,
            'validation' => $validation,
            'pass' => ($calculation['actual_fee'] == $scenario['expected_fee'])
        ];
    }

    return response()->json([
        'message' => 'Complete delivery charge system test',
        'test_scenarios' => $results,
        'summary' => [
            'total_scenarios' => count($testScenarios),
            'passed_scenarios' => count(array_filter($results, function($r) { return $r['pass']; })),
            'failed_scenarios' => count(array_filter($results, function($r) { return !$r['pass']; }))
        ]
    ]);
});

// Debug route to check vendor data from Firebase
Route::get('/debug-vendor-data/{vendorId}', function ($vendorId) {
    // This will help us see what data is available in the vendor document
    return response()->json([
        'vendor_id' => $vendorId,
        'message' => 'Check the browser console for vendor data from Firebase',
        'instructions' => [
            '1. Open browser console',
            '2. Go to product detail page',
            '3. Look for vendorDetails object',
            '4. Check if latitude and longitude exist'
        ]
    ]);
});

// Debug route to test vendor data fetching and coordinate setting
Route::get('/debug-vendor-coordinates/{vendorId}', function ($vendorId) {
    // Simulate the Firebase vendor data structure based on your document
    $vendorData = [
        'id' => $vendorId,
        'title' => 'Mastan hotel non veg chicken dum biriyani',
        'location' => 'Grand trunk road, beside zudio',
        'latitude' => 15.490739,
        'longitude' => 80.048471,
        'coordinates' => [
            'latitude' => 15.490739,
            'longitude' => 80.048471
        ],
        'restaurant_slug' => 'mastan-hotel-nonveg-chicken-dum-biriyani',
        'zone_slug' => 'ongole'
    ];

    return response()->json([
        'message' => 'Vendor data structure for testing',
        'vendor_data' => $vendorData,
        'coordinates_available' => [
            'latitude_exists' => isset($vendorData['latitude']),
            'longitude_exists' => isset($vendorData['longitude']),
            'coordinates_exists' => isset($vendorData['coordinates']),
            'latitude_value' => $vendorData['latitude'],
            'longitude_value' => $vendorData['longitude']
        ],
        'instructions' => [
            '1. Go to product detail page with this vendor ID',
            '2. Open browser console (F12)',
            '3. Look for "=== VENDOR DATA DEBUG ===" logs',
            '4. Check if vendorDetails.latitude and vendorDetails.longitude are set',
            '5. Add item to cart and check "=== ADD TO CART COORDINATES DEBUG ===" logs'
        ],
        'expected_behavior' => [
            'vendorDetails.latitude should be: 15.490739',
            'vendorDetails.longitude should be: 80.048471',
            'Hidden inputs should be populated with these values',
            'Cookies should be set with these coordinates'
        ]
    ]);
});

// Comprehensive test route for complete delivery charge workflow
Route::get('/test-complete-workflow', function () {
    $realVendorId = '0QcKVUa4aqJVYQ0957kz';
    $deliveryChargeService = new \App\Services\DeliveryChargeService();

    // Set test coordinates (different from restaurant to test distance calculation)
    setcookie('address_lat', '15.4865041', time() + 3600, '/');
    setcookie('address_lng', '80.0499408', time() + 3600, '/');
    setcookie('restaurant_latitude', '15.490739', time() + 3600, '/');
    setcookie('restaurant_longitude', '80.048471', time() + 3600, '/');

    // Calculate actual distance between coordinates
    $lat1 = 15.4865041; // User location
    $lon1 = 80.0499408;
    $lat2 = 15.490739;  // Restaurant location
    $lon2 = 80.048471;

    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $actualDistance = $miles * 1.609344;

    // Test different item totals
    $testScenarios = [
        ['item_total' => 180, 'description' => 'Below threshold (< 299)'],
        ['item_total' => 350, 'description' => 'Above threshold (≥ 299)']
    ];

    $results = [];
    foreach ($testScenarios as $scenario) {
        $calculation = $deliveryChargeService->calculateDeliveryCharge($scenario['item_total'], $actualDistance);
        $results[] = [
            'scenario' => $scenario['description'],
            'item_total' => $scenario['item_total'],
            'distance' => round($actualDistance, 2),
            'calculation' => $calculation,
            'business_rule_applied' => [
                'below_threshold' => $scenario['item_total'] < 299,
                'above_threshold' => $scenario['item_total'] >= 299,
                'within_free_distance' => $actualDistance <= 7,
                'beyond_free_distance' => $actualDistance > 7
            ]
        ];
    }

    return response()->json([
        'message' => 'Complete delivery charge workflow test',
        'vendor_id' => $realVendorId,
        'coordinates' => [
            'user_location' => [$lat1, $lon1],
            'restaurant_location' => [$lat2, $lon2],
            'calculated_distance_km' => round($actualDistance, 2)
        ],
        'test_scenarios' => $results,
        'cookies_set' => [
            'address_lat' => $_COOKIE['address_lat'] ?? 'NOT SET',
            'address_lng' => $_COOKIE['address_lng'] ?? 'NOT SET',
            'restaurant_latitude' => $_COOKIE['restaurant_latitude'] ?? 'NOT SET',
            'restaurant_longitude' => $_COOKIE['restaurant_longitude'] ?? 'NOT SET'
        ],
        'next_steps' => [
            '1. Visit product detail page',
            '2. Check browser console for vendor data',
            '3. Add item to cart',
            '4. Check delivery charge calculation',
            '5. Verify business rules are applied correctly'
        ]
    ]);
});

// Test route for new tax calculation
Route::get('/test-tax-calculation', function () {
    $cart = session()->get('cart', []);

    // Create a test cart with items
    $testCart = [
        'item' => [
            'restaurant1' => [
                'item1' => [
                    'item_price' => 100,
                    'extra_price' => 20,
                    'quantity' => 2
                ],
                'item2' => [
                    'item_price' => 150,
                    'extra_price' => 30,
                    'quantity' => 1
                ]
            ]
        ],
        'deliverycharge' => 23, // Base delivery charge
        'deliverychargemain' => 23
    ];

    // Calculate item total
    $itemTotal = 0;
    foreach ($testCart['item'] as $restaurantItems) {
        foreach ($restaurantItems as $item) {
            $basePrice = floatval($item['item_price'] ?? 0);
            $extraPrice = floatval($item['extra_price'] ?? 0);
            $quantity = floatval($item['quantity'] ?? 1);
            $itemTotal += ($basePrice + $extraPrice) * $quantity;
        }
    }

    // Calculate SGST (5% on item total before discounts)
    $sgst = ($itemTotal * 5) / 100;

    // Calculate GST (18% on delivery charge)
    $gst = ($testCart['deliverycharge'] * 18) / 100;

    // Total tax
    $totalTax = $sgst + $gst;

    $result = [
        'item_total_before_discount' => $itemTotal,
        'delivery_charge' => $testCart['deliverycharge'],
        'sgst_5_percent' => $sgst,
        'gst_18_percent' => $gst,
        'total_tax' => $totalTax,
        'breakdown' => [
            'item1' => '100 + 20 = 120 × 2 = 240',
            'item2' => '150 + 30 = 180 × 1 = 180',
            'total_items' => '240 + 180 = 420',
            'sgst_calculation' => '420 × 5% = 21',
            'gst_calculation' => '23 × 18% = 4.14',
            'total_tax_calculation' => '21 + 4.14 = 25.14'
        ]
    ];

    return response()->json($result);
});

// Test route for billing calculations
Route::get('/test-billing-calculations', function () {
    $cart = session()->get('cart', []);

    // Create a test cart with items
    $testCart = [
        'item' => [
            'restaurant1' => [
                'item1' => [
                    'item_price' => 100,
                    'extra_price' => 20,
                    'quantity' => 2
                ],
                'item2' => [
                    'item_price' => 150,
                    'extra_price' => 30,
                    'quantity' => 1
                ]
            ]
        ],
        'deliverycharge' => 23, // Base delivery charge
        'deliverychargemain' => 23,
        'tip_amount' => 5,
        'decimal_degits' => 2
    ];

    // Calculate item total
    $itemTotal = 0;
    foreach ($testCart['item'] as $restaurantItems) {
        foreach ($restaurantItems as $item) {
            $basePrice = floatval($item['item_price'] ?? 0);
            $extraPrice = floatval($item['extra_price'] ?? 0);
            $quantity = floatval($item['quantity'] ?? 1);
            $itemTotal += ($basePrice + $extraPrice) * $quantity;
        }
    }

    // Calculate SGST (5% on item total before discounts)
    $sgst = ($itemTotal * 5) / 100;

    // Calculate GST (18% on delivery charge)
    $gst = ($testCart['deliverycharge'] * 18) / 100;

    // Total tax
    $totalTax = $sgst + $gst;

    // Calculate final total
    $finalTotal = $itemTotal + $testCart['deliverycharge'] + $totalTax + $testCart['tip_amount'];

    $result = [
        'item_total' => $itemTotal,
        'delivery_charge' => $testCart['deliverycharge'],
        'tip_amount' => $testCart['tip_amount'],
        'sgst_5_percent' => $sgst,
        'gst_18_percent' => $gst,
        'total_tax' => $totalTax,
        'final_total' => $finalTotal,
        'breakdown' => [
            'item1' => '100 + 20 = 120 × 2 = 240',
            'item2' => '150 + 30 = 180 × 1 = 180',
            'total_items' => '240 + 180 = 420',
            'delivery_charge' => '23',
            'sgst_calculation' => '420 × 5% = 21',
            'gst_calculation' => '23 × 18% = 4.14',
            'total_tax_calculation' => '21 + 4.14 = 25.14',
            'tip_amount' => '5',
            'final_total_calculation' => '420 + 23 + 25.14 + 5 = 473.14'
        ]
    ];

    return response()->json($result);
});

// Test route for delivery charge inclusion
Route::get('/test-delivery-inclusion', function () {
    $cart = session()->get('cart', []);

    // Create a test cart with delivery charge
    $testCart = [
        'item' => [
            'restaurant1' => [
                'item1' => [
                    'item_price' => 100,
                    'extra_price' => 20,
                    'quantity' => 2
                ]
            ]
        ],
        'deliverycharge' => 23, // This should be included in total
        'deliverychargemain' => 23,
        'tax' => 25.14,
        'tip_amount' => 5,
        'decimal_degits' => 2
    ];

    // Calculate item total
    $itemTotal = 0;
    foreach ($testCart['item'] as $restaurantItems) {
        foreach ($restaurantItems as $item) {
            $basePrice = floatval($item['item_price'] ?? 0);
            $extraPrice = floatval($item['extra_price'] ?? 0);
            $quantity = floatval($item['quantity'] ?? 1);
            $itemTotal += ($basePrice + $extraPrice) * $quantity;
        }
    }

    // Calculate total including delivery charge
    $totalWithDelivery = $itemTotal + $testCart['deliverycharge'] + $testCart['tax'] + $testCart['tip_amount'];

    $result = [
        'item_total' => $itemTotal,
        'delivery_charge' => $testCart['deliverycharge'],
        'tax' => $testCart['tax'],
        'tip_amount' => $testCart['tip_amount'],
        'total_with_delivery' => $totalWithDelivery,
        'breakdown' => [
            'item1' => '100 + 20 = 120 × 2 = 240',
            'delivery_charge' => '23',
            'tax' => '25.14',
            'tip_amount' => '5',
            'calculation' => '240 + 23 + 25.14 + 5 = 293.14'
        ],
        'session_variables' => [
            'deliverycharge' => $testCart['deliverycharge'],
            'deliverychargemain' => $testCart['deliverychargemain'],
            'delivery_charge' => 'NOT SET'
        ]
    ];

    return response()->json($result);
});

// Debug route to check session delivery charge data
Route::get('/debug-session-delivery', function () {
    $cart = session()->get('cart', []);

    $result = [
        'session_cart' => $cart,
        'delivery_variables' => [
            'deliverycharge' => $cart['deliverycharge'] ?? 'NOT SET',
            'deliverychargemain' => $cart['deliverychargemain'] ?? 'NOT SET',
            'delivery_charge' => $cart['delivery_charge'] ?? 'NOT SET',
            'delivery_charge_calculation' => $cart['delivery_charge_calculation'] ?? 'NOT SET'
        ],
        'item_total' => 0,
        'calculated_total' => 0
    ];

    // Calculate item total if items exist
    if (isset($cart['item']) && !empty($cart['item'])) {
        $itemTotal = 0;
        foreach ($cart['item'] as $restaurantItems) {
            foreach ($restaurantItems as $item) {
                $basePrice = floatval($item['item_price'] ?? 0);
                $extraPrice = floatval($item['extra_price'] ?? 0);
                $quantity = floatval($item['quantity'] ?? 1);
                $itemTotal += ($basePrice + $extraPrice) * $quantity;
            }
        }
        $result['item_total'] = $itemTotal;
        $result['calculated_total'] = $itemTotal + floatval($cart['deliverycharge'] ?? 0) + floatval($cart['tax'] ?? 0) + floatval($cart['tip_amount'] ?? 0);
    }

    return response()->json($result);
});

// Comprehensive test for delivery charge flow
Route::get('/test-complete-delivery-flow', function () {
    $deliveryChargeService = new \App\Services\DeliveryChargeService();

    // Create a test cart with items
    $testCart = [
        'item' => [
            'restaurant1' => [
                'item1' => [
                    'item_price' => 100,
                    'extra_price' => 20,
                    'quantity' => 2
                ],
                'item2' => [
                    'item_price' => 150,
                    'extra_price' => 30,
                    'quantity' => 1
                ]
            ]
        ],
        'deliverykm' => 5, // 5km distance
        'decimal_degits' => 2
    ];

    // Calculate delivery charge using the service
    $updatedCart = $deliveryChargeService->updateCartDeliveryCharge($testCart);

    // Calculate item total
    $itemTotal = 0;
    foreach ($testCart['item'] as $restaurantItems) {
        foreach ($restaurantItems as $item) {
            $basePrice = floatval($item['item_price'] ?? 0);
            $extraPrice = floatval($item['extra_price'] ?? 0);
            $quantity = floatval($item['quantity'] ?? 1);
            $itemTotal += ($basePrice + $extraPrice) * $quantity;
        }
    }

    // Calculate tax (SGST + GST)
    $sgst = ($itemTotal * 5) / 100;
    $gst = ($updatedCart['deliverycharge'] * 18) / 100;
    $totalTax = $sgst + $gst;

    // Calculate final total
    $finalTotal = $itemTotal + $updatedCart['deliverycharge'] + $totalTax;

    $result = [
        'original_cart' => $testCart,
        'updated_cart' => $updatedCart,
        'calculations' => [
            'item_total' => $itemTotal,
            'delivery_charge' => $updatedCart['deliverycharge'],
            'delivery_charge_main' => $updatedCart['deliverychargemain'],
            'sgst_5_percent' => $sgst,
            'gst_18_percent' => $gst,
            'total_tax' => $totalTax,
            'final_total' => $finalTotal
        ],
        'session_variables_check' => [
            'deliverycharge_exists' => isset($updatedCart['deliverycharge']),
            'deliverychargemain_exists' => isset($updatedCart['deliverychargemain']),
            'deliverycharge_value' => $updatedCart['deliverycharge'],
            'deliverychargemain_value' => $updatedCart['deliverychargemain']
        ],
        'breakdown' => [
            'item1' => '100 + 20 = 120 × 2 = 240',
            'item2' => '150 + 30 = 180 × 1 = 180',
            'item_total' => '240 + 180 = 420',
            'distance' => '5km',
            'delivery_charge_calculation' => 'Base: 23 (5km ≤ 7km, item < 299)',
            'sgst_calculation' => '420 × 5% = 21',
            'gst_calculation' => '23 × 18% = 4.14',
            'total_tax_calculation' => '21 + 4.14 = 25.14',
            'final_total_calculation' => '420 + 23 + 25.14 = 468.14'
        ]
    ];

    return response()->json($result);
});


// routes/web.php

Route::prefix('mart')->group(function () {
    Route::get('/', function () {
        return view('mart.index');
    });
});
