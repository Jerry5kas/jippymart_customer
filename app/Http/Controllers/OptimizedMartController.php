<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseOptimizationService;
use Illuminate\Support\Facades\Log;

class OptimizedMartController extends Controller
{
    private $firebaseService;
    
    public function __construct()
    {
        $this->firebaseService = new FirebaseOptimizationService();
    }
    
    public function index()
    {
        // Set strict limits for shared hosting
        ini_set('memory_limit', '64M');
        set_time_limit(15);
        
        try {
            Log::info('OptimizedMartController: Starting data fetch');
            
            // =========================
            // 1️⃣ OPTIMIZED CATEGORIES
            // =========================
            $categoryData = $this->firebaseService->queryWithLimits('mart_categories', [], 15);
            $categoryIds = array_column($categoryData, 'id');
            
            // =========================
            // 2️⃣ OPTIMIZED SUBCATEGORIES
            // =========================
            $subcategoryData = $this->firebaseService->queryWithLimits('mart_subcategories', [], 30);
            
            // Group subcategories by parent
            $subcategoriesByParent = [];
            foreach ($subcategoryData as $sub) {
                $parentId = $sub['parent_category_id'] ?? null;
                if ($parentId && in_array($parentId, $categoryIds)) {
                    $subcategoriesByParent[$parentId][] = [
                        'id' => $sub['id'] ?? null,
                        'title' => $sub['title'] ?? 'No Title',
                        'photo' => $sub['photo'] ?? '/img/pro1.jpg',
                    ];
                }
            }
            
            // Attach subcategories to categories
            foreach ($categoryData as &$cat) {
                $cat['subcategories'] = $subcategoriesByParent[$cat['id']] ?? [];
            }
            
            // =========================
            // 3️⃣ OPTIMIZED ITEMS
            // =========================
            $allItemsData = $this->firebaseService->queryWithLimits('mart_items', [], 30);
            
            // Process items into different categories
            $spotlightProducts = [];
            $newProducts = [];
            $featuredProducts = [];
            
            foreach ($allItemsData as $item) {
                $processedItem = $this->formatItemData($item);
                
                // Categorize items
                if (isset($item['isSpotlight']) && $item['isSpotlight']) {
                    $spotlightProducts[] = $processedItem;
                } elseif (isset($item['isNew']) && $item['isNew']) {
                    $newProducts[] = $processedItem;
                } else {
                    $featuredProducts[] = $processedItem;
                }
            }
            
            // Limit each category to prevent memory issues
            $spotlightProducts = array_slice($spotlightProducts, 0, 10);
            $newProducts = array_slice($newProducts, 0, 10);
            $featuredProducts = array_slice($featuredProducts, 0, 20);
            
            // =========================
            // 4️⃣ RETURN OPTIMIZED DATA
            // =========================
            $data = [
                'categories' => $categoryData,
                'spotlight_products' => $spotlightProducts,
                'new_products' => $newProducts,
                'featured_products' => $featuredProducts,
                'total_categories' => count($categoryData),
                'total_products' => count($allItemsData),
                'optimized' => true
            ];
            
            // Cleanup Firebase connection
            $this->firebaseService->cleanup();
            
            Log::info('OptimizedMartController: Data fetch completed successfully');
            
            return response()->json($data);
            
        } catch (\Exception $e) {
            Log::error('OptimizedMartController error: ' . $e->getMessage());
            
            // Return fallback data
            return response()->json($this->getFallbackData());
        }
    }
    
    /**
     * Format item data for consistent output
     */
    private function formatItemData($item)
    {
        return [
            'id' => $item['id'] ?? null,
            'name' => $item['name'] ?? 'No Name',
            'price' => $item['price'] ?? 0,
            'image' => $item['image'] ?? '/img/placeholder.jpg',
            'description' => $item['description'] ?? '',
            'category' => $item['category'] ?? 'General',
            'isAvailable' => $item['isAvailable'] ?? true,
            'rating' => $item['rating'] ?? 0,
            'reviews' => $item['reviews'] ?? 0
        ];
    }
    
    /**
     * Get fallback data when Firebase fails
     */
    private function getFallbackData()
    {
        return [
            'categories' => [
                [
                    'id' => 'fallback-1',
                    'name' => 'Food & Beverages',
                    'subcategories' => []
                ]
            ],
            'spotlight_products' => [
                [
                    'id' => 'fallback-spotlight-1',
                    'name' => 'Sample Spotlight Product',
                    'price' => 15.00,
                    'image' => '/img/placeholder.jpg',
                    'description' => 'Sample product description',
                    'category' => 'General',
                    'isAvailable' => true,
                    'rating' => 4.5,
                    'reviews' => 10
                ]
            ],
            'new_products' => [],
            'featured_products' => [],
            'total_categories' => 1,
            'total_products' => 1,
            'optimized' => true,
            'fallback' => true
        ];
    }
}
