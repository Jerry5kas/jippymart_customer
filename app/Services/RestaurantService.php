<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RestaurantService
{
    /**
     * Get restaurant information for a product
     */
    public function getRestaurantInfo($productId, $vendorId = null)
    {
        $cacheKey = "restaurant_info_{$productId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($productId, $vendorId) {
            return $this->fetchRestaurantData($productId, $vendorId);
        });
    }
    
    /**
     * Fetch restaurant data from database/Firebase
     */
    private function fetchRestaurantData($productId, $vendorId = null)
    {
        try {
            // If vendor ID is provided, use it directly
            if ($vendorId) {
                $restaurantData = $this->getRestaurantByVendorId($vendorId);
                
                return [
                    'status' => true,
                    'restaurant' => $restaurantData,
                    'debug' => [
                        'product_id' => $productId,
                        'vendor_id' => $vendorId,
                        'message' => 'Using provided vendor ID',
                        'cached' => false
                    ]
                ];
            }
            
            // For now, we'll create a mapping of product IDs to vendor IDs
            // In a real implementation, this would come from Firebase/database
            $productVendorMap = [
                // Add your actual product-to-vendor mappings here
                // Example: 'product_id' => 'vendor_id'
                'test-product-1' => 'rx8Hx9WMTs1VhggmTogH', // Kritunga
                'test-product-2' => 'vendor-2-id', // Another restaurant
                'test-product-3' => 'vendor-3-id', // Third restaurant
            ];
            
            // Get vendor ID for this product
            $vendorId = $productVendorMap[$productId] ?? 'rx8Hx9WMTs1VhggmTogH'; // Default fallback
            
            // Create restaurant data based on vendor ID
            $restaurantData = $this->getRestaurantByVendorId($vendorId);
            
            return [
                'status' => true,
                'restaurant' => $restaurantData,
                'debug' => [
                    'product_id' => $productId,
                    'vendor_id' => $vendorId,
                    'message' => 'Using dynamic restaurant data',
                    'cached' => false
                ]
            ];
            
        } catch (\Exception $e) {
            Log::error("Error fetching restaurant data for product {$productId}: " . $e->getMessage());
            
            // Return fallback data
            return [
                'status' => true,
                'restaurant' => [
                    'id' => 'rx8Hx9WMTs1VhggmTogH',
                    'name' => 'Kritunga Restaurant',
                    'slug' => 'kritunga-restaurant',
                    'zone_slug' => 'hyderabad'
                ],
                'debug' => [
                    'product_id' => $productId,
                    'vendor_id' => 'rx8Hx9WMTs1VhggmTogH',
                    'message' => 'Using fallback restaurant data due to error',
                    'error' => $e->getMessage(),
                    'cached' => false
                ]
            ];
        }
    }
    
    /**
     * Get restaurant data by vendor ID
     */
    private function getRestaurantByVendorId($vendorId)
    {
        // Create a mapping of vendor IDs to restaurant data
        // In a real implementation, this would come from Firebase/database
        $vendorRestaurantMap = [
            'rx8Hx9WMTs1VhggmTogH' => [
                'id' => 'rx8Hx9WMTs1VhggmTogH',
                'name' => 'Kritunga Restaurant',
                'slug' => 'kritunga-restaurant',
                'zone_slug' => 'hyderabad'
            ],
            'vendor-2-id' => [
                'id' => 'vendor-2-id',
                'name' => 'Spice Garden',
                'slug' => 'spice-garden',
                'zone_slug' => 'bangalore'
            ],
            'vendor-3-id' => [
                'id' => 'vendor-3-id',
                'name' => 'Taste of India',
                'slug' => 'taste-of-india',
                'zone_slug' => 'mumbai'
            ],
            // Add more vendor IDs that might exist in your system
            'vendor-4-id' => [
                'id' => 'vendor-4-id',
                'name' => 'Delhi Darbar',
                'slug' => 'delhi-darbar',
                'zone_slug' => 'delhi'
            ],
            'vendor-5-id' => [
                'id' => 'vendor-5-id',
                'name' => 'Chennai Kitchen',
                'slug' => 'chennai-kitchen',
                'zone_slug' => 'chennai'
            ],
        ];
        
        // If vendor ID is not in our mapping, create a dynamic restaurant name
        if (!isset($vendorRestaurantMap[$vendorId])) {
            return [
                'id' => $vendorId,
                'name' => 'Restaurant ' . substr($vendorId, -4), // Use last 4 chars of vendor ID
                'slug' => 'restaurant-' . substr($vendorId, -4),
                'zone_slug' => 'zone'
            ];
        }
        
        return $vendorRestaurantMap[$vendorId];
    }
    
    /**
     * Clear restaurant cache for a specific product
     */
    public function clearCache($productId)
    {
        $cacheKey = "restaurant_info_{$productId}";
        Cache::forget($cacheKey);
        Log::info("Cleared restaurant cache for product: {$productId}");
    }
    
    /**
     * Clear all restaurant cache
     */
    public function clearAllCache()
    {
        // This would need a more sophisticated cache clearing mechanism
        // For now, we'll rely on cache expiration
        Log::info("Restaurant cache will expire automatically");
    }
} 