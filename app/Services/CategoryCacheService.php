<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CategoryCacheService
{
    const CACHE_PREFIX = 'categories_';
    const CACHE_TTL = 300; // 5 minutes
    const FALLBACK_CACHE_TTL = 3600; // 1 hour for fallback data
    
    /**
     * Get cached categories with fallback
     *
     * @param string $key
     * @param callable $fallback
     * @param int $ttl
     * @return array
     */
    public function get(string $key, callable $fallback, int $ttl = self::CACHE_TTL): array
    {
        $cacheKey = self::CACHE_PREFIX . $key;
        
        try {
            // Try to get from cache first
            $cached = Cache::get($cacheKey);
            if ($cached !== null) {
                Log::info("Cache hit for key: {$cacheKey}");
                return $cached;
            }
            
            // Cache miss - execute fallback
            Log::info("Cache miss for key: {$cacheKey}, executing fallback");
            $result = $fallback();
            
            // Cache the result
            if (is_array($result)) {
                Cache::put($cacheKey, $result, $ttl);
                Log::info("Cached result for key: {$cacheKey} with TTL: {$ttl}");
            }
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error("Cache error for key {$cacheKey}: " . $e->getMessage());
            
            // Try to get stale cache data as fallback
            $staleData = Cache::get($cacheKey . '_stale');
            if ($staleData !== null) {
                Log::info("Using stale cache data for key: {$cacheKey}");
                return $staleData;
            }
            
            // Return static fallback data
            return $this->getStaticFallback($key);
        }
    }
    
    /**
     * Store stale data for emergency fallback
     *
     * @param string $key
     * @param array $data
     * @return void
     */
    public function storeStale(string $key, array $data): void
    {
        $staleKey = self::CACHE_PREFIX . $key . '_stale';
        Cache::put($staleKey, $data, self::FALLBACK_CACHE_TTL);
    }
    
    /**
     * Clear cache for a specific key
     *
     * @param string $key
     * @return void
     */
    public function clear(string $key): void
    {
        $cacheKey = self::CACHE_PREFIX . $key;
        Cache::forget($cacheKey);
        Cache::forget($cacheKey . '_stale');
    }
    
    /**
     * Clear all category caches
     *
     * @return void
     */
    public function clearAll(): void
    {
        // This would need to be implemented based on your cache driver
        // For Redis: Cache::tags(['categories'])->flush();
        // For file cache: Clear specific patterns
        Log::info("Clearing all category caches");
    }
    
    /**
     * Get static fallback data when all else fails
     *
     * @param string $key
     * @return array
     */
    private function getStaticFallback(string $key): array
    {
        Log::warning("Using static fallback data for key: {$key}");
        
        $staticData = [
            'search_all' => [
                'data' => [
                    [
                        'id' => 'fallback_1',
                        'title' => 'Groceries',
                        'description' => 'Fresh groceries and daily essentials',
                        'photo' => '/img/pro1.jpg',
                        'section' => 'Grocery & Kitchen',
                        'category_order' => 1,
                        'section_order' => 1,
                        'show_in_homepage' => true
                    ],
                    [
                        'id' => 'fallback_2',
                        'title' => 'Medicine',
                        'description' => 'Health and wellness products',
                        'photo' => '/img/pro2.jpg',
                        'section' => 'Pharmacy & Health',
                        'category_order' => 2,
                        'section_order' => 2,
                        'show_in_homepage' => true
                    ],
                    [
                        'id' => 'fallback_3',
                        'title' => 'Pet Care',
                        'description' => 'Pet supplies and care products',
                        'photo' => '/img/pro3.jpg',
                        'section' => 'Pet Care',
                        'category_order' => 3,
                        'section_order' => 3,
                        'show_in_homepage' => true
                    ]
                ],
                'total' => 3,
                'has_more' => false,
                'current_page' => 1,
                'per_page' => 20
            ],
            'published' => [
                [
                    'id' => 'fallback_1',
                    'title' => 'Groceries',
                    'description' => 'Fresh groceries and daily essentials',
                    'photo' => '/img/pro1.jpg',
                    'section' => 'Grocery & Kitchen',
                    'category_order' => 1,
                    'section_order' => 1
                ],
                [
                    'id' => 'fallback_2',
                    'title' => 'Medicine',
                    'description' => 'Health and wellness products',
                    'photo' => '/img/pro2.jpg',
                    'section' => 'Pharmacy & Health',
                    'category_order' => 2,
                    'section_order' => 2
                ],
                [
                    'id' => 'fallback_3',
                    'title' => 'Pet Care',
                    'description' => 'Pet supplies and care products',
                    'photo' => '/img/pro3.jpg',
                    'section' => 'Pet Care',
                    'category_order' => 3,
                    'section_order' => 3
                ]
            ]
        ];
        
        return $staticData[$key] ?? [];
    }
}
