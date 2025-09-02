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
