<?php

namespace App\Services;

use Razorpay\Api\Api;
use Exception;
use Illuminate\Support\Facades\Log;

class RazorpayService
{
    protected $api;
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
        $this->initializeRazorpay();
    }

    /**
     * Initialize Razorpay API with settings from Firebase
     */
    private function initializeRazorpay()
    {
        try {
            $settings = $this->firebaseService->getRazorpaySettings();
            
            if (!$settings || !$settings['isEnabled']) {
                throw new Exception('Razorpay is not enabled or settings not found');
            }

            $apiKey = $settings['razorpayKey'];
            $apiSecret = $settings['razorpaySecret'];

            if (empty($apiKey) || empty($apiSecret)) {
                throw new Exception('Razorpay API credentials not configured');
            }

            $this->api = new Api($apiKey, $apiSecret);
        } catch (Exception $e) {
            Log::error('Failed to initialize Razorpay: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a new Razorpay order
     *
     * @param array $orderData
     * @return array
     */
    public function createOrder(array $orderData)
    {
        try {
            $orderParams = [
                'receipt' => 'order_' . time(),
                'amount' => $orderData['amount'] * 100, // Convert to paise
                'currency' => $orderData['currency'] ?? 'INR',
                'notes' => [
                    'order_type' => $orderData['order_type'] ?? 'order',
                    'user_id' => $orderData['user_id'] ?? '',
                    'restaurant_id' => $orderData['restaurant_id'] ?? ''
                ]
            ];

            $razorpayOrder = $this->api->order->create($orderParams);

            return [
                'success' => true,
                'order_id' => $razorpayOrder->id,
                'amount' => $orderData['amount'],
                'currency' => $orderData['currency'] ?? 'INR',
                'receipt' => $orderParams['receipt']
            ];
        } catch (Exception $e) {
            Log::error('Failed to create Razorpay order: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify payment signature
     *
     * @param string $paymentId
     * @param string $orderId
     * @param string $signature
     * @return bool
     */
    public function verifyPaymentSignature($paymentId, $orderId, $signature)
    {
        try {
            $attributes = [
                'razorpay_payment_id' => $paymentId,
                'razorpay_order_id' => $orderId,
                'razorpay_signature' => $signature
            ];

            $this->api->utility->verifyPaymentSignature($attributes);
            return true;
        } catch (Exception $e) {
            Log::error('Payment signature verification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Capture payment
     *
     * @param string $paymentId
     * @param int $amount
     * @return array
     */
    public function capturePayment($paymentId, $amount)
    {
        try {
            $payment = $this->api->payment->fetch($paymentId);
            $capturedPayment = $payment->capture(['amount' => $amount * 100]);

            return [
                'success' => true,
                'payment_id' => $capturedPayment->id,
                'status' => $capturedPayment->status,
                'amount' => $capturedPayment->amount / 100,
                'currency' => $capturedPayment->currency
            ];
        } catch (Exception $e) {
            Log::error('Failed to capture payment: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get payment details
     *
     * @param string $paymentId
     * @return array
     */
    public function getPaymentDetails($paymentId)
    {
        try {
            $payment = $this->api->payment->fetch($paymentId);
            
            return [
                'success' => true,
                'payment_id' => $payment->id,
                'status' => $payment->status,
                'amount' => $payment->amount / 100,
                'currency' => $payment->currency,
                'method' => $payment->method,
                'created_at' => $payment->created_at,
                'email' => $payment->email ?? null,
                'contact' => $payment->contact ?? null
            ];
        } catch (Exception $e) {
            Log::error('Failed to get payment details: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get Razorpay settings for mobile app
     *
     * @return array
     */
    public function getSettings()
    {
        try {
            $settings = $this->firebaseService->getRazorpaySettings();
            
            if (!$settings) {
                return [
                    'success' => false,
                    'error' => 'Razorpay settings not found'
                ];
            }

            return [
                'success' => true,
                'is_enabled' => $settings['isEnabled'] ?? false,
                'is_sandbox' => $settings['isSandboxEnabled'] ?? false,
                'key' => $settings['razorpayKey'] ?? null,
                'currency' => 'INR'
            ];
        } catch (Exception $e) {
            Log::error('Failed to get Razorpay settings: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Refund payment
     *
     * @param string $paymentId
     * @param int $amount
     * @param string $reason
     * @return array
     */
    public function refundPayment($paymentId, $amount = null, $reason = '')
    {
        try {
            $refundData = [
                'payment_id' => $paymentId,
                'reason' => $reason
            ];

            if ($amount) {
                $refundData['amount'] = $amount * 100; // Convert to paise
            }

            $refund = $this->api->refund->create($refundData);

            return [
                'success' => true,
                'refund_id' => $refund->id,
                'payment_id' => $refund->payment_id,
                'amount' => $refund->amount / 100,
                'status' => $refund->status
            ];
        } catch (Exception $e) {
            Log::error('Failed to refund payment: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}

