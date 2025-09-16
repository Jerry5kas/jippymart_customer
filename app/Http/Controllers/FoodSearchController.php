<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Services\RateLimitService;
use Illuminate\Http\JsonResponse;

class FoodSearchController extends Controller
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
     * Search food items API endpoint with rate limiting and fallbacks
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchFoodItems(Request $request): JsonResponse
    {
        $startTime = microtime(true);
        $clientIp = $request->ip();
        $userAgent = $request->userAgent();
        $rateLimitKey = 'food_search_' . md5($clientIp . $userAgent);

        try {
            // Optimized rate limiting check
            $rateLimitRequests = 100; // Increased from 60 to 100 requests per minute
            $rateLimitWindow = 60; // 1 minute window

            if (!$this->rateLimitService->isAllowed($rateLimitKey, $rateLimitRequests, $rateLimitWindow)) {
                $rateLimitInfo = $this->rateLimitService->getInfo($rateLimitKey, $rateLimitWindow);

                return response()->json([
                    'success' => false,
                    'message' => 'Rate limit exceeded. Please try again later.',
                    'rate_limit' => $rateLimitInfo,
                    'retry_after' => $rateLimitWindow
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

            // Search food items using Firebase service
            $result = $this->firebaseService->searchFoodItems($searchTerm, $limit, $offset);

            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            $rateLimitInfo = $this->rateLimitService->getInfo($rateLimitKey, $rateLimitWindow);

            $response = [
                'success' => true,
                'message' => 'Food items retrieved successfully',
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
                $response['message'] = 'Food items retrieved from fallback data';
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
            \Log::error('Food search error: ' . $e->getMessage(), [
                'search_term' => $searchTerm ?? '',
                'page' => $page ?? 1,
                'limit' => $limit ?? 20,
                'client_ip' => $clientIp,
                'user_agent' => $userAgent
            ]);

            // Return fallback data even on errors
            $fallbackResult = $this->getFallbackFoodResponse($searchTerm ?? '', $limit ?? 20, $offset ?? 0);

            return response()->json([
                'success' => true,
                'message' => 'Food items retrieved from fallback data due to service error',
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
    private function getFallbackFoodResponse(string $searchTerm, int $limit, int $offset): array
    {
        $fallbackData = [
            [
                'id' => 'fallback_food_1',
                'name' => 'Chicken Biryani',
                'description' => 'Aromatic basmati rice with tender chicken pieces',
                'price' => 250,
                'disPrice' => 220,
                'photo' => '/img/food1.jpg',
                'categoryTitle' => 'Main Course',
                'subcategoryTitle' => 'Biryani',
                'vendorTitle' => 'Spice Kitchen',
                'isAvailable' => true,
                'isBestSeller' => true,
                'isFeature' => true,
                'veg' => false,
                'cuisine' => 'Indian'
            ],
            [
                'id' => 'fallback_food_2',
                'name' => 'Margherita Pizza',
                'description' => 'Classic pizza with tomato sauce, mozzarella, and basil',
                'price' => 180,
                'disPrice' => 160,
                'photo' => '/img/food2.jpg',
                'categoryTitle' => 'Italian',
                'subcategoryTitle' => 'Pizza',
                'vendorTitle' => 'Pizza Corner',
                'isAvailable' => true,
                'isBestSeller' => false,
                'isFeature' => true,
                'veg' => true,
                'cuisine' => 'Italian'
            ],
            [
                'id' => 'fallback_food_3',
                'name' => 'Chicken Burger',
                'description' => 'Juicy chicken patty with fresh vegetables and sauce',
                'price' => 120,
                'disPrice' => 100,
                'photo' => '/img/food3.jpg',
                'categoryTitle' => 'Fast Food',
                'subcategoryTitle' => 'Burgers',
                'vendorTitle' => 'Burger House',
                'isAvailable' => true,
                'isBestSeller' => true,
                'isFeature' => false,
                'veg' => false,
                'cuisine' => 'American'
            ]
        ];

        // Filter by search term if provided
        if (!empty($searchTerm)) {
            $searchTerm = strtolower(trim($searchTerm));
            $fallbackData = array_filter($fallbackData, function($item) use ($searchTerm) {
                $name = strtolower($item['name'] ?? '');
                $description = strtolower($item['description'] ?? '');
                $category = strtolower($item['categoryTitle'] ?? '');
                $cuisine = strtolower($item['cuisine'] ?? '');
                return strpos($name, $searchTerm) !== false ||
                       strpos($description, $searchTerm) !== false ||
                       strpos($category, $searchTerm) !== false ||
                       strpos($cuisine, $searchTerm) !== false;
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
}
