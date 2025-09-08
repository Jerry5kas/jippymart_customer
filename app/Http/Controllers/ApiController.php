<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\VendorUsers;
use App\Models\User;
use App\Services\DeliveryChargeService;
use App\Helpers\DeliveryChargeHelper;

class ApiController extends Controller
{
    protected $deliveryChargeService;

    public function __construct(DeliveryChargeService $deliveryChargeService)
    {
        $this->deliveryChargeService = $deliveryChargeService;
    }

    public function deleteUserFromDb(Request $request) {

        $validator = Validator::make($request->all(), [
            'uuid' => 'required|exists:vendor_users,uuid',  
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'okay',
                'message' => $validator->errors()->first(), 
            ], 400);
        }
    
        DB::beginTransaction();
    
        try {
            $vendorUser = VendorUsers::where('uuid', $request->uuid)->first();
            if ($vendorUser) {
                $user_id = $vendorUser->user_id;  
                $user = User::find($user_id);
                if ($user) {
                    $user->delete();  
                } else {
                    return response()->json([
                        'status' => 'okay',
                        'message' => 'User not found with the provided user_id.',
                    ], 404);
                }
                $vendorUser->delete();
            } else {
                return response()->json([
                    'status' => 'okay',
                    'message' => 'No associated vendor user found with the provided UUID.',
                ], 404);
            }

            DB::commit();
    
            return response()->json([
                'status' => 'okay',
                'message' => 'User and associated records deleted successfully.',
            ], 200);
    
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete user. ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Calculate delivery charge using new business rules
     */
    public function calculateDeliveryCharge(Request $request)
    {
        $request->validate([
            'item_total' => 'required|numeric|min:0',
            'distance' => 'required|numeric|min:0',
            'delivery_settings' => 'sometimes|array'
        ]);

        $itemTotal = $request->input('item_total');
        $distance = $request->input('distance');
        $deliverySettings = $request->input('delivery_settings');

        try {
            $calculation = $this->deliveryChargeService->calculateDeliveryCharge(
                $itemTotal, 
                $distance, 
                $deliverySettings
            );

            $displayData = $this->deliveryChargeService->getDeliveryChargeDisplay(
                $itemTotal, 
                $distance, 
                $deliverySettings
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'calculation' => $calculation,
                    'display' => $displayData,
                    'formatted' => DeliveryChargeHelper::formatDeliveryCharge($displayData)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error calculating delivery charge: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get delivery charge settings
     */
    public function getDeliverySettings()
    {
        try {
            $settings = DeliveryChargeHelper::getDeliverySettings();
            
            return response()->json([
                'success' => true,
                'data' => $settings
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching delivery settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update delivery charge settings (admin only)
     */
    public function updateDeliverySettings(Request $request)
    {
        $request->validate([
            'base_delivery_charge' => 'required|numeric|min:0',
            'item_total_threshold' => 'required|numeric|min:0',
            'free_delivery_distance_km' => 'required|numeric|min:0',
            'per_km_charge_above_free_distance' => 'required|numeric|min:0',
            'vendor_can_modify' => 'boolean'
        ]);

        try {
            // Here you would update the settings in Firebase/Firestore
            // For now, just return success
            $settings = $request->only([
                'base_delivery_charge',
                'item_total_threshold', 
                'free_delivery_distance_km',
                'per_km_charge_above_free_distance',
                'vendor_can_modify'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Delivery settings updated successfully',
                'data' => $settings
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating delivery settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product stock information for mobile apps
     */
    public function getProductStockInfo(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string',
        ]);

        try {
            $productId = $request->input('product_id');
            
            // Get product from Firebase (you'll need to implement this based on your Firebase setup)
            // For now, this is a placeholder implementation
            $product = $this->getProductFromFirebase($productId);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            // Determine stock status using the same logic as web app
            $stockInfo = [
                'product_id' => $productId,
                'quantity' => $product['quantity'] ?? 0,
                'is_available' => false,
                'stock_status' => 'out_of_stock',
                'message' => 'Out of Stock'
            ];

            if ($product['quantity'] == -1) {
                $stockInfo['is_available'] = true;
                $stockInfo['stock_status'] = 'unlimited';
                $stockInfo['message'] = 'In Stock (Unlimited)';
            } elseif ($product['quantity'] > 0) {
                $stockInfo['is_available'] = true;
                $stockInfo['stock_status'] = 'limited';
                $stockInfo['message'] = 'In Stock (' . $product['quantity'] . ' left)';
            }

            return response()->json([
                'success' => true,
                'data' => $stockInfo
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching product stock info: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product from Firebase (placeholder implementation)
     */
    private function getProductFromFirebase($productId)
    {
        // This is a placeholder - implement based on your Firebase setup
        // You would typically use the Firebase SDK here
        return null;
    }
}
