<?php
namespace App\Http\Controllers;
use App\Models\VendorUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Storage;
use Google\Client as Google_Client;
use Kreait\Firebase\Factory;
use App\Services\RestaurantService;
use App\Services\DeliveryChargeService;

class ProductController extends Controller
{
    protected $restaurantService;
    protected $deliveryChargeService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RestaurantService $restaurantService, DeliveryChargeService $deliveryChargeService)
    {
        // Skip location check for deep link routes
        $currentRoute = request()->route();
        $isDeepLinkRoute = $currentRoute && in_array($currentRoute->getName(), [
            'productDetail', 'product.deep', 'restaurant.deep', 'mart.deep', 'products.deep', 'categories.deep'
        ]);

        if (!$isDeepLinkRoute && !isset($_COOKIE['address_name'])) {
            \Redirect::to('set-location')->send();
        }

        $this->restaurantService = $restaurantService;
        $this->deliveryChargeService = $deliveryChargeService;
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function productDetail($id)
    {
        $cart = session()->get('cart', []);
        return view('products.detail', ['id' => $id, 'cart' => $cart]);
    }

    public function productList($type, $id)
    {
        return view('products.list', ['type' => $type, 'id' => $id]);
    }
    public function cart()
    {
        return view('checkout');
    }
    public function productListAll()
    {
        // Check if Firebase credentials exist
        $credentialsPath = storage_path('app/firebase/credentials.json');
        $hasFirebaseCredentials = file_exists($credentialsPath);

        // Provide fallback data when Firebase is not available
        $fallbackProducts = [
            [
                'id' => '1',
                'name' => 'Fresh Vegetables',
                'description' => 'Fresh organic vegetables delivered to your doorstep',
                'photo' => '/img/placeholder-vegetables.jpg',
                'price' => 150,
                'disPrice' => 120,
                'rating' => 4.5,
                'reviews' => 25,
                'veg' => true
            ],
            [
                'id' => '2',
                'name' => 'Premium Rice',
                'description' => 'High quality basmati rice',
                'photo' => '/img/placeholder-rice.jpg',
                'price' => 300,
                'disPrice' => 250,
                'rating' => 4.8,
                'reviews' => 42,
                'veg' => true
            ],
            [
                'id' => '3',
                'name' => 'Fresh Fruits',
                'description' => 'Seasonal fresh fruits',
                'photo' => '/img/placeholder-fruits.jpg',
                'price' => 200,
                'disPrice' => 180,
                'rating' => 4.3,
                'reviews' => 18,
                'veg' => true
            ],
            [
                'id' => '4',
                'name' => 'Dairy Products',
                'description' => 'Fresh milk and dairy items',
                'photo' => '/img/placeholder-dairy.jpg',
                'price' => 80,
                'disPrice' => 70,
                'rating' => 4.6,
                'reviews' => 33,
                'veg' => true
            ],
            [
                'id' => '5',
                'name' => 'Spices & Masala',
                'description' => 'Authentic Indian spices',
                'photo' => '/img/placeholder-spices.jpg',
                'price' => 120,
                'disPrice' => 100,
                'rating' => 4.7,
                'reviews' => 28,
                'veg' => true
            ],
            [
                'id' => '6',
                'name' => 'Bakery Items',
                'description' => 'Fresh bread and bakery products',
                'photo' => '/img/placeholder-bakery.jpg',
                'price' => 60,
                'disPrice' => 50,
                'rating' => 4.4,
                'reviews' => 21,
                'veg' => true
            ]
        ];

        return view('products.list_arrivals', [
            'hasFirebaseCredentials' => $hasFirebaseCredentials,
            'fallbackProducts' => $fallbackProducts
        ]);
    }

    /**
     * Handle deep link requests for mobile app
     * Shows app installation flow when accessed via HTTPS links
     */
    public function deepLinkHandler(Request $request)
    {
        // Get ID from URL
        $id = $request->route('id');

        // Determine the type of link based on the route
        $routeName = $request->route()->getName();
        $linkType = 'product'; // default

        if (str_contains($routeName, 'restaurant')) {
            $linkType = 'restaurant';
        } elseif (str_contains($routeName, 'mart')) {
            $linkType = 'mart';
        }

        // Check for debug parameter to force show deep link handler (works in all environments)
        $forceShow = $request->has('debug') || $request->has('mobile') || $request->has('app');

        // Enhanced mobile detection
        $userAgent = $request->header('User-Agent', '');
        $isMobile = preg_match('/(android|iphone|ipad|mobile|tablet|blackberry|windows phone|opera mini|mobile safari)/i', $userAgent);

        // Additional mobile detection methods
        $isMobileApp = $request->hasHeader('X-Requested-With') &&
                      str_contains($request->header('X-Requested-With'), 'com.jippymart.customer');

        // Check if it's a deep link from the app
        $isDeepLink = $request->hasHeader('Referer') &&
                     str_contains($request->header('Referer'), 'jippymart.in');

        // Check for specific mobile indicators
        $hasMobileHeaders = $request->hasHeader('X-Mobile-App') ||
                           $request->hasHeader('X-Android-App') ||
                           $request->hasHeader('X-iOS-App');

        // Log the detection for debugging
        \Log::info('Deep Link Detection', [
            'user_agent' => $userAgent,
            'is_mobile' => $isMobile,
            'is_mobile_app' => $isMobileApp,
            'is_deep_link' => $isDeepLink,
            'has_mobile_headers' => $hasMobileHeaders,
            'force_show' => $forceShow,
            'product_id' => $id
        ]);

        // Show deep link handler for mobile devices, mobile apps, or when forced
        if ($forceShow || $isMobile || $isMobileApp || $isDeepLink || $hasMobileHeaders) {
            return view('deep-link-handler', [
                'productId' => $id,
                'linkType' => $linkType,
                'debug' => $forceShow,
                'userAgent' => $userAgent,
                'detectionInfo' => [
                    'isMobile' => $isMobile,
                    'isMobileApp' => $isMobileApp,
                    'isDeepLink' => $isDeepLink,
                    'hasMobileHeaders' => $hasMobileHeaders
                ]
            ]);
        }

        // For desktop browsers, redirect to appropriate web version
        switch ($linkType) {
            case 'restaurant':
                return redirect('/restaurant');
            case 'mart':
                return redirect('/mart');
            default:
                return redirect('/products');
        }
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function addToCart(Request $request)
    {
        $req = $request->all();
        $id = $req['id'];
        $restaurant_id = $req['restaurant_id'];
        $cart = Session::get('cart', []);

        // Initialize cart structure if it doesn't exist
        if (!isset($cart['item'])) {
            $cart['item'] = array();
        }
        // Initialize restaurant array if it doesn't exist
        if (!isset($cart['item'][$restaurant_id])) {
            $cart['item'][$restaurant_id] = array();
        }
        if (@$req['deliveryCharge']) {
            $cart['deliverychargemain'] = $req['deliveryCharge'];
        }
        $cart['restaurant_latitude'] = $req['restaurant_latitude'];
        $cart['restaurant_longitude'] = $req['restaurant_longitude'];
        $cart['distanceType'] = $req['distanceType'];
        $cart['isSelfDelivery'] = $req['isSelfDelivery'];
        $address_lat = @$_COOKIE['address_lat'];
        $address_lng = @$_COOKIE['address_lng'];
        $restaurant_latitude = @$_COOKIE['restaurant_latitude'];
        $restaurant_longitude = @$_COOKIE['restaurant_longitude'];
        $selfDelivery= $req['isSelfDelivery'];

        if (@$address_lat && @$address_lng && @$restaurant_latitude && @$restaurant_longitude) {
            if (! empty($req['distanceType'])) {
                $distanceType = $req['distanceType'];
            }else{
                $distanceType = 'km';
            }

            $kmradius = $this->distance($address_lat, $address_lng, $restaurant_latitude, $restaurant_longitude, $distanceType);

            // Store distance for new delivery charge system
            $cart['deliverykm'] = $kmradius;

            // Apply new delivery charge system if enabled
            if ($this->deliveryChargeService->shouldUseNewDeliverySystem()) {
                // Calculate item total for new delivery charge system
                $itemTotal = 0;
                if (isset($cart['item'][$restaurant_id])) {
                    foreach ($cart['item'][$restaurant_id] as $item) {
                        $basePrice = floatval($item['item_price'] ?? 0);
                        $extraPrice = floatval($item['extra_price'] ?? 0);
                        $quantity = floatval($item['quantity'] ?? 1);
                        $itemTotal += ($basePrice + $extraPrice) * $quantity;
                    }
                }

                // Add current item to total
                $currentItemPrice = floatval($req['item_price'] ?? 0);
                $currentItemExtra = floatval($req['extra_price'] ?? 0);
                $currentItemQty = floatval($req['quantity'] ?? 1);
                $itemTotal += ($currentItemPrice + $currentItemExtra) * $currentItemQty;

                // Calculate new delivery charge
                $calculation = $this->deliveryChargeService->calculateDeliveryCharge($itemTotal, $kmradius);
                $cart['deliverycharge'] = $calculation['actual_fee'];
                $cart['deliverychargemain'] = $calculation['original_fee'];
                $cart['delivery_charge_calculation'] = $calculation;
            }
        }

        if (Session::get('takeawayOption') == "true") {
            $req['delivery_option'] = "takeaway";
        } else {
            $req['delivery_option'] = "delivery";
        }
        if (@$req['delivery_option'] == "delivery") {
            // Use new system's actual fee if available, otherwise fall back to old system
            if (isset($cart['delivery_charge_calculation'])) {
                $cart['deliverycharge'] = $cart['delivery_charge_calculation']['actual_fee'];
            } else {
                $cart['deliverycharge'] = @$cart['deliverychargemain'];
            }
        } else {
            $cart['deliverycharge'] = 0;
            $cart['tip_amount'] = 0;
        }
        if ($selfDelivery === true || $selfDelivery === "true") {
            $cart['deliverycharge'] = 0;
            $cart['tip_amount'] = 0;
        }
        $cart['delivery_option'] = $req['delivery_option'];
        $cart['tip_amount'] = 0;
        /*by GA*/
        if (isset($req['variant_info']) && !empty($req['variant_info']['variant_id'])) {
            $id = $id . 'PV' . $req['variant_info']['variant_id'];
        }
        $cart['item'][$restaurant_id][$id] = [
            "name" => $req['name'],
            "quantity" => $req['quantity'],
            "stock_quantity" => $req['stock_quantity'],
            "item_price" => $req['item_price'],
            "price" => $req['price'],
            "dis_price" => $req['dis_price'],
            "extra_price" => $req['extra_price'],
            "extra" => @$req['extra'],
            "size" => @$req['size'],
            "image" => @$req['image'],
            "veg" => @$req['veg'],
            "iteam_extra_price" => @$req['iteam_extra_price'],
            "variant_info" => @$req['variant_info'],
            "category_id" => @$req['category_id'],
        ];
        $cart['restaurant']['id'] = @$restaurant_id;
        $cart['restaurant']['name'] = @$req['restaurant_name'];
        $cart['restaurant']['location'] = @$req['restaurant_location'];
        $cart['restaurant']['image'] = @$req['restaurant_image'];
        $cart['taxValue'] = @$req['taxValue'];
        $tax = 0;
        $tax_label = '';
        $total_item_price = 0;
        foreach ($cart['item'][$restaurant_id] as $key_cart => $value_cart) {
            $total_one_item_price = $value_cart['item_price'] * $value_cart['quantity'];
            if (@$value_cart['extra_price']) {
                $total_one_item_price = $total_one_item_price + ($value_cart['extra_price'] * $value_cart['quantity']);
            }
            $total_item_price = $total_item_price + $total_one_item_price;
        }
        $discount_amount = 0;
        /*Disctount*/
        if (@$cart['coupon'] && $cart['coupon']['discountType']) {
            $discountType = $cart['coupon']['discountType'];
            $coupon_code = $cart['coupon']['coupon_code'];
            $coupon_id = @$cart['coupon']['coupon_id'];
            $discount = $cart['coupon']['discount'];
            if ($discountType == "Fix Price") {
                $discount_amount = $cart['coupon']['discount'];
                if ($discount_amount > $total_item_price) {
                    $discount_amount = $total_item_price;
                }
            } else {
                $discount_amount = $cart['coupon']['discount'];
                $discount_amount = ($total_item_price * $discount_amount) / 100;
                if ($discount_amount > $total_item_price) {
                    $discount_amount = $total;
                }
            }
        }
        /*Special Offer Disctount*/
        $specialOfferDiscount = 0;
        $specialOfferType = '';
        $specialOfferDiscountVal = 0;
        if (@$req['specialOfferForHour']) {
            $specialOfferForHour = $req['specialOfferForHour'];
            if (count($specialOfferForHour) > 0) {
                foreach ($specialOfferForHour as $key => $value) {
                    $specialOfferType = $value['type'];
                    $specialOfferDiscountVal = $value['discount'];
                    if ($value['type'] == 'percentage') {
                        $specialOfferDiscount = ($total_item_price * $value['discount']) / 100;
                    } else {
                        $specialOfferDiscount = $value['discount'];
                    }
                }
            }
        }
        $total_item_price = $total_item_price - $discount_amount - $specialOfferDiscount;
        $cart['specialOfferDiscount'] = $specialOfferDiscount;
        $cart['specialOfferDiscountVal'] = $specialOfferDiscountVal;
        $cart['specialOfferType'] = $specialOfferType;

        // Calculate delivery charge for tax calculation
        $delivery_charge_for_tax = 0;
        if (isset($cart['deliverycharge'])) {
            $delivery_charge_for_tax = $cart['deliverycharge'];
        } elseif (isset($cart['deliverychargemain'])) {
            $delivery_charge_for_tax = $cart['deliverychargemain'];
        }

        // Calculate new tax (SGST + GST)
        $taxCalculation = $this->calculateNewTax($cart, $total_item_price, $delivery_charge_for_tax);

        $cart['tax'] = $taxCalculation['total_tax'];
        $cart['sgst'] = $taxCalculation['sgst'];
        $cart['gst'] = $taxCalculation['gst'];
        $cart['tax_label'] = 'SGST + GST';
        $cart['decimal_degits'] = $req['decimal_degits'] ?? 2;
        $discounted_subtotal = $total_item_price - $discount_amount - $specialOfferDiscount;
        $five_percent_charge = round($discounted_subtotal * 0.05, 2);
        $cart['eighteen_percent_charge'] = $five_percent_charge;
        $total_with_charge = $discounted_subtotal + $five_percent_charge;
        $total_pay = $total_with_charge + floatval(@$cart['tip_amount']);
        $cart['total_pay'] = $total_pay;
        Session::put('cart', $cart);
        Session::save();
        // Auto-apply SAVE30 coupon if item value >= 299, remove if below
        if ($total_item_price >= 299) {
            $cart['coupon'] = [
                'coupon_code' => 'SAVE30',
                'coupon_id' => 'auto-save30', // or leave blank if not needed
                'discount' => 30,
                'discountType' => 'Fix Price',
            ];
        } else {
            if (isset($cart['coupon']) && $cart['coupon']['coupon_code'] === 'SAVE30') {
                unset($cart['coupon']);
            }
        }
        $res = array('status' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart])->render());
        echo json_encode($res);
        exit;
    }
    /**
     * Calculate distance between two points using Haversine formula
     */
    public function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        if ($unit == 'km') {
            return $miles * 1.609344;
        } else {
            return $miles;
        }
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public
    function reorderaddToCart(Request $request)
    {
        $req = $request->all();
        $vendor_id = $req['restaurant_id'];
        $cart = Session::get('cart', []);

        Session::put('cart', $cart);
        Session::save();
        if (@$req['deliveryCharge']) {
            $cart['deliverychargemain'] = $req['deliveryCharge'];
        } else {
            $cart['deliverychargemain'] = 0;
        }
        if (Session::get('takeawayOption') == "true") {
            $req['delivery_option'] = "takeaway";
        } else {
            $req['delivery_option'] = "delivery";
        }
        if (@$req['delivery_option'] == "delivery") {
            $cart['deliverycharge'] = @$cart['deliverychargemain'];
        } else {
            $cart['deliverycharge'] = 0;
            $cart['tip_amount'] = 0;
        }
        $cart['delivery_option'] = $req['delivery_option'];
        $cart['tip_amount'] = 0;
        $cart['distanceType'] = $req['distanceType'];
        $cart['restaurant_latitude'] = $req['restaurant_latitude'];
        $cart['restaurant_longitude'] = $req['restaurant_longitude'];
        foreach ($req['item'] as $key => $value) {
            $id = 0;
            $name = '';
            $quantity = 0;
            $item_price = 0;
            $price = 0;
            $extra_price = 0;
            $extra = '';
            $size = 0;
            $image = '';
            if ($value['id']) {
                $id = $value['id'];
            }
            if ($value['name']) {
                $name = $value['name'];
            }
            if ($value['quantity']) {
                $quantity = $value['quantity'];
            }
            if ($value['item_price']) {
                $item_price = $value['item_price'];
            }
            if ($value['price']) {
                $price = $value['price'];
            }
            if ($value['extra_price']) {
                $extra_price = $value['extra_price'];
            }
            if ($value['extra']) {
                $extra = explode(',', $value['extra']);
            }
            if ($value['size']) {
                $size = $value['size'];
            }
            if ($value['image']) {
                $image = $value['image'];
            }
            /*by thm*/
            if (isset($req['variant_info']) && !empty($req['variant_info']['variant_id'])) {
                $id = $id . 'PV' . $req['variant_info']['variant_id'];
            }
            $cart['item'][$vendor_id][$id] = [
                "name" => @$name,
                "quantity" => @$quantity,
                "stock_quantity" => @$req['stock_quantity'],
                "item_price" => @$item_price,
                "price" => ($quantity * $price),
                "dis_price" => @$req['dis_price'],
                "extra_price" => ($quantity * $extra_price),
                "extra" => @$extra,
                "size" => @$size,
                "image" => @$image,
                "variant_info" => @$req['variant_info'],
                "category_id" => @$value['category_id'],
            ];
        }
        $cart['restaurant']['id'] = @$vendor_id;
        $cart['restaurant']['name'] = @$req['restaurant_name'];
        $cart['restaurant']['location'] = @$req['restaurant_location'];
        $cart['restaurant']['image'] = @$req['restaurant_image'];
        $cart['taxValue'] = @$req['taxValue'];
        $tax = 0;
        $tax_label = '';
        $total_item_price = 0;
        foreach ($cart['item'][$vendor_id] as $key_cart => $value_cart) {
            $total_one_item_price = $value_cart['item_price'] * $value_cart['quantity'];
            if ($value_cart['extra_price']) {
                $total_one_item_price = $total_one_item_price + ($value_cart['extra_price'] * $value_cart['quantity']);
            }
            $total_item_price = $total_item_price + $total_one_item_price;
        }
        $discount_amount = 0;
        /*Special Offer Disctount*/
        $specialOfferDiscount = 0;
        $specialOfferType = '';
        $specialOfferDiscountVal = 0;
        if (@$req['specialOfferForHour']) {
            $specialOfferForHour = $req['specialOfferForHour'];
            if (count($specialOfferForHour) > 0) {
                foreach ($specialOfferForHour as $key => $value) {
                    $specialOfferType = $value['type'];
                    $specialOfferDiscountVal = $value['discount'];
                    if ($value['type'] == 'percentage') {
                        $specialOfferDiscount = ($total_item_price * $value['discount']) / 100;
                    } else {
                        $specialOfferDiscount = $value['discount'];
                    }
                }
            }
        }
        $total_item_price = $total_item_price - $discount_amount - $specialOfferDiscount;
        $cart['specialOfferDiscount'] = $specialOfferDiscount;
        $cart['specialOfferDiscountVal'] = $specialOfferDiscountVal;
        $cart['specialOfferType'] = $specialOfferType;
        $totalTaxAmount = 0;
        if (is_array($cart['taxValue'])) {
            foreach ($cart['taxValue'] as $val) {
                if ($val['type'] == 'percentage') {
                    $tax = ($val['tax'] * $total_item_price) / 100;
                } else {
                    $tax = $val['tax'];
                }
                $totalTaxAmount += floatval($tax);
            }
            $tax = $totalTaxAmount;
            $tax_label = '';
        }
        $cart['tax_label'] = $tax_label;
        $cart['tax'] = $tax;
        $cart['decimal_degits'] = $req['decimal_degits'];
        $discounted_subtotal = $total_item_price - $discount_amount - $specialOfferDiscount;
        $five_percent_charge = round($discounted_subtotal * 0.05, 2);
        $cart['eighteen_percent_charge'] = $five_percent_charge;
        $total_with_charge = $discounted_subtotal + $five_percent_charge;
        $total_pay = $total_with_charge + floatval(@$cart['tip_amount']);
        $cart['total_pay'] = $total_pay;
        Session::put('cart', $cart);
        Session::save();
        // Auto-apply SAVE30 coupon if item value >= 299, remove if below
        if ($total_item_price >= 299) {
            $cart['coupon'] = [
                'coupon_code' => 'SAVE30',
                'coupon_id' => 'auto-save30', // or leave blank if not needed
                'discount' => 30,
                'discountType' => 'Fix Price',
            ];
        } else {
            if (isset($cart['coupon']) && $cart['coupon']['coupon_code'] === 'SAVE30') {
                unset($cart['coupon']);
            }
        }
        $res = array('status' => true);
        echo json_encode($res);
        exit;
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public
    function orderTipAdd(Request $request)
    {
        $req = $request->all();
        $cart = Session::get('cart', []);
        $cart['tip_amount'] = $req['tip'];
        Session::put('cart', $cart);
        Session::save();
        if (@$req['is_checkout']) {
            $email = Auth::user()->email;
            $user = VendorUsers::where('email', $email)->first();
            $res = array('status' => true, 'html' => view('restaurant.cart_item', ['is_checkout' => 1, 'id' => $user->uuid, 'cart' => $cart])->render());
        } else {
            $res = array('status' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart])->render());
        }
        echo json_encode($res);
        exit;
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public
    function orderDeliveryOption(Request $request)
    {
        $req = $request->all();
        $cart = Session::get('cart', []);
        $cart['delivery_option'] = $req['delivery_option'];
        if ($req['delivery_option'] == "takeaway") {
            //deliveryCharge
            $cart['tip_amount'] = 0;
            $cart['deliverycharge'] = 0;
        } else {
            //delivery
            if (isset($cart['deliverychargemain'])) {
                $cart['deliverycharge'] = $cart['deliverychargemain'];
            } else if (isset($req['deliveryCharge'])) {
                $cart['deliverychargemain'] = $req['deliveryCharge'];
                $cart['deliverycharge'] = $cart['deliverychargemain'];
            }
        }
        Session::put('cart', $cart);
        Session::save();
        if (@$req['is_checkout']) {
            $email = Auth::user()->email;
            $user = VendorUsers::where('email', $email)->first();
            $res = array('status' => true, 'html' => view('restaurant.cart_item', ['is_checkout' => 1, 'id' => $user->uuid, 'cart' => $cart])->render());
        } else {
            $res = array('status' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart])->render());
        }
        echo json_encode($res);
        exit;
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public
    function changeQuantityCart(Request $request)
    {
        $req = $request->all();
        $id = $req['id'];
        $restaurant_id = $req['restaurant_id'];
        $quantity = $req['quantity'];
        $cart = Session::get('cart');
        if (isset($cart['item'][$restaurant_id][$id])) {
            if ($req['quantity'] == 0) {
                if (isset($cart['item'][$restaurant_id][$id])) {
                    unset($cart['item'][$restaurant_id][$id]);
                    Session::put('cart', $cart);
                    Session::save();
                }
            } else {
                $cart['item'][$restaurant_id][$id]['quantity'] = $req['quantity'];
                $cart['item'][$restaurant_id][$id]['price'] = $cart['item'][$restaurant_id][$id]['item_price'] * $cart['item'][$restaurant_id][$id]['quantity'];
                $tax = 0;
                $tax_label = '';
                $total_item_price = 0;
                foreach ($cart['item'][$restaurant_id] as $key_cart => $value_cart) {
                    $total_one_item_price = $value_cart['item_price'] * $value_cart['quantity'];
                    if (@$value_cart['extra_price']) {
                        $total_one_item_price = $total_one_item_price + ($value_cart['extra_price'] * $value_cart['quantity']);
                    }
                    $total_item_price = $total_item_price + $total_one_item_price;
                }
                $discount_amount = 0;
                /*Disctount*/
                if (@$cart['coupon'] && $cart['coupon']['discountType']) {
                    $discountType = $cart['coupon']['discountType'];
                    $coupon_code = $cart['coupon']['coupon_code'];
                    $coupon_id = @$cart['coupon']['coupon_id'];
                    $discount = $cart['coupon']['discount'];
                    if ($discountType == "Fix Price") {
                        $discount_amount = $cart['coupon']['discount'];
                        if ($discount_amount > $total_item_price) {
                            $discount_amount = $total_item_price;
                        }
                    } else {
                        $discount_amount = $cart['coupon']['discount'];
                        $discount_amount = ($total_item_price * $discount_amount) / 100;
                        if ($discount_amount > $total_item_price) {
                            $discount_amount = $total;
                        }
                    }
                }
                /*Special Offer Disctount*/
                $specialOfferDiscount = 0;
                if (@$cart['specialOfferType'] && $cart['specialOfferType']) {
                    $specialOfferType = $cart['specialOfferType'];
                    $specialOfferDiscountVal = $cart['specialOfferDiscountVal'];
                    if ($specialOfferType == "amount") {
                        $specialOfferDiscount = $specialOfferDiscountVal;
                    } else {
                        $specialOfferDiscount = ($total_item_price * $specialOfferDiscountVal) / 100;
                    }
                }
                $total_item_price = $total_item_price - $discount_amount - $specialOfferDiscount;
                $totalTaxAmount = 0;
                if (is_array($cart['taxValue'])) {
                    foreach ($cart['taxValue'] as $val) {
                        if ($val['type'] == 'percentage') {
                            $tax = ($val['tax'] * $total_item_price) / 100;
                        } else {
                            $tax = $val['tax'];
                        }
                        $totalTaxAmount += floatval($tax);
                    }
                    $tax = $totalTaxAmount;
                    $tax_label = '';
                }
                $cart['tax_label'] = $tax_label;
                $cart['tax'] = $tax;
                $five_percent_charge = round($total_item_price * 0.05, 2);
                $cart['eighteen_percent_charge'] = $five_percent_charge;
                $total_with_charge = $total_item_price + $five_percent_charge;
                $total_pay = $total_with_charge + floatval(@$cart['tip_amount']);
                $cart['total_pay'] = $total_pay;
                Session::put('cart', $cart);
                Session::save();
                // Auto-apply SAVE30 coupon if item value >= 299, remove if below
                if ($total_item_price >= 299) {
                    $cart['coupon'] = [
                        'coupon_code' => 'SAVE30',
                        'coupon_id' => 'auto-save30',
                        'discount' => 30,
                        'discountType' => 'Fix Price',
                    ];
                } else {
                    if (isset($cart['coupon']) && $cart['coupon']['coupon_code'] === 'SAVE30') {
                        unset($cart['coupon']);
                    }
                }
            }
        }
        $cart = Session::get('cart');
        $res = array('status' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart, 'is_checkout' => 1])->render());
        echo json_encode($res);
        exit;
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public
    function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = Session::get('cart');
            $cart['item'][$request->id]["quantity"] = $request->quantity;
            $tax = 0;
            $tax_label = '';
            $total_item_price = 0;
            foreach ($cart['item'][$restaurant_id] as $key_cart => $value_cart) {
                $total_one_item_price = $value_cart['item_price'] * $value_cart['quantity'];
                if (@$value_cart['extra_price']) {
                    $total_one_item_price = $total_one_item_price + ($value_cart['extra_price'] * $value_cart['quantity']);
                }
                $total_item_price = $total_item_price + $total_one_item_price;
            }
            $discount_amount = 0;
            /*Disctount*/
            if (@$cart['coupon'] && $cart['coupon']['discountType']) {
                $discountType = $cart['coupon']['discountType'];
                $coupon_code = $cart['coupon']['coupon_code'];
                $coupon_id = @$cart['coupon']['coupon_id'];
                $discount = $cart['coupon']['discount'];
                if ($discountType == "Fix Price") {
                    $discount_amount = $cart['coupon']['discount'];
                    if ($discount_amount > $total_item_price) {
                        $discount_amount = $total_item_price;
                    }
                } else {
                    $discount_amount = $cart['coupon']['discount'];
                    $discount_amount = ($total_item_price * $discount_amount) / 100;
                    if ($discount_amount > $total_item_price) {
                        $discount_amount = $total;
                    }
                }
            }
            /*Special Offer Disctount*/
            $specialOfferDiscount = 0;
            if (@$cart['specialOfferType'] && $cart['specialOfferType']) {
                $specialOfferType = $cart['specialOfferType'];
                $specialOfferDiscountVal = $cart['specialOfferDiscountVal'];
                if ($specialOfferType == "amount") {
                    $specialOfferDiscount = $cart['specialOfferDiscount'];
                } else {
                    $specialOfferDiscount = ($total_item_price * $specialOfferDiscountVal) / 100;
                }
            }
            $total_item_price = $total_item_price - $discount_amount - $specialOfferDiscount;

            // Calculate delivery charge for tax calculation
            $delivery_charge_for_tax = 0;
            if (isset($cart['deliverycharge'])) {
                $delivery_charge_for_tax = $cart['deliverycharge'];
            } elseif (isset($cart['deliverychargemain'])) {
                $delivery_charge_for_tax = $cart['deliverychargemain'];
            }

            // Calculate new tax (SGST + GST)
            $taxCalculation = $this->calculateNewTax($cart, $total_item_price, $delivery_charge_for_tax);

            $cart['tax'] = $taxCalculation['total_tax'];
            $cart['sgst'] = $taxCalculation['sgst'];
            $cart['gst'] = $taxCalculation['gst'];
            $cart['tax_label'] = 'SGST + GST';

            // Ensure decimal_degits is set to 2 for proper display
            if (!isset($cart['decimal_degits']) || $cart['decimal_degits'] == 0) {
                $cart['decimal_degits'] = 2;
            }

            Session::put('cart', $cart);
            Session::save();
            // Auto-apply SAVE30 coupon if item value >= 299, remove if below
            if ($total_item_price >= 299) {
                $cart['coupon'] = [
                    'coupon_code' => 'SAVE30',
                    'coupon_id' => 'auto-save30',
                    'discount' => 30,
                    'discountType' => 'Fix Price',
                ];
            } else {
                if (isset($cart['coupon']) && $cart['coupon']['coupon_code'] === 'SAVE30') {
                    unset($cart['coupon']);
                }
            }
            $res = array('status' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart])->render());
            echo json_encode($res);
            exit;
        }
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public
    function applyCoupon(Request $request)
    {
        // Only store the coupon data in the session, do not perform any calculation here
        if ($request->coupon_code) {
            $cart = Session::get('cart');
            $cart['coupon'] = [
                'coupon_code' => $request->coupon_code,
                'coupon_id' => $request->coupon_id,
                'discount' => $request->discount,
                'discountType' => $request->discountType,
            ];
            Session::put('cart', $cart);
            Session::save();
            // Just return the updated cart view, let the Blade handle all calculations
            $res = array('status' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart])->render());
            echo json_encode($res);
            exit;
        }
    }
    public function orderComplete(Request $request)
    {
        $cart = array();
        Session::put('cart', $cart);
        Session::put('success', 'Your order has been successful!');
        if(Storage::disk('local')->has('firebase/credentials.json')){
            $client= new Google_Client();
            $client->setAuthConfig(storage_path('app/firebase/credentials.json'));
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->refreshTokenWithAssertion();
            $client_token = $client->getAccessToken();
            $access_token = $client_token['access_token'];
            $fcm_token = $request->fcm;
            if(!empty($access_token) && !empty($fcm_token)){
                $projectId = env('FIREBASE_PROJECT_ID');
                $url = 'https://fcm.googleapis.com/v1/projects/'.$projectId.'/messages:send';
                $data = [
                    'message' => [
                        'notification' => [
                            'title' => $request->subject,
                            'body' => $request->message,
                        ],
                        'data' => [
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'id' => '1',
                            'status' => 'done',
                        ],
                        'token' => $fcm_token,
                    ],
                ];
                $headers = array(
                    'Content-Type: application/json',
                    'Authorization: Bearer '.$access_token
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('FCM Send Error: ' . curl_error($ch));
                }
                curl_close($ch);
                $result=json_decode($result);
                $response = array();
                $response['success'] = true;
                $response['message'] = 'Notification successfully sent.';
                $response['result'] = $result;
            }else{
                $response = array();
                $response['success'] = false;
                $response['message'] = 'Missing sender id or token to send notification.';
            }
        }else{
            $response = array();
            $response['success'] = false;
            $response['message'] = 'Firebase credentials file not found.';
        }
        Session::save();
        $order_response = array('status' => true, 'order_complete' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart, 'order_complete' => true, 'is_checkout' => 1])->render(), 'response' => $response);
        return response()->json($order_response);
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function remove(Request $request)
    {
        if ($request->id && $request->restaurant_id) {
            $cart = Session::get('cart');
            if (isset($cart['item'][$request->restaurant_id][$request->id])) {
                unset($cart['item'][$request->restaurant_id][$request->id]);
                $total_item_price = 0;
                $id = array_key_first($cart['item']);
                $restaurant_id = $id;
                if ($restaurant_id) {
                    foreach ($cart['item'][$restaurant_id] as $key_cart => $value_cart) {
                        $total_one_item_price = $value_cart['item_price'] * $value_cart['quantity'];
                        if (@$value_cart['extra_price']) {
                            $total_one_item_price = $total_one_item_price + ($value_cart['extra_price'] * $value_cart['quantity']);
                        }
                        $total_item_price = $total_item_price + $total_one_item_price;
                    }
                    $discount_amount = 0;
                    /*Disctount*/
                    if (@$cart['coupon'] && $cart['coupon']['discountType']) {
                        $discountType = $cart['coupon']['discountType'];
                        $coupon_code = $cart['coupon']['coupon_code'];
                        $coupon_id = @$cart['coupon']['coupon_id'];
                        $discount = $cart['coupon']['discount'];
                        if ($discountType == "Fix Price") {
                            $discount_amount = $cart['coupon']['discount'];
                            if ($discount_amount > $total_item_price) {
                                $discount_amount = $total_item_price;
                            }
                        } else {
                            $discount_amount = $cart['coupon']['discount'];
                            $discount_amount = ($total_item_price * $discount_amount) / 100;
                            if ($discount_amount > $total_item_price) {
                                $discount_amount = $total;
                            }
                        }
                    }
                    /*Special Offer Disctount*/
                    $specialOfferDiscount = 0;
                    if (@$cart['specialOfferType'] && $cart['specialOfferType']) {
                        $specialOfferType = $cart['specialOfferType'];
                        $specialOfferDiscountVal = $cart['specialOfferDiscountVal'];
                        if ($specialOfferType == "amount") {
                            $specialOfferDiscount = $cart['specialOfferDiscount'];
                        } else {
                            $specialOfferDiscount = ($total_item_price * $specialOfferDiscountVal) / 100;
                        }
                    }
                    $total_item_price = $total_item_price - $discount_amount - $specialOfferDiscount;
                    if (is_array($cart['taxValue'])) {
                        $totalTaxAmount = 0;
                        foreach ($cart['taxValue'] as $val) {
                            if ($val['type'] == 'percentage') {
                                $tax = ($val['tax'] * $total_item_price) / 100;
                            } else {
                                $tax = $val['tax'];
                            }
                            $totalTaxAmount += floatval($tax);
                        }
                        $tax = $totalTaxAmount;
                        $tax_label = '';
                    }
                    if (is_array($cart['taxValue'])) {
                        foreach ($cart['taxValue'] as $val) {
                            if ($val['type'] == 'percentage') {
                                $tax = ($val['tax'] * $total_item_price) / 100;
                            } else {
                                $tax = $val['tax'];
                            }
                            $totalTaxAmount += floatval($tax);
                        }
                        $tax = $totalTaxAmount;
                        $tax_label = '';
                    }
                }
            }
            Session::put('cart', $cart);
            Session::save();
        }
        $cart = Session::get('cart');
        session()->flash('success', 'Product removed successfully');
        $res = array('status' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart])->render());
        echo json_encode($res);
        exit;
    }
    public
    function orderScheduleTimeAdd(Request $request)
    {
        $req = $request->all();
        $cart = Session::get('cart', []);
        $cart['scheduleTime'] = $req['scheduleTime'];
        Session::put('cart', $cart);
        Session::save();
        if (@$req['is_checkout']) {
            $email = Auth::user()->email;
            $user = VendorUsers::where('email', $email)->first();
            $res = array('status' => true, 'html' => view('restaurant.cart_item', ['is_checkout' => 1, 'id' => $user->uuid, 'cart' => $cart])->render());
        } else {
            $res = array('status' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart])->render());
        }
        echo json_encode($res);
        exit;
    }
    /**
     * Clear entire cart
     */
    public function clearCart(Request $request)
    {
        $cart = array();
        Session::put('cart', $cart);
        Session::save();

        $res = array('status' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart])->render());
        echo json_encode($res);
        exit;
    }

    /**
     * Sync cart from frontend modal (AJAX)
     */
    public function syncCart(Request $request)
    {
        $data = $request->all();
        \Log::debug('syncCart received', $data);
        $cart = [];
        // Accepts: item (grouped by vendor_id), restaurant (info)
        if (isset($data['item'])) {
            $cart['item'] = $data['item'];
        }
        if (isset($data['restaurant'])) {
            $cart['restaurant'] = $data['restaurant'];
        }
        // Set defaults for other required fields if needed
        $cart['taxValue'] = [];
        $cart['delivery_option'] = 'delivery';
        $cart['tip_amount'] = 0;
        $cart['coupon'] = [];
        $cart['specialOfferDiscount'] = 0;
        $cart['specialOfferDiscountVal'] = 0;
        $cart['specialOfferType'] = '';
        $cart['tax_label'] = '';
        $cart['tax'] = 0;

        // Get currency data from request or set default
        $cart['decimal_degits'] = $data['decimal_degits'] ?? 2;

        session(['cart' => $cart]);
        session()->save();
        \Log::debug('syncCart session cart', session('cart'));
        return response()->json(['status' => true]);
    }

    public function removeCoupon(Request $request)
    {
        $cart = Session::get('cart');
        if (isset($cart['coupon'])) {
            unset($cart['coupon']);
        }
        Session::put('cart', $cart);
        Session::save();
        $res = array('status' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart])->render());
        return response()->json($res);
    }

    /**
     * Get restaurant information for a product
     */
    public function getRestaurantInfo($productId, Request $request)
    {
        $vendorId = $request->query('vendor_id');
        return response()->json($this->restaurantService->getRestaurantInfo($productId, $vendorId));
    }

    /**
     * Calculate tax according to new rules:
     * SGST (5%) on Item Total (before discounts)
     * GST (18%) on Delivery Charges (base ₹23 + extra distance charges)
     */
    private function calculateNewTax($cart, $total_item_price, $delivery_charge)
    {
        $sgst = 0;
        $gst = 0;
        $total_tax = 0;

        // Calculate SGST (5%) on Item Total (before any discounts)
        $item_total_before_discount = 0;
        if (isset($cart['item']) && is_array($cart['item'])) {
            foreach ($cart['item'] as $restaurantItems) {
                if (is_array($restaurantItems)) {
                    foreach ($restaurantItems as $item) {
                        $basePrice = floatval($item['item_price'] ?? 0);
                        $extraPrice = floatval($item['extra_price'] ?? 0);
                        $quantity = floatval($item['quantity'] ?? 1);
                        $item_total_before_discount += ($basePrice + $extraPrice) * $quantity;
                    }
                }
            }
        }

        // SGST = 5% of item total (before discounts)
        $sgst = ($item_total_before_discount * 5) / 100;

        // GST = 18% of delivery charge
        $gst = ($delivery_charge * 18) / 100;

        // Total tax = SGST + GST
        $total_tax = $sgst + $gst;

        return [
            'sgst' => $sgst,
            'gst' => $gst,
            'total_tax' => $total_tax,
            'item_total_before_discount' => $item_total_before_discount,
            'delivery_charge' => $delivery_charge
        ];
    }
}
