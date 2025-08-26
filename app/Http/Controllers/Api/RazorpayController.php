<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RazorpayService;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorUsers;
use Exception;

class RazorpayController extends Controller
{
    protected $razorpayService;
    protected $firebaseService;

    public function __construct(RazorpayService $razorpayService, FirebaseService $firebaseService)
    {
        $this->razorpayService = $razorpayService;
        $this->firebaseService = $firebaseService;
    }

    /**
     * Get Razorpay settings for mobile app
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSettings()
    {
        try {
            $settings = $this->razorpayService->getSettings();

            if (!$settings['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $settings['error']
                ], 400);
            }

            return response()->json([
                'success' => true,
                'data' => $settings
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get Razorpay settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new Razorpay order
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'currency' => 'sometimes|string|size:3',
            'order_type' => 'required|in:order,wallet,giftcard',
            'restaurant_id' => 'required_if:order_type,order|string',
            'items' => 'required_if:order_type,order|array',
            'delivery_address' => 'required_if:order_type,order|array',
            'user_notes' => 'sometimes|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get authenticated user
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $vendorUser = VendorUsers::where('email', $user->email)->first();
            if (!$vendorUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Prepare order data
            $orderData = [
                'amount' => $request->amount,
                'currency' => $request->currency ?? 'INR',
                'order_type' => $request->order_type,
                'user_id' => $vendorUser->uuid,
                'restaurant_id' => $request->restaurant_id ?? null,
                'items' => $request->items ?? [],
                'delivery_address' => $request->delivery_address ?? [],
                'user_notes' => $request->user_notes ?? null
            ];

            // Create Razorpay order
            $razorpayOrder = $this->razorpayService->createOrder($orderData);

            if (!$razorpayOrder['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $razorpayOrder['error']
                ], 400);
            }

            // Save order data to Firebase
            $orderData['razorpay_order_id'] = $razorpayOrder['order_id'];
            $orderData['status'] = 'pending';
            $orderData['created_at'] = now()->toISOString();
            $orderData['updated_at'] = now()->toISOString();

            $firebaseOrderId = $this->firebaseService->saveOrderData($orderData);

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'order_id' => $razorpayOrder['order_id'],
                    'firebase_order_id' => $firebaseOrderId,
                    'amount' => $razorpayOrder['amount'],
                    'currency' => $razorpayOrder['currency'],
                    'receipt' => $razorpayOrder['receipt']
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify and capture payment
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
            'firebase_order_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verify payment signature
            $isValid = $this->razorpayService->verifyPaymentSignature(
                $request->razorpay_payment_id,
                $request->razorpay_order_id,
                $request->razorpay_signature
            );

            if (!$isValid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment signature verification failed'
                ], 400);
            }

            // Get payment details
            $paymentDetails = $this->razorpayService->getPaymentDetails($request->razorpay_payment_id);

            if (!$paymentDetails['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to get payment details: ' . $paymentDetails['error']
                ], 400);
            }

            // Capture payment if not already captured
            if ($paymentDetails['status'] !== 'captured') {
                $captureResult = $this->razorpayService->capturePayment(
                    $request->razorpay_payment_id,
                    $paymentDetails['amount']
                );

                if (!$captureResult['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to capture payment: ' . $captureResult['error']
                    ], 400);
                }
            }

            // Update order status in Firebase
            $updateData = [
                'payment_id' => $request->razorpay_payment_id,
                'payment_status' => 'completed',
                'status' => 'confirmed',
                'updated_at' => now()->toISOString()
            ];

            $this->firebaseService->updateOrderData($request->firebase_order_id, $updateData);

            return response()->json([
                'success' => true,
                'message' => 'Payment verified and captured successfully',
                'data' => [
                    'payment_id' => $request->razorpay_payment_id,
                    'order_id' => $request->razorpay_order_id,
                    'status' => 'captured',
                    'amount' => $paymentDetails['amount'],
                    'currency' => $paymentDetails['currency']
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment details
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $paymentDetails = $this->razorpayService->getPaymentDetails($request->payment_id);

            if (!$paymentDetails['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $paymentDetails['error']
                ], 400);
            }

            return response()->json([
                'success' => true,
                'data' => $paymentDetails
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get payment details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refund payment
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refundPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|string',
            'amount' => 'sometimes|numeric|min:1',
            'reason' => 'sometimes|string|max:200'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $refundResult = $this->razorpayService->refundPayment(
                $request->payment_id,
                $request->amount ?? null,
                $request->reason ?? 'Refund requested'
            );

            if (!$refundResult['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $refundResult['error']
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Refund processed successfully',
                'data' => $refundResult
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process refund: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order status
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firebase_order_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get order data from Firebase
            $orderData = $this->firebaseService->getOrderData($request->firebase_order_id);

            if (!$orderData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'order_id' => $orderData['razorpay_order_id'] ?? null,
                    'firebase_order_id' => $request->firebase_order_id,
                    'status' => $orderData['status'] ?? 'unknown',
                    'payment_status' => $orderData['payment_status'] ?? 'pending',
                    'amount' => $orderData['amount'] ?? 0,
                    'currency' => $orderData['currency'] ?? 'INR',
                    'order_type' => $orderData['order_type'] ?? 'order',
                    'created_at' => $orderData['created_at'] ?? null,
                    'updated_at' => $orderData['updated_at'] ?? null
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get order status: ' . $e->getMessage()
            ], 500);
        }
    }
}

