<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\FirebaseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FirebaseOptimizationService
{
    private $firestore;
    private $maxExecutionTime = 10; // 10 seconds max for shared hosting
    private $maxMemoryUsage = 50; // 50MB max
    private $queryLimit = 20; // Max 20 records per query
    
    public function __construct()
    {
        $this->initializeFirebase();
    }
    
    private function initializeFirebase()
    {
        try {
            $credentialsPath = base_path('storage/app/firebase/credentials.json');
            if (!file_exists($credentialsPath)) {
                throw new \Exception('Firebase credentials not found');
            }
            
            $factory = (new Factory)->withServiceAccount($credentialsPath);
            $this->firestore = $factory->createFirestore()->database();
        } catch (\Exception $e) {
            Log::error('Firebase initialization failed: ' . $e->getMessage());
            $this->firestore = null;
        }
    }
    
    /**
     * Optimized query with strict limits for shared hosting
     */
    public function queryWithLimits($collection, $filters = [], $limit = null)
    {
        if (!$this->firestore) {
            return $this->getFallbackData($collection);
        }
        
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);
        
        try {
            // Check resource limits before starting
            if ($this->isResourceLimitExceeded($startTime, $startMemory)) {
                Log::warning('Resource limits exceeded before query, using fallback');
                return $this->getFallbackData($collection);
            }
            
            $query = $this->firestore->collection($collection);
            
            // Apply filters with limits
            foreach ($filters as $field => $value) {
                $query = $query->where($field, '==', $value);
            }
            
            // Always add publish filter for published content only
            $query = $query->where('publish', '==', true);
            
            // Apply strict limit
            $limit = min($limit ?? $this->queryLimit, $this->queryLimit);
            $query = $query->limit($limit);
            
            // Execute query with timeout protection
            $documents = $query->documents();
            $results = [];
            
            foreach ($documents as $doc) {
                // Check timeout during iteration
                if ($this->isResourceLimitExceeded($startTime, $startMemory)) {
                    Log::warning('Resource limits exceeded during query iteration');
                    break;
                }
                
                if ($doc->exists()) {
                    $data = $doc->data();
                    $data['id'] = $doc->id();
                    $results[] = $data;
                }
            }
            
            // Log performance metrics
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            $memoryUsed = round((memory_get_usage(true) - $startMemory) / 1024 / 1024, 2);
            
            Log::info("Firebase query completed", [
                'collection' => $collection,
                'results_count' => count($results),
                'execution_time_ms' => $executionTime,
                'memory_used_mb' => $memoryUsed
            ]);
            
            return $results;
            
        } catch (\Exception $e) {
            Log::error('Firebase query failed: ' . $e->getMessage());
            return $this->getFallbackData($collection);
        }
    }
    
    /**
     * Check if resource limits are exceeded
     */
    private function isResourceLimitExceeded($startTime, $startMemory)
    {
        $currentTime = microtime(true);
        $currentMemory = memory_get_usage(true);
        
        $executionTime = $currentTime - $startTime;
        $memoryUsed = ($currentMemory - $startMemory) / 1024 / 1024;
        $totalMemory = $currentMemory / 1024 / 1024;
        
        return $executionTime > $this->maxExecutionTime || 
               $totalMemory > $this->maxMemoryUsage ||
               $memoryUsed > 30; // 30MB per query max
    }
    
    /**
     * Get fallback data when Firebase fails
     */
    private function getFallbackData($collection)
    {
        // Return cached data if available
        $cacheKey = "firebase_fallback_{$collection}";
        $cached = Cache::get($cacheKey);
        
        if ($cached) {
            Log::info("Using cached fallback data for {$collection}");
            return $cached;
        }
        
        // Return minimal fallback data
        $fallbackData = $this->getMinimalFallbackData($collection);
        
        // Cache fallback data for 1 hour
        Cache::put($cacheKey, $fallbackData, 3600);
        
        return $fallbackData;
    }
    
    /**
     * Get minimal fallback data for each collection
     */
    private function getMinimalFallbackData($collection)
    {
        switch ($collection) {
            case 'mart_categories':
                return [
                    [
                        'id' => 'fallback-1',
                        'name' => 'Food & Beverages',
                        'publish' => true,
                        'image' => '/img/placeholder.jpg'
                    ]
                ];
                
            case 'mart_items':
                return [
                    [
                        'id' => 'fallback-item-1',
                        'name' => 'Sample Product',
                        'price' => 10.00,
                        'publish' => true,
                        'image' => '/img/placeholder.jpg'
                    ]
                ];
                
            case 'vendor_products':
                return [
                    [
                        'id' => 'fallback-product-1',
                        'name' => 'Sample Food Item',
                        'price' => 15.00,
                        'publish' => true,
                        'isAvailable' => true,
                        'image' => '/img/placeholder.jpg'
                    ]
                ];
                
            default:
                return [];
        }
    }
    
    /**
     * Clear Firebase connection to free resources
     */
    public function cleanup()
    {
        $this->firestore = null;
        gc_collect_cycles();
    }
}
