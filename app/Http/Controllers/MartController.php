<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\FirebaseException;
class MartController extends Controller
{
    public function index()
    {
        // Set strict limits for shared hosting
        ini_set('memory_limit', '128M');
        set_time_limit(30); // 30 seconds max

        try {
            // Check if Firebase credentials exist
            $credentialsPath = base_path('storage/app/firebase/credentials.json');
            if (!file_exists($credentialsPath)) {
                \Log::error('Firebase credentials file not found at: ' . $credentialsPath);
                return $this->getFallbackData();
            }

            // Initialize Firebase with timeout and retry settings
            $factory = (new Factory)->withServiceAccount($credentialsPath);
            $firestore = $factory->createFirestore()->database();

            // Start performance monitoring
            $startTime = microtime(true);
            \Log::info('MartController: Starting data fetch');

        // =========================
        // 1️⃣ OPTIMIZED CATEGORIES & SUBCATEGORIES
        // =========================

        // Check execution time before heavy operations
        $startTime = microtime(true);
        $maxExecutionTime = 25; // 25 seconds max

        // Fetch all categories first
        $categoriesSnapshot = $firestore->collection('mart_categories')
            ->where('publish', '=', true)
            ->limit(50) // Limit for shared hosting
            ->documents();

        $categoryData = [];
        $categoryIds = [];

        foreach ($categoriesSnapshot as $category) {
            if (!$category->exists()) continue;
            $cat = $category->data();
            $categoryIds[] = $cat['id'];
            $categoryData[] = $cat;
        }

        // Check timeout before subcategories
        if ((microtime(true) - $startTime) > $maxExecutionTime) {
            \Log::warning('Timeout reached before subcategories, using fallback');
            return $this->getFallbackData();
        }

        // Fetch all subcategories in one query
        $subcategoriesSnapshot = $firestore->collection('mart_subcategories')
            ->where('publish', '=', true)
            ->limit(100) // Limit for shared hosting
            ->documents();

        $subcategoriesByParent = [];
        foreach ($subcategoriesSnapshot as $sub) {
            if ($sub->exists()) {
                $subData = $sub->data();
                $parentId = $subData['parent_category_id'] ?? null;
                if ($parentId && in_array($parentId, $categoryIds)) {
                    $subcategoriesByParent[$parentId][] = [
                        'id'    => $subData['id'] ?? null,
                        'title' => $subData['title'] ?? 'No Title',
                        'photo' => $subData['photo'] ?? '/img/pro1.jpg',
                    ];
                }
            }
        }



        // Attach subcategories to their parent categories
        foreach ($categoryData as &$cat) {
            $cat['subcategories'] = $subcategoriesByParent[$cat['id']] ?? [];
        }

        // Sort categories by subcategory count in descending order (categories with most subcategories first)
        usort($categoryData, function($a, $b) {
            $countA = count($a['subcategories'] ?? []);
            $countB = count($b['subcategories'] ?? []);
            return $countB <=> $countA; // Descending order
        });
        // Categories without ordering for maximum performance

        // =========================
        // 2️⃣ OPTIMIZED ITEMS QUERY - Single Query for All Products
        // =========================
        // Check timeout before items query
        if ((microtime(true) - $startTime) > $maxExecutionTime) {
            \Log::warning('Timeout reached before items query, using fallback');
            return $this->getFallbackData();
        }

        // Single optimized query to fetch all published items (reused for all product types)
        $itemsRef = $firestore->collection('mart_items');
        $query = $itemsRef->where('publish', '=', true)
                         ->limit(200); // Limit for shared hosting

        $documents = $query->documents();

        // Cache all items data to avoid multiple Firebase reads
        $allItems = [];
        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $allItems[] = [
                    'doc' => $doc,
                    'data' => $doc->data()
                ];
            }
        }

        // =========================
        // 3️⃣ Spotlight Products (using cached data)
        // =========================
        $products = [];
        foreach ($allItems as $item) {
            $doc = $item['doc'];
            $data = $item['data'];

                // Filter for spotlight and available products in PHP
                if (($data['isSpotlight'] ?? false) && ($data['isAvailable'] ?? false)) {
                    // Generate random rating between 4.0 and 5.0 if not present
                    $rating = $data['rating'] ?? round(4.0 + (mt_rand() / mt_getrandmax()) * 1.0, 1);
                    $reviews = $data['reviews'] ?? mt_rand(10, 500);

                    $products[] = [
                        'id' => $doc->id(),
                        'disPrice' => $data['disPrice'] ?? 0,
                        'name' => $data['name'] ?? 'Product',
                        'description' => $data['description'] ?? 'Product description',
                        'grams' => $data['grams'] ?? '200g',
                        'photo' => $data['photo'] ?? '',
                        'price' => $data['price'] ?? 0,
                    'rating' => $data['reviewSum'] ?? $rating,
                    'reviews' => $data['reviewCount'] ?? $reviews,
                        'section' => $data['section'] ?? 'General',
                    'subcategoryTitle' => !empty($data['subcategoryTitle']) ? $data['subcategoryTitle'] : 'General',
                    ];
            }
        }

        // =========================
        // 4️⃣ Featured Products (using cached data)
        // =========================
        $featuredProducts = [];
        foreach ($allItems as $item) {
            $doc = $item['doc'];
            $data = $item['data'];

                // Filter for featured and available products
                if (($data['isFeature'] ?? false) && ($data['isAvailable'] ?? false)) {
                $rating = $data['rating'] ?? round(4.0 + (mt_rand() / mt_getrandmax()) * 1.0, 1);
                $reviews = $data['reviews'] ?? mt_rand(10, 500);

                    $featuredProducts[] = [
                        'id' => $doc->id(),
                        'disPrice' => $data['disPrice'] ?? 0,
                        'name' => $data['name'] ?? 'Product',
                        'description' => $data['description'] ?? 'Product description',
                        'grams' => $data['grams'] ?? '200g',
                        'photo' => $data['photo'] ?? '',
                        'price' => $data['price'] ?? 0,
                    'rating' => $data['reviewSum'] ?? $rating,
                    'reviews' => $data['reviewCount'] ?? $reviews,
                        'section' => $data['section'] ?? 'General',
                    'subcategoryTitle' => !empty($data['subcategoryTitle']) ? $data['subcategoryTitle'] : 'General',
                        'categoryTitle' => $data['categoryTitle'] ?? 'Category',
                    ];
            }
        }
            // =========================
        // 5️⃣ Trending Products (using cached data)
// =========================
            $trendingProducts = [];
        foreach ($allItems as $item) {
            $doc = $item['doc'];
            $data = $item['data'];

                    if (($data['isTrending'] ?? false) && ($data['isAvailable'] ?? false)) {
                $rating = $data['rating'] ?? round(4.0 + (mt_rand() / mt_getrandmax()) * 1.0, 1);
                $reviews = $data['reviews'] ?? mt_rand(10, 500);

                        $trendingProducts[] = [
                            'id' => $doc->id(),
                            'disPrice' => $data['disPrice'] ?? 0,
                            'name' => $data['name'] ?? 'Product',
                            'description' => $data['description'] ?? 'Product description',
                            'grams' => $data['grams'] ?? '200g',
                            'photo' => $data['photo'] ?? '',
                            'price' => $data['price'] ?? 0,
                    'rating' => $data['reviewSum'] ?? $rating,
                    'reviews' => $data['reviewCount'] ?? $reviews,
                            'section' => $data['section'] ?? 'General',
                    'subcategoryTitle' => !empty($data['subcategoryTitle']) ? $data['subcategoryTitle'] : 'General',
                            'categoryTitle' => $data['categoryTitle'] ?? 'Category',
                        ];
                }
            }


            // =========================
        // 4️⃣ Banners (Top Position)
        // =========================
        $bannersSnapshot = $firestore->collection('mart_banners')
            ->where('position', '=', 'top')
            ->where('is_publish', '=', true)
            ->documents();

        $banners = [];
        foreach ($bannersSnapshot as $doc) {
            if ($doc->exists()) {
                $data = $doc->data();
                $banners[] = [
                    'id' => $data['id'] ?? $doc->id(),
                    'title' => $data['title'] ?? '',
                    'text' => $data['text'] ?? '',
                    'description' => $data['description'] ?? '',
                    'photo' => $data['photo'] ?? '',
                    'redirect_type' => $data['redirect_type'] ?? 'none',
                    'productId' => $data['productId'] ?? null,
                    'external_link' => $data['external_link'] ?? null,
                    'position' => $data['position'] ?? 'top',
                    'set_order' => $data['set_order'] ?? 0,
                    'is_publish' => $data['is_publish'] ?? false,
                ];
            }
        }

        // Sort banners by set_order ascending
        usort($banners, function($a, $b) {
            return ($a['set_order'] ?? 0) <=> ($b['set_order'] ?? 0);
        });
            // Best Seller Products
            $bestSellerProducts = [];
            foreach ($documents as $doc) {
                if ($doc->exists()) {
                    $data = $doc->data();

                    if (($data['isBestSeller'] ?? false) && ($data['isAvailable'] ?? false)) {

                        $rating = $data['rating'] ?? round(4.0 + (mt_rand() / mt_getrandmax()) * 1.0, 1);
                        $reviews = $data['reviews'] ?? mt_rand(10, 500);

                        $bestSellerProducts[] = [
                            'id' => $doc->id(),
                            'disPrice' => $data['disPrice'] ?? 0,
                            'name' => $data['name'] ?? 'Product',
                            'description' => $data['description'] ?? 'Product description',
                            'grams' => $data['grams'] ?? '200g',
                            'photo' => $data['photo'] ?? '',
                            'price' => $data['price'] ?? 0,
                            'rating' => $data['reviewSum'] ?? $rating,
                            'reviews' => $data['reviewCount'] ?? $reviews,
                            'section' => $data['section'] ?? 'General',
                            'subcategoryTitle' => $data['subcategoryTitle'] ?? 'category',
                            'categoryTitle' => $data['categoryTitle'] ?? 'Category',
                        ];
                    }
                }
            }


            // =========================
        // 5️⃣ Sections (Grouped Subcategories) - REUSE DATA
        // =========================
            $sections = [];
            $seenSubcategories = []; // Track seen subcategories to prevent duplicates

// Reuse the subcategories data we already fetched
            foreach ($subcategoriesSnapshot as $sub) {
                if (!$sub->exists()) continue;

                $subData = $sub->data();
                $sectionName = $subData['section'] ?? 'Others';
                $subcategoryId = $subData['id'] ?? null;

                if (!$subcategoryId) continue;

                // Prevent duplicates
                $subcategoryKey = $sectionName . '_' . $subcategoryId;
                if (isset($seenSubcategories[$subcategoryKey])) {
                    continue;
                }
                $seenSubcategories[$subcategoryKey] = true;

                if (!isset($sections[$sectionName])) {
                    $sections[$sectionName] = [];
                }

                $sections[$sectionName][] = [
                    'id'    => $subcategoryId,
                    'title' => $subData['title'] ?? 'No Title',
                    'photo' => $subData['photo'] ?? '/img/pro1.jpg',
                ];
            }

// ✅ Sort sections by subcategory count (descending)
            uasort($sections, function ($a, $b) {
                return count($b) <=> count($a);
            });

            // =========================
// Steal Of Moment, New Arrival & Seasonal Products
// =========================
            $stealOfMomentProducts = [];
            $newArrivalProducts = [];
            $seasonalProducts = [];

            foreach ($documents as $doc) {
                if ($doc->exists()) {
                    $data = $doc->data();

                    if (($data['isStealOfMoment'] ?? false) && ($data['isAvailable'] ?? false)) {

                        $rating = $data['rating'] ?? round(4.0 + (mt_rand() / mt_getrandmax()) * 1.0, 1);
                        $reviews = $data['reviews'] ?? mt_rand(10, 500);

                        $stealOfMomentProducts[] = [
                            'id' => $doc->id(),
                            'disPrice' => $data['disPrice'] ?? 0,
                            'name' => $data['name'] ?? 'Product',
                            'description' => $data['description'] ?? 'Product description',
                            'grams' => $data['grams'] ?? '200g',
                            'photo' => $data['photo'] ?? '',
                            'price' => $data['price'] ?? 0,
                            'rating' => $data['reviewSum'] ?? $rating,
                            'reviews' => $data['reviewCount'] ?? $reviews,
                            'section' => $data['section'] ?? 'General',
                            'subcategoryTitle' => $data['subcategoryTitle'] ?? 'category',
                            'categoryTitle' => $data['categoryTitle'] ?? 'Category',
                        ];
                    }

                    if (($data['isNew'] ?? false) && ($data['isAvailable'] ?? false)) {

                        $rating = $data['rating'] ?? round(4.0 + (mt_rand() / mt_getrandmax()) * 1.0, 1);
                        $reviews = $data['reviews'] ?? mt_rand(10, 500);

                        $newArrivalProducts[] = [
                            'id' => $doc->id(),
                            'disPrice' => $data['disPrice'] ?? 0,
                            'name' => $data['name'] ?? 'Product',
                            'description' => $data['description'] ?? 'Product description',
                            'grams' => $data['grams'] ?? '200g',
                            'photo' => $data['photo'] ?? '',
                            'price' => $data['price'] ?? 0,
                            'rating' => $data['reviewSum'] ?? $rating,
                            'reviews' => $data['reviewCount'] ?? $reviews,
                            'section' => $data['section'] ?? 'General',
                            'subcategoryTitle' => $data['subcategoryTitle'] ?? 'category',
                            'categoryTitle' => $data['categoryTitle'] ?? 'Category',
                        ];
                    }

                    if (($data['isSeasonal'] ?? false) && ($data['isAvailable'] ?? false)) {

                        $rating = $data['rating'] ?? round(4.0 + (mt_rand() / mt_getrandmax()) * 1.0, 1);
                        $reviews = $data['reviews'] ?? mt_rand(10, 500);

                        $seasonalProducts[] = [
                            'id' => $doc->id(),
                            'disPrice' => $data['disPrice'] ?? 0,
                            'name' => $data['name'] ?? 'Product',
                            'description' => $data['description'] ?? 'Product description',
                            'grams' => $data['grams'] ?? '200g',
                            'photo' => $data['photo'] ?? '',
                            'price' => $data['price'] ?? 0,
                            'rating' => $data['reviewSum'] ?? $rating,
                            'reviews' => $data['reviewCount'] ?? $reviews,
                            'section' => $data['section'] ?? 'General',
                            'subcategoryTitle' => $data['subcategoryTitle'] ?? 'category',
                            'categoryTitle' => $data['categoryTitle'] ?? 'Category',
                        ];
                    }
                }
            }


            // =========================
        // 6️⃣ Items Grouped by Sections (using cached data)
            // =========================
        $itemsBySection = [];

        // Group items by their section using cached data
        foreach ($allItems as $item) {
            $doc = $item['doc'];
            $data = $item['data'];

            // Only include available and published items
            if (($data['isAvailable'] ?? false) && ($data['publish'] ?? false)) {
                $sectionName = $data['section'] ?? 'Others';

                if (!isset($itemsBySection[$sectionName])) {
                    $itemsBySection[$sectionName] = [];
                }

                // Limit items per section to avoid performance issues
                if (count($itemsBySection[$sectionName]) < 20) {
                    $rating = $data['rating'] ?? round(4.0 + (mt_rand() / mt_getrandmax()) * 1.0, 1);
                    $reviews = $data['reviews'] ?? mt_rand(10, 500);

                    $itemsBySection[$sectionName][] = [
                        'id' => $doc->id(),
                        'disPrice' => $data['disPrice'] ?? 0,
                        'name' => $data['name'] ?? 'Product',
                        'description' => $data['description'] ?? 'Product description',
                        'grams' => $data['grams'] ?? '200g',
                        'photo' => $data['photo'] ?? '',
                        'price' => $data['price'] ?? 0,
                        'rating' => $data['reviewSum'] ?? $rating,
                        'reviews' => $data['reviewCount'] ?? $reviews,
                        'section' => $data['section'] ?? 'General',
                        'subcategoryTitle' => !empty($data['subcategoryTitle']) ? $data['subcategoryTitle'] : 'General',
                        'categoryTitle' => $data['categoryTitle'] ?? 'Category',
                        'isBestSeller' => $data['isBestSeller'] ?? false,
                        'isFeature' => $data['isFeature'] ?? false,
                        'isSpotlight' => $data['isSpotlight'] ?? false,
                        'isNew' => $data['isNew'] ?? false,
                        'veg' => $data['veg'] ?? true,
                        'nonveg' => $data['nonveg'] ?? false,
                        'quantity' => $data['quantity'] ?? 0,
                        'vendorID' => $data['vendorID'] ?? '',
                        'vendorTitle' => $data['vendorTitle'] ?? '',
                        'reviewSum' => $data['reviewSum'] ?? '',
                        'reviewCount' => $data['reviewCount'] ?? '',
                    ];
                }
            }
        }

            // Sort items within each section by name
            foreach ($itemsBySection as &$sectionItems) {
                usort($sectionItems, function($a, $b) {
                    return strcmp($a['name'], $b['name']);
                });
            }

            // =========================
            // 7️⃣ Performance Logging & Return to Blade
            // =========================
            $endTime = microtime(true);
            $executionTime = round(($endTime - $startTime) * 1000, 2);

            \Log::info("Mart data loaded in {$executionTime}ms: " . count($categoryData) . " categories, " . count($products) . " spotlight products, " . count($featuredProducts) . " featured products, " . count($banners) . " banners, " . count($itemsBySection) . " sections with items");

            // SEO data removed for performance optimization
            $seoData = [
                'title' => 'JippyMart - Fresh Groceries & Daily Essentials Delivered',
                'description' => 'Order fresh groceries, medicines, and daily essentials online. Fast delivery to your doorstep with quality guarantee.'
            ];

            return view('mart.index', [
                'categories' => $categoryData,
                'spotlight'  => $products,
                'featured'   => $featuredProducts,
                'banners'    => $banners,
                'sections'   => $sections,
                'itemsBySection' => $itemsBySection,
                'trendingProducts' => $trendingProducts,
                'bestSellerProducts' => $bestSellerProducts,
                'stealOfMomentProducts' => $stealOfMomentProducts,
                'newArrivalProducts' => $newArrivalProducts,
                'seasonalProducts' => $seasonalProducts,
                'seoData' => $seoData
            ]);

        } catch (FirebaseException $e) {
            \Log::error('Firebase error in MartController index method: ' . $e->getMessage());
            \Log::error('Firebase error details: ' . json_encode([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]));
            return $this->getFallbackData();
        } catch (\Exception $e) {
            \Log::error('General error in MartController index method: ' . $e->getMessage());
            return $this->getFallbackData();
        }
    }

    public function allItems(Request $request)
    {
        try {
            // Initialize Firebase
            $factory = (new Factory)->withServiceAccount(
                base_path('storage/app/firebase/credentials.json')
            );
            $firestore = $factory->createFirestore()->database();

            // Get filter parameters
            $categoryFilter = $request->get('category', '');
            $subcategoryFilter = $request->get('subcategory', '');
            $brandFilter = $request->get('brand', '');
            $priceMin = $request->get('price_min', '');
            $priceMax = $request->get('price_max', '');
            $sortBy = $request->get('sort', 'name'); // name, price_low, price_high, rating
            $search = $request->get('search', '');

            // Fetch all published and available items
            $itemsRef = $firestore->collection('mart_items');
            $query = $itemsRef->where('publish', '=', true)
                             ->where('isAvailable', '=', true);

            $documents = $query->documents();

            $items = [];
            $allBrands = []; // Collect all brands
            foreach ($documents as $doc) {
                if ($doc->exists()) {
                    $data = $doc->data();

                    // Collect brand for filter dropdown
                    if (!empty($data['brandTitle'])) {
                        $allBrands[$data['brandTitle']] = $data['brandTitle'];
                    }

                    // Apply filters
                    if (!empty($categoryFilter) && ($data['categoryTitle'] ?? '') !== $categoryFilter) {
                        continue;
                    }
                    if (!empty($subcategoryFilter) && ($data['subcategoryTitle'] ?? '') !== $subcategoryFilter) {
                        continue;
                    }
                    if (!empty($brandFilter) && ($data['brandTitle'] ?? '') !== $brandFilter) {
                        continue;
                    }
                    // Enhanced search: Search in name, description, and brandTitle
                    if (!empty($search)) {
                        $searchLower = strtolower($search);
                        $nameMatch = stripos($data['name'] ?? '', $search) !== false;
                        $descMatch = stripos($data['description'] ?? '', $search) !== false;
                        $brandMatch = stripos($data['brandTitle'] ?? '', $search) !== false;
                        
                        if (!$nameMatch && !$descMatch && !$brandMatch) {
                            continue;
                        }
                    }

                    $price = floatval($data['price'] ?? 0);
                    if (!empty($priceMin) && $price < floatval($priceMin)) {
                        continue;
                    }
                    if (!empty($priceMax) && $price > floatval($priceMax)) {
                        continue;
                    }

                    $items[] = [
                        'id' => $doc->id(),
                        'disPrice' => $data['disPrice'] ?? 0,
                        'name' => $data['name'] ?? 'Product',
                        'description' => $data['description'] ?? 'Product description',
                        'grams' => $data['grams'] ?? '200g',
                        'photo' => $data['photo'] ?? '',
                        'price' => $data['price'] ?? 0,
                        'section' => $data['section'] ?? 'General',
                        'subcategoryTitle' => !empty($data['subcategoryTitle']) ? $data['subcategoryTitle'] : 'General',
                        'categoryTitle' => $data['categoryTitle'] ?? 'Category',
                        'isBestSeller' => $data['isBestSeller'] ?? false,
                        'isFeature' => $data['isFeature'] ?? false,
                        'isSpotlight' => $data['isSpotlight'] ?? false,
                        'isNew' => $data['isNew'] ?? false,
                        'veg' => $data['veg'] ?? true,
                        'nonveg' => $data['nonveg'] ?? false,
                        'quantity' => $data['quantity'] ?? 0,
                        'vendorID' => $data['vendorID'] ?? '',
                        'vendorTitle' => $data['vendorTitle'] ?? '',
                        'brandID' => $data['brandID'] ?? '',
                        'brandTitle' => $data['brandTitle'] ?? '',
                        'reviewSum' => $data['reviewSum'] ?? 0,
                        'reviewCount' => $data['reviewCount'] ?? 0,
                    ];
                }
            }

            // Apply sorting
            switch ($sortBy) {
                case 'price_low':
                    usort($items, function($a, $b) {
                        return floatval($a['price']) <=> floatval($b['price']);
                    });
                    break;
                case 'price_high':
                    usort($items, function($a, $b) {
                        return floatval($b['price']) <=> floatval($a['price']);
                    });
                    break;
                case 'rating':
                    usort($items, function($a, $b) {
                        return floatval($b['reviewSum']) <=> floatval($a['reviewSum']);
                    });
                    break;
                default: // name
                    usort($items, function($a, $b) {
                        return strcmp($a['name'], $b['name']);
                    });
                    break;
            }

            // Get all categories for filter dropdown
            $categoriesSnapshot = $firestore->collection('mart_categories')
                ->where('publish', '=', true)
                ->documents();

            $categories = [];
            foreach ($categoriesSnapshot as $category) {
                if ($category->exists()) {
                    $cat = $category->data();
                    $categories[] = [
                        'id' => $cat['id'] ?? null,
                        'title' => $cat['title'] ?? 'No Title',
                    ];
                }
            }

            // Get all subcategories for filter dropdown
            $subcategoriesSnapshot = $firestore->collection('mart_subcategories')
                ->where('publish', '=', true)
                ->documents();

            $subcategories = [];
            foreach ($subcategoriesSnapshot as $sub) {
                if ($sub->exists()) {
                    $subData = $sub->data();
                    $subcategories[] = [
                        'id' => $subData['id'] ?? null,
                        'title' => $subData['title'] ?? 'No Title',
                        'parent_category_title' => $subData['parent_category_title'] ?? '',
                    ];
                }
            }

            // Sort brands alphabetically
            ksort($allBrands);
            
            \Log::info("All items loaded: " . count($items) . " items with filters applied");

            return view('mart.all-items', [
                'items' => $items,
                'categories' => $categories,
                'subcategories' => $subcategories,
                'brands' => array_values($allBrands),
                'filters' => [
                    'category' => $categoryFilter,
                    'subcategory' => $subcategoryFilter,
                    'brand' => $brandFilter,
                    'price_min' => $priceMin,
                    'price_max' => $priceMax,
                    'sort' => $sortBy,
                    'search' => $search,
                ],
                'totalItems' => count($items),
            ]);

        } catch (FirebaseException $e) {
            \Log::error('Firebase error in MartController allItems method: ' . $e->getMessage());
            return view('mart.all-items', [
                'items' => [],
                'categories' => [],
                'subcategories' => [],
                'brands' => [],
                'filters' => [
                    'category' => '',
                    'subcategory' => '',
                    'brand' => '',
                    'price_min' => '',
                    'price_max' => '',
                    'sort' => 'name',
                    'search' => '',
                ],
                'totalItems' => 0,
            ]);
        } catch (\Exception $e) {
            \Log::error('General error in MartController allItems method: ' . $e->getMessage());
            return view('mart.all-items', [
                'items' => [],
                'brands' => [],
                'categories' => [],
                'subcategories' => [],
                'filters' => [
                    'category' => '',
                    'subcategory' => '',
                    'price_min' => '',
                    'price_max' => '',
                    'sort' => 'name',
                    'search' => '',
                ],
                'totalItems' => 0,
            ]);
        }
    }

    public function itemsBySubcategory($subcategoryTitle)
    {
        try {
            // Initialize Firebase
            $factory = (new Factory)->withServiceAccount(
                base_path('storage/app/firebase/credentials.json')
            );
            $firestore = $factory->createFirestore()->database();

            // First, get category info from subcategory document directly (faster than querying items)
            $categoryTitle = '';
            $subcategoriesSnapshot = $firestore->collection('mart_subcategories')
                ->where('publish', '=', true)
                ->where('title', '=', $subcategoryTitle)
                ->documents();

            foreach ($subcategoriesSnapshot as $subDoc) {
                if ($subDoc->exists()) {
                    $subData = $subDoc->data();
                    $categoryTitle = $subData['parent_category_title'] ?? '';
                    break;
                }
            }

            // If still no category found, try to get from one item (fallback)
            if (empty($categoryTitle)) {
                $itemsRef = $firestore->collection('mart_items');
                $sampleQuery = $itemsRef->where('publish', '=', true)
                                       ->where('isAvailable', '=', true)
                                       ->where('subcategoryTitle', '=', $subcategoryTitle)
                                       ->limit(1);

                $sampleDocuments = $sampleQuery->documents();
                foreach ($sampleDocuments as $doc) {
                    if ($doc->exists()) {
                        $data = $doc->data();
                        $categoryTitle = $data['categoryTitle'] ?? '';
                        break;
                    }
                }
            }

            // Fetch ONLY items for this specific subcategory (much faster)
            $itemsRef = $firestore->collection('mart_items');
            $query = $itemsRef->where('publish', '=', true)
                              ->where('isAvailable', '=', true)
                              ->where('subcategoryTitle', '=', $subcategoryTitle);

            $documents = $query->documents();

            $items = [];
            foreach ($documents as $doc) {
                if ($doc->exists()) {
                    $data = $doc->data();

//                    // Generate random rating between 4.0 and 5.0 if not present
//                    $rating = $data['rating'] ?? round(4.0 + (mt_rand() / mt_getrandmax()) * 1.0, 1);
//                    $reviews = $data['reviews'] ?? mt_rand(10, 500);

                    $items[] = [
                        'id' => $doc->id(),
                        'disPrice' => $data['disPrice'] ?? 0,
                        'name' => $data['name'] ?? 'Product',
                        'description' => $data['description'] ?? 'Product description',
                        'grams' => $data['grams'] ?? '200g',
                        'photo' => $data['photo'] ?? '',
                        'price' => $data['price'] ?? 0,
//                        'rating' => $rating,
//                        'reviews' => $reviews,
                        'section' => $data['section'] ?? 'General',
                        'subcategoryTitle' => $data['subcategoryTitle'] ?? 'category',
                        'categoryTitle' => $data['categoryTitle'] ?? 'Category',
                        'isBestSeller' => $data['isBestSeller'] ?? false,
                        'isFeature' => $data['isFeature'] ?? false,
                        'isSpotlight' => $data['isSpotlight'] ?? false,
                        'isNew' => $data['isNew'] ?? false,
                        'veg' => $data['veg'] ?? true,
                        'nonveg' => $data['nonveg'] ?? false,
                        'quantity' => $data['quantity'] ?? 0,
                        'vendorID' => $data['vendorID'] ?? '',
                        'vendorTitle' => $data['vendorTitle'] ?? '',
                        'brandID' => $data['brandID'] ?? '',
                        'brandTitle' => $data['brandTitle'] ?? '',
                        'reviewSum' => $data['reviewSum'] ?? '',
                        'reviewCount' => $data['reviewCount'] ?? '',
                    ];
                }
            }

            // Sort items by set_order or name
            usort($items, function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });

            \Log::info("Items loaded for subcategory '{$subcategoryTitle}': " . count($items) . " items");

            // Get subcategories for sidebar (without counting items for performance)
            $subcategories = $this->getSubcategoriesForSidebar($firestore, $categoryTitle, $subcategoryTitle);

            \Log::info("Found " . count($subcategories) . " subcategories for category '{$categoryTitle}'");

            return view('mart.item-by-category', [
                'items' => $items,
                'subcategoryTitle' => $subcategoryTitle,
                'categoryTitle' => $categoryTitle,
                'subcategories' => $subcategories,
            ]);

        } catch (FirebaseException $e) {
            \Log::error('Firebase error in MartController itemsBySubcategory method: ' . $e->getMessage());
            return view('mart.item-by-category', [
                'items' => [],
                'subcategoryTitle' => $subcategoryTitle,
                'categoryTitle' => '',
                'subcategories' => [],
            ]);
        }
    }

    /**
     * Get subcategories for sidebar with optimized item counting
     */
    private function getSubcategoriesForSidebar($firestore, $categoryTitle, $currentSubcategoryTitle)
    {
        if (empty($categoryTitle)) {
            return [];
        }

        // Step 1: Get all subcategories for the category
        $subcategories = [];
        $subcategoriesSnapshot = $firestore->collection('mart_subcategories')
            ->where('publish', '=', true)
            ->where('parent_category_title', '=', $categoryTitle)
            ->documents();

        $subcategoryTitles = [];
        foreach ($subcategoriesSnapshot as $sub) {
            if ($sub->exists()) {
                $subData = $sub->data();
                $subcategoryTitles[] = $subData['title'] ?? '';

                $subcategories[] = [
                    'id'    => $subData['id'] ?? null,
                    'title' => $subData['title'] ?? 'No Title',
                    'photo' => $subData['photo'] ?? '/img/pro1.jpg',
                    'isActive' => ($subData['title'] ?? '') === $currentSubcategoryTitle,
                    'itemCount' => 0, // Will be updated below
                ];
            }
        }

        // Step 2: Get item counts for all subcategories in one optimized query
        if (!empty($subcategoryTitles)) {
            $itemCounts = $this->getSubcategoryItemCounts($firestore, $subcategoryTitles);

            // Step 3: Update subcategories with actual counts
            foreach ($subcategories as &$subcategory) {
                $subcategory['itemCount'] = $itemCounts[$subcategory['title']] ?? 0;
            }
        }

        // Step 4: Sort subcategories by item count (high to low), then by title
        usort($subcategories, function($a, $b) {
            // First sort by item count (descending)
            $countComparison = $b['itemCount'] <=> $a['itemCount'];
            if ($countComparison !== 0) {
                return $countComparison;
            }
            // If counts are equal, sort by title (ascending)
            return strcmp($a['title'], $b['title']);
        });

        return $subcategories;
    }

    /**
     * Optimized method to get item counts for multiple subcategories
     */
    private function getSubcategoryItemCounts($firestore, $subcategoryTitles)
    {
        $itemCounts = [];

        // Initialize all counts to 0
        foreach ($subcategoryTitles as $title) {
            $itemCounts[$title] = 0;
        }

        try {
            // Single query to get all items for all subcategories
            $itemsSnapshot = $firestore->collection('mart_items')
                ->where('publish', '=', true)
                ->where('isAvailable', '=', true)
                ->documents();

            // Count items by subcategory
            foreach ($itemsSnapshot as $doc) {
                if ($doc->exists()) {
                    $data = $doc->data();
                    $subcategoryTitle = $data['subcategoryTitle'] ?? '';

                    if (in_array($subcategoryTitle, $subcategoryTitles)) {
                        $itemCounts[$subcategoryTitle]++;
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error getting subcategory item counts: ' . $e->getMessage());
            // Return zero counts if there's an error
        }

        return $itemCounts;
    }

    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $type = $request->get('type', 'search'); // 'search' or 'suggestions'

            // Handle suggestions request
            if ($type === 'suggestions') {
                if (strlen($query) < 2) {
                    return response()->json([]);
                }

                $suggestions = $this->getSearchSuggestions($query);
                return response()->json($suggestions);
            }

            // Handle regular search request
            if (empty($query)) {
                return view('mart.search-results', [
                    'items' => [],
                    'query' => '',
                    'totalResults' => 0,
                ]);
            }

            $items = $this->getSearchResults($query);

            \Log::info("Search completed for query '{$query}': " . count($items) . " items found");

            return view('mart.search-results', [
                'items' => $items,
                'query' => $query,
                'totalResults' => count($items),
            ]);

        } catch (FirebaseException $e) {
            \Log::error('Firebase error in MartController search method: ' . $e->getMessage());

            if ($request->get('type') === 'suggestions') {
                return response()->json([]);
            }

            return view('mart.search-results', [
                'items' => [],
                'query' => $query ?? '',
                'totalResults' => 0,
            ]);
        }
    }

    /**
     * Get search results for products
     */
    private function getSearchResults($query)
    {
        // Initialize Firebase
        $factory = (new Factory)->withServiceAccount(
            base_path('storage/app/firebase/credentials.json')
        );
        $firestore = $factory->createFirestore()->database();

        // Search in mart_items collection
        $itemsRef = $firestore->collection('mart_items');
        $documents = $itemsRef->where('publish', '=', true)
                             ->where('isAvailable', '=', true)
                             ->documents();

        $items = [];
        $searchQuery = strtolower($query);

        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $data = $doc->data();

                // Search in multiple fields
                $name = strtolower($data['name'] ?? '');
                $description = strtolower($data['description'] ?? '');
                $categoryTitle = strtolower($data['categoryTitle'] ?? '');
                $subcategoryTitle = strtolower($data['subcategoryTitle'] ?? '');
                $section = strtolower($data['section'] ?? '');

                // Check if query matches any field
                if (strpos($name, $searchQuery) !== false ||
                    strpos($description, $searchQuery) !== false ||
                    strpos($categoryTitle, $searchQuery) !== false ||
                    strpos($subcategoryTitle, $searchQuery) !== false ||
                    strpos($section, $searchQuery) !== false) {

                    $items[] = [
                        'id' => $doc->id(),
                        'disPrice' => $data['disPrice'] ?? 0,
                        'name' => $data['name'] ?? 'Product',
                        'description' => $data['description'] ?? 'Product description',
                        'grams' => $data['grams'] ?? '200g',
                        'photo' => $data['photo'] ?? '',
                        'price' => $data['price'] ?? 0,
                        'section' => $data['section'] ?? 'General',
                        'subcategoryTitle' => $data['subcategoryTitle'] ?? 'category',
                        'categoryTitle' => $data['categoryTitle'] ?? 'Category',
                        'isBestSeller' => $data['isBestSeller'] ?? false,
                        'isFeature' => $data['isFeature'] ?? false,
                        'isSpotlight' => $data['isSpotlight'] ?? false,
                        'isNew' => $data['isNew'] ?? false,
                        'veg' => $data['veg'] ?? true,
                        'nonveg' => $data['nonveg'] ?? false,
                        'quantity' => $data['quantity'] ?? 0,
                        'vendorID' => $data['vendorID'] ?? '',
                        'vendorTitle' => $data['vendorTitle'] ?? '',
                        'reviewSum' => $data['reviewSum'] ?? '',
                        'reviewCount' => $data['reviewCount'] ?? '',
                    ];
                }
            }
        }

        // Sort items by name
        usort($items, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $items;
    }

    /**
     * Get search suggestions for autocomplete
     */
    private function getSearchSuggestions($query)
    {
        // Initialize Firebase
        $factory = (new Factory)->withServiceAccount(
            base_path('storage/app/firebase/credentials.json')
        );
        $firestore = $factory->createFirestore()->database();

        // Get all items for suggestions
        $itemsRef = $firestore->collection('mart_items');
        $documents = $itemsRef->where('publish', '=', true)
                             ->where('isAvailable', '=', true)
                             ->documents();

        $suggestions = [];
        $searchQuery = strtolower($query);
        $seen = [];

        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $data = $doc->data();

                // Get unique suggestions from name, category, and subcategory
                $name = strtolower($data['name'] ?? '');
                $categoryTitle = strtolower($data['categoryTitle'] ?? '');
                $subcategoryTitle = strtolower($data['subcategoryTitle'] ?? '');

                // Check name matches
                if (strpos($name, $searchQuery) === 0 && !in_array($name, $seen)) {
                    $suggestions[] = [
                        'text' => $data['name'],
                        'type' => 'product',
                        'category' => $data['categoryTitle'] ?? '',
                        'subcategory' => $data['subcategoryTitle'] ?? ''
                    ];
                    $seen[] = $name;
                }

                // Check category matches
                if (strpos($categoryTitle, $searchQuery) === 0 && !in_array($categoryTitle, $seen)) {
                    $suggestions[] = [
                        'text' => $data['categoryTitle'],
                        'type' => 'category',
                        'category' => $data['categoryTitle'] ?? '',
                        'subcategory' => ''
                    ];
                    $seen[] = $categoryTitle;
                }

                // Check subcategory matches
                if (strpos($subcategoryTitle, $searchQuery) === 0 && !in_array($subcategoryTitle, $seen)) {
                    $suggestions[] = [
                        'text' => $data['subcategoryTitle'],
                        'type' => 'subcategory',
                        'category' => $data['categoryTitle'] ?? '',
                        'subcategory' => $data['subcategoryTitle'] ?? ''
                    ];
                    $seen[] = $subcategoryTitle;
                }

                // Limit suggestions to 10
                if (count($suggestions) >= 10) {
                    break;
                }
            }
        }

        // Sort suggestions by type (products first, then categories, then subcategories)
        usort($suggestions, function($a, $b) {
            $typeOrder = ['product' => 0, 'category' => 1, 'subcategory' => 2];
            return $typeOrder[$a['type']] <=> $typeOrder[$b['type']];
        });

        return array_slice($suggestions, 0, 8); // Return max 8 suggestions
    }

    /**
     * Fetch mart coupons from Firebase
     */
    public function getMartCoupons()
    {
        try {
            // Initialize Firebase
            $factory = (new Factory)->withServiceAccount(
                base_path('storage/app/firebase/credentials.json')
            );
            $firestore = $factory->createFirestore()->database();

            // Fetch mart coupons
            $couponsRef = $firestore->collection('coupons')
                ->where('cType', '==', 'mart')
                ->where('isEnabled', '==', true)
                ->where('isPublic', '==', true)
                ->where('expiresAt', '>=', new \DateTime())
                ->documents();

            $coupons = [];
            foreach ($couponsRef as $doc) {
                if ($doc->exists()) {
                    $data = $doc->data();
                    $coupons[] = [
                        'id' => $doc->id(),
                        'code' => $data['code'] ?? '',
                        'description' => $data['description'] ?? '',
                        'discount' => $data['discount'] ?? 0,
                        'discountType' => $data['discountType'] ?? 'Fix Price',
                        'item_value' => $data['item_value'] ?? 0,
                        'expiresAt' => $data['expiresAt'] ?? null,
                        'image' => $data['image'] ?? '',
                        'usageLimit' => $data['usageLimit'] ?? 0,
                        'usedCount' => $data['usedCount'] ?? 0,
                    ];
                }
            }

            // Sort coupons by discount amount (highest first)
            usort($coupons, function($a, $b) {
                return $b['discount'] <=> $a['discount'];
            });

            return response()->json([
                'status' => true,
                'coupons' => $coupons
            ]);

        } catch (FirebaseException $e) {
            \Log::error('Firebase error in MartController getMartCoupons method: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch coupons',
                'coupons' => []
            ]);
        }
    }

    /**
     * Apply mart coupon
     */
    public function applyMartCoupon(Request $request)
    {
        try {
            $couponCode = $request->input('coupon_code');
            $cartTotal = $request->input('cart_total', 0);

            if (!$couponCode) {
                return response()->json([
                    'status' => false,
                    'message' => 'Coupon code is required'
                ]);
            }

            // Initialize Firebase
            $factory = (new Factory)->withServiceAccount(
                base_path('storage/app/firebase/credentials.json')
            );
            $firestore = $factory->createFirestore()->database();

            // Fetch coupon from Firebase
            $couponRef = $firestore->collection('coupons')
                ->where('code', '==', $couponCode)
                ->where('cType', '==', 'mart')
                ->where('isEnabled', '==', true)
                ->where('isPublic', '==', true)
                ->where('expiresAt', '>=', new \DateTime())
                ->limit(1)
                ->documents();

            $couponData = null;
            foreach ($couponRef as $doc) {
                if ($doc->exists()) {
                    $couponData = $doc->data();
                    $couponData['id'] = $doc->id();
                    break;
                }
            }

            if (!$couponData) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid or expired coupon code'
                ]);
            }

            // Check minimum order value
            $minOrderValue = $couponData['item_value'] ?? 0;
            if ($cartTotal < $minOrderValue) {
                return response()->json([
                    'status' => false,
                    'message' => "Minimum order value of ₹{$minOrderValue} required for this coupon"
                ]);
            }

            // Check usage limit
            $usageLimit = $couponData['usageLimit'] ?? 0;
            $usedCount = $couponData['usedCount'] ?? 0;
            if ($usageLimit > 0 && $usedCount >= $usageLimit) {
                return response()->json([
                    'status' => false,
                    'message' => 'This coupon has reached its usage limit'
                ]);
            }

            // Calculate discount
            $discount = $couponData['discount'] ?? 0;
            $discountType = $couponData['discountType'] ?? 'Fix Price';

            if ($discountType === 'Percentage') {
                $discountAmount = ($cartTotal * $discount) / 100;
            } else {
                $discountAmount = $discount;
            }

            // Ensure discount doesn't exceed cart total
            if ($discountAmount > $cartTotal) {
                $discountAmount = $cartTotal;
            }

            return response()->json([
                'status' => true,
                'message' => 'Coupon applied successfully',
                'coupon' => [
                    'id' => $couponData['id'],
                    'code' => $couponData['code'],
                    'discount' => $discount,
                    'discountType' => $discountType,
                    'discountAmount' => $discountAmount,
                    'description' => $couponData['description'] ?? ''
                ]
            ]);

        } catch (FirebaseException $e) {
            \Log::error('Firebase error in MartController applyMartCoupon method: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to apply coupon'
            ]);
        }
    }

    /**
     * Get fallback data when Firebase is unavailable
     */
    private function getFallbackData()
    {
        \Log::info('Using fallback data due to Firebase unavailability');

        // SEO data removed for performance optimization
        $seoData = [
            'title' => 'JippyMart - Fresh Groceries & Daily Essentials Delivered',
            'description' => 'Order fresh groceries, medicines, and daily essentials online. Fast delivery to your doorstep with quality guarantee.'
        ];

        return view('mart.index', [
            'categories' => [
                [
                    'id' => 'fallback-1',
                    'title' => 'Groceries',
                    'photo' => '/img/pro1.jpg',
                    'subcategories' => [
                        ['id' => 'sub-1', 'title' => 'Fruits & Vegetables', 'photo' => '/img/pro1.jpg'],
                        ['id' => 'sub-2', 'title' => 'Dairy Products', 'photo' => '/img/pro1.jpg'],
                    ]
                ],
                [
                    'id' => 'fallback-2',
                    'title' => 'Household',
                    'photo' => '/img/pro1.jpg',
                    'subcategories' => [
                        ['id' => 'sub-3', 'title' => 'Cleaning Supplies', 'photo' => '/img/pro1.jpg'],
                        ['id' => 'sub-4', 'title' => 'Personal Care', 'photo' => '/img/pro1.jpg'],
                    ]
                ]
            ],
            'spotlight' => [
                [
                    'id' => 'spot-1',
                    'name' => 'Fresh Apples',
                    'price' => 150,
                    'disPrice' => 120,
                    'photo' => '/img/pro1.jpg',
                    'rating' => 4.5,
                    'reviews' => 100,
                    'grams' => '1kg',
                    'description' => 'Fresh and juicy apples',
                    'subcategoryTitle' => 'Fruits'
                ]
            ],
            'featured' => [],
            'banners' => [
                [
                    'id' => 'banner-1',
                    'title' => 'Welcome to JippyMart',
                    'text' => 'Your one-stop shop for all needs',
                    'photo' => '/img/banner.jpg',
                    'position' => 'top',
                    'set_order' => 1
                ]
            ],
            'sections' => [
                'Fresh' => [
                    ['id' => 'fresh-1', 'title' => 'Fruits', 'photo' => '/img/pro1.jpg'],
                    ['id' => 'fresh-2', 'title' => 'Vegetables', 'photo' => '/img/pro1.jpg'],
                ],
                'Household' => [
                    ['id' => 'house-1', 'title' => 'Cleaning', 'photo' => '/img/pro1.jpg'],
                ]
            ],
            'trendingProducts' => [],
            'bestSellerProducts' => [],
            'stealOfMomentProducts' => [],
            'newArrivalProducts' => [],
            'seasonalProducts' => [],
            'itemsBySection' => [
                'Fresh' => [
                    [
                        'id' => 'fresh-1',
                        'name' => 'Fresh Apples',
                        'price' => 150,
                        'disPrice' => 120,
                        'photo' => '/img/pro1.jpg',
                        'rating' => 4.5,
                        'reviews' => 100,
                        'grams' => '1kg',
                        'description' => 'Fresh and juicy apples',
                        'section' => 'Fresh',
                        'subcategoryTitle' => 'Fruits',
                        'categoryTitle' => 'Groceries'
                    ],
                    [
                        'id' => 'fresh-2',
                        'name' => 'Organic Bananas',
                        'price' => 80,
                        'disPrice' => 70,
                        'photo' => '/img/pro1.jpg',
                        'rating' => 4.3,
                        'reviews' => 85,
                        'grams' => '500g',
                        'description' => 'Organic bananas',
                        'section' => 'Fresh',
                        'subcategoryTitle' => 'Fruits',
                        'categoryTitle' => 'Groceries'
                    ]
                ],
                'Household' => [
                    [
                        'id' => 'house-1',
                        'name' => 'Dish Soap',
                        'price' => 120,
                        'disPrice' => 100,
                        'photo' => '/img/pro1.jpg',
                        'rating' => 4.2,
                        'reviews' => 150,
                        'grams' => '500ml',
                        'description' => 'Effective dish cleaning soap',
                        'section' => 'Household',
                        'subcategoryTitle' => 'Cleaning',
                        'categoryTitle' => 'Household'
                    ]
                ]
            ],
            'seoData' => $seoData
        ]);
    }

    /**
     * Handle banner redirect - Fast redirect to all-items with search
     */
    public function bannerRedirect($bannerTitle)
    {
        try {
            // Fast redirect to all-items with search parameter
            return redirect()->route('mart.all.items', [
                'search' => $bannerTitle
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in bannerRedirect: ' . $e->getMessage());
            return redirect()->route('mart.all.items');
        }
    }

}
