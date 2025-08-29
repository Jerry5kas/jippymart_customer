<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth as FirebaseAuth;
use Google\Cloud\Firestore\FirestoreClient;

class FirebaseService
{
    protected $auth;
    protected $firestore;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/firebase/credentials.json'));
        $this->auth = $factory->createAuth();
        $this->firestore = new FirestoreClient([
            'projectId' => env('FIREBASE_PROJECT_ID'),
            'keyFilePath' => storage_path('app/firebase/credentials.json')
        ]);
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
     * Get mart categories
     *
     * @param array $filters
     * @return array
     */
    public function getMartCategories(array $filters = [])
    {
        try {
            $query = $this->firestore->collection('mart_categories');

            // Apply filters
            if (isset($filters['publish'])) {
                $query = $query->where('publish', '==', $filters['publish']);
            }

            $documents = $query->documents();
            $categories = [];

            foreach ($documents as $document) {
                $categoryData = $document->data();
                $categoryData['id'] = $document->id();
                $categoryData = $this->ensureCategoryFieldStructure($categoryData);
                $categories[] = $categoryData;
            }

            return $categories;
        } catch (\Exception $e) {
            \Log::error('Error fetching mart categories: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get mart categories with pagination and enhanced filtering
     *
     * @param array $filters
     * @param string|null $search
     * @param int $page
     * @param int $limit
     * @param string $sortBy
     * @param string $sortOrder
     * @return array
     */
    public function getMartCategoriesWithPagination(array $filters = [], ?string $search = null, int $page = 1, int $limit = 20, string $sortBy = 'title', string $sortOrder = 'asc')
    {
        try {
            $query = $this->firestore->collection('mart_categories');

            // Apply filters
            if (isset($filters['publish'])) {
                $query = $query->where('publish', '==', $filters['publish']);
            }
            if (isset($filters['show_in_homepage'])) {
                $query = $query->where('show_in_homepage', '==', $filters['show_in_homepage']);
            }
            if (isset($filters['has_subcategories'])) {
                $query = $query->where('has_subcategories', '==', $filters['has_subcategories']);
            }

            // Apply search if provided
            if ($search) {
                $query = $query->where('title', '>=', $search)
                              ->where('title', '<=', $search . '\uf8ff');
            }

            // Apply sorting
            if ($sortBy === 'title') {
                $query = $query->orderBy('title', $sortOrder);
            } elseif ($sortBy === 'category_order') {
                $query = $query->orderBy('category_order', $sortOrder);
            } elseif ($sortBy === 'section_order') {
                $query = $query->orderBy('section_order', $sortOrder);
            }

            // Apply pagination
            $offset = ($page - 1) * $limit;
            $query = $query->limit($limit)->offset($offset);

            $documents = $query->documents();
            $categories = [];

            foreach ($documents as $document) {
                $categoryData = $document->data();
                $categoryData['id'] = $document->id();
                $categoryData = $this->ensureCategoryFieldStructure($categoryData);
                $categories[] = $categoryData;
            }

            // For simplicity, we'll assume there are more results if we got the full limit
            $hasMore = count($categories) === $limit;

            return [
                'data' => $categories,
                'total' => count($categories) + ($page - 1) * $limit,
                'has_more' => $hasMore
            ];
        } catch (\Exception $e) {
            \Log::error('Error fetching mart categories with pagination: ' . $e->getMessage());
            return [
                'data' => [],
                'total' => 0,
                'has_more' => false
            ];
        }
    }

    /**
     * Get specific mart category by ID
     *
     * @param string $categoryId
     * @return array|null
     */
    public function getMartCategory(string $categoryId)
    {
        try {
            $document = $this->firestore->collection('mart_categories')->document($categoryId)->snapshot();

            if ($document->exists()) {
                $categoryData = $document->data();
                $categoryData['id'] = $document->id();
                $categoryData = $this->ensureCategoryFieldStructure($categoryData);
                return $categoryData;
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Error fetching mart category: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a new mart category
     *
     * @param array $categoryData
     * @return string|false
     */
    public function createMartCategory(array $categoryData)
    {
        try {
            $document = $this->firestore->collection('mart_categories')->add($categoryData);
            return $document->id();
        } catch (\Exception $e) {
            \Log::error('Error creating mart category: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update an existing mart category
     *
     * @param string $categoryId
     * @param array $updateData
     * @return bool
     */
    public function updateMartCategory(string $categoryId, array $updateData)
    {
        try {
            $this->firestore->collection('mart_categories')->document($categoryId)->set($updateData, ['merge' => true]);
            return true;
        } catch (\Exception $e) {
            \Log::error('Error updating mart category: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a mart category
     *
     * @param string $categoryId
     * @return bool
     */
    public function deleteMartCategory(string $categoryId)
    {
        try {
            $this->firestore->collection('mart_categories')->document($categoryId)->delete();
            return true;
        } catch (\Exception $e) {
            \Log::error('Error deleting mart category: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Search mart categories
     *
     * @param string $query
     * @param array $filters
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function searchMartCategories(string $query, array $filters = [], int $page = 1, int $limit = 20)
    {
        try {
            $searchQuery = $this->firestore->collection('mart_categories');

            // Apply filters first
            if (isset($filters['publish'])) {
                $searchQuery = $searchQuery->where('publish', '==', $filters['publish']);
            }

            // Apply search
            $searchQuery = $searchQuery->where('title', '>=', $query)
                                     ->where('title', '<=', $query . '\uf8ff');

            // Apply pagination
            $offset = ($page - 1) * $limit;
            $searchQuery = $searchQuery->limit($limit)->offset($offset);

            $documents = $searchQuery->documents();
            $categories = [];

            foreach ($documents as $document) {
                $categoryData = $document->data();
                $categoryData['id'] = $document->id();
                $categoryData = $this->ensureCategoryFieldStructure($categoryData);
                $categories[] = $categoryData;
            }

            $hasMore = count($categories) === $limit;

            return [
                'data' => $categories,
                'total' => count($categories) + ($page - 1) * $limit,
                'has_more' => $hasMore
            ];
        } catch (\Exception $e) {
            \Log::error('Error searching mart categories: ' . $e->getMessage());
            return [
                'data' => [],
                'total' => 0,
                'has_more' => false
            ];
        }
    }

    /**
     * Bulk update mart categories
     *
     * @param array $categoryIds
     * @param array $updateData
     * @return array
     */
    public function bulkUpdateMartCategories(array $categoryIds, array $updateData)
    {
        $results = [
            'updated' => 0,
            'failed' => 0,
            'failed_ids' => []
        ];

        try {
            $batch = $this->firestore->batch();

            foreach ($categoryIds as $categoryId) {
                try {
                    $ref = $this->firestore->collection('mart_categories')->document($categoryId);
                    $batch->update($ref, $updateData);
                    $results['updated']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['failed_ids'][] = $categoryId;
                    \Log::error("Error updating category {$categoryId}: " . $e->getMessage());
                }
            }

            if ($results['updated'] > 0) {
                $batch->commit();
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Error in bulk update mart categories: ' . $e->getMessage());
            $results['failed'] = count($categoryIds);
            $results['failed_ids'] = $categoryIds;
            return $results;
        }
    }

    /**
     * Get mart items with pagination and filters
     *
     * @param array $filters
     * @param string|null $search
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getMartItems(array $filters = [], ?string $search = null, int $page = 1, int $limit = 20)
    {
        try {
            $query = $this->firestore->collection('mart_items');

            // Apply filters
            if (isset($filters['vendor_id'])) {
                $query = $query->where('vendorID', '==', $filters['vendor_id']);
            }
            if (isset($filters['category_id'])) {
                $query = $query->where('categoryID', '==', $filters['category_id']);
            }
            if (isset($filters['is_available'])) {
                $query = $query->where('isAvailable', '==', $filters['is_available']);
            }
            if (isset($filters['publish'])) {
                $query = $query->where('publish', '==', $filters['publish']);
            }

            // Apply search if provided
            if ($search) {
                $query = $query->where('name', '>=', $search)
                              ->where('name', '<=', $search . '\uf8ff');
            }

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
            \Log::error('Error fetching mart items: ' . $e->getMessage());
            return [
                'data' => [],
                'total' => 0,
                'has_more' => false
            ];
        }
    }

    /**
     * Get specific mart item
     *
     * @param string $itemId
     * @return array|null
     */
    public function getMartItem(string $itemId)
    {
        try {
            $document = $this->firestore->collection('mart_items')->document($itemId)->snapshot();

            if ($document->exists()) {
                $itemData = $document->data();
                $itemData['id'] = $document->id();
                return $itemData;
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Error fetching mart item: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Search mart items
     *
     * @param string $query
     * @param array $filters
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function searchMartItems(string $query, array $filters = [], int $page = 1, int $limit = 20)
    {
        try {
            $searchQuery = $this->firestore->collection('mart_items');

            // Apply filters first
            if (isset($filters['vendor_id'])) {
                $searchQuery = $searchQuery->where('vendorID', '==', $filters['vendor_id']);
            }
            if (isset($filters['category_id'])) {
                $searchQuery = $searchQuery->where('categoryID', '==', $filters['category_id']);
            }

            // Apply search
            $searchQuery = $searchQuery->where('name', '>=', $query)
                                     ->where('name', '<=', $query . '\uf8ff');

            // Apply pagination
            $offset = ($page - 1) * $limit;
            $searchQuery = $searchQuery->limit($limit)->offset($offset);

            $documents = $searchQuery->documents();
            $items = [];

            foreach ($documents as $document) {
                $itemData = $document->data();
                $itemData['id'] = $document->id();
                $items[] = $itemData;
            }

            $hasMore = count($items) === $limit;

            return [
                'data' => $items,
                'total' => count($items) + ($page - 1) * $limit,
                'has_more' => $hasMore
            ];
        } catch (\Exception $e) {
            \Log::error('Error searching mart items: ' . $e->getMessage());
            return [
                'data' => [],
                'total' => 0,
                'has_more' => false
            ];
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
            $query = $this->firestore->collection('mart_items')
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
     * Sanitize data to remove Inf and NaN values
     *
     * @param mixed $data
     * @return mixed
     */
    private function sanitizeData($data)
    {
        try {
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $data[$key] = $this->sanitizeData($value);
                }
                return $data;
            } elseif (is_float($data)) {
                // Check for infinite or NaN values
                if (is_infinite($data) || is_nan($data)) {
                    \Log::warning('Found infinite or NaN value in vendor data: ' . $data);
                    return null;
                }
                return $data;
            } elseif (is_numeric($data)) {
                // Convert to float and check
                $floatValue = (float) $data;
                if (is_infinite($floatValue) || is_nan($floatValue)) {
                    \Log::warning('Found infinite or NaN value in vendor data: ' . $data);
                    return null;
                }
                return $data;
            } elseif (is_object($data)) {
                // Handle Firestore objects
                if (method_exists($data, 'toArray')) {
                    return $this->sanitizeData($data->toArray());
                }
                // Convert to array if possible
                return $this->sanitizeData((array) $data);
            }
            
            return $data;
        } catch (\Exception $e) {
            \Log::error('Error sanitizing data: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Ensure consistent field structure for mart categories with default values
     *
     * @param array $categoryData
     * @return array
     */
    private function ensureCategoryFieldStructure(array $categoryData): array
    {
        return array_merge([
            'title' => '',
            'description' => '',
            'photo' => '',
            'publish' => true,
            'show_in_homepage' => false,
            'category_order' => 0,
            'section' => '',
            'section_order' => 0,
            'review_attributes' => []
        ], $categoryData);
    }

    /**
     * Get all mart vendors with filters and pagination
     *
     * @param array $filters
     * @param string|null $search
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getAllMartVendors(array $filters = [], ?string $search = null, int $page = 1, int $limit = 20)
    {
        try {
            $query = $this->firestore->collection('vendors');

            // Always filter by vType = 'mart'
            $query = $query->where('vType', '==', 'mart');

            // Apply additional filters
            if (isset($filters['isOpen'])) {
                $query = $query->where('isOpen', '==', $filters['isOpen']);
            }
            if (isset($filters['enabledDelivery'])) {
                $query = $query->where('enabledDelivery', '==', $filters['enabledDelivery']);
            }
            if (isset($filters['categoryID'])) {
                $query = $query->where('categoryID', 'array-contains', $filters['categoryID']);
            }

            // Apply search if provided
            if ($search) {
                $query = $query->where('title', '>=', $search)
                              ->where('title', '<=', $search . '\uf8ff');
            }

            // Apply pagination
            $offset = ($page - 1) * $limit;
            $query = $query->limit($limit)->offset($offset);

            $documents = $query->documents();
            $vendors = [];

            foreach ($documents as $document) {
                $vendorData = $document->data();
                $vendorData['id'] = $document->id();
                
                // Sanitize the data to remove Inf and NaN values
                $vendorData = $this->sanitizeData($vendorData);
                
                $vendors[] = $vendorData;
            }

            // For simplicity, we'll assume there are more results if we got the full limit
            $hasMore = count($vendors) === $limit;

            $result = [
                'data' => $vendors,
                'total' => count($vendors) + ($page - 1) * $limit,
                'has_more' => $hasMore
            ];

            // Validate that the result can be JSON encoded
            $jsonTest = json_encode($result);
            if ($jsonTest === false) {
                \Log::error('JSON encoding failed for vendor data: ' . json_last_error_msg());
                // Return a simplified version if JSON encoding fails
                return [
                    'data' => [],
                    'total' => 0,
                    'has_more' => false
                ];
            }

            return $result;
        } catch (\Exception $e) {
            \Log::error('Error fetching all mart vendors: ' . $e->getMessage());
            return [
                'data' => [],
                'total' => 0,
                'has_more' => false
            ];
        }
    }

    // ==================== MART SUBCATEGORIES METHODS ====================

    /**
     * Get mart subcategories with pagination and enhanced filtering
     *
     * @param array $filters
     * @param string|null $search
     * @param int $page
     * @param int $limit
     * @param string $sortBy
     * @param string $sortOrder
     * @return array
     */
    public function getMartSubCategoriesWithPagination(array $filters = [], ?string $search = null, int $page = 1, int $limit = 20, string $sortBy = 'title', string $sortOrder = 'asc')
    {
        try {
            $query = $this->firestore->collection('mart_subcategories');

            // Apply filters
            if (isset($filters['publish'])) {
                $query = $query->where('publish', '==', $filters['publish']);
            }
            if (isset($filters['show_in_homepage'])) {
                $query = $query->where('show_in_homepage', '==', $filters['show_in_homepage']);
            }
            if (isset($filters['parent_category_id'])) {
                $query = $query->where('parent_category_id', '==', $filters['parent_category_id']);
            }
            if (isset($filters['mart_id'])) {
                $query = $query->where('mart_id', '==', $filters['mart_id']);
            }

            // Apply search if provided
            if ($search) {
                $query = $query->where('title', '>=', $search)
                              ->where('title', '<=', $search . '\uf8ff');
            }

            // Apply sorting
            if ($sortBy === 'title') {
                $query = $query->orderBy('title', $sortOrder);
            } elseif ($sortBy === 'subcategory_order') {
                $query = $query->orderBy('subcategory_order', $sortOrder);
            } elseif ($sortBy === 'category_order') {
                $query = $query->orderBy('category_order', $sortOrder);
            } elseif ($sortBy === 'section_order') {
                $query = $query->orderBy('section_order', $sortOrder);
            }

            // Apply pagination
            $offset = ($page - 1) * $limit;
            $query = $query->limit($limit)->offset($offset);

            $documents = $query->documents();
            $subcategories = [];

            foreach ($documents as $document) {
                $subcategoryData = $document->data();
                $subcategoryData['id'] = $document->id();
                $subcategoryData = $this->ensureSubcategoryFieldStructure($subcategoryData);
                $subcategories[] = $subcategoryData;
            }

            // For simplicity, we'll assume there are more results if we got the full limit
            $hasMore = count($subcategories) === $limit;

            return [
                'data' => $subcategories,
                'total' => count($subcategories) + ($page - 1) * $limit,
                'has_more' => $hasMore
            ];
        } catch (\Exception $e) {
            \Log::error('Error fetching mart subcategories with pagination: ' . $e->getMessage());
            return [
                'data' => [],
                'total' => 0,
                'has_more' => false
            ];
        }
    }

    /**
     * Get specific mart subcategory by ID
     *
     * @param string $subcategoryId
     * @return array|null
     */
    public function getMartSubCategory(string $subcategoryId)
    {
        try {
            $document = $this->firestore->collection('mart_subcategories')->document($subcategoryId)->snapshot();

            if ($document->exists()) {
                $subcategoryData = $document->data();
                $subcategoryData['id'] = $document->id();
                $subcategoryData = $this->ensureSubcategoryFieldStructure($subcategoryData);
                return $subcategoryData;
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Error fetching mart subcategory: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a new mart subcategory
     *
     * @param array $subcategoryData
     * @return string|false
     */
    public function createMartSubCategory(array $subcategoryData)
    {
        try {
            $document = $this->firestore->collection('mart_subcategories')->add($subcategoryData);
            return $document->id();
        } catch (\Exception $e) {
            \Log::error('Error creating mart subcategory: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update an existing mart subcategory
     *
     * @param string $subcategoryId
     * @param array $updateData
     * @return bool
     */
    public function updateMartSubCategory(string $subcategoryId, array $updateData)
    {
        try {
            $this->firestore->collection('mart_subcategories')->document($subcategoryId)->set($updateData, ['merge' => true]);
            return true;
        } catch (\Exception $e) {
            \Log::error('Error updating mart subcategory: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a mart subcategory
     *
     * @param string $subcategoryId
     * @return bool
     */
    public function deleteMartSubCategory(string $subcategoryId)
    {
        try {
            $this->firestore->collection('mart_subcategories')->document($subcategoryId)->delete();
            return true;
        } catch (\Exception $e) {
            \Log::error('Error deleting mart subcategory: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Search mart subcategories
     *
     * @param string $query
     * @param array $filters
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function searchMartSubCategories(string $query, array $filters = [], int $page = 1, int $limit = 20)
    {
        try {
            $searchQuery = $this->firestore->collection('mart_subcategories');

            // Apply filters first
            if (isset($filters['publish'])) {
                $searchQuery = $searchQuery->where('publish', '==', $filters['publish']);
            }
            if (isset($filters['parent_category_id'])) {
                $searchQuery = $searchQuery->where('parent_category_id', '==', $filters['parent_category_id']);
            }

            // Apply search
            $searchQuery = $searchQuery->where('title', '>=', $query)
                                     ->where('title', '<=', $query . '\uf8ff');

            // Apply pagination
            $offset = ($page - 1) * $limit;
            $searchQuery = $searchQuery->limit($limit)->offset($offset);

            $documents = $searchQuery->documents();
            $subcategories = [];

            foreach ($documents as $document) {
                $subcategoryData = $document->data();
                $subcategoryData['id'] = $document->id();
                $subcategoryData = $this->ensureSubcategoryFieldStructure($subcategoryData);
                $subcategories[] = $subcategoryData;
            }

            $hasMore = count($subcategories) === $limit;

            return [
                'data' => $subcategories,
                'total' => count($subcategories) + ($page - 1) * $limit,
                'has_more' => $hasMore
            ];
        } catch (\Exception $e) {
            \Log::error('Error searching mart subcategories: ' . $e->getMessage());
            return [
                'data' => [],
                'total' => 0,
                'has_more' => false
            ];
        }
    }

    /**
     * Bulk update mart subcategories
     *
     * @param array $subcategoryIds
     * @param array $updateData
     * @return array
     */
    public function bulkUpdateMartSubCategories(array $subcategoryIds, array $updateData)
    {
        $results = [
            'updated' => 0,
            'failed' => 0,
            'failed_ids' => []
        ];

        try {
            $batch = $this->firestore->batch();

            foreach ($subcategoryIds as $subcategoryId) {
                try {
                    $documentRef = $this->firestore->collection('mart_subcategories')->document($subcategoryId);
                    $batch->update($documentRef, $updateData);
                    $results['updated']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['failed_ids'][] = $subcategoryId;
                    \Log::error("Error updating subcategory {$subcategoryId}: " . $e->getMessage());
                }
            }

            $batch->commit();

            return $results;
        } catch (\Exception $e) {
            \Log::error('Error in bulk update mart subcategories: ' . $e->getMessage());
            return $results;
        }
    }

    /**
     * Increment subcategories count for a parent category
     *
     * @param string $categoryId
     * @return bool
     */
    public function incrementSubcategoriesCount(string $categoryId)
    {
        try {
            $categoryRef = $this->firestore->collection('mart_categories')->document($categoryId);
            $categoryRef->update([
                ['path' => 'subcategories_count', 'value' => \Google\Cloud\Firestore\FieldValue::increment(1)]
            ]);
            return true;
        } catch (\Exception $e) {
            \Log::error('Error incrementing subcategories count: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Decrement subcategories count for a parent category
     *
     * @param string $categoryId
     * @return bool
     */
    public function decrementSubcategoriesCount(string $categoryId)
    {
        try {
            $categoryRef = $this->firestore->collection('mart_categories')->document($categoryId);
            $categoryRef->update([
                ['path' => 'subcategories_count', 'value' => \Google\Cloud\Firestore\FieldValue::increment(-1)]
            ]);
            return true;
        } catch (\Exception $e) {
            \Log::error('Error decrementing subcategories count: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ensure subcategory field structure
     *
     * @param array $subcategoryData
     * @return array
     */
    private function ensureSubcategoryFieldStructure(array $subcategoryData): array
    {
        return array_merge([
            'title' => '',
            'description' => '',
            'parent_category_id' => '',
            'parent_category_title' => '',
            'photo' => '',
            'publish' => true,
            'show_in_homepage' => false,
            'category_order' => 1,
            'subcategory_order' => 1,
            'section' => '',
            'section_order' => 1,
            'mart_id' => '',
            'review_attributes' => [],
            'migratedBy' => 'migrate:mart-subcategories'
        ], $subcategoryData);
    }

    /**
     * Get mart items with enhanced pagination and filters
     *
     * @param array $filters
     * @param string|null $search
     * @param int $page
     * @param int $limit
     * @param string $sortBy
     * @param string $sortOrder
     * @return array
     */
    public function getMartItemsWithPagination(array $filters = [], ?string $search = null, int $page = 1, int $limit = 20, string $sortBy = 'name', string $sortOrder = 'asc')
    {
        try {
            $query = $this->firestore->collection('mart_items');

            // Apply filters
            if (isset($filters['vendor_id'])) {
                $query = $query->where('vendorID', '==', $filters['vendor_id']);
            }
            if (isset($filters['category_id'])) {
                $query = $query->where('categoryID', '==', $filters['category_id']);
            }
            if (isset($filters['subcategory_id'])) {
                // Handle both single subcategory_id and array of subcategory_ids
                if (is_array($filters['subcategory_id'])) {
                    $query = $query->where('subcategoryID', 'array-contains-any', $filters['subcategory_id']);
                } else {
                    // Single subcategory_id - check if it exists in the array
                    $query = $query->where('subcategoryID', 'array-contains', $filters['subcategory_id']);
                }
            }
            if (isset($filters['is_available'])) {
                $query = $query->where('isAvailable', '==', $filters['is_available']);
            }
            if (isset($filters['publish'])) {
                $query = $query->where('publish', '==', $filters['publish']);
            }
            if (isset($filters['has_options'])) {
                $query = $query->where('has_options', '==', $filters['has_options']);
            }
            
            // Enhanced filter fields
            if (isset($filters['is_spotlight'])) {
                $query = $query->where('isSpotlight', '==', $filters['is_spotlight']);
            }
            if (isset($filters['is_steal_of_moment'])) {
                $query = $query->where('isStealOfMoment', '==', $filters['is_steal_of_moment']);
            }
            if (isset($filters['is_feature'])) {
                $query = $query->where('isFeature', '==', $filters['is_feature']);
            }
            if (isset($filters['is_trending'])) {
                $query = $query->where('isTrending', '==', $filters['is_trending']);
            }
            if (isset($filters['is_new'])) {
                $query = $query->where('isNew', '==', $filters['is_new']);
            }
            if (isset($filters['is_best_seller'])) {
                $query = $query->where('isBestSeller', '==', $filters['is_best_seller']);
            }
            if (isset($filters['is_seasonal'])) {
                $query = $query->where('isSeasonal', '==', $filters['is_seasonal']);
            }
            
            // Dietary filters
            if (isset($filters['veg'])) {
                $query = $query->where('veg', '==', $filters['veg']);
            }
            if (isset($filters['nonveg'])) {
                $query = $query->where('nonveg', '==', $filters['nonveg']);
            }
            if (isset($filters['takeaway_option'])) {
                $query = $query->where('takeawayOption', '==', $filters['takeaway_option']);
            }
            
            // Price filters
            if (isset($filters['min_price'])) {
                $query = $query->where('price', '>=', $filters['min_price']);
            }
            if (isset($filters['max_price'])) {
                $query = $query->where('price', '<=', $filters['max_price']);
            }

            // Apply search if provided
            if ($search) {
                $query = $query->where('name', '>=', $search)
                              ->where('name', '<=', $search . '\uf8ff');
            }

            // Apply sorting
            if ($sortBy === 'created_at') {
                $query = $query->orderBy('created_at', $sortOrder);
            } elseif ($sortBy === 'updated_at') {
                $query = $query->orderBy('updated_at', $sortOrder);
            } elseif ($sortBy === 'price') {
                $query = $query->orderBy('price', $sortOrder);
            } else {
                $query = $query->orderBy('name', $sortOrder);
            }

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
            \Log::error('Error fetching mart items with pagination: ' . $e->getMessage());
            return [
                'data' => [],
                'total' => 0,
                'has_more' => false
            ];
        }
    }

    /**
     * Search mart items with enhanced filters
     *
     * @param string $query
     * @param array $filters
     * @param int $page
     * @param int $limit
     * @param string $sortBy
     * @param string $sortOrder
     * @return array
     */
    public function searchMartItemsWithFilters(string $query, array $filters = [], int $page = 1, int $limit = 20, string $sortBy = 'name', string $sortOrder = 'asc')
    {
        try {
            $searchQuery = $this->firestore->collection('mart_items');

            // Apply filters first
            if (isset($filters['vendor_id'])) {
                $searchQuery = $searchQuery->where('vendorID', '==', $filters['vendor_id']);
            }
            if (isset($filters['category_id'])) {
                $searchQuery = $searchQuery->where('categoryID', '==', $filters['category_id']);
            }
            if (isset($filters['subcategory_id'])) {
                $searchQuery = $searchQuery->where('subcategoryID', 'array-contains-any', $filters['subcategory_id']);
            }
            if (isset($filters['is_available'])) {
                $searchQuery = $searchQuery->where('isAvailable', '==', $filters['is_available']);
            }
            if (isset($filters['publish'])) {
                $searchQuery = $searchQuery->where('publish', '==', $filters['publish']);
            }
            if (isset($filters['has_options'])) {
                $searchQuery = $searchQuery->where('has_options', '==', $filters['has_options']);
            }
            if (isset($filters['min_price'])) {
                $searchQuery = $searchQuery->where('price', '>=', $filters['min_price']);
            }
            if (isset($filters['max_price'])) {
                $searchQuery = $searchQuery->where('price', '<=', $filters['max_price']);
            }

            // Apply search
            $searchQuery = $searchQuery->where('name', '>=', $query)
                                     ->where('name', '<=', $query . '\uf8ff');

            // Apply sorting
            if ($sortBy === 'created_at') {
                $searchQuery = $searchQuery->orderBy('created_at', $sortOrder);
            } elseif ($sortBy === 'updated_at') {
                $searchQuery = $searchQuery->orderBy('updated_at', $sortOrder);
            } elseif ($sortBy === 'price') {
                $searchQuery = $searchQuery->orderBy('price', $sortOrder);
            } else {
                $searchQuery = $searchQuery->orderBy('name', $sortOrder);
            }

            // Apply pagination
            $offset = ($page - 1) * $limit;
            $searchQuery = $searchQuery->limit($limit)->offset($offset);

            $documents = $searchQuery->documents();
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
            \Log::error('Error searching mart items with filters: ' . $e->getMessage());
            return [
                'data' => [],
                'total' => 0,
                'has_more' => false
            ];
        }
    }

    /**
     * Create a new mart item
     *
     * @param array $itemData
     * @return string|false
     */
    public function createMartItem(array $itemData)
    {
        try {
            $itemData = $this->ensureMartItemFieldStructure($itemData);
            
            $documentRef = $this->firestore->collection('mart_items')->add($itemData);
            
            return $documentRef->id();
        } catch (\Exception $e) {
            \Log::error('Error creating mart item: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update a mart item
     *
     * @param string $itemId
     * @param array $updateData
     * @return bool
     */
    public function updateMartItem(string $itemId, array $updateData)
    {
        try {
            $itemRef = $this->firestore->collection('mart_items')->document($itemId);
            $itemRef->update($updateData);
            return true;
        } catch (\Exception $e) {
            \Log::error('Error updating mart item: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a mart item
     *
     * @param string $itemId
     * @return bool
     */
    public function deleteMartItem(string $itemId)
    {
        try {
            $this->firestore->collection('mart_items')->document($itemId)->delete();
            return true;
        } catch (\Exception $e) {
            \Log::error('Error deleting mart item: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Bulk update mart items
     *
     * @param array $itemIds
     * @param array $updates
     * @param string $userId
     * @return bool
     */
    public function bulkUpdateMartItems(array $itemIds, array $updates, string $userId)
    {
        try {
            $batch = $this->firestore->batch();
            
            // Add updated_by and updated_at to updates
            $updates['updated_by'] = $userId;
            $updates['updated_at'] = now()->toISOString();
            
            foreach ($itemIds as $itemId) {
                $itemRef = $this->firestore->collection('mart_items')->document($itemId);
                $batch->update($itemRef, $updates);
            }
            
            $batch->commit();
            return true;
        } catch (\Exception $e) {
            \Log::error('Error bulk updating mart items: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ensure mart item field structure
     *
     * @param array $itemData
     * @return array
     */
    private function ensureMartItemFieldStructure(array $itemData): array
    {
        return array_merge([
            'name' => '',
            'description' => '',
            'price' => 0,
            'disPrice' => 0,
            'vendorID' => '',
            'categoryID' => '',
            'subcategoryID' => [],
            'photo' => '',
            'photos' => [],
            'publish' => true,
            'isAvailable' => true,
            'veg' => true,
            'nonveg' => false,
            'takeawayOption' => false,
            
            // Enhanced filter fields
            'isSpotlight' => false,
            'isStealOfMoment' => false,
            'isFeature' => false,
            'isTrending' => false,
            'isNew' => false,
            'isBestSeller' => false,
            'isSeasonal' => false,
            
            // Options configuration
            'has_options' => false,
            'options_enabled' => false,
            'options_toggle' => false,
            'options_count' => 0,
            'options' => [],
            'min_price' => 0,
            'max_price' => 0,
            'price_range' => '',
            'default_option_id' => '',
            'best_value_option' => '',
            'savings_percentage' => 0,
            
            // Nutrition fields
            'calories' => 0,
            'grams' => 0,
            'proteins' => 0,
            'fats' => 0,
            
            // Additional fields
            'quantity' => -1,
            'addOnsTitle' => [],
            'addOnsPrice' => [],
            'product_specification' => [],
            'item_attribute' => null,
            
            // Timestamps
            'created_at' => now()->toISOString(),
            'updated_at' => now()->toISOString(),
            'created_by' => '',
            'updated_by' => ''
        ], $itemData);
    }
}
