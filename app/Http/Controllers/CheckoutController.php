<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorUsers;
use App\Models\User;
use Razorpay\Api\Api;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\XenditSdkException;
use GuzzleHttp\Client;
use Session;
use App\Services\DeliveryChargeService;

class CheckoutController extends Controller
{
    protected $deliveryChargeService;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DeliveryChargeService $deliveryChargeService)
    {
        if (!isset($_COOKIE['address_name'])) {
            \Redirect::to('set-location')->send();
        }
        $this->middleware('auth');
        $this->deliveryChargeService = $deliveryChargeService;
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function checkout()
    {
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $cart = Session::get('cart', []);
        if (Session::get('takeawayOption') == 'true') {
        } else {
            $address_lat = @$_COOKIE['address_lat'];
            $address_lng = @$_COOKIE['address_lng'];
            $restaurant_latitude = @$_COOKIE['restaurant_latitude'];
            $restaurant_longitude = @$_COOKIE['restaurant_longitude'];
            
            if (@$address_lat && @$address_lng && @$restaurant_latitude && @$restaurant_longitude) {
                if (!empty($cart['distanceType'])) {
                    $distanceType = $cart['distanceType'];
                } else {
                    $distanceType = 'km';
                }
                
                $kmradius = $this->distance($address_lat, $address_lng, $restaurant_latitude, $restaurant_longitude, $distanceType);
                
                // Store distance for new delivery charge system
                $cart['deliverykm'] = $kmradius;
                
                // Apply new delivery charge system if enabled
                if ($this->deliveryChargeService->shouldUseNewDeliverySystem()) {
                    // Calculate item total for new delivery charge system
                    $itemTotal = $this->calculateCartItemTotal($cart);
                    
                    // Debug information (remove in production)
                    if (isset($_GET['debug'])) {
                        error_log("DEBUG: Item total = " . $itemTotal);
                        error_log("DEBUG: Distance = " . $kmradius);
                        error_log("DEBUG: Address lat = " . $address_lat);
                        error_log("DEBUG: Address lng = " . $address_lng);
                        error_log("DEBUG: Restaurant lat = " . $restaurant_latitude);
                        error_log("DEBUG: Restaurant lng = " . $restaurant_longitude);
                    }
                    
                    // Calculate new delivery charge
                    $calculation = $this->deliveryChargeService->calculateDeliveryCharge($itemTotal, $kmradius);
                    $cart['deliverycharge'] = $calculation['actual_fee'];
                    $cart['deliverychargemain'] = $calculation['original_fee'];
                    $cart['delivery_charge_calculation'] = $calculation;
                    
                    // Debug information (remove in production)
                    if (isset($_GET['debug'])) {
                        error_log("DEBUG: Actual fee = " . $calculation['actual_fee']);
                        error_log("DEBUG: Original fee = " . $calculation['original_fee']);
                        error_log("DEBUG: Is free delivery = " . ($calculation['is_free_delivery'] ? 'true' : 'false'));
                        error_log("DEBUG: UI type = " . $calculation['ui_components']['type']);
                    }
                }
            }

            if (@$cart['isSelfDelivery'] === true || @$cart['isSelfDelivery'] === "true" ) {
                $cart['deliverycharge'] = 0;
            } else {
                // Use new system's actual fee if available, otherwise fall back to old system
                if (isset($cart['delivery_charge_calculation'])) {
                    $cart['deliverycharge'] = $cart['delivery_charge_calculation']['actual_fee'];
                } else {
                    // If no delivery charge calculation exists, try to calculate with default distance
                    if ($this->deliveryChargeService->shouldUseNewDeliverySystem()) {
                        $itemTotal = $this->calculateCartItemTotal($cart);
                        
                        // Debug information (remove in production)
                        if (isset($_GET['debug'])) {
                            error_log("DEBUG FALLBACK: Item total = " . $itemTotal);
                            error_log("DEBUG FALLBACK: Address lat exists = " . (isset($_COOKIE['address_lat']) ? 'yes' : 'no'));
                            error_log("DEBUG FALLBACK: Address lng exists = " . (isset($_COOKIE['address_lng']) ? 'yes' : 'no'));
                        }
                        
                        // Only use fallback if we have some location data
                        if (isset($_COOKIE['address_lat']) && isset($_COOKIE['address_lng'])) {
                            // For items below threshold, always use base delivery charge regardless of distance
                            if ($itemTotal < 299) {
                                $cart['deliverycharge'] = 23; // Base delivery charge
                                $cart['deliverychargemain'] = 23;
                                
                                // Debug information (remove in production)
                                if (isset($_GET['debug'])) {
                                    error_log("DEBUG FALLBACK: Item < 299, using base charge 23 regardless of distance");
                                }
                            } else {
                                // For items above threshold, use a default distance of 5km if restaurant coordinates are missing
                                $defaultDistance = 5;
                                $calculation = $this->deliveryChargeService->calculateDeliveryCharge($itemTotal, $defaultDistance);
                                $cart['deliverycharge'] = $calculation['actual_fee'];
                                $cart['deliverychargemain'] = $calculation['original_fee'];
                                $cart['delivery_charge_calculation'] = $calculation;
                                
                                // Debug information (remove in production)
                                if (isset($_GET['debug'])) {
                                    error_log("DEBUG FALLBACK: Using default distance calculation for item >= 299");
                                    error_log("DEBUG FALLBACK: Actual fee = " . $calculation['actual_fee']);
                                    error_log("DEBUG FALLBACK: UI type = " . $calculation['ui_components']['type']);
                                }
                            }
                        } else {
                            // No location data available - use base delivery charge for items below threshold
                            if ($itemTotal < 299) {
                                $cart['deliverycharge'] = 23; // Base delivery charge
                                $cart['deliverychargemain'] = 23;
                                
                                // Debug information (remove in production)
                                if (isset($_GET['debug'])) {
                                    error_log("DEBUG FALLBACK: No location data, item < 299, using base charge 23");
                                }
                            } else {
                                // For items above threshold, assume free delivery within range
                                $cart['deliverycharge'] = 0;
                                $cart['deliverychargemain'] = 23;
                                
                                // Debug information (remove in production)
                                if (isset($_GET['debug'])) {
                                    error_log("DEBUG FALLBACK: No location data, item >= 299, using free delivery");
                                }
                            }
                        }
                    } else {
                        $cart['deliverycharge'] = @$cart['deliverychargemain'];
                    }
                }
            }

            // Recalculate tax with new delivery charges
            if (isset($cart['item']) && !empty($cart['item'])) {
                $itemTotal = $this->calculateCartItemTotal($cart);
                $delivery_charge_for_tax = $cart['deliverycharge'] ?? 0;
                
                // Calculate new tax (SGST + GST)
                $taxCalculation = $this->calculateNewTax($cart, $itemTotal, $delivery_charge_for_tax);
                
                $cart['tax'] = $taxCalculation['total_tax'];
                $cart['sgst'] = $taxCalculation['sgst'];
                $cart['gst'] = $taxCalculation['gst'];
                $cart['tax_label'] = 'SGST + GST';
            }

            // Ensure decimal_degits is set to 2 for proper display
            if (!isset($cart['decimal_degits']) || $cart['decimal_degits'] == 0) {
                $cart['decimal_degits'] = 2;
            }

            Session::put('cart', $cart);
            Session::save();
        }
        return view('checkout.checkout', ['is_checkout' => 1, 'cart' => $cart, 'id' => $user->uuid]);
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function proccesstopay(Request $request)
    {
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $cart = Session::get('cart', []);
        if (@$cart['cart_order']) {
            // For COD payments, place order directly and redirect to success
            // if ($cart['cart_order']['payment_method'] == 'cod') {
            //     // Place order directly
            //     $this->placeOrderDirectly($cart);
            //     return redirect()->route('success');
            // }
            if ($cart['cart_order']['payment_method'] == 'razorpay') {
                $razorpaySecret = $cart['cart_order']['razorpaySecret'];
                $razorpayKey = $cart['cart_order']['razorpayKey'];
                $authorName = $cart['cart_order']['authorName'];
                $total_pay = $cart['cart_order']['total_pay'];
                $formatted_price = $cart['cart_order']['currencyData']['symbol'] . number_format($total_pay, $cart['cart_order']['currencyData']['decimal_degits']);
                return view('checkout.razorpay', ['is_checkout' => 1, 'cart' => $cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'razorpaySecret' => $razorpaySecret, 'razorpayKey' => $razorpayKey, 'cart_order' => $cart['cart_order'], 'formatted_price' => $formatted_price]);
            } elseif ($cart['cart_order']['payment_method'] == 'payfast') {
                $payfast_merchant_key = $cart['cart_order']['payfast_merchant_key'];
                $payfast_merchant_id = $cart['cart_order']['payfast_merchant_id'];
                $payfast_isSandbox = $cart['cart_order']['payfast_isSandbox'];
                $payfast_return_url = route('success');
                $payfast_notify_url = route('notify');
                $payfast_cancel_url = route('pay');
                $authorName = $cart['cart_order']['authorName'];
                $total_pay = $cart['cart_order']['total_pay'];
                $formatted_price = $cart['cart_order']['currencyData']['symbol'] . number_format($total_pay, $cart['cart_order']['currencyData']['decimal_degits']);
                $amount = 0;
                $token = uniqid();
                Session::put('payfast_payment_token', $token);
                Session::save();
                $payfast_return_url = $payfast_return_url . '?token=' . $token;
                $amount = number_format($total_pay, 2, '.', '');
                $data = [
                    'merchant_id' => $payfast_merchant_id,
                    'merchant_key' => $payfast_merchant_key,
                    'return_url' => $payfast_return_url,
                    'cancel_url' => $payfast_cancel_url,
                    'notify_url' => $payfast_notify_url,
                    'name_first' => $authorName,
                    'm_payment_id' => $token,
                    'amount' => $amount,
                    'item_name' => 'Test',
                ];
                $signature = $this->generateSignature($data);
                $data['signature'] = $signature;
                $pfHost = $payfast_isSandbox == 'true' ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
                return view('checkout.payfast', [
                    'amount' => $amount,
                    'pfHost' => $pfHost,
                    'data' => $data,
                    'payfast_merchant_key' => $payfast_merchant_key,
                    'payfast_merchant_id' => $payfast_merchant_id,
                    'payfast_return_url' => $payfast_return_url,
                    'payfast_notify_url' => $payfast_notify_url,
                    'payfast_cancel_url' => $payfast_cancel_url,
                    'item_name' => 'Test',
                    'formatted_price' => $formatted_price,
                ]);
            } elseif ($cart['cart_order']['payment_method'] == 'xendit') {
                $xendit_enable = $cart['cart_order']['xendit_enable'];
                $xendit_apiKey = $cart['cart_order']['xendit_apiKey'];
                if (isset($xendit_enable) && $xendit_enable == true) {
                    $total_pay = $cart['cart_order']['total_pay'];
                    $currency = $cart['cart_order']['currencyData']['code'];
                    $fail_url = route('pay');
                    $success_url = route('success');
                    Configuration::setXenditKey($xendit_apiKey);
                    $token = uniqid();
                    $success_url = $success_url . '?xendit_token=' . $token;
                    Session::put('xendit_payment_token', $token);
                    Session::save();
                    $apiInstance = new InvoiceApi();
                    $create_invoice_request = new CreateInvoiceRequest([
                        'external_id' => $token,
                        'description' => '#' . $token . ' Order place',
                        'amount' => (int) $total_pay * 1000,
                        'invoice_duration' => 300,
                        'currency' => 'IDR',
                        'success_redirect_url' => $success_url,
                        'failure_redirect_url' => $fail_url,
                    ]);
                    try {
                        $result = $apiInstance->createInvoice($create_invoice_request);
                        return redirect($result['invoice_url']);
                    } catch (XenditSdkException $e) {
                        return response()->json(
                            [
                                'message' => 'Exception when calling InvoiceApi->createInvoice: ' . $e->getMessage(),
                                'error' => $e->getFullError(),
                            ],
                            500,
                        );
                    }
                }
            } elseif ($cart['cart_order']['payment_method'] == 'midtrans') {
                $midtrans_enable = $cart['cart_order']['midtrans_enable'];
                $midtrans_serverKey = $cart['cart_order']['midtrans_serverKey'];
                $midtrans_isSandbox = $cart['cart_order']['midtrans_isSandbox'];
                if (isset($midtrans_enable) && isset($midtrans_serverKey) && $midtrans_enable == true) {
                    if ($midtrans_isSandbox == true) {
                        $url = 'https://api.sandbox.midtrans.com/v1/payment-links';
                    } else {
                        $url = 'https://api.midtrans.com/v1/payment-links';
                    }
                    $total_pay = $cart['cart_order']['total_pay'];
                    $currency = $cart['cart_order']['currencyData']['code'];
                    $fail_url = route('pay');
                    $success_url = route('success');
                    $token = uniqid();
                    $success_url = $success_url . '?midtrans_token=' . $token;
                    Session::put('midtrans_payment_token', $token);
                    Session::save();
                    $payload = [
                        'transaction_details' => [
                            'order_id' => $token,
                            'gross_amount' => (int) $total_pay * 1000,
                        ],
                        'usage_limit' => 1,
                        'callbacks' => [
                            'error' => $fail_url,
                            'unfinish' => $fail_url,
                            'close' => $fail_url,
                            'finish' => $success_url,
                        ],
                    ];
                    try {
                        $client = new Client();
                        $response = $client->post($url, [
                            'headers' => [
                                'Accept' => 'application/json',
                                'Content-Type' => 'application/json',
                                'Authorization' => 'Basic ' . base64_encode($midtrans_serverKey),
                            ],
                            'body' => json_encode($payload),
                        ]);
                        $responseBody = json_decode($response->getBody(), true);
                        if (isset($responseBody['payment_url'])) {
                            return redirect($responseBody['payment_url']);
                        } else {
                            return response()->json(['error' => 'Failed to generate payment link'], 500);
                        }
                    } catch (\Exception $e) {
                        return response()->json(['error' => $e->getMessage()], 500);
                    }
                }
            } elseif ($cart['cart_order']['payment_method'] == 'orangepay') {
                $orangepay_enable = $cart['cart_order']['orangepay_enable'];
                $orangepay_isSandbox = $cart['cart_order']['orangepay_isSandbox'];
                Session::put('orangepay_isSandbox', $orangepay_isSandbox);
                Session::save();
                $orangepay_clientId = $cart['cart_order']['orangepay_clientId'];
                $orangepay_clientSecret = $cart['cart_order']['orangepay_clientSecret'];
                $orangepay_merchantKey = $cart['cart_order']['orangepay_merchantKey'];
                $token = $this->getAccessToken($orangepay_clientId, $orangepay_clientSecret);
                Session::put('orangepay_access_token', $token);
                Session::save();
                if (isset($token) && $token != null && isset($orangepay_enable) && isset($orangepay_clientId) && $orangepay_enable == true) {
                    if ($orangepay_isSandbox == true) {
                        $url = 'https://api.orange.com/orange-money-webpay/dev/v1/webpayment';
                    } else {
                        $url = 'https://api.orange.com/orange-money-webpay/cm/v1/webpayment';
                    }
                    $total_pay = $cart['cart_order']['total_pay'];
                    $currency = $orangepay_isSandbox == true ? 'OUV' : $cart['cart_order']['currencyData']['code'];
                    $orangepay_token = uniqid();
                    $fail_url = route('pay');
                    $success_url = route('success');
                    $success_url = $success_url . '?orangepay_token=' . $orangepay_token;
                    $notify_url = $success_url . '?orangepay_token=' . $orangepay_token;
                    Session::put('orangepay_payment_token', $orangepay_token);
                    Session::save();
                    $payload = [
                        'merchant_key' => $orangepay_merchantKey,
                        'currency' => $currency,
                        'order_id' => $orangepay_token,
                        'amount' => (int) $total_pay,
                        'return_url' => $success_url,
                        'cancel_url' => $fail_url,
                        'notif_url' => $notify_url,
                        'lang' => 'en',
                        'reference' => $orangepay_token,
                    ];
                    try {
                        $client = new Client();
                        $response = $client->post($url, [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $token,
                                'Content-Type' => 'application/json',
                            ],
                            'body' => json_encode($payload),
                        ]);
                        $responseBody = json_decode($response->getBody(), true);
                        if (isset($responseBody['payment_url'])) {
                            Session::put('orangepay_payment_check_token', $responseBody['pay_token']);
                            Session::save();
                            return redirect($responseBody['payment_url']);
                        } else {
                            return response()->json(['error' => 'Payment request failed']);
                        }
                    } catch (\Exception $e) {
                        return response()->json(['error' => $e->getMessage()]);
                    }
                }
            } elseif ($cart['cart_order']['payment_method'] == 'paystack') {
                $paystack_public_key = $cart['cart_order']['paystack_public_key'];
                $paystack_secret_key = $cart['cart_order']['paystack_secret_key'];
                $paystack_isSandbox = $cart['cart_order']['paystack_isSandbox'];
                $authorName = $cart['cart_order']['authorName'];
                $total_pay = $cart['cart_order']['total_pay'];
                $amount = 0;
                \Paystack\Paystack::init($paystack_secret_key);
                $payment = \Paystack\Transaction::initialize([
                    'email' => $email,
                    'amount' => (int) ($total_pay * 100),
                ]);
                Session::put('paystack_authorization_url', $payment->authorization_url);
                Session::put('paystack_access_code', $payment->access_code);
                Session::put('paystack_reference', $payment->reference);
                Session::save();
                if ($payment->authorization_url) {
                    $script = "<script>
                        window.location = '" . $payment->authorization_url . "';
                    </script>";
                    echo $script;
                    exit();
                } else {
                    $script = "<script>
                        window.location = '" . url('
                        ') . "';
                    </script>";
                    echo $script;
                    exit();
                }
            } elseif ($cart['cart_order']['payment_method'] == 'flutterwave') {
                $currency = 'USD';
                if (@$cart['cart_order']['currencyData']['code']) {
                    $currency = $cart['cart_order']['currencyData']['code'];
                }
                $flutterWave_secret_key = $cart['cart_order']['flutterWave_secret_key'];
                $flutterWave_public_key = $cart['cart_order']['flutterWave_public_key'];
                $flutterWave_isSandbox = $cart['cart_order']['flutterWave_isSandbox'];
                $flutterWave_encryption_key = $cart['cart_order']['flutterWave_encryption_key'];
                $authorName = $cart['cart_order']['authorName'];
                $total_pay = $cart['cart_order']['total_pay'];
                $formatted_price = $cart['cart_order']['currencyData']['symbol'] . number_format($total_pay, $cart['cart_order']['currencyData']['decimal_degits']);
                Session::put('flutterwave_pay', 1);
                Session::save();
                $token = uniqid();
                Session::put('flutterwave_pay_tx_ref', $token);
                Session::save();
                return view('checkout.flutterwave', ['is_checkout' => 1, 'cart' => $cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'flutterWave_secret_key' => $flutterWave_secret_key, 'flutterWave_public_key' => $flutterWave_public_key, 'flutterWave_isSandbox' => $flutterWave_isSandbox, 'flutterWave_encryption_key' => $flutterWave_encryption_key, 'token' => $token, 'cart_order' => $cart['cart_order'], 'currency' => $currency, 'formatted_price' => $formatted_price]);
            } elseif ($cart['cart_order']['payment_method'] == 'mercadopago') {
                $currency = 'USD';
                if (@$cart['cart_order']['currencyData']['code']) {
                    $currency = $cart['cart_order']['currencyData']['code'];
                }
                $mercadopago_public_key = $cart['cart_order']['mercadopago_public_key'];
                $mercadopago_access_token = $cart['cart_order']['mercadopago_access_token'];
                $mercadopago_isSandbox = $cart['cart_order']['mercadopago_isSandbox'];
                $mercadopago_isEnabled = $cart['cart_order']['mercadopago_isEnabled'];
                $quantity = $cart['cart_order']['quantity'];
                $id = $cart['cart_order']['id'];
                $total_pay = $cart['cart_order']['total_pay'];
                $items['title'] = $id;
                $items['quantity'] = 1;
                $items['unit_price'] = floatval($total_pay);
                $fields[] = $items;
                $item['items'] = $fields;
                $item['back_urls']['failure'] = route('pay');
                $item['back_urls']['pending'] = route('notify');
                $item['back_urls']['success'] = route('success');
                $item['auto_return'] = 'all';
                Session::put('mercadopago_pay', 1);
                Session::save();
                $url = 'https://api.mercadopago.com/checkout/preferences';
                $data = ['Accept: application/json', 'Authorization:Bearer ' . $mercadopago_access_token];
                $post_data = json_encode($item);
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization:Bearer ' . $mercadopago_access_token]);
                $response = curl_exec($ch);
                $mercadopago = json_decode($response);
                Session::put('mercadopago_preference_id', $mercadopago->id);
                Session::save();
                if ($mercadopago === null) {
                    die(curl_error($ch));
                }
                $authorName = $cart['cart_order']['authorName'];
                $total_pay = $cart['cart_order']['total_pay'];
                if ($mercadopago_isSandbox == 'true') {
                    $payment_url = $mercadopago->sandbox_init_point;
                } else {
                    $payment_url = $mercadopago->init_point;
                }
                echo "<script>
                    location.href = '" . $payment_url . "';
                </script>";
                exit();
            } elseif ($cart['cart_order']['payment_method'] == 'stripe') {
                $stripeKey = $cart['cart_order']['stripeKey'];
                $stripeSecret = $cart['cart_order']['stripeSecret'];
                $authorName = $cart['cart_order']['authorName'];
                $total_pay = $cart['cart_order']['total_pay'];
                $address_line1 = $cart['cart_order']['address_line1'];
                $address_line2 = $cart['cart_order']['address_line2'];
                $address_zipcode = $cart['cart_order']['address_zipcode'];
                $address_city = $cart['cart_order']['address_city'];
                $address_country = $cart['cart_order']['address_country'];
                $stripeSecret = $cart['cart_order']['stripeSecret'];
                $stripeKey = $cart['cart_order']['stripeKey'];
                $isStripeSandboxEnabled = $cart['cart_order']['isStripeSandboxEnabled'];
                $authorName = $cart['cart_order']['authorName'];
                $total_pay = $cart['cart_order']['total_pay'];
                $formatted_price = $cart['cart_order']['currencyData']['symbol'] . number_format($total_pay, $cart['cart_order']['currencyData']['decimal_degits']);
                $amount = 0;
                return view('checkout.stripe', ['is_checkout' => 1, 'cart' => $cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'stripeSecret' => $stripeSecret, 'stripeKey' => $stripeKey, 'cart_order' => $cart['cart_order'], 'formatted_price' => $formatted_price]);
            } elseif ($cart['cart_order']['payment_method'] == 'paypal') {
                $paypalKey = $cart['cart_order']['paypalKey'];
                $paypalSecret = $cart['cart_order']['paypalSecret'];
                $authorName = $cart['cart_order']['authorName'];
                $total_pay = $cart['cart_order']['total_pay'];
                $address_line1 = $cart['cart_order']['address_line1'];
                $address_line2 = $cart['cart_order']['address_line2'];
                $address_zipcode = $cart['cart_order']['address_zipcode'];
                $address_city = $cart['cart_order']['address_city'];
                $address_country = $cart['cart_order']['address_country'];
                $ispaypalSandboxEnabled = $cart['cart_order']['ispaypalSandboxEnabled'];
                $amount = 0;
                $formatted_price = $cart['cart_order']['currencyData']['symbol'] . number_format($total_pay, $cart['cart_order']['currencyData']['decimal_degits']);
                return view('checkout.paypal', ['is_checkout' => 1, 'cart' => $cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'paypalSecret' => $paypalSecret, 'paypalKey' => $paypalKey, 'cart_order' => $cart['cart_order'], 'formatted_price' => $formatted_price]);
            }// ... existing code ...
} elseif ($cart['cart_order']['payment_method'] == 'cashondelivery' || $cart['cart_order']['payment_method'] == 'cod') {
    // Mark payment as successful in session
    $cart['payment_status'] = true;
    Session::put('cart', $cart);
    Session::put('success', 'Order placed successfully! Please pay on delivery.');
    Session::save();

    // Option 1: Redirect directly to the success page
    return redirect()->route('success');

    // Option 2: Render a custom COD confirmation page (uncomment if you want a separate view)
    // return view('checkout.cod', [
    //     'is_checkout' => 1,
    //     'cart' => $cart,
    //     'id' => $user->uuid,
    //     'email' => $email,
    //     'authorName' => $cart['cart_order']['authorName'] ?? '',
    //     'amount' => $cart['cart_order']['total_pay'] ?? 0,
    // ]);
} else {
            return redirect()->route('checkout');
        }
    }
    public function notify()
    {
        if ($_POST) {
            $pfData = $_POST;
            if (@$pfData['payment_status']) {
                Session::put('payfast_payment', $pfData);
                Session::save();
            }
        }
    }
    function generateSignature($data)
    {
        $getString = http_build_query($data, '', '&', PHP_QUERY_RFC3986);
        return md5($getString);
    }
    private function getAccessToken($clientId, $clientSecret)
    {
        $authUrl = 'https://api.orange.com/oauth/v3/token';
        $client = new Client();
        try {
            $response = $client->post($authUrl, [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret),
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                ],
            ]);
            $body = json_decode($response->getBody(), true);
            return $body['access_token'] ?? null;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function processStripePayment(Request $request)
    {
        $email = Auth::user()->email;
        $input = $request->all();
        $cart = Session::get('cart', []);
        if (@$cart['cart_order'] && $input['token_id']) {
            if ($cart['cart_order']['stripeKey'] && $cart['cart_order']['stripeSecret']) {
                $currency = 'usd';
                if (@$cart['cart_order']['currency']) {
                    $currency = $cart['cart_order']['currency'];
                }
                $stripeSecret = $cart['cart_order']['stripeSecret'];
                $stripe = new \Stripe\StripeClient($stripeSecret);
                $name = $input['name'];
                $address_line1 = $input['address_line1'];
                $address_line2 = $input['address_line2'];
                $address_city = $input['address_city'];
                $address_state = $input['address_state'];
                $address_country = $input['address_country'];
                $address_zipcode = $input['address_zipcode'];
                $description = env('APP_NAME', 'JippyMart') . ' Order';
                try {
                    $charge = $stripe->paymentIntents->create([
                        'amount' => $cart['cart_order']['total_pay'] * 1000,
                        'currency' => $currency,
                        'description' => $description,
                    ]);
                    $cart['payment_status'] = true;
                    Session::put('cart', $cart);
                    Session::put('success', 'Payment successful');
                    Session::save();
                    $res = ['status' => true, 'data' => $charge, 'message' => 'success'];
                    echo json_encode($res);
                    exit();
                } catch (Exception $e) {
                    $cart['payment_status'] = false;
                    Session::put('cart', $cart);
                    Session::put('error', $e->getMessage());
                    Session::save();
                    $res = ['status' => false, 'message' => $e->getMessage()];
                    echo json_encode($res);
                    exit();
                }
            }
        }
    }
    public function processMercadoPagoPayment(Request $request)
    {
        $email = Auth::user()->email;
        $input = $request->all();
        $cart = Session::get('cart', []);
        if (@$cart['cart_order'] && $input['token_id']) {
            if ($cart['cart_order']['PublicKey'] && $cart['cart_order']['AccessToken']) {
                $currency = 'usd';
                if (@$cart['cart_order']['currency']) {
                    $currency = $cart['cart_order']['currency'];
                }
                $mercadopagoAccess = $cart['cart_order']['AccessToken'];
                $name = $input['name'];
                $urladdress = 'https://api.mercadopago.com/checkout/preferences';
                $data = 'PublicKey=' . $request->input('PublicKey') . '&AccessToken=' . $request->input('AccessToken') . '&amount=' . $request->input('amount');
            }
        }
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function processPaypalPayment(Request $request)
    {
        $email = Auth::user()->email;
        $input = $request->all();
        $cart = Session::get('cart', []);
        if (@$cart['cart_order']) {
            if ($cart['cart_order']) {
                $cart['payment_status'] = true;
                Session::put('cart', $cart);
                Session::put('success', 'Payment successful');
                Session::save();
                $res = ['status' => true, 'data' => [], 'message' => 'success'];
                echo json_encode($res);
                exit();
            }
        }
        $cart['payment_status'] = false;
        Session::put('cart', $cart);
        Session::put('error', 'Faild Payment');
        Session::save();
        $res = ['status' => false, 'message' => 'Faild Payment'];
        echo json_encode($res);
        exit();
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function razorpaypayment(Request $request)
    {
        $input = $request->all();
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $cart = Session::get('cart', []);
        $api_secret = $cart['cart_order']['razorpaySecret'];
        $api_key = $cart['cart_order']['razorpayKey'];
        $api = new Api($api_key, $api_secret);
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        if (count($input) && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(['amount' => $payment['amount']]);
                $cart['payment_status'] = true;
                Session::put('cart', $cart);
                Session::save();
            } catch (Exception $e) {
                return $e->getMessage();
                Session::put('error', $e->getMessage());
                return redirect()->back();
            }
        }
        Session::put('success', 'Payment successful');
        return redirect()->route('success');
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function success()
    {
        $cart = Session::get('cart', []);
        $order_json = [];
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();

        // Clear COD order session data after success page loads
        if (isset($cart['cod_order'])) {
            unset($cart['cod_order']);
            Session::put('cart', $cart);
            Session::save();
        }

        // Clear COD order session data after success page loads
        if (isset($cart['cod_order'])) {
            unset($cart['cod_order']);
            Session::put('cart', $cart);
            Session::save();
        }

        // Clear COD order session data after success page loads
        if (isset($cart['cod_order'])) {
            unset($cart['cod_order']);
            Session::put('cart', $cart);
            Session::save();
        }
        if (isset($_GET['xendit_token'])) {
            $xendit_payment = Session::get('xendit_payment_token');
            if ($xendit_payment == $_GET['xendit_token']) {
                $cart['payment_status'] = true;
                Session::put('cart', $cart);
                Session::put('success', 'Payment successful');
                Session::save();
            }
        }
        if (isset($_GET['midtrans_token'])) {
            $midtrans_payment = Session::get('midtrans_payment_token');
            if ($midtrans_payment === $_GET['midtrans_token']) {
                $cart['payment_status'] = true;
                Session::put('cart', $cart);
                Session::put('success', 'Payment successful');
                Session::save();
            }
        }
        if (isset($_GET['orangepay_token'])) {
            $orangepay_token = Session::get('orangepay_payment_token');
            if ($orangepay_token === $_GET['orangepay_token']) {
                $orangepay_access_token = Session::get('orangepay_access_token');
                $payToken = session('orangepay_payment_check_token');
                $orangepay_isSandbox = session('orangepay_isSandbox');
                $fail_url = route('pay');
                if (!$payToken && !$orangepay_access_token) {
                    return response()->json(['error' => 'Payment token not found in session']);
                }
                $url = $orangepay_isSandbox == false ? 'https://api.orange.com/orange-money-webpay/cm/v1/transactionstatus' : 'https://api.orange.com/orange-money-webpay/dev/v1/transactionstatus';
                try {
                    $client = new Client();
                    $payload = [
                        'pay_token' => $payToken,
                    ];
                    $response = $client->post($url, [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $orangepay_access_token,
                            'Content-Type' => 'application/json',
                        ],
                        'body' => json_encode($payload),
                    ]);
                    $responseBody = json_decode($response->getBody(), true);
                    if (isset($responseBody['status']) && $responseBody['status'] == 'SUCCESS') {
                        $cart['payment_status'] = true;
                        Session::put('cart', $cart);
                        Session::put('success', 'Payment successful');
                        Session::save();
                    } else {
                        return redirect($fail_url);
                    }
                } catch (\Exception $e) {
                    return response()->json(['error' => $e->getMessage()]);
                }
            }
        }
        if (isset($_GET['token'])) {
            $payfast_payment = Session::get('payfast_payment_token');
            if ($payfast_payment == $_GET['token']) {
                $cart['payment_status'] = true;
                Session::put('cart', $cart);
                Session::put('success', 'Payment successful');
                Session::save();
            }
        }
        if (isset($_GET['reference'])) {
            $paystack_reference = Session::get('paystack_reference');
            $paystack_access_code = Session::get('paystack_access_code');
            if ($paystack_reference == $_GET['reference']) {
                $cart['payment_status'] = true;
                Session::put('cart', $cart);
                Session::put('success', 'Payment successful');
                Session::save();
            }
        }
        if (isset($_GET['transaction_id']) && isset($_GET['tx_ref']) && isset($_GET['status'])) {
            $flutterwave_pay_tx_ref = Session::get('flutterwave_pay_tx_ref');
            if ($_GET['status'] == 'successful' && $flutterwave_pay_tx_ref == $_GET['tx_ref']) {
                $cart['payment_status'] = true;
                Session::put('cart', $cart);
                Session::put('success', 'Payment successful');
                Session::save();
            } else {
                return redirect()->route('checkout');
            }
        }
        if (isset($_GET['preference_id']) && isset($_GET['payment_id']) && isset($_GET['status'])) {
            $mercadopago_preference_id = Session::get('mercadopago_preference_id');
            if ($_GET['status'] == 'approved' && $mercadopago_preference_id == $_GET['preference_id']) {
                $cart['payment_status'] = true;
                Session::put('cart', $cart);
                Session::put('success', 'Payment successful');
                Session::save();
            } else {
                return redirect()->route('checkout');
            }
        }
        $payment_method = @$cart['cart_order']['payment_method'] ? $cart['cart_order']['payment_method'] : 'cod';
        return view('checkout.success', ['cart' => $cart, 'id' => $user->uuid, 'email' => $email, 'payment_method' => $payment_method]);
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function orderProccessing(Request $request)
    {
        $cart_order = $request->all();
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $cart = Session::get('cart', []);
        $cart['cart_order'] = $cart_order;
        Session::put('cart', $cart);
        Session::save();
        $res = ['status' => true];
        echo json_encode($res);
        exit();
    }

    public function storeOrderSession(Request $request)
    {
        $order_id = $request->order_id;
        $payment_method = $request->payment_method;

        // Store order info in session for success page
        $cart = Session::get('cart', []);
        $cart['cod_order'] = [
            'order_id' => $order_id,
            'payment_method' => $payment_method,
            'payment_status' => true
        ];
        Session::put('cart', $cart);
        Session::save();

        return response()->json(['status' => true]);
    }
    public function proccesspaystack(Request $request)
    {
        $cart = Session::get('cart', []);
        $paystack_public_key = $cart['cart_order']['paystack_public_key'];
        $paystack_secret_key = $cart['cart_order']['paystack_secret_key'];
        \Paystack\Paystack::init($paystack_secret_key);
        $payment = \Paystack\Transaction::initialize([
            'email' => 'jame@gosling.com',
            'amount' => '3000',
        ]);
        exit();
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function failed()
    {
        echo 'failed payment';
    }
    /**
     * Write code on Method
     *
     * @return response()
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
     * Calculate total item value from cart
     */
    private function calculateCartItemTotal($cart)
    {
        $total = 0;
        
        if (isset($cart['item']) && is_array($cart['item'])) {
            foreach ($cart['item'] as $restaurantItems) {
                if (is_array($restaurantItems)) {
                    foreach ($restaurantItems as $item) {
                        $basePrice = floatval($item['item_price'] ?? 0);
                        $extraPrice = floatval($item['extra_price'] ?? 0);
                        $quantity = floatval($item['quantity'] ?? 1);
                        
                        $total += ($basePrice + $extraPrice) * $quantity;
                    }
                }
            }
        }
        
        return $total;
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

    // /**
    //  * Place order directly for COD payments
    //  */
    // private function placeOrderDirectly($cart)
    // {
    //     try {
    //         // Set payment status as successful for COD
    //         $cart['payment_status'] = true;
    //         Session::put('cart', $cart);
    //         Session::put('success', 'Order placed successfully! Please pay on delivery.');
    //         Session::save();

    //         // Here you can add any additional order processing logic
    //         // For example, creating order in database, sending notifications, etc.
            
    //         return true;
    //     } catch (\Exception $e) {
    //         Log::error('Error placing COD order: ' . $e->getMessage());
    //         return false;
    //     }
    // }
}
