<?php

namespace App\Http\Controllers\Api\Mart;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

class MartItemController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Get all layouts items with pagination and filters
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'sometimes|string',
            'category_id' => 'sometimes|string',
            'subcategory_id' => 'sometimes|string|array',
            'is_available' => 'sometimes|boolean',
            'publish' => 'sometimes|boolean',
            'has_options' => 'sometimes|boolean',
            'is_spotlight' => 'sometimes|boolean',
            'is_steal_of_moment' => 'sometimes|boolean',
            'is_feature' => 'sometimes|boolean',
            'is_trending' => 'sometimes|boolean',
            'is_new' => 'sometimes|boolean',
            'is_best_seller' => 'sometimes|boolean',
            'is_seasonal' => 'sometimes|boolean',
            'veg' => 'sometimes|boolean',
            'nonveg' => 'sometimes|boolean',
            'takeaway_option' => 'sometimes|boolean',
            'min_price' => 'sometimes|numeric|min:0',
            'max_price' => 'sometimes|numeric|min:0',
            'search' => 'sometimes|string|max:100',
            'sort_by' => 'sometimes|string|in:name,price,created_at,updated_at',
            'sort_order' => 'sometimes|string|in:asc,desc',
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

            // Basic filters
            if ($request->has('vendor_id')) {
                $filters['vendor_id'] = $request->vendor_id;
            }
            if ($request->has('category_id')) {
                $filters['category_id'] = $request->category_id;
            }
            if ($request->has('subcategory_id')) {
                $filters['subcategory_id'] = $request->subcategory_id;
            }
            if ($request->has('is_available')) {
                $filters['is_available'] = $request->is_available;
            }
            if ($request->has('publish')) {
                $filters['publish'] = $request->publish;
            }
            if ($request->has('has_options')) {
                $filters['has_options'] = $request->has_options;
            }

            // Feature filters
            if ($request->has('is_spotlight')) {
                $filters['is_spotlight'] = $request->is_spotlight;
            }
            if ($request->has('is_steal_of_moment')) {
                $filters['is_steal_of_moment'] = $request->is_steal_of_moment;
            }
            if ($request->has('is_feature')) {
                $filters['is_feature'] = $request->is_feature;
            }
            if ($request->has('is_trending')) {
                $filters['is_trending'] = $request->is_trending;
            }
            if ($request->has('is_new')) {
                $filters['is_new'] = $request->is_new;
            }
            if ($request->has('is_best_seller')) {
                $filters['is_best_seller'] = $request->is_best_seller;
            }
            if ($request->has('is_seasonal')) {
                $filters['is_seasonal'] = $request->is_seasonal;
            }

            // Dietary filters
            if ($request->has('veg')) {
                $filters['veg'] = $request->veg;
            }
            if ($request->has('nonveg')) {
                $filters['nonveg'] = $request->nonveg;
            }
            if ($request->has('takeaway_option')) {
                $filters['takeaway_option'] = $request->takeaway_option;
            }

            // Price filters
            if ($request->has('min_price')) {
                $filters['min_price'] = $request->min_price;
            }
            if ($request->has('max_price')) {
                $filters['max_price'] = $request->max_price;
            }

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;
            $search = $request->search ?? null;
            $sortBy = $request->sort_by ?? 'name';
            $sortOrder = $request->sort_order ?? 'asc';

            $items = $this->firebaseService->getMartItemsWithPagination(
                $filters,
                $search,
                $page,
                $limit,
                $sortBy,
                $sortOrder
            );

            return response()->json([
                'success' => true,
                'data' => $items['data'],
                'meta' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $items['total'],
                    'has_more' => $items['has_more'],
                    'filters_applied' => $filters,
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartItemController@index: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get layouts items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific layouts item by ID
     */
    public function show(Request $request, $item_id)
    {
        if (empty($item_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Item ID is required'
            ], 422);
        }

        try {
            $itemData = $this->firebaseService->getMartItem($item_id);

            if (!$itemData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mart item not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $itemData
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartItemController@show: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get layouts item details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new layouts item
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'dis_price' => 'sometimes|numeric|min:0',
            'vendor_id' => 'required|string',
            'category_id' => 'required|string',
            'subcategory_id' => 'sometimes|string|array',
            'photo' => 'sometimes|string|url',
            'photos' => 'sometimes|array',
            'photos.*' => 'string|url',
            'publish' => 'sometimes|boolean',
            'is_available' => 'sometimes|boolean',
            'veg' => 'sometimes|boolean',
            'nonveg' => 'sometimes|boolean',
            'takeaway_option' => 'sometimes|boolean',

            // Enhanced filter fields
            'is_spotlight' => 'sometimes|boolean',
            'is_steal_of_moment' => 'sometimes|boolean',
            'is_feature' => 'sometimes|boolean',
            'is_trending' => 'sometimes|boolean',
            'is_new' => 'sometimes|boolean',
            'is_best_seller' => 'sometimes|boolean',
            'is_seasonal' => 'sometimes|boolean',

            // Options configuration
            'has_options' => 'sometimes|boolean',
            'options_enabled' => 'sometimes|boolean',
            'options_toggle' => 'sometimes|boolean',
            'options' => 'sometimes|array',
            'options.*.id' => 'required_with:options|string',
            'options.*.option_type' => 'required_with:options|string',
            'options.*.option_title' => 'required_with:options|string',
            'options.*.option_subtitle' => 'sometimes|string',
            'options.*.price' => 'required_with:options|numeric|min:0',
            'options.*.original_price' => 'sometimes|numeric|min:0',
            'options.*.quantity' => 'sometimes|numeric|min:0',
            'options.*.quantity_unit' => 'sometimes|string',
            'options.*.unit_measure' => 'sometimes|numeric|min:0',
            'options.*.unit_measure_type' => 'sometimes|string',
            'options.*.unit_price' => 'sometimes|numeric|min:0',
            'options.*.discount_amount' => 'sometimes|numeric|min:0',
            'options.*.image' => 'sometimes|string|url',
            'options.*.is_available' => 'sometimes|boolean',
            'options.*.is_featured' => 'sometimes|boolean',
            'options.*.sort_order' => 'sometimes|integer|min:1',

            // Nutrition fields
            'calories' => 'sometimes|numeric|min:0',
            'grams' => 'sometimes|numeric|min:0',
            'proteins' => 'sometimes|numeric|min:0',
            'fats' => 'sometimes|numeric|min:0',

            // Additional fields
            'quantity' => 'sometimes|integer|min:-1',
            'add_ons_title' => 'sometimes|array',
            'add_ons_price' => 'sometimes|array',
            'add_ons_price.*' => 'numeric|min:0',
            'product_specification' => 'sometimes|array',
            'item_attribute' => 'sometimes|string'
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

            // Verify vendor exists
            $vendor = $this->firebaseService->getVendorData($request->vendor_id);
            if (!$vendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found'
                ], 404);
            }

            // Verify category exists
            $category = $this->firebaseService->getMartCategory($request->category_id);
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            $itemData = [
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'disPrice' => $request->dis_price ?? $request->price,
                'vendorID' => $request->vendor_id,
                'categoryID' => $request->category_id,
                'subcategoryID' => $request->subcategory_id ?? [],
                'photo' => $request->photo ?? '',
                'photos' => $request->photos ?? [],
                'publish' => $request->publish ?? true,
                'isAvailable' => $request->is_available ?? true,
                'veg' => $request->veg ?? true,
                'nonveg' => $request->nonveg ?? false,
                'takeawayOption' => $request->takeaway_option ?? false,

                // Enhanced filter fields
                'isSpotlight' => $request->is_spotlight ?? false,
                'isStealOfMoment' => $request->is_steal_of_moment ?? false,
                'isFeature' => $request->is_feature ?? false,
                'isTrending' => $request->is_trending ?? false,
                'isNew' => $request->is_new ?? false,
                'isBestSeller' => $request->is_best_seller ?? false,
                'isSeasonal' => $request->is_seasonal ?? false,

                // Options configuration
                'has_options' => $request->has_options ?? false,
                'options_enabled' => $request->options_enabled ?? false,
                'options_toggle' => $request->options_toggle ?? false,
                'options_count' => $request->has_options ? count($request->options ?? []) : 0,
                'options' => $request->options ?? [],

                // Calculate price range for items with options
                'min_price' => $request->has_options ? min(array_column($request->options ?? [], 'price')) : $request->price,
                'max_price' => $request->has_options ? max(array_column($request->options ?? [], 'price')) : $request->price,
                'price_range' => $request->has_options ?
                    '₹' . min(array_column($request->options ?? [], 'price')) . ' - ₹' . max(array_column($request->options ?? [], 'price')) :
                    '₹' . $request->price,

                // Default option and best value
                'default_option_id' => $request->has_options ?
                    collect($request->options)->where('is_featured', true)->first()['id'] ??
                    collect($request->options)->first()['id'] ?? '' : '',
                'best_value_option' => $request->has_options ?
                    collect($request->options)->sortBy('unit_price')->first()['id'] ?? '' : '',

                // Nutrition fields
                'calories' => $request->calories ?? 0,
                'grams' => $request->grams ?? 0,
                'proteins' => $request->proteins ?? 0,
                'fats' => $request->fats ?? 0,

                // Additional fields
                'quantity' => $request->quantity ?? -1,
                'addOnsTitle' => $request->add_ons_title ?? [],
                'addOnsPrice' => $request->add_ons_price ?? [],
                'product_specification' => $request->product_specification ?? [],
                'item_attribute' => $request->item_attribute ?? null,

                // Timestamps
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString(),
                'created_by' => $user->id
            ];

            $itemId = $this->firebaseService->createMartItem($itemData);

            if (!$itemId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create layouts item'
                ], 500);
            }

            // Get the created item data
            $createdItem = $this->firebaseService->getMartItem($itemId);

            return response()->json([
                'success' => true,
                'message' => 'Mart item created successfully',
                'data' => $createdItem
            ], 201);

        } catch (Exception $e) {
            \Log::error('Error in MartItemController@store: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create layouts item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a layouts item
     */
    public function update(Request $request, $item_id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:100',
            'description' => 'sometimes|string|max:1000',
            'price' => 'sometimes|numeric|min:0',
            'dis_price' => 'sometimes|numeric|min:0',
            'category_id' => 'sometimes|string',
            'subcategory_id' => 'sometimes|string|array',
            'photo' => 'sometimes|string|url',
            'photos' => 'sometimes|array',
            'photos.*' => 'string|url',
            'publish' => 'sometimes|boolean',
            'is_available' => 'sometimes|boolean',
            'veg' => 'sometimes|boolean',
            'nonveg' => 'sometimes|boolean',
            'takeaway_option' => 'sometimes|boolean',

            // Enhanced filter fields
            'is_spotlight' => 'sometimes|boolean',
            'is_steal_of_moment' => 'sometimes|boolean',
            'is_feature' => 'sometimes|boolean',
            'is_trending' => 'sometimes|boolean',
            'is_new' => 'sometimes|boolean',
            'is_best_seller' => 'sometimes|boolean',
            'is_seasonal' => 'sometimes|boolean',

            // Options configuration
            'has_options' => 'sometimes|boolean',
            'options_enabled' => 'sometimes|boolean',
            'options_toggle' => 'sometimes|boolean',
            'options' => 'sometimes|array',
            'options.*.id' => 'required_with:options|string',
            'options.*.option_type' => 'required_with:options|string',
            'options.*.option_title' => 'required_with:options|string',
            'options.*.option_subtitle' => 'sometimes|string',
            'options.*.price' => 'required_with:options|numeric|min:0',
            'options.*.original_price' => 'sometimes|numeric|min:0',
            'options.*.quantity' => 'sometimes|numeric|min:0',
            'options.*.quantity_unit' => 'sometimes|string',
            'options.*.unit_measure' => 'sometimes|numeric|min:0',
            'options.*.unit_measure_type' => 'sometimes|string',
            'options.*.unit_price' => 'sometimes|numeric|min:0',
            'options.*.discount_amount' => 'sometimes|numeric|min:0',
            'options.*.image' => 'sometimes|string|url',
            'options.*.is_available' => 'sometimes|boolean',
            'options.*.is_featured' => 'sometimes|boolean',
            'options.*.sort_order' => 'sometimes|integer|min:1',

            // Nutrition fields
            'calories' => 'sometimes|numeric|min:0',
            'grams' => 'sometimes|numeric|min:0',
            'proteins' => 'sometimes|numeric|min:0',
            'fats' => 'sometimes|numeric|min:0',

            // Additional fields
            'quantity' => 'sometimes|integer|min:-1',
            'add_ons_title' => 'sometimes|array',
            'add_ons_price' => 'sometimes|array',
            'add_ons_price.*' => 'numeric|min:0',
            'product_specification' => 'sometimes|array',
            'item_attribute' => 'sometimes|string'
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

            // Check if item exists
            $existingItem = $this->firebaseService->getMartItem($item_id);
            if (!$existingItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mart item not found'
                ], 404);
            }

            // Verify category if provided
            if ($request->has('category_id')) {
                $category = $this->firebaseService->getMartCategory($request->category_id);
                if (!$category) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Category not found'
                    ], 404);
                }
            }

            $updateData = [];

            // Basic fields
            if ($request->has('name')) $updateData['name'] = $request->name;
            if ($request->has('description')) $updateData['description'] = $request->description;
            if ($request->has('price')) $updateData['price'] = $request->price;
            if ($request->has('dis_price')) $updateData['disPrice'] = $request->dis_price;
            if ($request->has('category_id')) $updateData['categoryID'] = $request->category_id;
            if ($request->has('subcategory_id')) $updateData['subcategoryID'] = $request->subcategory_id;
            if ($request->has('photo')) $updateData['photo'] = $request->photo;
            if ($request->has('photos')) $updateData['photos'] = $request->photos;
            if ($request->has('publish')) $updateData['publish'] = $request->publish;
            if ($request->has('is_available')) $updateData['isAvailable'] = $request->is_available;
            if ($request->has('veg')) $updateData['veg'] = $request->veg;
            if ($request->has('nonveg')) $updateData['nonveg'] = $request->nonveg;
            if ($request->has('takeaway_option')) $updateData['takeawayOption'] = $request->takeaway_option;

            // Enhanced filter fields
            if ($request->has('is_spotlight')) $updateData['isSpotlight'] = $request->is_spotlight;
            if ($request->has('is_steal_of_moment')) $updateData['isStealOfMoment'] = $request->is_steal_of_moment;
            if ($request->has('is_feature')) $updateData['isFeature'] = $request->is_feature;
            if ($request->has('is_trending')) $updateData['isTrending'] = $request->is_trending;
            if ($request->has('is_new')) $updateData['isNew'] = $request->is_new;
            if ($request->has('is_best_seller')) $updateData['isBestSeller'] = $request->is_best_seller;
            if ($request->has('is_seasonal')) $updateData['isSeasonal'] = $request->is_seasonal;

            // Options configuration
            if ($request->has('has_options')) $updateData['has_options'] = $request->has_options;
            if ($request->has('options_enabled')) $updateData['options_enabled'] = $request->options_enabled;
            if ($request->has('options_toggle')) $updateData['options_toggle'] = $request->options_toggle;
            if ($request->has('options')) {
                $updateData['options'] = $request->options;
                $updateData['options_count'] = count($request->options);

                // Recalculate price range
                $updateData['min_price'] = min(array_column($request->options, 'price'));
                $updateData['max_price'] = max(array_column($request->options, 'price'));
                $updateData['price_range'] = '₹' . $updateData['min_price'] . ' - ₹' . $updateData['max_price'];

                // Update default and best value options
                $updateData['default_option_id'] = collect($request->options)->where('is_featured', true)->first()['id'] ??
                    collect($request->options)->first()['id'] ?? '';
                $updateData['best_value_option'] = collect($request->options)->sortBy('unit_price')->first()['id'] ?? '';
            }

            // Nutrition fields
            if ($request->has('calories')) $updateData['calories'] = $request->calories;
            if ($request->has('grams')) $updateData['grams'] = $request->grams;
            if ($request->has('proteins')) $updateData['proteins'] = $request->proteins;
            if ($request->has('fats')) $updateData['fats'] = $request->fats;

            // Additional fields
            if ($request->has('quantity')) $updateData['quantity'] = $request->quantity;
            if ($request->has('add_ons_title')) $updateData['addOnsTitle'] = $request->add_ons_title;
            if ($request->has('add_ons_price')) $updateData['addOnsPrice'] = $request->add_ons_price;
            if ($request->has('product_specification')) $updateData['product_specification'] = $request->product_specification;
            if ($request->has('item_attribute')) $updateData['item_attribute'] = $request->item_attribute;

            // Update timestamp
            $updateData['updated_at'] = now()->toISOString();
            $updateData['updated_by'] = $user->id;

            $success = $this->firebaseService->updateMartItem($item_id, $updateData);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update layouts item'
                ], 500);
            }

            // Get the updated item data
            $updatedItem = $this->firebaseService->getMartItem($item_id);

            return response()->json([
                'success' => true,
                'message' => 'Mart item updated successfully',
                'data' => $updatedItem
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartItemController@update: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update layouts item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a layouts item
     */
    public function destroy(Request $request, $item_id)
    {
        if (empty($item_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Item ID is required'
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

            // Check if item exists
            $existingItem = $this->firebaseService->getMartItem($item_id);
            if (!$existingItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mart item not found'
                ], 404);
            }

            $success = $this->firebaseService->deleteMartItem($item_id);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete layouts item'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Mart item deleted successfully'
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartItemController@destroy: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete layouts item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search layouts items
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2|max:100',
            'vendor_id' => 'sometimes|string',
            'category_id' => 'sometimes|string',
            'subcategory_id' => 'sometimes|string|array',
            'is_available' => 'sometimes|boolean',
            'publish' => 'sometimes|boolean',
            'has_options' => 'sometimes|boolean',
            'min_price' => 'sometimes|numeric|min:0',
            'max_price' => 'sometimes|numeric|min:0',
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:50',
            'sort_by' => 'sometimes|string|in:name,price,created_at,updated_at',
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
            $filters = [];

            if ($request->has('vendor_id')) {
                $filters['vendor_id'] = $request->vendor_id;
            }
            if ($request->has('category_id')) {
                $filters['category_id'] = $request->category_id;
            }
            if ($request->has('subcategory_id')) {
                $filters['subcategory_id'] = $request->subcategory_id;
            }
            if ($request->has('is_available')) {
                $filters['is_available'] = $request->is_available;
            }
            if ($request->has('publish')) {
                $filters['publish'] = $request->publish;
            }
            if ($request->has('has_options')) {
                $filters['has_options'] = $request->has_options;
            }
            if ($request->has('min_price')) {
                $filters['min_price'] = $request->min_price;
            }
            if ($request->has('max_price')) {
                $filters['max_price'] = $request->max_price;
            }

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;
            $sortBy = $request->sort_by ?? 'name';
            $sortOrder = $request->sort_order ?? 'asc';

            $results = $this->firebaseService->searchMartItemsWithFilters(
                $request->query,
                $filters,
                $page,
                $limit,
                $sortBy,
                $sortOrder
            );

            return response()->json([
                'success' => true,
                'data' => $results['data'],
                'meta' => [
                    'query' => $request->query,
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $results['total'],
                    'has_more' => $results['has_more'],
                    'filters_applied' => $filters,
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartItemController@search: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to search layouts items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get featured items (spotlight, trending, etc.)
     */
    public function getFeaturedItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'feature_type' => 'required|string|in:spotlight,steal_of_moment,featured,trending,new,best_seller,seasonal',
            'vendor_id' => 'sometimes|string',
            'category_id' => 'sometimes|string',
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
                'publish' => true,
                'is_available' => true
            ];

            // Map feature type to field name
            $featureMap = [
                'spotlight' => 'is_spotlight',
                'steal_of_moment' => 'is_steal_of_moment',
                'featured' => 'is_feature',
                'trending' => 'is_trending',
                'new' => 'is_new',
                'best_seller' => 'is_best_seller',
                'seasonal' => 'is_seasonal'
            ];

            $filters[$featureMap[$request->feature_type]] = true;

            if ($request->has('vendor_id')) {
                $filters['vendor_id'] = $request->vendor_id;
            }
            if ($request->has('category_id')) {
                $filters['category_id'] = $request->category_id;
            }

            $limit = $request->limit ?? 20;

            $items = $this->firebaseService->getMartItemsWithPagination(
                $filters,
                null,
                1,
                $limit,
                'created_at',
                'desc'
            );

            return response()->json([
                'success' => true,
                'data' => $items['data'],
                'meta' => [
                    'feature_type' => $request->feature_type,
                    'total' => $items['total'],
                    'filters_applied' => $filters
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartItemController@getFeaturedItems: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get featured items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get items by vendor
     */
    public function getByVendor(Request $request, $vendor_id)
    {
        if (empty($vendor_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Vendor ID is required'
            ], 422);
        }

        try {
            $filters = [
                'vendor_id' => $vendor_id,
                'publish' => true,
                'is_available' => true
            ];

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;
            $sortBy = $request->sort_by ?? 'name';
            $sortOrder = $request->sort_order ?? 'asc';

            $items = $this->firebaseService->getMartItemsWithPagination(
                $filters,
                null,
                $page,
                $limit,
                $sortBy,
                $sortOrder
            );

            return response()->json([
                'success' => true,
                'data' => $items['data'],
                'meta' => [
                    'vendor_id' => $vendor_id,
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $items['total'],
                    'has_more' => $items['has_more'],
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartItemController@getByVendor: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get vendor items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get items by category
     */
    public function getByCategory(Request $request, $category_id)
    {
        if (empty($category_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Category ID is required'
            ], 422);
        }

        try {
            $filters = [
                'category_id' => $category_id,
                'publish' => true,
                'is_available' => true
            ];

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;
            $sortBy = $request->sort_by ?? 'name';
            $sortOrder = $request->sort_order ?? 'asc';

            $items = $this->firebaseService->getMartItemsWithPagination(
                $filters,
                null,
                $page,
                $limit,
                $sortBy,
                $sortOrder
            );

            return response()->json([
                'success' => true,
                'data' => $items['data'],
                'meta' => [
                    'category_id' => $category_id,
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $items['total'],
                    'has_more' => $items['has_more'],
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartItemController@getByCategory: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get category items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get items by subcategory
     */
    public function getBySubCategory(Request $request, $subcategory_id)
    {
        if (empty($subcategory_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Subcategory ID is required'
            ], 422);
        }

        try {
            $filters = [
                'subcategory_id' => $subcategory_id
                // Temporarily removed publish and is_available filters for debugging
            ];

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;
            $sortBy = $request->sort_by ?? 'name';
            $sortOrder = $request->sort_order ?? 'asc';

            $items = $this->firebaseService->getMartItemsBySubcategory(
                $subcategory_id,
                $filters,
                $page,
                $limit,
                $sortBy,
                $sortOrder
            );

            return response()->json([
                'success' => true,
                'data' => $items['data'],
                'meta' => [
                    'subcategory_id' => $subcategory_id,
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $items['total'],
                    'has_more' => $items['has_more'],
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartItemController@getBySubCategory: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get subcategory items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get best seller items
     */
    public function getBestSellers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'sometimes|string',
            'category_id' => 'sometimes|string',
            'subcategory_id' => 'sometimes|string',
            'limit' => 'sometimes|integer|min:1|max:50',
            'page' => 'sometimes|integer|min:1',
            'sort_by' => 'sometimes|string|in:name,price,created_at,updated_at',
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
                'is_best_seller' => true,
                'publish' => true,
                'is_available' => true
            ];

            if ($request->has('vendor_id')) {
                $filters['vendor_id'] = $request->vendor_id;
            }
            if ($request->has('category_id')) {
                $filters['category_id'] = $request->category_id;
            }
            if ($request->has('subcategory_id')) {
                $filters['subcategory_id'] = $request->subcategory_id;
            }

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;
            $sortBy = $request->sort_by ?? 'name';
            $sortOrder = $request->sort_order ?? 'asc';

            $items = $this->firebaseService->getMartItemsWithPagination(
                $filters,
                null,
                $page,
                $limit,
                $sortBy,
                $sortOrder
            );

            return response()->json([
                'success' => true,
                'data' => $items['data'],
                'meta' => [
                    'filter_type' => 'best_sellers',
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $items['total'],
                    'has_more' => $items['has_more'],
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder,
                    'filters_applied' => $filters
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartItemController@getBestSellers: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get best seller items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get featured items
     */
    public function getFeatured(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'sometimes|string',
            'category_id' => 'sometimes|string',
            'subcategory_id' => 'sometimes|string',
            'limit' => 'sometimes|integer|min:1|max:50',
            'page' => 'sometimes|integer|min:1',
            'sort_by' => 'sometimes|string|in:name,price,created_at,updated_at',
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
                'is_feature' => true,
                'publish' => true,
                'is_available' => true
            ];

            if ($request->has('vendor_id')) {
                $filters['vendor_id'] = $request->vendor_id;
            }
            if ($request->has('category_id')) {
                $filters['category_id'] = $request->category_id;
            }
            if ($request->has('subcategory_id')) {
                $filters['subcategory_id'] = $request->subcategory_id;
            }

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;
            $sortBy = $request->sort_by ?? 'name';
            $sortOrder = $request->sort_order ?? 'asc';

            $items = $this->firebaseService->getMartItemsWithPagination(
                $filters,
                null,
                $page,
                $limit,
                $sortBy,
                $sortOrder
            );

            return response()->json([
                'success' => true,
                'data' => $items['data'],
                'meta' => [
                    'filter_type' => 'featured',
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $items['total'],
                    'has_more' => $items['has_more'],
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder,
                    'filters_applied' => $filters
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartItemController@getFeatured: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get featured items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get new items
     */
    public function getNewItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'sometimes|string',
            'category_id' => 'sometimes|string',
            'subcategory_id' => 'sometimes|string',
            'limit' => 'sometimes|integer|min:1|max:50',
            'page' => 'sometimes|integer|min:1',
            'sort_by' => 'sometimes|string|in:name,price,created_at,updated_at',
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
                'is_new' => true,
                'publish' => true,
                'is_available' => true
            ];

            if ($request->has('vendor_id')) {
                $filters['vendor_id'] = $request->vendor_id;
            }
            if ($request->has('category_id')) {
                $filters['category_id'] = $request->category_id;
            }
            if ($request->has('subcategory_id')) {
                $filters['subcategory_id'] = $request->subcategory_id;
            }

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;
            $sortBy = $request->sort_by ?? 'name';
            $sortOrder = $request->sort_order ?? 'asc';

            $items = $this->firebaseService->getMartItemsWithPagination(
                $filters,
                null,
                $page,
                $limit,
                $sortBy,
                $sortOrder
            );

            return response()->json([
                'success' => true,
                'data' => $items['data'],
                'meta' => [
                    'filter_type' => 'new_items',
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $items['total'],
                    'has_more' => $items['has_more'],
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder,
                    'filters_applied' => $filters
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartItemController@getNewItems: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get new items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get seasonal items
     */
    public function getSeasonal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'sometimes|string',
            'category_id' => 'sometimes|string',
            'subcategory_id' => 'sometimes|string',
            'limit' => 'sometimes|integer|min:1|max:50',
            'page' => 'sometimes|integer|min:1',
            'sort_by' => 'sometimes|string|in:name,price,created_at,updated_at',
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
                'is_seasonal' => true,
                'publish' => true,
                'is_available' => true
            ];

            if ($request->has('vendor_id')) {
                $filters['vendor_id'] = $request->vendor_id;
            }
            if ($request->has('category_id')) {
                $filters['category_id'] = $request->category_id;
            }
            if ($request->has('subcategory_id')) {
                $filters['subcategory_id'] = $request->subcategory_id;
            }

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;
            $sortBy = $request->sort_by ?? 'name';
            $sortOrder = $request->sort_order ?? 'asc';

            $items = $this->firebaseService->getMartItemsWithPagination(
                $filters,
                null,
                $page,
                $limit,
                $sortBy,
                $sortOrder
            );

            return response()->json([
                'success' => true,
                'data' => $items['data'],
                'meta' => [
                    'filter_type' => 'seasonal',
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $items['total'],
                    'has_more' => $items['has_more'],
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder,
                    'filters_applied' => $filters
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartItemController@getSeasonal: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get seasonal items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get spotlight items
     */
    public function getSpotlight(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'sometimes|string',
            'category_id' => 'sometimes|string',
            'subcategory_id' => 'sometimes|string',
            'limit' => 'sometimes|integer|min:1|max:50',
            'page' => 'sometimes|integer|min:1',
            'sort_by' => 'sometimes|string|in:name,price,created_at,updated_at',
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
                'is_spotlight' => true,
                'publish' => true,
                'is_available' => true
            ];

            if ($request->has('vendor_id')) {
                $filters['vendor_id'] = $request->vendor_id;
            }
            if ($request->has('category_id')) {
                $filters['category_id'] = $request->category_id;
            }
            if ($request->has('subcategory_id')) {
                $filters['subcategory_id'] = $request->subcategory_id;
            }

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;
            $sortBy = $request->sort_by ?? 'name';
            $sortOrder = $request->sort_order ?? 'asc';

            $items = $this->firebaseService->getMartItemsWithPagination(
                $filters,
                null,
                $page,
                $limit,
                $sortBy,
                $sortOrder
            );

            return response()->json([
                'success' => true,
                'data' => $items['data'],
                'meta' => [
                    'filter_type' => 'spotlight',
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $items['total'],
                    'has_more' => $items['has_more'],
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder,
                    'filters_applied' => $filters
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartItemController@getSpotlight: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get spotlight items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get steal of moment items
     */
    public function getStealOfMoment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'sometimes|string',
            'category_id' => 'sometimes|string',
            'subcategory_id' => 'sometimes|string',
            'limit' => 'sometimes|integer|min:1|max:50',
            'page' => 'sometimes|integer|min:1',
            'sort_by' => 'sometimes|string|in:name,price,created_at,updated_at',
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
                'is_steal_of_moment' => true,
                'publish' => true,
                'is_available' => true
            ];

            if ($request->has('vendor_id')) {
                $filters['vendor_id'] = $request->vendor_id;
            }
            if ($request->has('category_id')) {
                $filters['category_id'] = $request->category_id;
            }
            if ($request->has('subcategory_id')) {
                $filters['subcategory_id'] = $request->subcategory_id;
            }

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;
            $sortBy = $request->sort_by ?? 'name';
            $sortOrder = $request->sort_order ?? 'asc';

            $items = $this->firebaseService->getMartItemsWithPagination(
                $filters,
                null,
                $page,
                $limit,
                $sortBy,
                $sortOrder
            );

            return response()->json([
                'success' => true,
                'data' => $items['data'],
                'meta' => [
                    'filter_type' => 'steal_of_moment',
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $items['total'],
                    'has_more' => $items['has_more'],
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder,
                    'filters_applied' => $filters
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartItemController@getStealOfMoment: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get steal of moment items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get trending items
     */
    public function getTrending(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'sometimes|string',
            'category_id' => 'sometimes|string',
            'subcategory_id' => 'sometimes|string',
            'limit' => 'sometimes|integer|min:1|max:50',
            'page' => 'sometimes|integer|min:1',
            'sort_by' => 'sometimes|string|in:name,price,created_at,updated_at',
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
                'is_trending' => true,
                'publish' => true,
                'is_available' => true
            ];

            if ($request->has('vendor_id')) {
                $filters['vendor_id'] = $request->vendor_id;
            }
            if ($request->has('category_id')) {
                $filters['category_id'] = $request->category_id;
            }
            if ($request->has('subcategory_id')) {
                $filters['subcategory_id'] = $request->subcategory_id;
            }

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;
            $sortBy = $request->sort_by ?? 'name';
            $sortOrder = $request->sort_order ?? 'asc';

            $items = $this->firebaseService->getMartItemsWithPagination(
                $filters,
                null,
                $page,
                $limit,
                $sortBy,
                $sortOrder
            );

            return response()->json([
                'success' => true,
                'data' => $items['data'],
                'meta' => [
                    'filter_type' => 'trending',
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $items['total'],
                    'has_more' => $items['has_more'],
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder,
                    'filters_applied' => $filters
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartItemController@getTrending: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get trending items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update layouts items
     */
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_ids' => 'required|array|min:1',
            'item_ids.*' => 'string',
            'updates' => 'required|array',
            'updates.publish' => 'sometimes|boolean',
            'updates.is_available' => 'sometimes|boolean',
            'updates.is_spotlight' => 'sometimes|boolean',
            'updates.is_steal_of_moment' => 'sometimes|boolean',
            'updates.is_feature' => 'sometimes|boolean',
            'updates.is_trending' => 'sometimes|boolean',
            'updates.is_new' => 'sometimes|boolean',
            'updates.is_best_seller' => 'sometimes|boolean',
            'updates.is_seasonal' => 'sometimes|boolean'
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

            $success = $this->firebaseService->bulkUpdateMartItems(
                $request->item_ids,
                $request->updates,
                $user->id
            );

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to bulk update layouts items'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Mart items updated successfully',
                'meta' => [
                    'items_updated' => count($request->item_ids),
                    'updates_applied' => $request->updates
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartItemController@bulkUpdate: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to bulk update layouts items: ' . $e->getMessage()
            ], 500);
        }
    }
}
