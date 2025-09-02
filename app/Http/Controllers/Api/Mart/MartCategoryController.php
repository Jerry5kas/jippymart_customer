<?php

namespace App\Http\Controllers\Api\Mart;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

class MartCategoryController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Get all layouts categories with enhanced filtering
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'publish' => 'sometimes|boolean',
            'show_in_homepage' => 'sometimes|boolean',
            'search' => 'sometimes|string|max:100',
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:100',
            'sort_by' => 'sometimes|string|in:title,category_order,section_order',
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

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;
            $search = $request->search ?? null;
            $sortBy = $request->sort_by ?? 'title';
            $sortOrder = $request->sort_order ?? 'asc';

            $categories = $this->firebaseService->getMartCategoriesWithPagination(
                $filters,
                $search,
                $page,
                $limit,
                $sortBy,
                $sortOrder
            );

            return response()->json([
                'success' => true,
                'data' => $categories['data'],
                'meta' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $categories['total'],
                    'has_more' => $categories['has_more'],
                    'filters_applied' => $filters,
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartCategoryController@index: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get categories: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific layouts category by ID
     *
     * @param Request $request
     * @param string $category_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $category_id)
    {
        // Validate category_id parameter
        if (empty($category_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Category ID is required'
            ], 422);
        }

        try {
            $categoryData = $this->firebaseService->getMartCategory($category_id);

            if (!$categoryData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $categoryData
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartCategoryController@show: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get category details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new layouts category
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'photo' => 'sometimes|string|url',
            'publish' => 'sometimes|boolean',
            'show_in_homepage' => 'sometimes|boolean',
            'has_subcategories' => 'sometimes|boolean',
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

            $categoryData = [
                'title' => $request->title,
                'description' => $request->description,
                'photo' => $request->photo ?? '',
                'publish' => $request->publish ?? true,
                'show_in_homepage' => $request->show_in_homepage ?? false,
                'has_subcategories' => $request->has_subcategories ?? false,
                'subcategories_count' => 0,
                'review_attributes' => $request->review_attributes ?? [],
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString(),
                'created_by' => $user->id
            ];

            $categoryId = $this->firebaseService->createMartCategory($categoryData);

            if (!$categoryId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create category'
                ], 500);
            }

            // Get the created category data
            $createdCategory = $this->firebaseService->getMartCategory($categoryId);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => $createdCategory
            ], 201);

        } catch (Exception $e) {
            \Log::error('Error in MartCategoryController@store: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing layouts category
     *
     * @param Request $request
     * @param string $category_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $category_id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:100',
            'description' => 'sometimes|string|max:500',
            'photo' => 'sometimes|string|url',
            'publish' => 'sometimes|boolean',
            'show_in_homepage' => 'sometimes|boolean',
            'has_subcategories' => 'sometimes|boolean',
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

            // Check if category exists
            $existingCategory = $this->firebaseService->getMartCategory($category_id);
            if (!$existingCategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            $updateData = $request->only([
                'title', 'description', 'photo', 'publish',
                'show_in_homepage', 'has_subcategories', 'review_attributes'
            ]);

            $updateData['updated_at'] = now()->toISOString();
            $updateData['updated_by'] = $user->id;

            $success = $this->firebaseService->updateMartCategory($category_id, $updateData);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update category'
                ], 500);
            }

            // Get the updated category data
            $updatedCategory = $this->firebaseService->getMartCategory($category_id);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => $updatedCategory
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartCategoryController@update: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a layouts category
     *
     * @param Request $request
     * @param string $category_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $category_id)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Check if category exists
            $existingCategory = $this->firebaseService->getMartCategory($category_id);
            if (!$existingCategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            // Check if category has subcategories
            if ($existingCategory['subcategories_count'] > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with subcategories'
                ], 400);
            }

            $success = $this->firebaseService->deleteMartCategory($category_id);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete category'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartCategoryController@destroy: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get categories for homepage
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHomepageCategories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'sometimes|integer|min:1|max:20'
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

            // First try with both filters (requires composite index)
            $filters = [
                'publish' => true,
                'show_in_homepage' => true
            ];

            $categories = $this->firebaseService->getMartCategoriesWithPagination(
                $filters,
                null,
                1,
                $limit,
                'title',
                'asc'
            );

            // If no results and it might be due to missing index, try fallback approach
            if (empty($categories['data']) && $categories['total'] === 0) {
                \Log::warning('No categories found with composite filter, trying fallback approach');

                // Simple fallback: Get all categories without filters and filter in PHP
                $allCategories = $this->firebaseService->getMartCategoriesWithPagination(
                    [], // No filters
                    null,
                    1,
                    20, // Reduced limit to avoid timeout
                    'title',
                    'asc'
                );

                // Filter for both publish and show_in_homepage in PHP
                $filteredCategories = array_filter($allCategories['data'], function($category) {
                    return isset($category['publish']) && $category['publish'] === true &&
                           isset($category['show_in_homepage']) && $category['show_in_homepage'] === true;
                });

                // Limit the results
                $filteredCategories = array_slice($filteredCategories, 0, $limit);

                $categories = [
                    'data' => array_values($filteredCategories),
                    'total' => count($filteredCategories),
                    'has_more' => false
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $categories['data'],
                'meta' => [
                    'total' => $categories['total'],
                    'limit' => $limit
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartCategoryController@getHomepageCategories: ' . $e->getMessage());

            // If it's an index error, try the fallback approach
            if (strpos($e->getMessage(), 'requires an index') !== false) {
                \Log::warning('Index error detected, trying fallback approach');

                try {
                    $limit = $request->limit ?? 10;

                    // Simple fallback: Get all categories without filters and filter in PHP
                    $allCategories = $this->firebaseService->getMartCategoriesWithPagination(
                        [], // No filters
                        null,
                        1,
                        20, // Reduced limit to avoid timeout
                        'title',
                        'asc'
                    );

                    // Filter for both publish and show_in_homepage in PHP
                    $filteredCategories = array_filter($allCategories['data'], function($category) {
                        return isset($category['publish']) && $category['publish'] === true &&
                               isset($category['show_in_homepage']) && $category['show_in_homepage'] === true;
                    });

                    // Limit the results
                    $filteredCategories = array_slice($filteredCategories, 0, $limit);

                    return response()->json([
                        'success' => true,
                        'data' => array_values($filteredCategories),
                        'meta' => [
                            'total' => count($filteredCategories),
                            'limit' => $limit,
                            'note' => 'Using fallback query due to missing Firebase index'
                        ]
                    ]);
                } catch (Exception $fallbackError) {
                    \Log::error('Fallback approach also failed: ' . $fallbackError->getMessage());
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to get homepage categories: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search categories by title or description
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2|max:100',
            'publish' => 'sometimes|boolean',
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

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 20;

            $results = $this->firebaseService->searchMartCategories(
                $request->input('query'),
                $filters,
                $page,
                $limit
            );

            // If no results, try fallback approach
            if (empty($results['data']) && $results['total'] === 0) {
                \Log::warning('No categories found with search query, trying fallback approach');

                // Fallback: Get all categories and filter in PHP
                $allCategories = $this->firebaseService->getMartCategoriesWithPagination(
                    [], // No filters
                    null,
                    1,
                    50, // Get more to filter from
                    'title',
                    'asc'
                );

                // Filter categories in PHP based on search query and filters
                $filteredCategories = array_filter($allCategories['data'], function($category) use ($request, $filters) {
                    $query = strtolower($request->input('query'));
                    $title = strtolower($category['title'] ?? '');

                    // Check if title contains the search query
                    $matchesQuery = strpos($title, $query) !== false;

                    // Apply publish filter if requested
                    $matchesPublish = true;
                    if (isset($filters['publish'])) {
                        $matchesPublish = isset($category['publish']) && $category['publish'] === $filters['publish'];
                    }

                    return $matchesQuery && $matchesPublish;
                });

                // Apply pagination
                $offset = ($page - 1) * $limit;
                $paginatedCategories = array_slice($filteredCategories, $offset, $limit);

                $results = [
                    'data' => array_values($paginatedCategories),
                    'total' => count($filteredCategories),
                    'has_more' => count($filteredCategories) > ($offset + $limit)
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
            \Log::error('Error in MartCategoryController@search: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to search categories: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get categories with subcategories
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoriesWithSubcategories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'publish' => 'sometimes|boolean',
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

            $filters = [
                'has_subcategories' => true
            ];

            if ($request->has('publish')) {
                $filters['publish'] = $request->publish;
            }

            $categories = $this->firebaseService->getMartCategoriesWithPagination(
                $filters,
                null,
                $page,
                $limit,
                'title',
                'asc'
            );

            // If no results and it might be due to missing index, try fallback approach
            if (empty($categories['data']) && $categories['total'] === 0) {
                \Log::warning('No categories found with subcategories filter, trying fallback approach');

                // Simple fallback: Get all categories without filters and filter in PHP
                $allCategories = $this->firebaseService->getMartCategoriesWithPagination(
                    [], // No filters
                    null,
                    1,
                    50, // Get more to filter from
                    'title',
                    'asc'
                );

                // Filter for has_subcategories in PHP
                $filteredCategories = array_filter($allCategories['data'], function($category) use ($request) {
                    $hasSubcategories = isset($category['has_subcategories']) && $category['has_subcategories'] === true;

                    // Apply publish filter if requested
                    if ($request->has('publish')) {
                        $isPublished = isset($category['publish']) && $category['publish'] === $request->publish;
                        return $hasSubcategories && $isPublished;
                    }

                    return $hasSubcategories;
                });

                // Apply pagination
                $offset = ($page - 1) * $limit;
                $filteredCategories = array_slice($filteredCategories, $offset, $limit);

                $categories = [
                    'data' => array_values($filteredCategories),
                    'total' => count($filteredCategories),
                    'has_more' => false
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $categories['data'],
                'meta' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $categories['total'],
                    'has_more' => $categories['has_more']
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartCategoryController@getCategoriesWithSubcategories: ' . $e->getMessage());

            // If it's an index error, try the fallback approach
            if (strpos($e->getMessage(), 'requires an index') !== false) {
                \Log::warning('Index error detected for subcategories, trying fallback approach');

                try {
                    $page = $request->page ?? 1;
                    $limit = $request->limit ?? 20;

                    // Simple fallback: Get all categories without filters and filter in PHP
                    $allCategories = $this->firebaseService->getMartCategoriesWithPagination(
                        [], // No filters
                        null,
                        1,
                        50, // Get more to filter from
                        'title',
                        'asc'
                    );

                    // Filter for has_subcategories in PHP
                    $filteredCategories = array_filter($allCategories['data'], function($category) use ($request) {
                        $hasSubcategories = isset($category['has_subcategories']) && $category['has_subcategories'] === true;

                        // Apply publish filter if requested
                        if ($request->has('publish')) {
                            $isPublished = isset($category['publish']) && $category['publish'] === $request->publish;
                            return $hasSubcategories && $isPublished;
                        }

                        return $hasSubcategories;
                    });

                    // Apply pagination
                    $offset = ($page - 1) * $limit;
                    $filteredCategories = array_slice($filteredCategories, $offset, $limit);

                    return response()->json([
                        'success' => true,
                        'data' => array_values($filteredCategories),
                        'meta' => [
                            'current_page' => $page,
                            'per_page' => $limit,
                            'total' => count($filteredCategories),
                            'has_more' => false,
                            'note' => 'Using fallback query due to missing Firebase index'
                        ]
                    ]);
                } catch (Exception $fallbackError) {
                    \Log::error('Fallback approach also failed for subcategories: ' . $fallbackError->getMessage());
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to get categories with subcategories: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update categories
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'string',
            'updates' => 'required|array',
            'updates.publish' => 'sometimes|boolean',
            'updates.show_in_homepage' => 'sometimes|boolean'
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

            $results = $this->firebaseService->bulkUpdateMartCategories(
                $request->category_ids,
                $updateData
            );

            return response()->json([
                'success' => true,
                'message' => 'Categories updated successfully',
                'data' => [
                    'updated_count' => $results['updated'],
                    'failed_count' => $results['failed'],
                    'failed_ids' => $results['failed_ids']
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartCategoryController@bulkUpdate: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to bulk update categories: ' . $e->getMessage()
            ], 500);
        }
    }
}
