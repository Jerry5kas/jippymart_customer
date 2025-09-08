<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Services\RateLimitService;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    protected $firebaseService;
    protected $rateLimitService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FirebaseService $firebaseService, RateLimitService $rateLimitService)
    {
        $this->firebaseService = $firebaseService;
        $this->rateLimitService = $rateLimitService;
        
        // Only apply location check for web routes, not API routes
        if (!request()->is('api/*') && !isset($_COOKIE['address_name'])) {
            \Redirect::to('set-location')->send();
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('search.search');
    }

    /**
     * Search categories API endpoint with rate limiting and fallbacks
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchCategories(Request $request): JsonResponse
    {
        $startTime = microtime(true);
        $clientIp = $request->ip();
        $userAgent = $request->userAgent();
        $rateLimitKey = 'search_' . md5($clientIp . $userAgent);
        
        try {
            // Rate limiting check
            if (!$this->rateLimitService->isAllowed($rateLimitKey, 60, 60)) { // 60 requests per minute
                $rateLimitInfo = $this->rateLimitService->getInfo($rateLimitKey, 60);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Rate limit exceeded. Please try again later.',
                    'rate_limit' => $rateLimitInfo,
                    'retry_after' => 60
                ], 429);
            }
            
            // Validate request parameters
            $request->validate([
                'q' => 'nullable|string|max:100',
                'page' => 'nullable|integer|min:1|max:100',
                'limit' => 'nullable|integer|min:1|max:50'
            ]);

            $searchTerm = $request->input('q', '');
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 20);
            
            // Calculate offset for pagination
            $offset = ($page - 1) * $limit;

            // Search categories using Firebase service
            $result = $this->firebaseService->searchCategories($searchTerm, $limit, $offset);
            
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            $rateLimitInfo = $this->rateLimitService->getInfo($rateLimitKey, 60);

            $response = [
                'success' => true,
                'message' => 'Categories retrieved successfully',
                'data' => $result['data'],
                'pagination' => [
                    'current_page' => $result['current_page'],
                    'per_page' => $result['per_page'],
                    'total' => $result['total'],
                    'has_more' => $result['has_more']
                ],
                'search_term' => $searchTerm,
                'response_time_ms' => $responseTime,
                'rate_limit' => $rateLimitInfo
            ];
            
            // Add fallback indicator if using fallback data
            if (isset($result['fallback']) && $result['fallback']) {
                $response['fallback'] = true;
                $response['message'] = 'Categories retrieved from fallback data';
            }

            return response()->json($response, 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
                'response_time_ms' => round((microtime(true) - $startTime) * 1000, 2)
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Category search error: ' . $e->getMessage(), [
                'search_term' => $searchTerm ?? '',
                'page' => $page ?? 1,
                'limit' => $limit ?? 20,
                'client_ip' => $clientIp,
                'user_agent' => $userAgent
            ]);
            
            // Return fallback data even on errors
            $fallbackResult = $this->getFallbackResponse($searchTerm ?? '', $limit ?? 20, $offset ?? 0);
            
            return response()->json([
                'success' => true,
                'message' => 'Categories retrieved from fallback data due to service error',
                'data' => $fallbackResult['data'],
                'pagination' => $fallbackResult['pagination'],
                'search_term' => $searchTerm ?? '',
                'fallback' => true,
                'response_time_ms' => round((microtime(true) - $startTime) * 1000, 2)
            ], 200); // Return 200 with fallback data instead of 500
        }
    }
    
    /**
     * Get fallback response when all else fails
     *
     * @param string $searchTerm
     * @param int $limit
     * @param int $offset
     * @return array
     */
    private function getFallbackResponse(string $searchTerm, int $limit, int $offset): array
    {
        $fallbackData = [
            [
                'id' => 'fallback_1',
                'title' => 'Groceries',
                'description' => 'Fresh groceries and daily essentials',
                'photo' => 'https://via.placeholder.com/150x150?text=Groceries',
                'section' => 'Grocery & Kitchen',
                'category_order' => 1,
                'section_order' => 1,
                'show_in_homepage' => true
            ],
            [
                'id' => 'fallback_2',
                'title' => 'Medicine',
                'description' => 'Health and wellness products',
                'photo' => 'https://via.placeholder.com/150x150?text=Medicine',
                'section' => 'Pharmacy & Health',
                'category_order' => 2,
                'section_order' => 2,
                'show_in_homepage' => true
            ],
            [
                'id' => 'fallback_3',
                'title' => 'Pet Care',
                'description' => 'Pet supplies and care products',
                'photo' => 'https://via.placeholder.com/150x150?text=Pet+Care',
                'section' => 'Pet Care',
                'category_order' => 3,
                'section_order' => 3,
                'show_in_homepage' => true
            ]
        ];
        
        // Filter by search term if provided
        if (!empty($searchTerm)) {
            $searchTerm = strtolower(trim($searchTerm));
            $fallbackData = array_filter($fallbackData, function($category) use ($searchTerm) {
                $title = strtolower($category['title'] ?? '');
                $description = strtolower($category['description'] ?? '');
                return strpos($title, $searchTerm) !== false || strpos($description, $searchTerm) !== false;
            });
        }
        
        $totalCount = count($fallbackData);
        $paginatedResults = array_slice($fallbackData, $offset, $limit);
        
        return [
            'data' => $paginatedResults,
            'pagination' => [
                'current_page' => floor($offset / $limit) + 1,
                'per_page' => $limit,
                'total' => $totalCount,
                'has_more' => ($offset + $limit) < $totalCount
            ]
        ];
    }

    /**
     * Get all published categories for homepage
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPublishedCategories(Request $request): JsonResponse
    {
        try {
            // Validate request parameters
            $request->validate([
                'limit' => 'nullable|integer|min:1|max:100'
            ]);

            $limit = $request->input('limit', 50);

            // Get published categories using Firebase service
            $categories = $this->firebaseService->getPublishedCategories($limit);

            return response()->json([
                'success' => true,
                'message' => 'Published categories retrieved successfully',
                'data' => $categories,
                'count' => count($categories)
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Published categories error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching published categories',
                'data' => []
            ], 500);
        }
    }
    
    /**
     * Search mart items API endpoint with multiple filters
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchMartItems(Request $request): JsonResponse
    {
        $startTime = microtime(true);
        $clientIp = $request->ip();
        
        // Rate limiting
        $rateLimitKey = 'mart_items_search_' . md5($clientIp);
        if (!$this->rateLimitService->isAllowed($rateLimitKey, 60, 60)) {
            return response()->json([
                'success' => false,
                'message' => 'Rate limit exceeded. Please try again later.',
                'rate_limit' => $this->rateLimitService->getInfo($rateLimitKey, 60)
            ], 429);
        }

        // Validate request parameters
        $request->validate([
            'search' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'subcategory' => 'nullable|string|max:100',
            'vendor' => 'nullable|string|max:100',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'veg' => 'nullable|boolean',
            'isAvailable' => 'nullable|boolean',
            'isBestSeller' => 'nullable|boolean',
            'isFeature' => 'nullable|boolean',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        try {
            // Build filters array
            $filters = [];
            if ($request->filled('search')) $filters['search'] = $request->search;
            if ($request->filled('category')) $filters['category'] = $request->category;
            if ($request->filled('subcategory')) $filters['subcategory'] = $request->subcategory;
            if ($request->filled('vendor')) $filters['vendor'] = $request->vendor;
            if ($request->filled('min_price')) $filters['min_price'] = (float)$request->min_price;
            if ($request->filled('max_price')) $filters['max_price'] = (float)$request->max_price;
            if ($request->has('veg')) $filters['veg'] = (bool)$request->veg;
            if ($request->has('isAvailable')) $filters['isAvailable'] = (bool)$request->isAvailable;
            if ($request->has('isBestSeller')) $filters['isBestSeller'] = (bool)$request->isBestSeller;
            if ($request->has('isFeature')) $filters['isFeature'] = (bool)$request->isFeature;

            $page = $request->get('page', 1);
            $limit = $request->get('limit', 20);
            $offset = ($page - 1) * $limit;

            // Search mart items
            $result = $this->firebaseService->searchMartItems($filters, $limit, $offset);
            
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            $rateLimitInfo = $this->rateLimitService->getInfo($rateLimitKey, 60);

            return response()->json([
                'success' => true,
                'message' => 'Mart items retrieved successfully',
                'data' => $result['data'],
                'pagination' => [
                    'current_page' => $result['current_page'],
                    'per_page' => $result['per_page'],
                    'total' => $result['total'],
                    'has_more' => $result['has_more']
                ],
                'filters_applied' => $result['filters_applied'],
                'response_time_ms' => $responseTime,
                'rate_limit' => $rateLimitInfo,
                'fallback' => $result['fallback'] ?? false
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
                'fallback' => true
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Mart items search error: ' . $e->getMessage());
            
            // Return fallback data with 200 status for graceful degradation
            return response()->json([
                'success' => true,
                'message' => 'Mart items retrieved with fallback data',
                'data' => $this->getFallbackMartItemsResponse($request),
                'pagination' => [
                    'current_page' => 1,
                    'per_page' => 20,
                    'total' => 0,
                    'has_more' => false
                ],
                'filters_applied' => [],
                'fallback' => true
            ], 200);
        }
    }

    /**
     * Get featured mart items (best sellers, trending, etc.)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getFeaturedMartItems(Request $request): JsonResponse
    {
        $startTime = microtime(true);
        $clientIp = $request->ip();
        
        // Rate limiting
        $rateLimitKey = 'featured_items_' . md5($clientIp);
        if (!$this->rateLimitService->isAllowed($rateLimitKey, 30, 60)) {
            return response()->json([
                'success' => false,
                'message' => 'Rate limit exceeded. Please try again later.',
                'rate_limit' => $this->rateLimitService->getInfo($rateLimitKey, 60)
            ], 429);
        }

        $request->validate([
            'type' => 'nullable|string|in:best_seller,trending,featured,new,spotlight',
            'limit' => 'nullable|integer|min:1|max:50'
        ]);

        try {
            $type = $request->get('type', 'featured');
            $limit = $request->get('limit', 20);
            
            // Build filters based on type
            $filters = ['isAvailable' => true];
            switch ($type) {
                case 'best_seller':
                    $filters['isBestSeller'] = true;
                    break;
                case 'trending':
                    $filters['isTrending'] = true;
                    break;
                case 'featured':
                    $filters['isFeature'] = true;
                    break;
                case 'new':
                    $filters['isNew'] = true;
                    break;
                case 'spotlight':
                    $filters['isSpotlight'] = true;
                    break;
            }

            $result = $this->firebaseService->searchMartItems($filters, $limit, 0);
            
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            $rateLimitInfo = $this->rateLimitService->getInfo($rateLimitKey, 60);

            return response()->json([
                'success' => true,
                'message' => ucfirst($type) . ' items retrieved successfully',
                'data' => $result['data'],
                'type' => $type,
                'count' => count($result['data']),
                'response_time_ms' => $responseTime,
                'rate_limit' => $rateLimitInfo,
                'fallback' => $result['fallback'] ?? false
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Featured items error: ' . $e->getMessage());
            
            return response()->json([
                'success' => true,
                'message' => 'Featured items retrieved with fallback data',
                'data' => [],
                'type' => $request->get('type', 'featured'),
                'count' => 0,
                'fallback' => true
            ], 200);
        }
    }

    /**
     * Get fallback mart items response
     */
    private function getFallbackMartItemsResponse(Request $request): array
    {
        return [
            [
                'id' => 'fallback_item_1',
                'name' => 'Fresh Orange Juice',
                'description' => 'Freshly squeezed orange juice',
                'price' => 120,
                'disPrice' => 110,
                'photo' => 'https://via.placeholder.com/150',
                'categoryTitle' => 'Beverages (Non-Alcoholic)',
                'subcategoryTitle' => 'Juices',
                'vendorTitle' => 'Jippy Mart',
                'isAvailable' => true,
                'isBestSeller' => false,
                'isFeature' => true,
                'veg' => true
            ]
        ];
    }

    /**
     * Health check endpoint for monitoring
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function healthCheck(Request $request): JsonResponse
    {
        $startTime = microtime(true);
        $circuitBreakerStatus = null;
        
        try {
            // Test Firestore connection
            $firestoreStatus = 'unknown';
            
            // Use reflection to access circuit breaker
            $reflection = new \ReflectionClass($this->firebaseService);
            $circuitBreakerProperty = $reflection->getProperty('circuitBreaker');
            $circuitBreakerProperty->setAccessible(true);
            $circuitBreaker = $circuitBreakerProperty->getValue($this->firebaseService);
            $circuitBreakerStatus = $circuitBreaker->getStatus('firestore');
            
            // Use reflection to access firestore
            $firestoreProperty = $reflection->getProperty('firestore');
            $firestoreProperty->setAccessible(true);
            $firestore = $firestoreProperty->getValue($this->firebaseService);
            
            if ($firestore) {
                // Try a simple query
                $testQuery = $firestore->collection('mart_categories')->limit(1)->documents();
                $firestoreStatus = 'healthy';
            } else {
                $firestoreStatus = 'unavailable';
            }
            
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            
            return response()->json([
                'status' => 'healthy',
                'timestamp' => now()->toISOString(),
                'services' => [
                    'firestore' => $firestoreStatus,
                    'circuit_breaker' => $circuitBreakerStatus
                ],
                'response_time_ms' => $responseTime,
                'version' => '1.0.0'
            ], 200);
            
        } catch (\Exception $e) {
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Try to get circuit breaker status even on error
            if (!$circuitBreakerStatus) {
                try {
                    $reflection = new \ReflectionClass($this->firebaseService);
                    $circuitBreakerProperty = $reflection->getProperty('circuitBreaker');
                    $circuitBreakerProperty->setAccessible(true);
                    $circuitBreaker = $circuitBreakerProperty->getValue($this->firebaseService);
                    $circuitBreakerStatus = $circuitBreaker->getStatus('firestore');
                } catch (\Exception $cbError) {
                    $circuitBreakerStatus = ['state' => 'unknown', 'error' => $cbError->getMessage()];
                }
            }
            
            return response()->json([
                'status' => 'degraded',
                'timestamp' => now()->toISOString(),
                'services' => [
                    'firestore' => 'unhealthy',
                    'circuit_breaker' => $circuitBreakerStatus
                ],
                'error' => $e->getMessage(),
                'response_time_ms' => $responseTime,
                'version' => '1.0.0'
            ], 200); // Return 200 but with degraded status
        }
    }

}
