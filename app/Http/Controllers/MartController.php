<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Google\Cloud\Firestore\FirestoreClient;

class MartController extends Controller
{
    protected $firestore;

    public function __construct()
    {
        try {
            $this->firestore = new FirestoreClient([
                'projectId' => env('FIREBASE_PROJECT_ID'),
                'keyFilePath' => storage_path('app/firebase/credentials.json')
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to initialize Firestore client: ' . $e->getMessage());
            $this->firestore = null;
        }
    }

    /**
     * Get mart categories for homepage
     */
    public function getHomepageCategories()
    {
        return Cache::remember('mart_homepage_categories', 300, function () {
            if (!$this->firestore) {
                Log::warning('Firestore client not available, returning demo categories');
                return $this->getDemoCategories();
            }

            try {
                $query = $this->firestore->collection('mart_categories')
                    ->where('publish', '=', true)
                    ->where('show_in_homepage', '=', true)
                    ->orderBy('category_order');

                $documents = $query->documents();
                $categories = [];

                foreach ($documents as $document) {
                    $data = $document->data();
                    $data['id'] = $document->id();
                    $categories[] = $data;
                }

                Log::info('Successfully fetched ' . count($categories) . ' mart categories');
                return collect($categories)->sortBy('category_order')->values();
            } catch (\Exception $e) {
                Log::error('Error fetching mart categories: ' . $e->getMessage());
                return $this->getDemoCategories();
            }
        });
    }

    /**
     * Get mart subcategories for a specific category
     */
    public function getSubcategoriesByCategory($categoryId)
    {
        $cacheKey = "mart_subcategories_{$categoryId}";
        
        return Cache::remember($cacheKey, 300, function () use ($categoryId) {
            if (!$this->firestore) {
                Log::warning('Firestore client not available, returning demo subcategories');
                return $this->getDemoSubcategories($categoryId);
            }

            try {
                $query = $this->firestore->collection('mart_subcategories')
                    ->where('publish', '=', true)
                    ->where('show_in_homepage', '=', true)
                    ->where('parent_category_id', '=', $categoryId)
                    ->orderBy('subcategory_order');

                $documents = $query->documents();
                $subcategories = [];

                foreach ($documents as $document) {
                    $data = $document->data();
                    $data['id'] = $document->id();
                    // Add image loading state properties
                    $data['imageLoaded'] = false;
                    $data['imageError'] = false;
                    $subcategories[] = $data;
                }

                Log::info("Successfully fetched " . count($subcategories) . " subcategories for category {$categoryId}");
                return collect($subcategories)->sortBy('subcategory_order')->values();
            } catch (\Exception $e) {
                Log::error("Error fetching mart subcategories for category {$categoryId}: " . $e->getMessage());
                return $this->getDemoSubcategories($categoryId);
            }
        });
    }

    /**
     * Get all mart subcategories for homepage
     */
    public function getAllHomepageSubcategories()
    {
        return Cache::remember('mart_all_homepage_subcategories', 300, function () {
            if (!$this->firestore) {
                Log::warning('Firestore client not available, returning demo subcategories');
                return $this->getDemoSubcategories();
            }

            try {
                $query = $this->firestore->collection('mart_subcategories')
                    ->where('publish', '=', true)
                    ->where('show_in_homepage', '=', true)
                    ->orderBy('subcategory_order');

                $documents = $query->documents();
                $subcategories = [];

                foreach ($documents as $document) {
                    $data = $document->data();
                    $data['id'] = $document->id();
                    // Add image loading state properties
                    $data['imageLoaded'] = false;
                    $data['imageError'] = false;
                    $subcategories[] = $data;
                }

                Log::info('Successfully fetched ' . count($subcategories) . ' mart subcategories');
                return collect($subcategories)->sortBy('subcategory_order')->values();
            } catch (\Exception $e) {
                Log::error('Error fetching all mart subcategories: ' . $e->getMessage());
                return $this->getDemoSubcategories();
            }
        });
    }

    /**
     * Show mart index page with dynamic data
     */
    public function index()
    {
        try {
            $categories = $this->getHomepageCategories();
            $subcategories = $this->getAllHomepageSubcategories();
            
            Log::info('Mart index page loaded with ' . $categories->count() . ' categories and ' . $subcategories->count() . ' subcategories');
            
            return view('mart.index', compact('categories', 'subcategories'));
        } catch (\Exception $e) {
            Log::error('Error loading mart index page: ' . $e->getMessage());
            
            // Return view with empty collections as fallback
            return view('mart.index', [
                'categories' => collect(),
                'subcategories' => collect()
            ]);
        }
    }

    /**
     * Test method to verify data fetching
     */
    public function testData()
    {
        try {
            $categories = $this->getHomepageCategories();
            $subcategories = $this->getAllHomepageSubcategories();
            
            $result = [
                'success' => true,
                'timestamp' => now()->toISOString(),
                'categories' => [
                    'count' => $categories->count(),
                    'data' => $categories->toArray()
                ],
                'subcategories' => [
                    'count' => $subcategories->count(),
                    'data' => $subcategories->toArray()
                ],
                'firebase_status' => $this->firestore ? 'connected' : 'not_connected',
                'environment' => [
                    'project_id' => env('FIREBASE_PROJECT_ID'),
                    'credentials_file' => storage_path('app/firebase/credentials.json'),
                    'credentials_exist' => file_exists(storage_path('app/firebase/credentials.json'))
                ]
            ];
            
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Get demo categories for testing/fallback
     */
    private function getDemoCategories()
    {
        return collect([
            [
                'id' => 'demo-groceries',
                'title' => 'Groceries',
                'description' => 'Fresh groceries and kitchen essentials',
                'photo' => 'https://firebasestorage.googleapis.com/v0/b/jippymart-27c08.firebasestorage.app/o/images%2Fgroc_1756460123245.jpg?alt=media&token=68038522-7865-40dd-bf5f-0f8bc64db7c1',
                'publish' => true,
                'show_in_homepage' => true,
                'category_order' => 1,
                'section' => 'Grocery & Kitchen',
                'section_order' => 1,
                'review_attributes' => []
            ],
            [
                'id' => 'demo-fresh',
                'title' => 'Fresh',
                'description' => 'Fresh fruits, vegetables and dairy',
                'photo' => 'https://icon2.cleanpng.com/lnd/20250108/yj/011d1e60d8d65ba818e537fc0cf2d3.webp',
                'publish' => true,
                'show_in_homepage' => true,
                'category_order' => 2,
                'section' => 'Fresh Food',
                'section_order' => 2,
                'review_attributes' => []
            ],
            [
                'id' => 'demo-home',
                'title' => 'Home',
                'description' => 'Home and kitchen essentials',
                'photo' => 'https://icon2.cleanpng.com/20180629/sij/kisspng-atta-flour-aashirvaad-multigrain-bread-roti-whole-barely-5b3636ab6d17d2.0614586915302795954469.jpg',
                'publish' => true,
                'show_in_homepage' => true,
                'category_order' => 3,
                'section' => 'Home & Living',
                'section_order' => 3,
                'review_attributes' => []
            ]
        ]);
    }

    /**
     * Get demo subcategories for testing/fallback
     */
    private function getDemoSubcategories($categoryId = null)
    {
        $allSubcategories = [
            [
                'id' => 'demo-veggies',
                'title' => 'Veggies',
                'description' => 'Fresh vegetables and greens',
                'photo' => 'https://firebasestorage.googleapis.com/v0/b/jippymart-27c08.firebasestorage.app/o/images%2Flogo%20jippy%20bike_1751542703043.jpg?alt=media&token=159c87a4-cc51-456f-a196-5848d9c83ee6',
                'publish' => true,
                'show_in_homepage' => true,
                'parent_category_id' => 'demo-groceries',
                'parent_category_title' => 'Groceries',
                'subcategory_order' => 1,
                'category_order' => 1,
                'section' => 'Grocery & Kitchen',
                'section_order' => 1,
                'review_attributes' => []
            ],
            [
                'id' => 'demo-fruits',
                'title' => 'Fruits',
                'description' => 'Fresh seasonal fruits',
                'photo' => 'https://icon2.cleanpng.com/lnd/20250108/yj/011d1e60d8d65ba818e537fc0cf2d3.webp',
                'publish' => true,
                'show_in_homepage' => true,
                'parent_category_id' => 'demo-fresh',
                'parent_category_title' => 'Fresh',
                'subcategory_order' => 1,
                'category_order' => 2,
                'section' => 'Fresh Food',
                'section_order' => 2,
                'review_attributes' => []
            ],
            [
                'id' => 'demo-dairy',
                'title' => 'Dairy',
                'description' => 'Fresh dairy products',
                'photo' => 'https://icon2.cleanpng.com/20180629/sij/kisspng-atta-flour-aashirvaad-multigrain-bread-roti-whole-barely-5b3636ab6d17d2.0614586915302795954469.jpg',
                'publish' => true,
                'show_in_homepage' => true,
                'parent_category_id' => 'demo-fresh',
                'parent_category_title' => 'Fresh',
                'subcategory_order' => 2,
                'category_order' => 2,
                'section' => 'Fresh Food',
                'section_order' => 2,
                'review_attributes' => []
            ],
            [
                'id' => 'demo-kitchen',
                'title' => 'Kitchen',
                'description' => 'Kitchen essentials and tools',
                'photo' => 'https://icon2.cleanpng.com/lnd/20250108/yj/011d1e60d8d65ba818e537fc0cf2d3.webp',
                'publish' => true,
                'show_in_homepage' => true,
                'parent_category_id' => 'demo-home',
                'parent_category_title' => 'Home',
                'subcategory_order' => 1,
                'category_order' => 3,
                'section' => 'Home & Living',
                'section_order' => 3,
                'review_attributes' => []
            ]
        ];

        if ($categoryId) {
            return collect($allSubcategories)->where('parent_category_id', $categoryId)->values();
        }

        return collect($allSubcategories);
    }
}
