<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OptimizedMartController extends Controller
{
    public function index()
    {
        // Set strict limits for shared hosting
        ini_set('memory_limit', '64M'); // Reduced from 128M
        set_time_limit(15); // Reduced from 30 seconds

        try {
            // Check cache first
            $cacheKey = 'mart_data_optimized';
            $cachedData = Cache::get($cacheKey);
            
            if ($cachedData) {
                Log::info('MartController: Serving from cache');
                return response()->json($cachedData);
            }

            // Lightweight data structure
            $data = $this->getLightweightMartData();
            
            // Cache for 5 minutes
            Cache::put($cacheKey, $data, 300);
            
            Log::info('MartController: Data generated and cached');
            return response()->json($data);

        } catch (\Exception $e) {
            Log::error('MartController error: ' . $e->getMessage());
            
            // Return fallback data
            return response()->json($this->getFallbackData());
        }
    }

    /**
     * Get lightweight mart data
     */
    private function getLightweightMartData()
    {
        return [
            'categories' => $this->getLightweightCategories(),
            'featured_items' => $this->getLightweightFeaturedItems(),
            'offers' => $this->getLightweightOffers(),
            'meta' => [
                'generated_at' => now()->toISOString(),
                'version' => 'optimized_v1',
                'cache_duration' => '5 minutes'
            ]
        ];
    }

    /**
     * Get lightweight categories
     */
    private function getLightweightCategories()
    {
        // Static categories for better performance
        return [
            [
                'id' => 'groceries',
                'name' => 'Groceries',
                'image' => '/img/categories/groceries.jpg',
                'subcategories' => [
                    ['id' => 'vegetables', 'name' => 'Vegetables'],
                    ['id' => 'fruits', 'name' => 'Fruits'],
                    ['id' => 'dairy', 'name' => 'Dairy Products']
                ]
            ],
            [
                'id' => 'household',
                'name' => 'Household',
                'image' => '/img/categories/household.jpg',
                'subcategories' => [
                    ['id' => 'cleaning', 'name' => 'Cleaning Supplies'],
                    ['id' => 'kitchen', 'name' => 'Kitchen Items']
                ]
            ],
            [
                'id' => 'personal_care',
                'name' => 'Personal Care',
                'image' => '/img/categories/personal-care.jpg',
                'subcategories' => [
                    ['id' => 'hygiene', 'name' => 'Hygiene'],
                    ['id' => 'cosmetics', 'name' => 'Cosmetics']
                ]
            ]
        ];
    }

    /**
     * Get lightweight featured items
     */
    private function getLightweightFeaturedItems()
    {
        return [
            [
                'id' => 'featured_1',
                'name' => 'Fresh Vegetables',
                'price' => 299,
                'image' => '/img/items/vegetables.jpg',
                'discount' => '10% OFF'
            ],
            [
                'id' => 'featured_2',
                'name' => 'Organic Fruits',
                'price' => 399,
                'image' => '/img/items/fruits.jpg',
                'discount' => '15% OFF'
            ]
        ];
    }

    /**
     * Get lightweight offers
     */
    private function getLightweightOffers()
    {
        return [
            [
                'id' => 'offer_1',
                'title' => 'Free Delivery',
                'description' => 'Free delivery on orders above ₹500',
                'code' => 'FREEDEL500'
            ],
            [
                'id' => 'offer_2',
                'title' => 'New User Discount',
                'description' => '20% off on first order',
                'code' => 'WELCOME20'
            ]
        ];
    }

    /**
     * Get fallback data when Firebase fails
     */
    private function getFallbackData()
    {
        return [
            'categories' => $this->getLightweightCategories(),
            'featured_items' => $this->getLightweightFeaturedItems(),
            'offers' => $this->getLightweightOffers(),
            'meta' => [
                'generated_at' => now()->toISOString(),
                'version' => 'fallback_v1',
                'status' => 'fallback_mode'
            ],
            'message' => 'Service temporarily using fallback data'
        ];
    }
}
