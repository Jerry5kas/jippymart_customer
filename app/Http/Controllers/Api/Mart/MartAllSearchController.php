<?php

namespace App\Http\Controllers\Api\Mart;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Exception;

class MartAllSearchController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function search(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'query' => 'required|string|min:1',
                'search_type' => 'string|in:all,items,categories,subcategories,vendors',
                'category_id' => 'string',
                'vendor_id' => 'string',
                'publish' => 'boolean',
                'show_in_homepage' => 'boolean',
                'is_available' => 'boolean',
                'page' => 'integer|min:1',
                'limit' => 'integer|min:1|max:100'
            ]);

            $query = $request->input('query');
            $searchType = $request->input('search_type', 'all');
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 20);
            $publish = $request->input('publish', true);
            $showInHomepage = $request->input('show_in_homepage');
            $isAvailable = $request->input('is_available');
            $categoryId = $request->input('category_id');
            $vendorId = $request->input('vendor_id');

            \Log::info("Starting global search for query: '$query' with type: $searchType");

            $results = [];
            $totalResults = 0;
            $searchErrors = [];

            // Search Items (Priority 1)
            if ($searchType === 'all' || $searchType === 'items') {
                try {
                    \Log::info("Searching items for query: '$query'");
                    $itemsResult = $this->executeSearchWithTimeout(
                        'items',
                        function() use ($query, $page, $limit, $publish, $isAvailable, $categoryId, $vendorId) {
                            return $this->firebaseService->searchMartItems(
                                $query,
                                $page,
                                $limit,
                                $publish,
                                $isAvailable,
                                $categoryId,
                                $vendorId
                            );
                        },
                        15 // 15 second timeout for items search
                    );
                    
                    $results['items'] = $itemsResult['data'];
                    $totalResults += $itemsResult['data']['total'] ?? 0;
                    if ($itemsResult['error']) {
                        $searchErrors[] = 'Items: ' . $itemsResult['error'];
                    }
                    \Log::info("Items search completed successfully");
                } catch (\Exception $e) {
                    \Log::error("Items search failed: " . $e->getMessage());
                    $searchErrors[] = 'Items: ' . $e->getMessage();
                    $results['items'] = [
                        'data' => [],
                        'total' => 0,
                        'has_more' => false,
                        'note' => 'Items search failed due to missing Firebase indexes'
                    ];
                }
            }

            // Search Categories (Priority 2)
            if ($searchType === 'all' || $searchType === 'categories') {
                try {
                    \Log::info("Searching categories for query: '$query'");
                    $categoriesResult = $this->executeSearchWithTimeout(
                        'categories',
                        function() use ($query, $page, $limit, $publish, $showInHomepage) {
                            return $this->firebaseService->searchMartCategories(
                                $query,
                                $page,
                                $limit,
                                $publish,
                                $showInHomepage
                            );
                        },
                        10 // 10 second timeout for categories search
                    );
                    
                    $results['categories'] = $categoriesResult['data'];
                    $totalResults += $categoriesResult['data']['total'] ?? 0;
                    if ($categoriesResult['error']) {
                        $searchErrors[] = 'Categories: ' . $categoriesResult['error'];
                    }
                    \Log::info("Categories search completed successfully");
                } catch (\Exception $e) {
                    \Log::error("Categories search failed: " . $e->getMessage());
                    $searchErrors[] = 'Categories: ' . $e->getMessage();
                    $results['categories'] = [
                        'data' => [],
                        'total' => 0,
                        'has_more' => false,
                        'note' => 'Categories search failed due to missing Firebase indexes'
                    ];
                }
            }

            // Search Subcategories (Priority 3)
            if ($searchType === 'all' || $searchType === 'subcategories') {
                try {
                    \Log::info("Searching subcategories for query: '$query'");
                    $subcategoriesResult = $this->executeSearchWithTimeout(
                        'subcategories',
                        function() use ($query, $page, $limit, $publish, $showInHomepage, $categoryId) {
                            return $this->firebaseService->searchMartSubcategories(
                                $query,
                                $page,
                                $limit,
                                $publish,
                                $showInHomepage,
                                $categoryId
                            );
                        },
                        10 // 10 second timeout for subcategories search
                    );
                    
                    $results['subcategories'] = $subcategoriesResult['data'];
                    $totalResults += $subcategoriesResult['data']['total'] ?? 0;
                    if ($subcategoriesResult['error']) {
                        $searchErrors[] = 'Subcategories: ' . $subcategoriesResult['error'];
                    }
                    \Log::info("Subcategories search completed successfully");
                } catch (\Exception $e) {
                    \Log::error("Subcategories search failed: " . $e->getMessage());
                    $searchErrors[] = 'Subcategories: ' . $e->getMessage();
                    $results['subcategories'] = [
                        'data' => [],
                        'total' => 0,
                        'has_more' => false,
                        'note' => 'Subcategories search failed due to missing Firebase indexes'
                    ];
                }
            }

            // Search Vendors (Priority 4)
            if ($searchType === 'all' || $searchType === 'vendors') {
                try {
                    \Log::info("Searching vendors for query: '$query'");
                    $vendorsResult = $this->executeSearchWithTimeout(
                        'vendors',
                        function() use ($query, $page, $limit, $publish, $categoryId) {
                            return $this->firebaseService->searchMartVendors(
                                $query,
                                $page,
                                $limit,
                                $publish,
                                $categoryId
                            );
                        },
                        10 // 10 second timeout for vendors search
                    );
                    
                    $results['vendors'] = $vendorsResult['data'];
                    $totalResults += $vendorsResult['data']['total'] ?? 0;
                    if ($vendorsResult['error']) {
                        $searchErrors[] = 'Vendors: ' . $vendorsResult['error'];
                    }
                    \Log::info("Vendors search completed successfully");
                } catch (\Exception $e) {
                    \Log::error("Vendors search failed: " . $e->getMessage());
                    $searchErrors[] = 'Vendors: ' . $e->getMessage();
                    $results['vendors'] = [
                        'data' => [],
                        'total' => 0,
                        'has_more' => false,
                        'note' => 'Vendors search failed due to missing Firebase indexes'
                    ];
                }
            }

            // Build meta information
            $meta = [
                'query' => $query,
                'search_type' => $searchType,
                'current_page' => $page,
                'per_page' => $limit,
                'total_results' => $totalResults,
                'priority_order' => 'Items > Categories > Subcategories > Vendors',
                'status' => empty($searchErrors) ? 'Search completed successfully' : 'Search completed with some errors',
                'search_insights' => [
                    'items_found' => $results['items']['total'] ?? 0,
                    'categories_found' => $results['categories']['total'] ?? 0,
                    'subcategories_found' => $results['subcategories']['total'] ?? 0,
                    'vendors_found' => $results['vendors']['total'] ?? 0,
                    'search_suggestions' => $this->generateSearchSuggestions($query, $totalResults)
                ]
            ];

            // Add filters applied
            $filters = [];
            if ($publish !== null) $filters['publish'] = $publish;
            if ($showInHomepage !== null) $filters['show_in_homepage'] = $showInHomepage;
            if ($isAvailable !== null) $filters['is_available'] = $isAvailable;
            if ($categoryId) $filters['category_id'] = $categoryId;
            if ($vendorId) $filters['vendor_id'] = $vendorId;
            
            if (!empty($filters)) {
                $meta['filters_applied'] = $filters;
            }

            // Add search errors if any
            if (!empty($searchErrors)) {
                $meta['search_errors'] = $searchErrors;
                $meta['firebase_index_note'] = 'Some searches failed due to missing Firebase indexes. Please create the required indexes for optimal performance.';
            }

            $results['meta'] = $meta;

            \Log::info("Global search completed successfully for query: '$query'");

            return response()->json([
                'success' => true,
                'data' => $results
            ]);

        } catch (Exception $e) {
            \Log::error('Error in MartAllSearchController@search: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform global search: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Execute a search function with timeout protection
     */
    private function executeSearchWithTimeout(string $searchType, callable $searchFunction, int $timeoutSeconds = 10)
    {
        try {
            // Set a custom timeout for this specific operation
            $originalTimeout = ini_get('max_execution_time');
            set_time_limit($timeoutSeconds);

            $startTime = microtime(true);
            
            $result = $searchFunction();
            
            $executionTime = microtime(true) - $startTime;
            
            // Reset timeout to original value
            set_time_limit($originalTimeout);
            
            \Log::info("$searchType search completed in " . round($executionTime, 2) . " seconds");
            
            return [
                'data' => $result,
                'error' => null,
                'execution_time' => round($executionTime, 2)
            ];
            
        } catch (Exception $e) {
            // Reset timeout to original value
            set_time_limit($originalTimeout);
            
            $errorMessage = $e->getMessage();
            \Log::warning("$searchType search failed: $errorMessage");
            
            // Check if it's a Firebase index error
            if (strpos($errorMessage, 'FAILED_PRECONDITION') !== false || 
                strpos($errorMessage, 'requires an index') !== false) {
                $errorMessage = 'Firebase index required - query cannot execute without proper indexes';
            }
            
            // Return fallback data
            $fallbackData = [
                'data' => [],
                'total' => 0,
                'has_more' => false,
                'note' => "$searchType search failed: $errorMessage",
                'requires_firebase_index' => true
            ];
            
            return [
                'data' => $fallbackData,
                'error' => $errorMessage,
                'execution_time' => 0
            ];
        }
    }

    private function generateSearchSuggestions($query, $totalResults)
    {
        $suggestions = [];
        
        if ($totalResults === 0) {
            $suggestions[] = "No results found for '$query'";
            $suggestions[] = "Try different keywords or broader search terms";
            $suggestions[] = "Check spelling and try synonyms";
        } else {
            $suggestions[] = "Found $totalResults results for '$query'";
            $suggestions[] = "Results are prioritized: Items > Categories > Subcategories > Vendors";
        }
        
        $suggestions[] = "Use search_type parameter to focus on specific entity types";
        $suggestions[] = "Add filters like category_id or vendor_id for more targeted results";
        
        return $suggestions;
    }
}
