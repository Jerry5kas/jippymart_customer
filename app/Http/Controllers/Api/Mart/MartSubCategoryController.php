<?php

namespace App\Http\Controllers\Api\Mart;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

class MartSubCategoryController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Get all layouts subcategories with enhanced filtering
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'publish' => 'sometimes|boolean',
            'show_in_homepage' => 'sometimes|boolean',
            'parent_category_id' => 'sometimes|string',
            'mart_id' => 'sometimes|string',
            'search' => 'sometimes|string|max:100',
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:100',
            'sort_by' => 'sometimes|string|in:title,subcategory_order,category_order,section_order',
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

            // Apply filters
            if ($request->has('publish')) {
                $filters['publish'] = $request->publish;
            }
            if ($request->has('show_in_homepage')) {
                $filters['show_in_homepage'] = $request->show_in_homepage;
            }
            if ($request->has('parent_category_id')) {
                $filters['parent_category_id'] = $request->parent_category_id;
            }
            if ($request->has('mart_id')) {
                $filters['mart_id'] = $request->mart_id;
            }

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;
            $search = $request->search ?? null;
            $sortBy = $request->sort_by ?? 'title';
            $sortOrder = $request->sort_order ?? 'asc';

            $subcategories = $this->firebaseService->getMartSubCategoriesWithPagination(
                $filters,
                $search,
                $page,
                $limit,
                $sortBy,
                $sortOrder
            );

            return response()->json([
                'success' => true,
                'data' => $subcategories['data'],
                'meta' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $subcategories['total'],
                    'has_more' => $subcategories['has_more'],
                    'filters_applied' => $filters,
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartSubCategoryController@index: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get subcategories: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific layouts subcategory by ID
     *
     * @param Request $request
     * @param string $subcategory_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $subcategory_id)
    {
        // Validate subcategory_id parameter
        if (empty($subcategory_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Subcategory ID is required'
            ], 422);
        }

        try {
            $subcategoryData = $this->firebaseService->getMartSubCategory($subcategory_id);

            if (!$subcategoryData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subcategory not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $subcategoryData
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartSubCategoryController@show: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get subcategory details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new layouts subcategory
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'parent_category_id' => 'required|string',
            'photo' => 'sometimes|string|url',
            'publish' => 'sometimes|boolean',
            'show_in_homepage' => 'sometimes|boolean',
            'category_order' => 'sometimes|integer|min:1',
            'subcategory_order' => 'sometimes|integer|min:1',
            'section' => 'sometimes|string|max:100',
            'section_order' => 'sometimes|integer|min:1',
            'mart_id' => 'sometimes|string',
            'review_attributes' => 'sometimes|array',
            'review_attributes.*' => 'string'
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

            // Verify parent category exists
            $parentCategory = $this->firebaseService->getMartCategory($request->parent_category_id);
            if (!$parentCategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parent category not found'
                ], 404);
            }

            $subcategoryData = [
                'title' => $request->title,
                'description' => $request->description,
                'parent_category_id' => $request->parent_category_id,
                'parent_category_title' => $parentCategory['title'],
                'photo' => $request->photo ?? '',
                'publish' => $request->publish ?? true,
                'show_in_homepage' => $request->show_in_homepage ?? false,
                'category_order' => $request->category_order ?? 1,
                'subcategory_order' => $request->subcategory_order ?? 1,
                'section' => $request->section ?? $parentCategory['section'] ?? '',
                'section_order' => $request->section_order ?? 1,
                'mart_id' => $request->mart_id ?? '',
                'review_attributes' => $request->review_attributes ?? [],
                'migratedBy' => 'migrate:layouts-subcategories',
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString(),
                'created_by' => $user->id
            ];

            $subcategoryId = $this->firebaseService->createMartSubCategory($subcategoryData);

            if (!$subcategoryId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create subcategory'
                ], 500);
            }

            // Update parent category subcategories count
            $this->firebaseService->incrementSubcategoriesCount($request->parent_category_id);

            // Get the created subcategory data
            $createdSubcategory = $this->firebaseService->getMartSubCategory($subcategoryId);

            return response()->json([
                'success' => true,
                'message' => 'Subcategory created successfully',
                'data' => $createdSubcategory
            ], 201);

        } catch (Exception $e) {
            \Log::error('Error in MartSubCategoryController@store: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create subcategory: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing layouts subcategory
     *
     * @param Request $request
     * @param string $subcategory_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $subcategory_id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:100',
            'description' => 'sometimes|string|max:500',
            'parent_category_id' => 'sometimes|string',
            'photo' => 'sometimes|string|url',
            'publish' => 'sometimes|boolean',
            'show_in_homepage' => 'sometimes|boolean',
            'category_order' => 'sometimes|integer|min:1',
            'subcategory_order' => 'sometimes|integer|min:1',
            'section' => 'sometimes|string|max:100',
            'section_order' => 'sometimes|integer|min:1',
            'mart_id' => 'sometimes|string',
            'review_attributes' => 'sometimes|array',
            'review_attributes.*' => 'string'
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

            // Check if subcategory exists
            $existingSubcategory = $this->firebaseService->getMartSubCategory($subcategory_id);
            if (!$existingSubcategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subcategory not found'
                ], 404);
            }

            $updateData = $request->only([
                'title', 'description', 'photo', 'publish',
                'show_in_homepage', 'category_order', 'subcategory_order',
                'section', 'section_order', 'mart_id', 'review_attributes'
            ]);

            // If parent_category_id is being updated, verify it exists
            if ($request->has('parent_category_id') && $request->parent_category_id !== $existingSubcategory['parent_category_id']) {
                $newParentCategory = $this->firebaseService->getMartCategory($request->parent_category_id);
                if (!$newParentCategory) {
                    return response()->json([
                        'success' => false,
                        'message' => 'New parent category not found'
                    ], 404);
                }

                $updateData['parent_category_id'] = $request->parent_category_id;
                $updateData['parent_category_title'] = $newParentCategory['title'];

                // Update subcategories count for old and new parent categories
                $this->firebaseService->decrementSubcategoriesCount($existingSubcategory['parent_category_id']);
                $this->firebaseService->incrementSubcategoriesCount($request->parent_category_id);
            }

            $updateData['updated_at'] = now()->toISOString();
            $updateData['updated_by'] = $user->id;

            $success = $this->firebaseService->updateMartSubCategory($subcategory_id, $updateData);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update subcategory'
                ], 500);
            }

            // Get the updated subcategory data
            $updatedSubcategory = $this->firebaseService->getMartSubCategory($subcategory_id);

            return response()->json([
                'success' => true,
                'message' => 'Subcategory updated successfully',
                'data' => $updatedSubcategory
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartSubCategoryController@update: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update subcategory: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a layouts subcategory
     *
     * @param Request $request
     * @param string $subcategory_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $subcategory_id)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Check if subcategory exists
            $existingSubcategory = $this->firebaseService->getMartSubCategory($subcategory_id);
            if (!$existingSubcategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subcategory not found'
                ], 404);
            }

            $success = $this->firebaseService->deleteMartSubCategory($subcategory_id);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete subcategory'
                ], 500);
            }

            // Decrement parent category subcategories count
            $this->firebaseService->decrementSubcategoriesCount($existingSubcategory['parent_category_id']);

            return response()->json([
                'success' => true,
                'message' => 'Subcategory deleted successfully'
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartSubCategoryController@destroy: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subcategory: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subcategories by parent category ID
     *
     * @param Request $request
     * @param string $parent_category_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByParentCategory(Request $request, $parent_category_id)
    {
        $validator = Validator::make($request->all(), [
            'publish' => 'sometimes|boolean',
            'show_in_homepage' => 'sometimes|boolean',
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:50',
            'sort_by' => 'sometimes|string|in:title,subcategory_order,category_order',
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
            // Verify parent category exists
            $parentCategory = $this->firebaseService->getMartCategory($parent_category_id);
            if (!$parentCategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parent category not found'
                ], 404);
            }

            $filters = [
                'parent_category_id' => $parent_category_id
            ];

            if ($request->has('publish')) {
                $filters['publish'] = $request->publish;
            }
            if ($request->has('show_in_homepage')) {
                $filters['show_in_homepage'] = $request->show_in_homepage;
            }

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;
            $sortBy = $request->sort_by ?? 'subcategory_order';
            $sortOrder = $request->sort_order ?? 'asc';

            $subcategories = $this->firebaseService->getMartSubCategoriesWithPagination(
                $filters,
                null,
                $page,
                $limit,
                $sortBy,
                $sortOrder
            );

            return response()->json([
                'success' => true,
                'data' => $subcategories['data'],
                'parent_category' => $parentCategory,
                'meta' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $subcategories['total'],
                    'has_more' => $subcategories['has_more']
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartSubCategoryController@getByParentCategory: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get subcategories: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subcategories for homepage
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHomepageSubcategories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'sometimes|integer|min:1|max:20',
            'parent_category_id' => 'sometimes|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $limit = $request->limit ?? 10;

            $filters = [
                'publish' => true,
                'show_in_homepage' => true
            ];

            if ($request->has('parent_category_id')) {
                $filters['parent_category_id'] = $request->parent_category_id;
            }

            $subcategories = $this->firebaseService->getMartSubCategoriesWithPagination(
                $filters,
                null,
                1,
                $limit,
                'subcategory_order',
                'asc'
            );

            return response()->json([
                'success' => true,
                'data' => $subcategories['data'],
                'meta' => [
                    'total' => $subcategories['total'],
                    'limit' => $limit
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartSubCategoryController@getHomepageSubcategories: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get homepage subcategories: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search subcategories by title or description
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2|max:100',
            'publish' => 'sometimes|boolean',
            'parent_category_id' => 'sometimes|string',
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
            if ($request->has('publish')) {
                $filters['publish'] = $request->publish;
            }
            if ($request->has('parent_category_id')) {
                $filters['parent_category_id'] = $request->parent_category_id;
            }

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;

            $results = $this->firebaseService->searchMartSubCategories(
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
            \Log::error('Error in MartSubCategoryController@search: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to search subcategories: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update subcategories
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subcategory_ids' => 'required|array|min:1',
            'subcategory_ids.*' => 'string',
            'updates' => 'required|array',
            'updates.publish' => 'sometimes|boolean',
            'updates.show_in_homepage' => 'sometimes|boolean',
            'updates.subcategory_order' => 'sometimes|integer|min:1'
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

            $updateData = $request->updates;
            $updateData['updated_at'] = now()->toISOString();
            $updateData['updated_by'] = $user->id;

            $results = $this->firebaseService->bulkUpdateMartSubCategories(
                $request->subcategory_ids,
                $updateData
            );

            return response()->json([
                'success' => true,
                'message' => 'Subcategories updated successfully',
                'data' => [
                    'updated_count' => $results['updated'],
                    'failed_count' => $results['failed'],
                    'failed_ids' => $results['failed_ids']
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartSubCategoryController@bulkUpdate: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to bulk update subcategories: ' . $e->getMessage()
            ], 500);
        }
    }
}
