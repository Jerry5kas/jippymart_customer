<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RateLimitService
{
    const DEFAULT_LIMIT = 100; // requests per minute
    const DEFAULT_WINDOW = 60; // seconds
    
    /**
     * Check if request is within rate limit
     *
     * @param string $key
     * @param int $limit
     * @param int $window
     * @return bool
     */
    public function isAllowed(string $key, int $limit = self::DEFAULT_LIMIT, int $window = self::DEFAULT_WINDOW): bool
    {
        $cacheKey = "rate_limit_{$key}";
        $current = Cache::get($cacheKey, 0);
        
        if ($current >= $limit) {
            Log::warning("Rate limit exceeded for key: {$key} ({$current}/{$limit})");
            return false;
        }
        
        // Increment counter
        Cache::increment($cacheKey);
        
        // Set expiration if this is the first request
        if ($current === 0) {
            Cache::put($cacheKey, 1, $window);
        }
        
        return true;
    }
    
    /**
     * Get remaining requests
     *
     * @param string $key
     * @param int $limit
     * @return int
     */
    public function getRemaining(string $key, int $limit = self::DEFAULT_LIMIT): int
    {
        $cacheKey = "rate_limit_{$key}";
        $current = Cache::get($cacheKey, 0);
        
        return max(0, $limit - $current);
    }
    
    /**
     * Get rate limit info
     *
     * @param string $key
     * @param int $limit
     * @return array
     */
    public function getInfo(string $key, int $limit = self::DEFAULT_LIMIT): array
    {
        $cacheKey = "rate_limit_{$key}";
        $current = Cache::get($cacheKey, 0);
        
        return [
            'key' => $key,
            'current' => $current,
            'limit' => $limit,
            'remaining' => max(0, $limit - $current),
            'reset_time' => Cache::get($cacheKey . '_reset', time() + 60)
        ];
    }
}
