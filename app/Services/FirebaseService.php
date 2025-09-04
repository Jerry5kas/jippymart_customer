<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth as FirebaseAuth;
use Google\Cloud\Firestore\FirestoreClient;
use App\Services\CategoryCacheService;
use App\Services\CircuitBreakerService;

class FirebaseService
{
    protected $auth;
    protected $firestore;
    protected $cacheService;
    protected $circuitBreaker;

    public function __construct(CategoryCacheService $cacheService, CircuitBreakerService $circuitBreaker)
    {
        $this->cacheService = $cacheService;
        $this->circuitBreaker = $circuitBreaker;
        
        try {
            $factory = (new Factory)
                ->withServiceAccount(storage_path('app/firebase/credentials.json'));
            $this->auth = $factory->createAuth();
            $this->firestore = new FirestoreClient([
                'projectId' => env('FIREBASE_PROJECT_ID'),
                'keyFilePath' => storage_path('app/firebase/credentials.json')
            ]);
        } catch (\Exception $e) {
            \Log::error('Firebase initialization failed: ' . $e->getMessage());
            $this->firestore = null;
        }
    }

    /**
     * Create a Firebase custom token for a given UID.
     *
     * @param string $uid
     * @return string
     */
    public function createCustomToken(string $uid): string
    {
        $customToken = $this->auth->createCustomToken($uid);
        return $customToken->toString();
    }

    /**
     * Get Razorpay settings from Firestore
     *
     * @return array|null
     */
    public function getRazorpaySettings()
    {
        try {
            $document = $this->firestore->collection('settings')->document('razorpaySettings')->snapshot();

            if ($document->exists()) {
                return $document->data();
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Error fetching Razorpay settings: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get user data from Firestore
     *
     * @param string $userId
     * @return array|null
     */
    public function getUserData(string $userId)
    {
        try {
            $document = $this->firestore->collection('users')->document($userId)->snapshot();

            if ($document->exists()) {
                return $document->data();
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Error fetching user data: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Save order data to Firestore
     *
     * @param array $orderData
     * @return string|null
     */
    public function saveOrderData(array $orderData)
    {
        try {
            $document = $this->firestore->collection('orders')->add($orderData);
            return $document->id();
        } catch (\Exception $e) {
            \Log::error('Error saving order data: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update order data in Firestore
     *
     * @param string $orderId
     * @param array $updateData
     * @return bool
     */
    public function updateOrderData(string $orderId, array $updateData)
    {
        try {
            $this->firestore->collection('orders')->document($orderId)->update($updateData);
            return true;
        } catch (\Exception $e) {
            \Log::error('Error updating order data: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get order data from Firestore
     *
     * @param string $orderId
     * @return array|null
     */
    public function getOrderData(string $orderId)
    {
        try {
            $document = $this->firestore->collection('orders')->document($orderId)->snapshot();

            if ($document->exists()) {
                return $document->data();
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Error fetching order data: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update user data in Firestore
     *
     * @param string $userId
     * @param array $updateData
     * @return bool
     */
    public function updateUserData(string $userId, array $updateData)
    {
        try {
            $this->firestore->collection('users')->document($userId)->update($updateData);
            return true;
        } catch (\Exception $e) {
            \Log::error('Error updating user data: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get vendor data from Firestore
     *
     * @param string $vendorId
     * @return array|null
     */
    public function getVendorData(string $vendorId)
    {
        try {
            $document = $this->firestore->collection('vendors')->document($vendorId)->snapshot();

            if ($document->exists()) {
                $vendorData = $document->data();
                // Sanitize the data to remove Inf and NaN values
                return $this->sanitizeData($vendorData);
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Error fetching vendor data: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get nearby vendors using geospatial queries
     *
     * @param float $latitude
     * @param float $longitude
     * @param float $radius
     * @param int $limit
     * @return array
     */
    public function getNearbyVendors(float $latitude, float $longitude, float $radius = 10, int $limit = 20)
    {
        try {
            // Create a GeoPoint for the center
            $center = new \Google\Cloud\Core\GeoPoint($latitude, $longitude);

            // Calculate bounding box for the radius (approximate)
            $latDelta = $radius / 111.32; // 1 degree = 111.32 km
            $lngDelta = $radius / (111.32 * cos(deg2rad($latitude)));

            $minLat = $latitude - $latDelta;
            $maxLat = $latitude + $latDelta;
            $minLng = $longitude - $lngDelta;
            $maxLng = $longitude + $lngDelta;

            // Query vendors within the bounding box
            $query = $this->firestore->collection('vendors')
                ->where('latitude', '>=', $minLat)
                ->where('latitude', '<=', $maxLat)
                ->where('longitude', '>=', $minLng)
                ->where('longitude', '<=', $maxLng)
                ->where('isOpen', '==', true)
                ->limit($limit);

            $documents = $query->documents();
            $vendors = [];

            foreach ($documents as $document) {
                $vendorData = $document->data();
                $vendorData['id'] = $document->id();

                // Sanitize the data to remove Inf and NaN values
                $vendorData = $this->sanitizeData($vendorData);

                // Calculate actual distance
                $vendorLat = $vendorData['latitude'] ?? 0;
                $vendorLng = $vendorData['longitude'] ?? 0;
                $distance = $this->calculateDistance($latitude, $longitude, $vendorLat, $vendorLng);

                // Only include vendors within the specified radius
                if ($distance <= $radius) {
                    $vendorData['distance'] = round($distance, 2);
                    $vendors[] = $vendorData;
                }
            }

            // Sort by distance
            usort($vendors, function($a, $b) {
                return $a['distance'] <=> $b['distance'];
            });

            return $vendors;
        } catch (\Exception $e) {
            \Log::error('Error fetching nearby vendors: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get vendor items by category
     *
     * @param string $vendorId
     * @param string $categoryId
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getVendorItemsByCategory(string $vendorId, string $categoryId, int $page = 1, int $limit = 20)
    {
        try {
            $query = $this->firestore->collection('items')
                ->where('vendorID', '==', $vendorId)
                ->where('categoryID', '==', $categoryId)
                ->where('publish', '==', true)
                ->where('isAvailable', '==', true);

            // Apply pagination
            $offset = ($page - 1) * $limit;
            $query = $query->limit($limit)->offset($offset);

            $documents = $query->documents();
            $items = [];

            foreach ($documents as $document) {
                $itemData = $document->data();
                $itemData['id'] = $document->id();
                $items[] = $itemData;
            }

            // For simplicity, we'll assume there are more results if we got the full limit
            $hasMore = count($items) === $limit;

            return [
                'data' => $items,
                'total' => count($items) + ($page - 1) * $limit,
                'has_more' => $hasMore
            ];
        } catch (\Exception $e) {
            \Log::error('Error fetching vendor items by category: ' . $e->getMessage());
            return [
                'data' => [],
                'total' => 0,
                'has_more' => false
            ];
        }
    }

    /**
     * Get vendor items by subcategory
     *
     * @param string $vendorId
     * @param string $subcategoryId
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getVendorItemsBySubcategory(string $vendorId, string $subcategoryId, int $page = 1, int $limit = 20)
    {
        try {
            $query = $this->firestore->collection('items')
                ->where('vendorID', '==', $vendorId)
                ->where('subcategoryID', '==', $subcategoryId)
                ->where('publish', '==', true)
                ->where('isAvailable', '==', true);

            // Apply pagination
            $offset = ($page - 1) * $limit;
            $query = $query->limit($limit)->offset($offset);

            $documents = $query->documents();
            $items = [];

            foreach ($documents as $document) {
                $itemData = $document->data();
                $itemData['id'] = $document->id();
                $items[] = $itemData;
            }

            // For simplicity, we'll assume there are more results if we got the full limit
            $hasMore = count($items) === $limit;

            return [
                'data' => $items,
                'total' => count($items) + ($page - 1) * $limit,
                'has_more' => $hasMore
            ];
        } catch (\Exception $e) {
            \Log::error('Error fetching vendor items by subcategory: ' . $e->getMessage());
            return [
                'data' => [],
                'total' => 0,
                'has_more' => false
            ];
        }
    }

    /**
     * Get vendor working hours
     *
     * @param string $vendorId
     * @return array|null
     */
    public function getVendorWorkingHours(string $vendorId)
    {
        try {
            $document = $this->firestore->collection('vendors')->document($vendorId)->snapshot();

            if ($document->exists()) {
                $vendorData = $document->data();
                return $vendorData['workingHours'] ?? null;
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Error fetching vendor working hours: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get vendor special discounts
     *
     * @param string $vendorId
     * @return array|null
     */
    public function getVendorSpecialDiscounts(string $vendorId)
    {
        try {
            $document = $this->firestore->collection('vendors')->document($vendorId)->snapshot();

            if ($document->exists()) {
                $vendorData = $document->data();
                return $vendorData['specialDiscounts'] ?? null;
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Error fetching vendor special discounts: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Calculate distance between two points using Haversine formula
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float
     */
    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Search categories with optimized query, caching, and fallbacks
     *
     * @param string $searchTerm
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function searchCategories(string $searchTerm = '', int $limit = 20, int $offset = 0): array
    {
        $startTime = microtime(true);
        $cacheKey = 'search_' . md5($searchTerm . '_' . $limit . '_' . $offset);
        
        try {
            // Check circuit breaker
            if ($this->circuitBreaker->isOpen('firestore')) {
                \Log::warning('Circuit breaker is open for Firestore, using fallback');
                return $this->getFallbackSearchResult($searchTerm, $limit, $offset);
            }
            
            // Check if Firestore is available
            if (!$this->firestore) {
                \Log::error('Firestore not available, using fallback');
                return $this->getFallbackSearchResult($searchTerm, $limit, $offset);
            }
            
            // Try to get from cache first
            $result = $this->cacheService->get($cacheKey, function() use ($searchTerm, $limit, $offset) {
                return $this->performFirestoreSearch($searchTerm, $limit, $offset);
            });
            
            // Record success
            $this->circuitBreaker->recordSuccess('firestore');
            
            // Store as stale data for emergency fallback
            $this->cacheService->storeStale($cacheKey, $result);
            
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            \Log::info("Category search completed in {$responseTime}ms", [
                'search_term' => $searchTerm,
                'results_count' => count($result['data']),
                'cache_hit' => true
            ]);
            
            return $result;
            
        } catch (\Exception $e) {
            // Record failure
            $this->circuitBreaker->recordFailure('firestore');
            
            \Log::error('Error searching categories: ' . $e->getMessage(), [
                'search_term' => $searchTerm,
                'limit' => $limit,
                'offset' => $offset
            ]);
            
            return $this->getFallbackSearchResult($searchTerm, $limit, $offset);
        }
    }
    
    /**
     * Perform the actual Firestore search
     *
     * @param string $searchTerm
     * @param int $limit
     * @param int $offset
     * @return array
     */
    private function performFirestoreSearch(string $searchTerm, int $limit, int $offset): array
    {
        $query = $this->firestore->collection('mart_categories')
            ->where('publish', '==', true);

        if (!empty($searchTerm)) {
            $searchTerm = strtolower(trim($searchTerm));
            
            // Fetch all published categories and filter in PHP
            $documents = $query->documents();
            $filteredCategories = [];
            
            foreach ($documents as $document) {
                $categoryData = $document->data();
                $categoryData['id'] = $document->id();
                
                // Check if search term matches title or description (case-insensitive)
                $title = strtolower($categoryData['title'] ?? '');
                $description = strtolower($categoryData['description'] ?? '');
                
                if (strpos($title, $searchTerm) !== false || strpos($description, $searchTerm) !== false) {
                    $filteredCategories[] = $this->formatCategoryData($categoryData);
                }
            }
            
            // Sort and paginate
            usort($filteredCategories, [$this, 'sortCategories']);
            $totalCount = count($filteredCategories);
            $paginatedResults = array_slice($filteredCategories, $offset, $limit);
            
            return [
                'data' => $paginatedResults,
                'total' => $totalCount,
                'has_more' => ($offset + $limit) < $totalCount,
                'current_page' => floor($offset / $limit) + 1,
                'per_page' => $limit
            ];
        } else {
            // No search term - return all published categories
            $documents = $query->documents();
            $allCategories = [];
            
            foreach ($documents as $document) {
                $categoryData = $document->data();
                $categoryData['id'] = $document->id();
                $allCategories[] = $categoryData;
            }
            
            // Sort by section_order and category_order
            usort($allCategories, [$this, 'sortCategories']);
            
            // Apply pagination
            $totalCount = count($allCategories);
            $paginatedResults = array_slice($allCategories, $offset, $limit);
            
            $categories = [];
            foreach ($paginatedResults as $categoryData) {
                $categories[] = $this->formatCategoryData($categoryData);
            }
            
            return [
                'data' => $categories,
                'total' => $totalCount,
                'has_more' => ($offset + $limit) < $totalCount,
                'current_page' => floor($offset / $limit) + 1,
                'per_page' => $limit
            ];
        }
    }
    
    /**
     * Format category data for response
     *
     * @param array $categoryData
     * @return array
     */
    private function formatCategoryData(array $categoryData): array
    {
        return [
            'id' => $categoryData['id'],
            'title' => $categoryData['title'] ?? '',
            'description' => $categoryData['description'] ?? '',
            'photo' => $categoryData['photo'] ?? '',
            'section' => $categoryData['section'] ?? '',
            'category_order' => $categoryData['category_order'] ?? 0,
            'section_order' => $categoryData['section_order'] ?? 0,
            'show_in_homepage' => $categoryData['show_in_homepage'] ?? false
        ];
    }
    
    /**
     * Sort categories by section_order and category_order
     *
     * @param array $a
     * @param array $b
     * @return int
     */
    private function sortCategories(array $a, array $b): int
    {
        $sectionOrderA = $a['section_order'] ?? 0;
        $sectionOrderB = $b['section_order'] ?? 0;
        $categoryOrderA = $a['category_order'] ?? 0;
        $categoryOrderB = $b['category_order'] ?? 0;
        
        if ($sectionOrderA == $sectionOrderB) {
            return $categoryOrderA <=> $categoryOrderB;
        }
        return $sectionOrderA <=> $sectionOrderB;
    }
    
    /**
     * Get fallback search result when Firestore fails
     *
     * @param string $searchTerm
     * @param int $limit
     * @param int $offset
     * @return array
     */
    private function getFallbackSearchResult(string $searchTerm, int $limit, int $offset): array
    {
        $fallbackData = $this->cacheService->get('search_all', function() {
            return $this->getStaticFallbackData('search_all');
        }, 3600); // Cache fallback for 1 hour
        
        if (!empty($searchTerm)) {
            $searchTerm = strtolower(trim($searchTerm));
            $filtered = array_filter($fallbackData['data'], function($category) use ($searchTerm) {
                $title = strtolower($category['title'] ?? '');
                $description = strtolower($category['description'] ?? '');
                return strpos($title, $searchTerm) !== false || strpos($description, $searchTerm) !== false;
            });
            
            $totalCount = count($filtered);
            $paginatedResults = array_slice($filtered, $offset, $limit);
            
            return [
                'data' => $paginatedResults,
                'total' => $totalCount,
                'has_more' => ($offset + $limit) < $totalCount,
                'current_page' => floor($offset / $limit) + 1,
                'per_page' => $limit,
                'fallback' => true
            ];
        }
        
        $totalCount = count($fallbackData['data']);
        $paginatedResults = array_slice($fallbackData['data'], $offset, $limit);
        
        return [
            'data' => $paginatedResults,
            'total' => $totalCount,
            'has_more' => ($offset + $limit) < $totalCount,
            'current_page' => floor($offset / $limit) + 1,
            'per_page' => $limit,
            'fallback' => true
        ];
    }
    
    /**
     * Get static fallback data
     *
     * @param string $key
     * @return array
     */
    private function getStaticFallbackData(string $key): array
    {
        $staticData = [
            'search_all' => [
                'data' => [
                    [
                        'id' => 'fallback_1',
                        'title' => 'Groceries',
                        'description' => 'Fresh groceries and daily essentials',
                        'photo' => 'https://via.placeholder.com/150x150?text=Groceries',
                        'section' => 'Grocery & Kitchen',
                        'category_order' => 1,
                        'section_order' => 1,
                        'show_in_homepage' => true
                    ],
                    [
                        'id' => 'fallback_2',
                        'title' => 'Medicine',
                        'description' => 'Health and wellness products',
                        'photo' => 'https://via.placeholder.com/150x150?text=Medicine',
                        'section' => 'Pharmacy & Health',
                        'category_order' => 2,
                        'section_order' => 2,
                        'show_in_homepage' => true
                    ],
                    [
                        'id' => 'fallback_3',
                        'title' => 'Pet Care',
                        'description' => 'Pet supplies and care products',
                        'photo' => 'https://via.placeholder.com/150x150?text=Pet+Care',
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
            ]
        ];
        
        return $staticData[$key] ?? [];
    }

    /**
     * Get all published categories (lightweight version for homepage)
     *
     * @param int $limit
     * @return array
     */
    public function getPublishedCategories(int $limit = 50): array
    {
        try {
            // Fetch all published categories first, then filter and sort in PHP
            $query = $this->firestore->collection('mart_categories')
                ->where('publish', '==', true);

            $documents = $query->documents();
            $allCategories = [];

            foreach ($documents as $document) {
                $categoryData = $document->data();
                $categoryData['id'] = $document->id();
                
                // Filter for homepage categories
                if (isset($categoryData['show_in_homepage']) && $categoryData['show_in_homepage'] === true) {
                    $allCategories[] = $categoryData;
                }
            }

            // Sort by section_order and category_order in PHP
            usort($allCategories, function($a, $b) {
                $sectionOrderA = $a['section_order'] ?? 0;
                $sectionOrderB = $b['section_order'] ?? 0;
                $categoryOrderA = $a['category_order'] ?? 0;
                $categoryOrderB = $b['category_order'] ?? 0;
                
                if ($sectionOrderA == $sectionOrderB) {
                    return $categoryOrderA <=> $categoryOrderB;
                }
                return $sectionOrderA <=> $sectionOrderB;
            });

            // Apply limit
            $limitedCategories = array_slice($allCategories, 0, $limit);
            $categories = [];

            foreach ($limitedCategories as $categoryData) {
                // Return only essential fields for lightweight response
                $categories[] = [
                    'id' => $categoryData['id'],
                    'title' => $categoryData['title'] ?? '',
                    'description' => $categoryData['description'] ?? '',
                    'photo' => $categoryData['photo'] ?? '',
                    'section' => $categoryData['section'] ?? '',
                    'category_order' => $categoryData['category_order'] ?? 0,
                    'section_order' => $categoryData['section_order'] ?? 0
                ];
            }

            return $categories;
        } catch (\Exception $e) {
            \Log::error('Error fetching published categories: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Search mart items with multiple filters
     */
    public function searchMartItems(array $filters = [], int $limit = 20, int $offset = 0): array
    {
        $startTime = microtime(true);
        
        // Check circuit breaker
        if ($this->circuitBreaker->isOpen('firestore')) {
            \Log::warning('Firestore circuit breaker is open, using fallback for mart_items search');
            return $this->getFallbackMartItemsResult($filters, $limit, $offset);
        }

        $cacheKey = 'mart_items_search_' . md5(serialize($filters) . $limit . $offset);
        
        try {
            $result = $this->cacheService->get($cacheKey, function() use ($filters, $limit, $offset) {
                return $this->performFirestoreMartItemsSearch($filters, $limit, $offset);
            }, 300); // 5 minutes cache

            // Record success
            $this->circuitBreaker->recordSuccess('firestore');
            
            // Store stale data for emergency fallback
            $this->cacheService->storeStale($cacheKey, $result, 3600);
            
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            \Log::info("Mart items search completed in {$responseTime}ms");
            
            return $result;
            
        } catch (\Exception $e) {
            \Log::error('Mart items search failed: ' . $e->getMessage());
            
            // Record failure
            $this->circuitBreaker->recordFailure('firestore');
            
            // Try to get stale data
            $staleData = $this->cacheService->get($cacheKey . '_stale');
            if ($staleData) {
                \Log::info('Using stale mart items data as fallback');
                return $staleData;
            }
            
            // Final fallback to static data
            return $this->getFallbackMartItemsResult($filters, $limit, $offset);
        }
    }

    /**
     * Perform the actual Firestore search for mart items
     */
    private function performFirestoreMartItemsSearch(array $filters, int $limit, int $offset): array
    {
        if (!$this->firestore) {
            throw new \Exception('Firestore not initialized');
        }

        $collection = $this->firestore->collection('mart_items');
        $query = $collection->where('publish', '==', true);

        // Execute query to get all published items
        $documents = $query->documents();
        $items = [];

        foreach ($documents as $document) {
            if ($document->exists()) {
                $data = $document->data();
                $data['id'] = $document->id();
                
                // Apply filters
                if ($this->matchesFilters($data, $filters)) {
                    $items[] = $this->formatMartItemData($data);
                }
            }
        }

        // Sort items by relevance/price
        $items = $this->sortMartItems($items, $filters);

        // Apply pagination
        $total = count($items);
        $items = array_slice($items, $offset, $limit);
        $hasMore = ($offset + $limit) < $total;

        return [
            'data' => $items,
            'total' => $total,
            'has_more' => $hasMore,
            'current_page' => floor($offset / $limit) + 1,
            'per_page' => $limit,
            'filters_applied' => $filters
        ];
    }

    /**
     * Check if item matches the applied filters
     */
    private function matchesFilters(array $item, array $filters): bool
    {
        foreach ($filters as $key => $value) {
            if (empty($value)) continue;

            switch ($key) {
                case 'search':
                    $searchTerm = strtolower($value);
                    $searchFields = ['name', 'description', 'categoryTitle', 'subcategoryTitle', 'vendorTitle'];
                    $matches = false;
                    
                    foreach ($searchFields as $field) {
                        if (isset($item[$field]) && strpos(strtolower($item[$field]), $searchTerm) !== false) {
                            $matches = true;
                            break;
                        }
                    }
                    if (!$matches) return false;
                    break;

                case 'category':
                    if (isset($item['categoryTitle']) && strtolower($item['categoryTitle']) !== strtolower($value)) {
                        return false;
                    }
                    break;

                case 'subcategory':
                    if (isset($item['subcategoryTitle']) && strtolower($item['subcategoryTitle']) !== strtolower($value)) {
                        return false;
                    }
                    break;

                case 'vendor':
                    if (isset($item['vendorTitle']) && strtolower($item['vendorTitle']) !== strtolower($value)) {
                        return false;
                    }
                    break;

                case 'min_price':
                    if (isset($item['price']) && $item['price'] < $value) {
                        return false;
                    }
                    break;

                case 'max_price':
                    if (isset($item['price']) && $item['price'] > $value) {
                        return false;
                    }
                    break;

                case 'veg':
                    if (isset($item['veg']) && $item['veg'] !== (bool)$value) {
                        return false;
                    }
                    break;

                case 'isAvailable':
                    if (isset($item['isAvailable']) && $item['isAvailable'] !== (bool)$value) {
                        return false;
                    }
                    break;

                case 'isBestSeller':
                    if (isset($item['isBestSeller']) && $item['isBestSeller'] !== (bool)$value) {
                        return false;
                    }
                    break;

                case 'isFeature':
                    if (isset($item['isFeature']) && $item['isFeature'] !== (bool)$value) {
                        return false;
                    }
                    break;
            }
        }

        return true;
    }

    /**
     * Format mart item data for response
     */
    private function formatMartItemData(array $data): array
    {
        return [
            'id' => $data['id'] ?? '',
            'name' => $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'price' => $data['price'] ?? 0,
            'disPrice' => $data['disPrice'] ?? 0,
            'photo' => $data['photo'] ?? '',
            'photos' => $data['photos'] ?? [],
            'categoryID' => $data['categoryID'] ?? '',
            'categoryTitle' => $data['categoryTitle'] ?? '',
            'subcategoryID' => $data['subcategoryID'] ?? '',
            'subcategoryTitle' => $data['subcategoryTitle'] ?? '',
            'vendorID' => $data['vendorID'] ?? '',
            'vendorTitle' => $data['vendorTitle'] ?? '',
            'section' => $data['section'] ?? '',
            'veg' => $data['veg'] ?? false,
            'nonveg' => $data['nonveg'] ?? false,
            'isAvailable' => $data['isAvailable'] ?? false,
            'isBestSeller' => $data['isBestSeller'] ?? false,
            'isFeature' => $data['isFeature'] ?? false,
            'isNew' => $data['isNew'] ?? false,
            'isTrending' => $data['isTrending'] ?? false,
            'isSpotlight' => $data['isSpotlight'] ?? false,
            'isSeasonal' => $data['isSeasonal'] ?? false,
            'isStealOfMoment' => $data['isStealOfMoment'] ?? false,
            'quantity' => $data['quantity'] ?? -1,
            'calories' => $data['calories'] ?? 0,
            'proteins' => $data['proteins'] ?? 0,
            'fats' => $data['fats'] ?? 0,
            'grams' => $data['grams'] ?? 0,
            'has_options' => $data['has_options'] ?? false,
            'options_count' => $data['options_count'] ?? 0,
            'options_enabled' => $data['options_enabled'] ?? false,
            'options_toggle' => $data['options_toggle'] ?? false,
            'options' => $data['options'] ?? [],
            'addOnsTitle' => $data['addOnsTitle'] ?? [],
            'addOnsPrice' => $data['addOnsPrice'] ?? [],
            'reviewCount' => $data['reviewCount'] ?? '0',
            'reviewSum' => $data['reviewSum'] ?? '0',
            'takeawayOption' => $data['takeawayOption'] ?? false,
            'created_at' => $data['created_at'] ?? null,
            'updated_at' => $data['updated_at'] ?? null
        ];
    }

    /**
     * Sort mart items based on relevance and filters
     */
    private function sortMartItems(array $items, array $filters): array
    {
        usort($items, function($a, $b) use ($filters) {
            // Priority sorting based on filters
            $scoreA = $this->calculateRelevanceScore($a, $filters);
            $scoreB = $this->calculateRelevanceScore($b, $filters);
            
            if ($scoreA !== $scoreB) {
                return $scoreB - $scoreA; // Higher score first
            }
            
            // Secondary sort by price (ascending)
            return $a['price'] <=> $b['price'];
        });

        return $items;
    }

    /**
     * Calculate relevance score for sorting
     */
    private function calculateRelevanceScore(array $item, array $filters): int
    {
        $score = 0;
        
        // Boost featured items
        if ($item['isFeature']) $score += 100;
        if ($item['isBestSeller']) $score += 80;
        if ($item['isTrending']) $score += 60;
        if ($item['isSpotlight']) $score += 40;
        if ($item['isNew']) $score += 20;
        
        // Boost available items
        if ($item['isAvailable']) $score += 10;
        
        // Boost items with discounts
        if ($item['disPrice'] > 0 && $item['disPrice'] < $item['price']) {
            $score += 30;
        }
        
        return $score;
    }

    /**
     * Get fallback mart items result
     */
    private function getFallbackMartItemsResult(array $filters, int $limit, int $offset): array
    {
        $fallbackData = $this->getStaticMartItemsFallbackData();
        
        // Apply basic filtering to fallback data
        $filteredData = array_filter($fallbackData, function($item) use ($filters) {
            return $this->matchesFilters($item, $filters);
        });
        
        $total = count($filteredData);
        $items = array_slice($filteredData, $offset, $limit);
        $hasMore = ($offset + $limit) < $total;

        return [
            'data' => $items,
            'total' => $total,
            'has_more' => $hasMore,
            'current_page' => floor($offset / $limit) + 1,
            'per_page' => $limit,
            'filters_applied' => $filters,
            'fallback' => true
        ];
    }

    /**
     * Get static fallback data for mart items
     */
    private function getStaticMartItemsFallbackData(): array
    {
        return [
            [
                'id' => 'fallback_item_1',
                'name' => 'Fresh Orange Juice',
                'description' => 'Freshly squeezed orange juice',
                'price' => 120,
                'disPrice' => 110,
                'photo' => 'https://via.placeholder.com/150',
                'photos' => ['https://via.placeholder.com/150'],
                'categoryID' => '68b17af92183b',
                'categoryTitle' => 'Beverages (Non-Alcoholic)',
                'subcategoryID' => '68b6e7b0ebe24',
                'subcategoryTitle' => 'Juices',
                'vendorID' => '4ir2OLhuMEc2yg9L1YxX',
                'vendorTitle' => 'Jippy Mart',
                'section' => 'Beverages & Juices',
                'veg' => true,
                'nonveg' => false,
                'isAvailable' => true,
                'isBestSeller' => false,
                'isFeature' => true,
                'isNew' => false,
                'isTrending' => true,
                'isSpotlight' => true,
                'isSeasonal' => false,
                'isStealOfMoment' => true,
                'quantity' => 10,
                'calories' => 0,
                'proteins' => 0,
                'fats' => 0,
                'grams' => 0,
                'has_options' => false,
                'options_count' => 0,
                'options_enabled' => false,
                'options_toggle' => false,
                'options' => [],
                'addOnsTitle' => [],
                'addOnsPrice' => [],
                'reviewCount' => '0',
                'reviewSum' => '0',
                'takeawayOption' => false,
                'created_at' => null,
                'updated_at' => null
            ],
            [
                'id' => 'fallback_item_2',
                'name' => 'Apple Juice',
                'description' => 'Pure apple juice',
                'price' => 100,
                'disPrice' => 90,
                'photo' => 'https://via.placeholder.com/150',
                'photos' => ['https://via.placeholder.com/150'],
                'categoryID' => '68b17af92183b',
                'categoryTitle' => 'Beverages (Non-Alcoholic)',
                'subcategoryID' => '68b6e7b0ebe24',
                'subcategoryTitle' => 'Juices',
                'vendorID' => '4ir2OLhuMEc2yg9L1YxX',
                'vendorTitle' => 'Jippy Mart',
                'section' => 'Beverages & Juices',
                'veg' => true,
                'nonveg' => false,
                'isAvailable' => true,
                'isBestSeller' => true,
                'isFeature' => false,
                'isNew' => true,
                'isTrending' => false,
                'isSpotlight' => false,
                'isSeasonal' => false,
                'isStealOfMoment' => false,
                'quantity' => 15,
                'calories' => 0,
                'proteins' => 0,
                'fats' => 0,
                'grams' => 0,
                'has_options' => false,
                'options_count' => 0,
                'options_enabled' => false,
                'options_toggle' => false,
                'options' => [],
                'addOnsTitle' => [],
                'addOnsPrice' => [],
                'reviewCount' => '0',
                'reviewSum' => '0',
                'takeawayOption' => false,
                'created_at' => null,
                'updated_at' => null
            ]
        ];
    }

    /**
     * Sanitize data to remove Inf and NaN values
     *
     * @param array $data
     * @return array
     */
    private function sanitizeData(array $data): array
    {
        array_walk_recursive($data, function(&$value) {
            if (is_numeric($value)) {
                if (is_infinite($value) || is_nan($value)) {
                    $value = 0;
                }
            }
        });

        return $data;
    }
}
