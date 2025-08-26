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
                $categories[] = $categoryData;
            }

            return $categories;
        } catch (\Exception $e) {
            \Log::error('Error fetching mart categories: ' . $e->getMessage());
            return [];
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
}
