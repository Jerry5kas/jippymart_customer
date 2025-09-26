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
     * Get user by phone number from Firestore
     *
     * @param string $phone
     * @return array|null
     */
    public function getUserByPhone(string $phone)
    {
        try {
            if (!$this->firestore) {
                return null;
            }

            $query = $this->firestore->collection('users')
                ->where('phoneNumber', '==', $phone)
                ->limit(1);

            $documents = $query->documents();

            foreach ($documents as $document) {
                if ($document->exists()) {
                    return $document->data();
                }
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Error fetching user by phone: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create user in Firestore
     *
     * @param array $userData
     * @return array|null
     */
    public function createUser(array $userData)
    {
        try {
            if (!$this->firestore) {
                return null;
            }

            $userId = $userData['id'] ?? 'user_' . uniqid();

            $this->firestore->collection('users')->document($userId)->set($userData);

            return $userData;
        } catch (\Exception $e) {
            \Log::error('Error creating user in Firebase: ' . $e->getMessage());
            return null;
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
     * Search food items with optimized query, caching, and fallbacks
     *
     * @param string $searchTerm
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function searchFoodItems(string $searchTerm = '', int $limit = 20, int $offset = 0): array
    {
        $startTime = microtime(true);
        $cacheKey = 'food_search_' . md5($searchTerm . '_' . $limit . '_' . $offset);

        // Set execution time limit to prevent timeout (reduced for shared hosting)
        $maxExecutionTime = 15; // 15 seconds max for shared hosting

        // Check if this is admin panel request and use stricter limits
        $isAdminRequest = request()->is('admin*') || request()->getHost() === 'admin.jippymart.in';
        if ($isAdminRequest) {
            $maxExecutionTime = 8; // Even stricter for admin panel
            $limit = min($limit, 10); // Reduce limit for admin panel
        }

        try {
            // Check circuit breaker
            if ($this->circuitBreaker->isOpen('firestore')) {
                \Log::warning('Circuit breaker is open for Firestore, using fallback for food search');
                return $this->getFallbackFoodSearchResult($searchTerm, $limit, $offset);
            }

            // Check if Firestore is available
            if (!$this->firestore) {
                \Log::error('Firestore not available, using fallback for food search');
                return $this->getFallbackFoodSearchResult($searchTerm, $limit, $offset);
            }

            // Try to get from cache first with optimized TTL
            $cacheTTL = empty($searchTerm) ? 600 : 300; // 10 minutes for general search, 5 minutes for specific search
            $result = $this->cacheService->get($cacheKey, function() use ($searchTerm, $limit, $offset) {
                return $this->performFirestoreFoodSearch($searchTerm, $limit, $offset);
            }, $cacheTTL);

            // Record success
            $this->circuitBreaker->recordSuccess('firestore');

            // Store as stale data for emergency fallback
            $this->cacheService->storeStale($cacheKey, $result);

            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            \Log::info("Food search completed in {$responseTime}ms", [
                'search_term' => $searchTerm,
                'results_count' => count($result['data']),
                'cache_hit' => true
            ]);

            return $result;

        } catch (\Exception $e) {
            // Record failure
            $this->circuitBreaker->recordFailure('firestore');

            \Log::error('Error searching food items: ' . $e->getMessage(), [
                'search_term' => $searchTerm,
                'limit' => $limit,
                'offset' => $offset
            ]);

            return $this->getFallbackFoodSearchResult($searchTerm, $limit, $offset);
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
                        'photo' => '/img/pro1.jpgx150?text=Groceries',
                        'section' => 'Grocery & Kitchen',
                        'category_order' => 1,
                        'section_order' => 1,
                        'show_in_homepage' => true
                    ],
                    [
                        'id' => 'fallback_2',
                        'title' => 'Medicine',
                        'description' => 'Health and wellness products',
                        'photo' => '/img/pro1.jpgx150?text=Medicine',
                        'section' => 'Pharmacy & Health',
                        'category_order' => 2,
                        'section_order' => 2,
                        'show_in_homepage' => true
                    ],
                    [
                        'id' => 'fallback_3',
                        'title' => 'Pet Care',
                        'description' => 'Pet supplies and care products',
                        'photo' => '/img/pro1.jpgx150?text=Pet+Care',
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
                'photo' => '/img/pro1.jpg',
                'photos' => ['/img/pro1.jpg'],
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
                'photo' => '/img/pro1.jpg',
                'photos' => ['/img/pro1.jpg'],
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

    // SEO methods removed for performance optimization

    // All SEO methods removed for performance optimization

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

    /**
     * Perform the actual Firestore search for food items (OPTIMIZED)
     *
     * @param string $searchTerm
     * @param int $limit
     * @param int $offset
     * @return array
     */
    private function performFirestoreFoodSearch(string $searchTerm, int $limit, int $offset): array
    {
        // Optimized: Only search the primary collection first
        $primaryCollection = 'vendor_products';
        $startTime = microtime(true);
        $maxExecutionTime = 12; // 12 seconds max for shared hosting

        \Log::info('Starting optimized Firestore food search', [
            'search_term' => $searchTerm,
            'limit' => $limit,
            'offset' => $offset,
            'primary_collection' => $primaryCollection
        ]);

        try {
            // Check timeout before starting
            if ((microtime(true) - $startTime) > $maxExecutionTime) {
                \Log::warning('Timeout reached before starting search, using fallback');
                return $this->getFallbackFoodSearchResult($searchTerm, $limit, $offset);
            }
            // Use Firestore's built-in pagination instead of fetching all data
            $query = $this->firestore->collection($primaryCollection)
                ->where('publish', '==', true)
                ->where('isAvailable', '==', true);

                if (!empty($searchTerm)) {
                    $searchTerm = strtolower(trim($searchTerm));

                    // Check timeout before vendor search
                    if ((microtime(true) - $startTime) > $maxExecutionTime) {
                        \Log::warning('Timeout reached before vendor search, using fallback');
                        return $this->getFallbackFoodSearchResult($searchTerm, $limit, $offset);
                    }

                    // First, check if this is a restaurant/vendor search
                    $vendorSearchResults = $this->performVendorBasedSearch($searchTerm, $limit, $offset);

                    if (!empty($vendorSearchResults['data'])) {
                        \Log::info('Vendor search returned results', [
                            'search_term' => $searchTerm,
                            'results_count' => count($vendorSearchResults['data'])
                        ]);
                        return $vendorSearchResults;
                    }

                    // Check timeout before food item search
                    if ((microtime(true) - $startTime) > $maxExecutionTime) {
                        \Log::warning('Timeout reached before food item search, using fallback');
                        return $this->getFallbackFoodSearchResult($searchTerm, $limit, $offset);
                    }

                    // If no vendor results, try direct food item search
                    $searchResults = $this->performDirectFoodItemSearch($searchTerm, $limit, $offset);

                    if (!empty($searchResults['data'])) {
                        \Log::info('Food item search returned results', [
                            'search_term' => $searchTerm,
                            'results_count' => count($searchResults['data'])
                        ]);
                        return $searchResults;
                    }

                    \Log::warning('No results found for search term', ['search_term' => $searchTerm]);
                    return ['data' => [], 'total' => 0, 'has_more' => false, 'current_page' => 1, 'per_page' => $limit];
                } else {
                // No search term - use Firestore's built-in pagination
                return $this->performPaginatedQuery($query, $limit, $offset);
            }

        } catch (\Exception $e) {
            \Log::warning("Primary collection search failed: " . $e->getMessage());

            // Fallback to other collections only if primary fails
            return $this->performFallbackCollectionSearch($searchTerm, $limit, $offset);
        }
    }

    /**
     * Perform optimized search using Firestore's native capabilities
     */
    private function performOptimizedSearch($query, string $searchTerm, int $limit, int $offset): array
    {
        try {
            // Strategy 1: Try exact name matches first (fastest)
            $nameQuery = clone $query;
            $nameQuery = $nameQuery->where('name', '>=', $searchTerm)
                                  ->where('name', '<=', $searchTerm . '\uf8ff')
                                  ->limit($limit);

            $documents = $nameQuery->documents();
            $items = [];

            foreach ($documents as $document) {
                $itemData = $document->data();
                $itemData['id'] = $document->id();
                $items[] = $this->formatFoodItemData($itemData);
            }

            if (!empty($items)) {
                return [
                    'data' => $items,
                    'total' => count($items),
                    'has_more' => count($items) === $limit,
                    'current_page' => floor($offset / $limit) + 1,
                    'per_page' => $limit
                ];
            }

            // Strategy 2: If no exact matches, try broader search with PHP filtering
            return $this->performBroadSearch($query, $searchTerm, $limit, $offset);

        } catch (\Exception $e) {
            \Log::error('Optimized search failed: ' . $e->getMessage());
            return ['data' => [], 'total' => 0, 'has_more' => false, 'current_page' => 1, 'per_page' => $limit];
        }
    }

    /**
     * Perform broad search with PHP filtering (fallback)
     */
    private function performBroadSearch($query, string $searchTerm, int $limit, int $offset): array
    {
        // Limit the initial fetch to reduce memory usage
        $maxFetch = min(1000, $limit * 10); // Fetch max 1000 or 10x limit
        $documents = $query->limit($maxFetch)->documents();

        $filteredItems = [];
        $matchingVendorIds = $this->findMatchingVendorIds($searchTerm);

        foreach ($documents as $document) {
            $itemData = $document->data();
            $itemData['id'] = $document->id();

            if ($this->matchesSearchTerm($itemData, $searchTerm, $matchingVendorIds)) {
                $filteredItems[] = $this->formatFoodItemData($itemData);
            }
        }

        // Sort and paginate
        usort($filteredItems, [$this, 'sortFoodItems']);
        $totalCount = count($filteredItems);
        $paginatedResults = array_slice($filteredItems, $offset, $limit);

        return [
            'data' => $paginatedResults,
            'total' => $totalCount,
            'has_more' => ($offset + $limit) < $totalCount,
            'current_page' => floor($offset / $limit) + 1,
            'per_page' => $limit
        ];
    }

    /**
     * Check if item matches search term
     */
    private function matchesSearchTerm(array $itemData, string $searchTerm, array $matchingVendorIds): bool
    {
        $name = strtolower($itemData['name'] ?? '');
        $description = strtolower($itemData['description'] ?? '');
        $category = strtolower($itemData['categoryTitle'] ?? '');
        $cuisine = strtolower($itemData['cuisine'] ?? '');
        $vendorTitle = strtolower($itemData['vendorTitle'] ?? '');
        $restaurantName = strtolower($itemData['restaurantName'] ?? '');
        $vendorName = strtolower($itemData['vendorName'] ?? '');
        $vendorId = $itemData['vendorID'] ?? '';

        // Check if search term matches any of these fields
        $matchesFoodItem = strpos($name, $searchTerm) !== false ||
            strpos($description, $searchTerm) !== false ||
            strpos($category, $searchTerm) !== false ||
            strpos($cuisine, $searchTerm) !== false ||
            strpos($vendorTitle, $searchTerm) !== false ||
            strpos($restaurantName, $searchTerm) !== false ||
            strpos($vendorName, $searchTerm) !== false;

        // Also check if this item belongs to a matching vendor
        $matchesVendor = in_array($vendorId, $matchingVendorIds);

        return $matchesFoodItem || $matchesVendor;
    }

    /**
     * Perform vendor-based search (when searching for restaurant names)
     */
    private function performVendorBasedSearch(string $searchTerm, int $limit, int $offset): array
    {
        try {
            \Log::info('Starting vendor-based search', [
                'search_term' => $searchTerm,
                'limit' => $limit,
                'offset' => $offset
            ]);

            $matchingVendorIds = $this->findMatchingVendorIds($searchTerm);

            if (empty($matchingVendorIds)) {
                \Log::info('No matching vendors found for search term', ['search_term' => $searchTerm]);
                return ['data' => [], 'total' => 0, 'has_more' => false, 'current_page' => 1, 'per_page' => $limit];
            }

            \Log::info('Found matching vendors', [
                'vendor_ids' => $matchingVendorIds,
                'count' => count($matchingVendorIds)
            ]);

            // Search for items from matching vendors
            $query = $this->firestore->collection('vendor_products')
                ->where('publish', '==', true)
                ->where('isAvailable', '==', true);

            $allItems = [];
            $totalItemsFound = 0;

            foreach ($matchingVendorIds as $vendorId) {
                \Log::info('Searching products for vendor', ['vendor_id' => $vendorId]);

                $vendorQuery = clone $query;
                $vendorQuery = $vendorQuery->where('vendorID', '==', $vendorId)->limit(100); // Increased limit to get more items
                $documents = $vendorQuery->documents();

                $vendorItemCount = 0;
                foreach ($documents as $document) {
                    $itemData = $document->data();
                    $itemData['id'] = $document->id();
                    $allItems[] = $this->formatFoodItemData($itemData);
                    $vendorItemCount++;
                    $totalItemsFound++;
                }

                \Log::info('Found items for vendor', [
                    'vendor_id' => $vendorId,
                    'items_count' => $vendorItemCount
                ]);
            }

            \Log::info('Total items found from all vendors', [
                'total_items' => $totalItemsFound,
                'search_term' => $searchTerm
            ]);

            // Sort and paginate
            usort($allItems, [$this, 'sortFoodItems']);
            $totalCount = count($allItems);
            $paginatedResults = array_slice($allItems, $offset, $limit);

            \Log::info('Vendor search completed', [
                'total_items' => $totalCount,
                'returned_items' => count($paginatedResults),
                'has_more' => ($offset + $limit) < $totalCount
            ]);

            return [
                'data' => $paginatedResults,
                'total' => $totalCount,
                'has_more' => ($offset + $limit) < $totalCount,
                'current_page' => floor($offset / $limit) + 1,
                'per_page' => $limit
            ];

        } catch (\Exception $e) {
            \Log::error('Vendor-based search failed: ' . $e->getMessage(), [
                'search_term' => $searchTerm,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ['data' => [], 'total' => 0, 'has_more' => false, 'current_page' => 1, 'per_page' => $limit];
        }
    }

    /**
     * Perform paginated query using Firestore's built-in pagination
     */
    private function performPaginatedQuery($query, int $limit, int $offset): array
    {
        try {
            // Use Firestore's built-in pagination
            $paginatedQuery = $query->limit($limit);

            if ($offset > 0) {
                // For offset > 0, we need to use cursor-based pagination
                // This is more efficient than offset-based pagination
                $documents = $query->limit($offset + $limit)->documents();
                $allItems = [];

                foreach ($documents as $document) {
                    $itemData = $document->data();
                    $itemData['id'] = $document->id();
                    $allItems[] = $itemData;
                }

                // Apply offset and limit
                $paginatedItems = array_slice($allItems, $offset, $limit);
                $items = [];

                foreach ($paginatedItems as $itemData) {
                    $items[] = $this->formatFoodItemData($itemData);
                }

                return [
                    'data' => $items,
                    'total' => count($allItems), // Approximate total
                    'has_more' => count($allItems) === ($offset + $limit),
                    'current_page' => floor($offset / $limit) + 1,
                    'per_page' => $limit
                ];
            } else {
                // For offset = 0, use direct limit
                $documents = $paginatedQuery->documents();
                $items = [];

                foreach ($documents as $document) {
                    $itemData = $document->data();
                    $itemData['id'] = $document->id();
                    $items[] = $this->formatFoodItemData($itemData);
                }

                return [
                    'data' => $items,
                    'total' => count($items), // Approximate total
                    'has_more' => count($items) === $limit,
                    'current_page' => 1,
                    'per_page' => $limit
                ];
            }

        } catch (\Exception $e) {
            \Log::error('Paginated query failed: ' . $e->getMessage());
            return ['data' => [], 'total' => 0, 'has_more' => false, 'current_page' => 1, 'per_page' => $limit];
        }
    }

    /**
     * Fallback to other collections if primary fails
     */
    private function performFallbackCollectionSearch(string $searchTerm, int $limit, int $offset): array
    {
        $fallbackCollections = ['food_items', 'items', 'food_products', 'products', 'mart_items'];

        foreach ($fallbackCollections as $collectionName) {
            try {
                \Log::info("Trying fallback collection: {$collectionName}");

                $query = $this->firestore->collection($collectionName)
                    ->where('publish', '==', true)
                    ->where('isAvailable', '==', true);

                if (!empty($searchTerm)) {
                    return $this->performBroadSearch($query, $searchTerm, $limit, $offset);
                } else {
                    return $this->performPaginatedQuery($query, $limit, $offset);
                }

            } catch (\Exception $e) {
                \Log::warning("Fallback collection '{$collectionName}' failed: " . $e->getMessage());
                continue;
            }
        }

        throw new \Exception('All food item collections failed');
    }

    /**
     * Format food item data for response
     *
     * @param array $itemData
     * @return array
     */
    private function formatFoodItemData(array $itemData): array
    {
        return [
            'id' => $itemData['id'] ?? '',
            'name' => $itemData['name'] ?? '',
            'description' => $itemData['description'] ?? '',
            'price' => $itemData['price'] ?? 0,
            'disPrice' => $itemData['disPrice'] ?? 0,
            'photo' => $itemData['photo'] ?? '',
            'photos' => $itemData['photos'] ?? [],
            'categoryID' => $itemData['categoryID'] ?? '',
            'categoryTitle' => $itemData['categoryTitle'] ?? '',
            'subcategoryID' => $itemData['subcategoryID'] ?? '',
            'subcategoryTitle' => $itemData['subcategoryTitle'] ?? '',
            'vendorID' => $itemData['vendorID'] ?? '',
            'vendorTitle' => $itemData['vendorTitle'] ?? '',
            'section' => $itemData['section'] ?? '',
            'veg' => $itemData['veg'] ?? false,
            'nonveg' => $itemData['nonveg'] ?? false,
            'isAvailable' => $itemData['isAvailable'] ?? false,
            'isBestSeller' => $itemData['isBestSeller'] ?? false,
            'isFeature' => $itemData['isFeature'] ?? false,
            'isNew' => $itemData['isNew'] ?? false,
            'isTrending' => $itemData['isTrending'] ?? false,
            'isSpotlight' => $itemData['isSpotlight'] ?? false,
            'cuisine' => $itemData['cuisine'] ?? '',
            'quantity' => $itemData['quantity'] ?? -1,
            'calories' => $itemData['calories'] ?? 0,
            'proteins' => $itemData['proteins'] ?? 0,
            'fats' => $itemData['fats'] ?? 0,
            'grams' => $itemData['grams'] ?? 0,
            'has_options' => $itemData['has_options'] ?? false,
            'options_count' => $itemData['options_count'] ?? 0,
            'options_enabled' => $itemData['options_enabled'] ?? false,
            'options_toggle' => $itemData['options_toggle'] ?? false,
            'options' => $itemData['options'] ?? [],
            'addOnsTitle' => $itemData['addOnsTitle'] ?? [],
            'addOnsPrice' => $itemData['addOnsPrice'] ?? [],
            'reviewCount' => $itemData['reviewCount'] ?? '0',
            'reviewSum' => $itemData['reviewSum'] ?? '0',
            'takeawayOption' => $itemData['takeawayOption'] ?? false,
            'created_at' => $itemData['created_at'] ?? null,
            'updated_at' => $itemData['updated_at'] ?? null
        ];
    }

    /**
     * Sort food items by relevance
     *
     * @param array $a
     * @param array $b
     * @return int
     */
    private function sortFoodItems(array $a, array $b): int
    {
        // Priority: featured > best seller > trending > new > available
        $scoreA = $this->calculateFoodItemScore($a);
        $scoreB = $this->calculateFoodItemScore($b);

        if ($scoreA !== $scoreB) {
            return $scoreB - $scoreA; // Higher score first
        }

        // Secondary sort by price (ascending)
        $priceA = $a['price'] ?? 0;
        $priceB = $b['price'] ?? 0;
        return $priceA <=> $priceB;
    }

    /**
     * Calculate relevance score for food items
     *
     * @param array $item
     * @return int
     */
    private function calculateFoodItemScore(array $item): int
    {
        $score = 0;

        // Boost featured items
        if ($item['isFeature'] ?? false) $score += 100;
        if ($item['isBestSeller'] ?? false) $score += 80;
        if ($item['isTrending'] ?? false) $score += 60;
        if ($item['isSpotlight'] ?? false) $score += 40;
        if ($item['isNew'] ?? false) $score += 20;

        // Boost available items
        if ($item['isAvailable'] ?? false) $score += 10;

        // Boost items with discounts
        $price = $item['price'] ?? 0;
        $disPrice = $item['disPrice'] ?? 0;
        if ($disPrice > 0 && $disPrice < $price) {
            $score += 30;
        }

        return $score;
    }

    /**
     * Get fallback food search result when Firestore fails
     *
     * @param string $searchTerm
     * @param int $limit
     * @param int $offset
     * @return array
     */
    private function getFallbackFoodSearchResult(string $searchTerm, int $limit, int $offset): array
    {
        $fallbackData = $this->getStaticFoodFallbackData();

        if (!empty($searchTerm)) {
            $searchTerm = strtolower(trim($searchTerm));
            $filtered = array_filter($fallbackData, function($item) use ($searchTerm) {
                $name = strtolower($item['name'] ?? '');
                $description = strtolower($item['description'] ?? '');
                $category = strtolower($item['categoryTitle'] ?? '');
                $cuisine = strtolower($item['cuisine'] ?? '');
                $vendorTitle = strtolower($item['vendorTitle'] ?? '');
                $restaurantName = strtolower($item['restaurantName'] ?? '');
                $vendorName = strtolower($item['vendorName'] ?? '');
                return strpos($name, $searchTerm) !== false ||
                       strpos($description, $searchTerm) !== false ||
                       strpos($category, $searchTerm) !== false ||
                       strpos($cuisine, $searchTerm) !== false ||
                       strpos($vendorTitle, $searchTerm) !== false ||
                       strpos($restaurantName, $searchTerm) !== false ||
                       strpos($vendorName, $searchTerm) !== false;
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

        $totalCount = count($fallbackData);
        $paginatedResults = array_slice($fallbackData, $offset, $limit);

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
     * Get static fallback data for food items
     *
     * @return array
     */
    private function getStaticFoodFallbackData(): array
    {
        return [
            [
                'id' => 'fallback_food_1',
                'name' => 'Chicken Biryani',
                'description' => 'Aromatic basmati rice with tender chicken pieces',
                'price' => 250,
                'disPrice' => 220,
                'photo' => '/img/food1.jpg',
                'photos' => ['/img/food1.jpg'],
                'categoryID' => 'food_main_course',
                'categoryTitle' => 'Main Course',
                'subcategoryID' => 'food_biryani',
                'subcategoryTitle' => 'Biryani',
                'vendorID' => 'spice_kitchen',
                'vendorTitle' => 'Spice Kitchen',
                'restaurantName' => 'Spice Kitchen',
                'vendorName' => 'Spice Kitchen',
                'section' => 'Indian Cuisine',
                'veg' => false,
                'nonveg' => true,
                'isAvailable' => true,
                'isBestSeller' => true,
                'isFeature' => true,
                'isNew' => false,
                'isTrending' => true,
                'isSpotlight' => false,
                'cuisine' => 'Indian',
                'quantity' => 5,
                'calories' => 450,
                'proteins' => 25,
                'fats' => 15,
                'grams' => 300,
                'has_options' => true,
                'options_count' => 3,
                'options_enabled' => true,
                'options_toggle' => true,
                'options' => [
                    ['title' => 'Extra Spicy', 'price' => 20],
                    ['title' => 'Mild', 'price' => 0],
                    ['title' => 'Extra Raita', 'price' => 15]
                ],
                'addOnsTitle' => ['Extra Raita', 'Pickle'],
                'addOnsPrice' => [15, 10],
                'reviewCount' => '25',
                'reviewSum' => '4.2',
                'takeawayOption' => true,
                'created_at' => '2024-01-01T00:00:00Z',
                'updated_at' => '2024-01-01T00:00:00Z'
            ],
            [
                'id' => 'fallback_food_2',
                'name' => 'Margherita Pizza',
                'description' => 'Classic pizza with tomato sauce, mozzarella, and basil',
                'price' => 180,
                'disPrice' => 160,
                'photo' => '/img/food2.jpg',
                'photos' => ['/img/food2.jpg'],
                'categoryID' => 'food_italian',
                'categoryTitle' => 'Italian',
                'subcategoryID' => 'food_pizza',
                'subcategoryTitle' => 'Pizza',
                'vendorID' => 'pizza_corner',
                'vendorTitle' => 'Pizza Corner',
                'restaurantName' => 'Pizza Corner',
                'vendorName' => 'Pizza Corner',
                'section' => 'Italian Cuisine',
                'veg' => true,
                'nonveg' => false,
                'isAvailable' => true,
                'isBestSeller' => false,
                'isFeature' => true,
                'isNew' => false,
                'isTrending' => false,
                'isSpotlight' => true,
                'cuisine' => 'Italian',
                'quantity' => 8,
                'calories' => 320,
                'proteins' => 18,
                'fats' => 12,
                'grams' => 250,
                'has_options' => true,
                'options_count' => 2,
                'options_enabled' => true,
                'options_toggle' => true,
                'options' => [
                    ['title' => 'Extra Cheese', 'price' => 25],
                    ['title' => 'Thin Crust', 'price' => 0]
                ],
                'addOnsTitle' => ['Extra Cheese', 'Olives'],
                'addOnsPrice' => [25, 20],
                'reviewCount' => '18',
                'reviewSum' => '4.5',
                'takeawayOption' => true,
                'created_at' => '2024-01-01T00:00:00Z',
                'updated_at' => '2024-01-01T00:00:00Z'
            ],
            [
                'id' => 'fallback_food_3',
                'name' => 'Chicken Burger',
                'description' => 'Juicy chicken patty with fresh vegetables and sauce',
                'price' => 120,
                'disPrice' => 100,
                'photo' => '/img/food3.jpg',
                'photos' => ['/img/food3.jpg'],
                'categoryID' => 'food_fast_food',
                'categoryTitle' => 'Fast Food',
                'subcategoryID' => 'food_burgers',
                'subcategoryTitle' => 'Burgers',
                'vendorID' => 'burger_house',
                'vendorTitle' => 'Burger House',
                'restaurantName' => 'Burger House',
                'vendorName' => 'Burger House',
                'section' => 'American Cuisine',
                'veg' => false,
                'nonveg' => true,
                'isAvailable' => true,
                'isBestSeller' => true,
                'isFeature' => false,
                'isNew' => true,
                'isTrending' => true,
                'isSpotlight' => false,
                'cuisine' => 'American',
                'quantity' => 12,
                'calories' => 380,
                'proteins' => 22,
                'fats' => 18,
                'grams' => 200,
                'has_options' => true,
                'options_count' => 4,
                'options_enabled' => true,
                'options_toggle' => true,
                'options' => [
                    ['title' => 'Extra Patty', 'price' => 50],
                    ['title' => 'Bacon', 'price' => 30],
                    ['title' => 'Extra Cheese', 'price' => 20],
                    ['title' => 'No Pickles', 'price' => 0]
                ],
                'addOnsTitle' => ['Extra Patty', 'Bacon', 'Extra Cheese'],
                'addOnsPrice' => [50, 30, 20],
                'reviewCount' => '32',
                'reviewSum' => '4.3',
                'takeawayOption' => true,
                'created_at' => '2024-01-01T00:00:00Z',
                'updated_at' => '2024-01-01T00:00:00Z'
            ]
        ];
    }

    /**
     * Find vendor IDs that match the search term (restaurant names) - OPTIMIZED
     *
     * @param string $searchTerm
     * @return array
     */
    private function findMatchingVendorIds(string $searchTerm): array
    {
        $cacheKey = 'vendor_search_' . md5($searchTerm);

         // Try to get from cache first
         $cachedResult = $this->cacheService->get($cacheKey, function() use ($searchTerm) {
             return $this->performVendorSearch($searchTerm);
         }, 300);

         \Log::info("Vendor search completed", [
             'search_term' => $searchTerm,
             'matching_vendors_count' => count($cachedResult),
             'vendor_ids' => $cachedResult
         ]);

         return $cachedResult;
    }

    /**
     * Perform the actual vendor search (used by cache callback)
     *
     * @param string $searchTerm
     * @return array
     */
    private function performVendorSearch(string $searchTerm): array
    {
        $matchingVendorIds = [];

        try {
            if (!$this->firestore) {
                return $matchingVendorIds;
            }

            \Log::info('Searching vendors collection', [
                'search_term' => $searchTerm,
                'collection' => 'vendors'
            ]);

            // Strategy 1: Try exact prefix matching first (fastest)
            $vendorsQuery = $this->firestore->collection('vendors')
                ->where('title', '>=', $searchTerm)
                ->where('title', '<=', $searchTerm . '\uf8ff')
                ->limit(50);

            $vendorDocuments = $vendorsQuery->documents();
            $processedVendors = [];

            foreach ($vendorDocuments as $document) {
                $vendorData = $document->data();
                $vendorTitle = strtolower($vendorData['title'] ?? '');

                // Check if vendor title contains the search term
                if (strpos($vendorTitle, $searchTerm) !== false) {
                    $matchingVendorIds[] = $document->id();
                    $processedVendors[] = [
                        'id' => $document->id(),
                        'title' => $vendorData['title'] ?? 'N/A'
                    ];
                    \Log::info('Found matching vendor (prefix search)', [
                        'vendor_id' => $document->id(),
                        'vendor_title' => $vendorData['title'] ?? 'N/A',
                        'search_term' => $searchTerm
                    ]);
                }
            }

            // Strategy 2: If no results from prefix search, try broader search
            if (empty($matchingVendorIds)) {
                \Log::info('No prefix matches found, trying broader vendor search');

                // Get more vendors and filter in PHP
                $broaderQuery = $this->firestore->collection('vendors')->limit(100);
                $broaderDocuments = $broaderQuery->documents();

                foreach ($broaderDocuments as $document) {
                    $vendorData = $document->data();
                    $vendorId = $document->id();
                    $processedVendors[] = $vendorId;

                    // Check various vendor name fields
                    $title = strtolower($vendorData['title'] ?? '');
                    $name = strtolower($vendorData['name'] ?? '');
                    $restaurantName = strtolower($vendorData['restaurantName'] ?? '');
                    $businessName = strtolower($vendorData['businessName'] ?? '');
                    $displayName = strtolower($vendorData['displayName'] ?? '');

                    $searchTermLower = strtolower($searchTerm);

                    // Check if search term matches any vendor name field
                    if (strpos($title, $searchTermLower) !== false ||
                        strpos($name, $searchTermLower) !== false ||
                        strpos($restaurantName, $searchTermLower) !== false ||
                        strpos($businessName, $searchTermLower) !== false ||
                        strpos($displayName, $searchTermLower) !== false) {

                        $matchingVendorIds[] = $vendorId;
                        \Log::info("Found matching vendor (broader search)", [
                            'vendor_id' => $vendorId,
                            'title' => $vendorData['title'] ?? 'N/A',
                            'search_term' => $searchTerm
                        ]);
                    }
                }
            }

            // If no matches found with native query, try broader search
            if (empty($matchingVendorIds)) {
                $matchingVendorIds = $this->performBroaderVendorSearch($searchTerm, $processedVendors);
            }

            \Log::info("Vendor search results", [
                'search_term' => $searchTerm,
                'matching_vendor_ids' => $matchingVendorIds,
                'total_matches' => count($matchingVendorIds)
            ]);

        } catch (\Exception $e) {
            \Log::error('Error searching vendors: ' . $e->getMessage());
        }

        return $matchingVendorIds;
    }

    /**
     * Perform broader vendor search if native query fails
     */
    private function performBroaderVendorSearch(string $searchTerm, array $excludeIds = []): array
    {
        $matchingVendorIds = [];

        try {
            // Get all vendors and filter in PHP (fallback)
            $vendorsQuery = $this->firestore->collection('vendors')->limit(200);
            $vendorDocuments = $vendorsQuery->documents();

            foreach ($vendorDocuments as $document) {
                $vendorId = $document->id();

                // Skip already processed vendors
                if (in_array($vendorId, $excludeIds)) {
                    continue;
                }

                $vendorData = $document->data();

                // Check various vendor name fields
                $title = strtolower($vendorData['title'] ?? '');
                $name = strtolower($vendorData['name'] ?? '');
                $restaurantName = strtolower($vendorData['restaurantName'] ?? '');
                $businessName = strtolower($vendorData['businessName'] ?? '');
                $displayName = strtolower($vendorData['displayName'] ?? '');

                // Check if search term matches any vendor name field
                if (strpos($title, $searchTerm) !== false ||
                    strpos($name, $searchTerm) !== false ||
                    strpos($restaurantName, $searchTerm) !== false ||
                    strpos($businessName, $searchTerm) !== false ||
                    strpos($displayName, $searchTerm) !== false) {

                    $matchingVendorIds[] = $vendorId;
                }
            }

        } catch (\Exception $e) {
            \Log::error('Broader vendor search failed: ' . $e->getMessage());
        }

        return $matchingVendorIds;
    }

    /**
     * Perform direct food item search in vendor_products collection
     *
     * @param string $searchTerm
     * @param int $limit
     * @param int $offset
     * @return array
     */
    private function performDirectFoodItemSearch(string $searchTerm, int $limit, int $offset): array
    {
        try {
            \Log::info('Starting direct food item search', [
                'search_term' => $searchTerm,
                'limit' => $limit,
                'offset' => $offset
            ]);

            // Use a simpler, faster approach - just do a broad search with PHP filtering
            // This avoids complex Firestore queries that can timeout
            return $this->performSimpleFoodSearch($searchTerm, $limit, $offset);

        } catch (\Exception $e) {
            \Log::error('Direct food item search failed: ' . $e->getMessage());
            return ['data' => [], 'total' => 0, 'has_more' => false, 'current_page' => 1, 'per_page' => $limit];
        }
    }

    /**
     * Search food items by specific field using Firestore's string matching
     */
    private function searchFoodByField($query, string $field, string $searchTerm, int $limit, int $offset): array
    {
        try {
            $searchTermLower = strtolower(trim($searchTerm));

            // Use Firestore's string range queries for better performance
            $fieldQuery = clone $query;
            $fieldQuery = $fieldQuery->where($field, '>=', $searchTermLower)
                                   ->where($field, '<=', $searchTermLower . '\uf8ff')
                                   ->limit($limit + $offset);

            $documents = $fieldQuery->documents();
            $items = [];

            foreach ($documents as $document) {
                $itemData = $document->data();
                $itemData['id'] = $document->id();
                $items[] = $this->formatFoodItemData($itemData);
            }

            // Apply offset
            $totalCount = count($items);
            $paginatedItems = array_slice($items, $offset, $limit);

            \Log::info("Search by field {$field} completed", [
                'field' => $field,
                'search_term' => $searchTermLower,
                'total_found' => $totalCount,
                'returned' => count($paginatedItems)
            ]);

            return [
                'data' => $paginatedItems,
                'total' => $totalCount,
                'has_more' => ($offset + $limit) < $totalCount,
                'current_page' => floor($offset / $limit) + 1,
                'per_page' => $limit
            ];

        } catch (\Exception $e) {
            \Log::error("Search by field {$field} failed: " . $e->getMessage());
            return ['data' => [], 'total' => 0, 'has_more' => false, 'current_page' => 1, 'per_page' => $limit];
        }
    }

    /**
     * Perform broad food item search with PHP filtering
     */
    private function performBroadFoodItemSearch($query, string $searchTerm, int $limit, int $offset): array
    {
        try {
            // Fetch a reasonable number of documents for filtering
            $maxFetch = min(500, $limit * 20); // Fetch max 500 or 20x limit
            $documents = $query->limit($maxFetch)->documents();

            $filteredItems = [];
            $searchTermLower = strtolower(trim($searchTerm));

            foreach ($documents as $document) {
                $itemData = $document->data();
                $itemData['id'] = $document->id();

                // Check if search term matches any relevant field
                $name = strtolower($itemData['name'] ?? '');
                $description = strtolower($itemData['description'] ?? '');
                $category = strtolower($itemData['categoryTitle'] ?? '');
                $cuisine = strtolower($itemData['cuisine'] ?? '');
                $tags = $itemData['tags'] ?? [];

                // Check if search term is in any of these fields
                if (strpos($name, $searchTermLower) !== false ||
                    strpos($description, $searchTermLower) !== false ||
                    strpos($category, $searchTermLower) !== false ||
                    strpos($cuisine, $searchTermLower) !== false ||
                    (is_array($tags) && in_array($searchTermLower, array_map('strtolower', $tags)))) {

                    $filteredItems[] = $this->formatFoodItemData($itemData);
                }
            }

            // Sort by relevance (exact matches first, then partial matches)
            usort($filteredItems, function($a, $b) use ($searchTermLower) {
                $aName = strtolower($a['name'] ?? '');
                $bName = strtolower($b['name'] ?? '');

                $aExact = strpos($aName, $searchTermLower) === 0 ? 0 : 1;
                $bExact = strpos($bName, $searchTermLower) === 0 ? 0 : 1;

                if ($aExact !== $bExact) {
                    return $aExact - $bExact;
                }

                return strcmp($aName, $bName);
            });

            $totalCount = count($filteredItems);
            $paginatedItems = array_slice($filteredItems, $offset, $limit);

            \Log::info('Broad food item search completed', [
                'total_found' => $totalCount,
                'returned' => count($paginatedItems),
                'search_term' => $searchTermLower
            ]);

            return [
                'data' => $paginatedItems,
                'total' => $totalCount,
                'has_more' => ($offset + $limit) < $totalCount,
                'current_page' => floor($offset / $limit) + 1,
                'per_page' => $limit
            ];

        } catch (\Exception $e) {
            \Log::error('Broad food item search failed: ' . $e->getMessage());
            return ['data' => [], 'total' => 0, 'has_more' => false, 'current_page' => 1, 'per_page' => $limit];
        }
    }

    /**
     * Perform simple and fast food search to avoid timeout issues
     */
    private function performSimpleFoodSearch(string $searchTerm, int $limit, int $offset): array
    {
        try {
            \Log::info('Starting simple food search', [
                'search_term' => $searchTerm,
                'limit' => $limit,
                'offset' => $offset
            ]);

            // Set a reasonable limit to prevent timeout
            $maxFetch = min(200, $limit * 10); // Fetch max 200 documents

            // Simple query without complex filtering
            $query = $this->firestore->collection('vendor_products')
                ->where('publish', '==', true)
                ->where('isAvailable', '==', true)
                ->limit($maxFetch);

            $documents = $query->documents();
            $filteredItems = [];
            $searchTermLower = strtolower(trim($searchTerm));

            foreach ($documents as $document) {
                $itemData = $document->data();
                $itemData['id'] = $document->id();

                // Simple field matching
                $name = strtolower($itemData['name'] ?? '');
                $description = strtolower($itemData['description'] ?? '');
                $category = strtolower($itemData['categoryTitle'] ?? '');

                // Check if search term matches any field
                if (strpos($name, $searchTermLower) !== false ||
                    strpos($description, $searchTermLower) !== false ||
                    strpos($category, $searchTermLower) !== false) {

                    $filteredItems[] = $this->formatFoodItemData($itemData);
                }
            }

            // Simple sorting by name
            usort($filteredItems, function($a, $b) {
                return strcmp(strtolower($a['name'] ?? ''), strtolower($b['name'] ?? ''));
            });

            $totalCount = count($filteredItems);
            $paginatedItems = array_slice($filteredItems, $offset, $limit);

            \Log::info('Simple food search completed', [
                'total_found' => $totalCount,
                'returned' => count($paginatedItems),
                'search_term' => $searchTermLower
            ]);

            return [
                'data' => $paginatedItems,
                'total' => $totalCount,
                'has_more' => ($offset + $limit) < $totalCount,
                'current_page' => floor($offset / $limit) + 1,
                'per_page' => $limit
            ];

        } catch (\Exception $e) {
            \Log::error('Simple food search failed: ' . $e->getMessage());
            return ['data' => [], 'total' => 0, 'has_more' => false, 'current_page' => 1, 'per_page' => $limit];
        }
    }
}
