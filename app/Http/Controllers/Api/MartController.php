<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

class MartController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Get user profile
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserProfile(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $userData = $this->firebaseService->getUserData($user->id);

            if (!$userData) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $userData
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get user profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user profile
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'sometimes|string|max:50',
            'lastName' => 'sometimes|string|max:50',
            'phoneNumber' => 'sometimes|string|max:15',
            'profilePictureURL' => 'sometimes|string|url',
            'countryCode' => 'sometimes|string|max:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $updateData = $request->only([
                'firstName', 'lastName', 'phoneNumber', 
                'profilePictureURL', 'countryCode'
            ]);

            $updateData['updatedAt'] = now()->toISOString();

            $success = $this->firebaseService->updateUserData($user->id, $updateData);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update user profile'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vendor details
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVendorDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $vendorData = $this->firebaseService->getVendorData($request->vendor_id);

            if (!$vendorData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $vendorData
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get vendor details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get nearby vendors
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNearbyVendors(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'sometimes|numeric|min:0.1|max:50', // in kilometers
            'limit' => 'sometimes|integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $radius = $request->radius ?? 10; // default 10km
            $limit = $request->limit ?? 20;

            $vendors = $this->firebaseService->getNearbyVendors(
                $latitude, 
                $longitude, 
                $radius, 
                $limit
            );

            return response()->json([
                'success' => true,
                'data' => $vendors,
                'meta' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'radius' => $radius,
                    'count' => count($vendors)
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get nearby vendors: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get mart categories
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMartCategories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'sometimes|string',
            'publish' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $filters = [];
            if ($request->has('vendor_id')) {
                $filters['vendor_id'] = $request->vendor_id;
            }
            if ($request->has('publish')) {
                $filters['publish'] = $request->publish;
            }

            $categories = $this->firebaseService->getMartCategories($filters);

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get categories: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get mart items
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMartItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'sometimes|string',
            'category_id' => 'sometimes|string',
            'is_available' => 'sometimes|boolean',
            'publish' => 'sometimes|boolean',
            'search' => 'sometimes|string|max:100',
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $filters = [];
            if ($request->has('vendor_id')) {
                $filters['vendor_id'] = $request->vendor_id;
            }
            if ($request->has('category_id')) {
                $filters['category_id'] = $request->category_id;
            }
            if ($request->has('is_available')) {
                $filters['is_available'] = $request->is_available;
            }
            if ($request->has('publish')) {
                $filters['publish'] = $request->publish;
            }

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;
            $search = $request->search ?? null;

            $items = $this->firebaseService->getMartItems($filters, $search, $page, $limit);

            return response()->json([
                'success' => true,
                'data' => $items['data'],
                'meta' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $items['total'],
                    'has_more' => $items['has_more']
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get item details
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getItemDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $itemData = $this->firebaseService->getMartItem($request->item_id);

            if (!$itemData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $itemData
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get item details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search items
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2|max:100',
            'vendor_id' => 'sometimes|string',
            'category_id' => 'sometimes|string',
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $filters = [];
            if ($request->has('vendor_id')) {
                $filters['vendor_id'] = $request->vendor_id;
            }
            if ($request->has('category_id')) {
                $filters['category_id'] = $request->category_id;
            }

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;

            $results = $this->firebaseService->searchMartItems(
                $request->query, 
                $filters, 
                $page, 
                $limit
            );

            return response()->json([
                'success' => true,
                'data' => $results['data'],
                'meta' => [
                    'query' => $request->query,
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $results['total'],
                    'has_more' => $results['has_more']
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vendor items by category
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVendorItemsByCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|string',
            'category_id' => 'required|string',
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;

            $items = $this->firebaseService->getVendorItemsByCategory(
                $request->vendor_id,
                $request->category_id,
                $page,
                $limit
            );

            return response()->json([
                'success' => true,
                'data' => $items['data'],
                'meta' => [
                    'vendor_id' => $request->vendor_id,
                    'category_id' => $request->category_id,
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $items['total'],
                    'has_more' => $items['has_more']
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get vendor items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vendor working hours
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVendorWorkingHours(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $vendorData = $this->firebaseService->getVendorData($request->vendor_id);

            if (!$vendorData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found'
                ], 404);
            }

            $workingHours = $vendorData['workingHours'] ?? [];
            $isOpen = $vendorData['isOpen'] ?? false;

            return response()->json([
                'success' => true,
                'data' => [
                    'is_open' => $isOpen,
                    'working_hours' => $workingHours
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get working hours: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vendor special discounts
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVendorSpecialDiscounts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $vendorData = $this->firebaseService->getVendorData($request->vendor_id);

            if (!$vendorData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found'
                ], 404);
            }

            $specialDiscounts = $vendorData['specialDiscount'] ?? [];
            $specialDiscountEnable = $vendorData['specialDiscountEnable'] ?? false;

            return response()->json([
                'success' => true,
                'data' => [
                    'enabled' => $specialDiscountEnable,
                    'discounts' => $specialDiscounts
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get special discounts: ' . $e->getMessage()
            ], 500);
        }
    }
}
