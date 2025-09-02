<?php

namespace App\Http\Controllers\Api\Mart;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

class MartVendorController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Get all mart vendors with filtering and pagination
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'publish' => 'sometimes|boolean',
            'is_open' => 'sometimes|boolean',
            'enabled_delivery' => 'sometimes|boolean',
            'category_id' => 'sometimes|string',
            'zone_id' => 'sometimes|string',
            'search' => 'sometimes|string|max:100',
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:100',
            'sort_by' => 'sometimes|string|in:title,createdAt,restaurantCost',
            'sort_order' => 'sometimes|string|in:asc,desc'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $filters = [
                'vType' => 'mart' // Only get mart vendors
            ];

            // Apply filters
            if ($request->has('publish')) {
                $filters['publish'] = $request->publish;
            }
            if ($request->has('is_open')) {
                $filters['isOpen'] = $request->is_open;
            }
            if ($request->has('enabled_delivery')) {
                $filters['enabledDelivery'] = $request->enabled_delivery;
            }
            if ($request->has('category_id')) {
                $filters['categoryID'] = $request->category_id;
            }
            if ($request->has('zone_id')) {
                $filters['zoneId'] = $request->zone_id;
            }

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;
            $search = $request->search ?? null;
            $sortBy = $request->sort_by ?? 'title';
            $sortOrder = $request->sort_order ?? 'asc';

            $vendors = $this->firebaseService->getAllMartVendors(
                $filters,
                $search,
                $page,
                $limit
            );

            // If no results and it might be due to missing index, try fallback approach
            if (empty($vendors['data']) && $vendors['total'] === 0) {
                \Log::warning('No mart vendors found with filters, trying fallback approach');
                
                // Simple fallback: Get all vendors without filters and filter in PHP
                $allVendors = $this->firebaseService->getVendorsByTypeSimple('mart', 50);

                // Filter vendors in PHP based on requested filters
                $filteredVendors = array_filter($allVendors['data'], function($vendor) use ($request) {
                    // Check publish filter
                    if ($request->has('publish')) {
                        if (!isset($vendor['publish']) || $vendor['publish'] !== $request->publish) {
                            return false;
                        }
                    }

                    // Check is_open filter
                    if ($request->has('is_open')) {
                        if (!isset($vendor['isOpen']) || $vendor['isOpen'] !== $request->is_open) {
                            return false;
                        }
                    }

                    // Check enabled_delivery filter
                    if ($request->has('enabled_delivery')) {
                        if (!isset($vendor['enabledDelivery']) || $vendor['enabledDelivery'] !== $request->enabled_delivery) {
                            return false;
                        }
                    }

                    // Check category_id filter
                    if ($request->has('category_id')) {
                        if (!isset($vendor['categoryID']) || !in_array($request->category_id, $vendor['categoryID'])) {
                            return false;
                        }
                    }

                    // Check zone_id filter
                    if ($request->has('zone_id')) {
                        if (!isset($vendor['zoneId']) || $vendor['zoneId'] !== $request->zone_id) {
                            return false;
                        }
                    }

                    return true;
                });

                // Apply search filter if provided
                if ($search) {
                    $searchLower = strtolower($search);
                    $filteredVendors = array_filter($filteredVendors, function($vendor) use ($searchLower) {
                        $title = strtolower($vendor['title'] ?? '');
                        $description = strtolower($vendor['description'] ?? '');
                        $location = strtolower($vendor['location'] ?? '');
                        
                        return strpos($title, $searchLower) !== false ||
                               strpos($description, $searchLower) !== false ||
                               strpos($location, $searchLower) !== false;
                    });
                }

                // Apply pagination
                $offset = ($page - 1) * $limit;
                $paginatedVendors = array_slice($filteredVendors, $offset, $limit);

                $vendors = [
                    'data' => array_values($paginatedVendors),
                    'total' => count($filteredVendors),
                    'has_more' => count($filteredVendors) > ($offset + $limit)
                ];

                // Add note about fallback usage
                $vendors['note'] = 'Using fallback query due to missing Firebase index';
            }

            return response()->json([
                'success' => true,
                'data' => $vendors['data'],
                'meta' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $vendors['total'],
                    'has_more' => $vendors['has_more'],
                    'filters_applied' => $filters,
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder,
                    'note' => $vendors['note'] ?? null
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartVendorController@index: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get mart vendors: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific mart vendor by ID
     *
     * @param Request $request
     * @param string $vendor_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $vendor_id)
    {
        // Validate vendor_id parameter
        if (empty($vendor_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Vendor ID is required'
            ], 422);
        }

        try {
            $vendorData = $this->firebaseService->getVendorData($vendor_id);

            if (!$vendorData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found'
                ], 404);
            }

            // Check if it's a mart vendor
            if (($vendorData['vType'] ?? '') !== 'mart') {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor is not a mart vendor'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'data' => $vendorData
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartVendorController@show: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get vendor details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get nearby mart vendors based on coordinates
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNearbyVendors(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'sometimes|numeric|min:0.1|max:50',
            'limit' => 'sometimes|integer|min:1|max:100',
            'category_id' => 'sometimes|string',
            'enabled_delivery' => 'sometimes|boolean'
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
            $radius = $request->radius ?? 10; // Default 10km radius
            $limit = $request->limit ?? 20;

            $vendors = $this->firebaseService->getNearbyVendors(
                $latitude,
                $longitude,
                $radius,
                $limit
            );

            // Filter for mart vendors only and apply additional filters
            $filteredVendors = array_filter($vendors, function($vendor) use ($request) {
                // Must be a mart vendor
                if (($vendor['vType'] ?? '') !== 'mart') {
                    return false;
                }

                // Apply category filter if requested
                if ($request->has('category_id')) {
                    if (!isset($vendor['categoryID']) || !in_array($request->category_id, $vendor['categoryID'])) {
                        return false;
                    }
                }

                // Apply delivery filter if requested
                if ($request->has('enabled_delivery')) {
                    if (!isset($vendor['enabledDelivery']) || $vendor['enabledDelivery'] !== $request->enabled_delivery) {
                        return false;
                    }
                }

                return true;
            });

            return response()->json([
                'success' => true,
                'data' => array_values($filteredVendors),
                'meta' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'radius_km' => $radius,
                    'count' => count($filteredVendors),
                    'filters_applied' => [
                        'category_id' => $request->category_id ?? null,
                        'enabled_delivery' => $request->enabled_delivery ?? null
                    ]
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartVendorController@getNearbyVendors: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get nearby vendors: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vendors by category
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|string',
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:100',
            'sort_by' => 'sometimes|string|in:title,createdAt,restaurantCost',
            'sort_order' => 'sometimes|string|in:asc,desc'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $categoryId = $request->category_id;
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;
            $sortBy = $request->sort_by ?? 'title';
            $sortOrder = $request->sort_order ?? 'asc';

            $filters = [
                'vType' => 'mart',
                'categoryID' => $categoryId
            ];

            $vendors = $this->firebaseService->getAllMartVendors(
                $filters,
                null,
                $page,
                $limit
            );

            // If no results and it might be due to missing index, try fallback approach
            if (empty($vendors['data']) && $vendors['total'] === 0) {
                \Log::warning('No mart vendors found for category, trying fallback approach');
                
                // Simple fallback: Get all mart vendors and filter by category in PHP
                $allVendors = $this->firebaseService->getVendorsByTypeSimple('mart', 50);

                // Filter vendors by category in PHP
                $filteredVendors = array_filter($allVendors['data'], function($vendor) use ($categoryId) {
                    return isset($vendor['categoryID']) && in_array($categoryId, $vendor['categoryID']);
                });

                // Apply pagination
                $offset = ($page - 1) * $limit;
                $paginatedVendors = array_slice($filteredVendors, $offset, $limit);

                $vendors = [
                    'data' => array_values($paginatedVendors),
                    'total' => count($filteredVendors),
                    'has_more' => count($filteredVendors) > ($offset + $limit)
                ];

                // Add note about fallback usage
                $vendors['note'] = 'Using fallback query due to missing Firebase index';
            }

            return response()->json([
                'success' => true,
                'data' => $vendors['data'],
                'meta' => [
                    'category_id' => $categoryId,
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $vendors['total'],
                    'has_more' => $vendors['has_more'],
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder,
                    'note' => $vendors['note'] ?? null
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartVendorController@getByCategory: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get vendors by category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vendor working hours
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWorkingHours(Request $request)
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

            // Check if it's a mart vendor
            if (($vendorData['vType'] ?? '') !== 'mart') {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor is not a mart vendor'
                ], 400);
            }

            $workingHours = $vendorData['workingHours'] ?? [];
            $isOpen = $vendorData['isOpen'] ?? false;
            $openDineTime = $vendorData['openDineTime'] ?? null;
            $closeDineTime = $vendorData['closeDineTime'] ?? null;

            return response()->json([
                'success' => true,
                'data' => [
                    'vendor_id' => $request->vendor_id,
                    'vendor_title' => $vendorData['title'] ?? '',
                    'is_open' => $isOpen,
                    'open_dine_time' => $openDineTime,
                    'close_dine_time' => $closeDineTime,
                    'working_hours' => $workingHours
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartVendorController@getWorkingHours: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get vendor working hours: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vendor special discounts
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSpecialDiscounts(Request $request)
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

            // Check if it's a mart vendor
            if (($vendorData['vType'] ?? '') !== 'mart') {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor is not a mart vendor'
                ], 400);
            }

            $specialDiscounts = $vendorData['specialDiscount'] ?? [];
            $specialDiscountEnable = $vendorData['specialDiscountEnable'] ?? false;

            return response()->json([
                'success' => true,
                'data' => [
                    'vendor_id' => $request->vendor_id,
                    'vendor_title' => $vendorData['title'] ?? '',
                    'special_discount_enable' => $specialDiscountEnable,
                    'special_discounts' => $specialDiscounts
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartVendorController@getSpecialDiscounts: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get vendor special discounts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search mart vendors
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2|max:100',
            'publish' => 'sometimes|boolean',
            'is_open' => 'sometimes|boolean',
            'enabled_delivery' => 'sometimes|boolean',
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
            $filters = [
                'vType' => 'mart'
            ];

            if ($request->has('publish')) {
                $filters['publish'] = $request->publish;
            }
            if ($request->has('is_open')) {
                $filters['isOpen'] = $request->is_open;
            }
            if ($request->has('enabled_delivery')) {
                $filters['enabledDelivery'] = $request->enabled_delivery;
            }
            if ($request->has('category_id')) {
                $filters['categoryID'] = $request->category_id;
            }

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;

            $results = $this->firebaseService->getAllMartVendors(
                $filters,
                $request->input('query'),
                $page,
                $limit
            );

            // If no results, try fallback approach
            if (empty($results['data']) && $results['total'] === 0) {
                \Log::warning('No mart vendors found with search query, trying fallback approach');
                
                // Fallback: Get all mart vendors and filter in PHP
                $allVendors = $this->firebaseService->getVendorsByTypeSimple('mart', 50);

                // Filter vendors in PHP based on search query and filters
                $filteredVendors = array_filter($allVendors['data'], function($vendor) use ($request, $filters) {
                    $query = strtolower($request->input('query'));
                    $title = strtolower($vendor['title'] ?? '');
                    $description = strtolower($vendor['description'] ?? '');
                    $location = strtolower($vendor['location'] ?? '');
                    
                    // Check if any field contains the search query
                    $matchesQuery = strpos($title, $query) !== false ||
                                   strpos($description, $query) !== false ||
                                   strpos($location, $query) !== false;
                    
                    // Apply publish filter if requested
                    $matchesPublish = true;
                    if (isset($filters['publish'])) {
                        $matchesPublish = isset($vendor['publish']) && $vendor['publish'] === $filters['publish'];
                    }

                    // Apply is_open filter if requested
                    $matchesIsOpen = true;
                    if (isset($filters['isOpen'])) {
                        $matchesIsOpen = isset($vendor['isOpen']) && $vendor['isOpen'] === $filters['isOpen'];
                    }

                    // Apply enabled_delivery filter if requested
                    $matchesDelivery = true;
                    if (isset($filters['enabledDelivery'])) {
                        $matchesDelivery = isset($vendor['enabledDelivery']) && $vendor['enabledDelivery'] === $filters['enabledDelivery'];
                    }

                    // Apply category_id filter if requested
                    $matchesCategory = true;
                    if (isset($filters['categoryID'])) {
                        $matchesCategory = isset($vendor['categoryID']) && in_array($filters['categoryID'], $vendor['categoryID']);
                    }
                    
                    return $matchesQuery && $matchesPublish && $matchesIsOpen && $matchesDelivery && $matchesCategory;
                });

                // Apply pagination
                $offset = ($page - 1) * $limit;
                $paginatedVendors = array_slice($filteredVendors, $offset, $limit);
                
                $results = [
                    'data' => array_values($paginatedVendors),
                    'total' => count($filteredVendors),
                    'has_more' => count($filteredVendors) > ($offset + $limit)
                ];
                
                // Add note about fallback usage
                $results['note'] = 'Using fallback query due to missing Firebase index';
            }

            return response()->json([
                'success' => true,
                'data' => $results['data'],
                'meta' => [
                    'query' => $request->input('query'),
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $results['total'],
                    'has_more' => $results['has_more'],
                    'note' => $results['note'] ?? null
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartVendorController@search: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to search mart vendors: ' . $e->getMessage()
            ], 500);
        }
    }
}
