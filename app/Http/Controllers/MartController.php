<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\FirebaseException;

class MartController extends Controller
{
    public function index()
    {
        try {
            // Initialize Firebase
            $factory = (new Factory)->withServiceAccount(
                base_path('storage/app/firebase/credentials.json')
            );
            $firestore = $factory->createFirestore()->database();

        // =========================
        // 1️⃣ OPTIMIZED CATEGORIES & SUBCATEGORIES
        // =========================

        // Fetch all categories first
        $categoriesSnapshot = $firestore->collection('mart_categories')
            ->where('publish', '=', true)
            ->documents();

        $categoryData = [];
        $categoryIds = [];

        foreach ($categoriesSnapshot as $category) {
            if (!$category->exists()) continue;
            $cat = $category->data();
            $categoryIds[] = $cat['id'];
            $categoryData[] = $cat;
        }

        // Fetch all subcategories in one query
        $subcategoriesSnapshot = $firestore->collection('mart_subcategories')
            ->where('publish', '=', true)
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
            // Sort banners by set_order ascending
            usort($categoryData, function($b, $a) {
                return ($b['set_order'] ?? 0) <=> ($a['set_order'] ?? 0);
            });
        // Categories without ordering for maximum performance

        // =========================
        // 2️⃣ Spotlight Products
        // =========================
        // Fetch spotlight products (simplified query to avoid composite index)
        $itemsRef = $firestore->collection('mart_items');
        $query = $itemsRef->where('publish', '=', true);

        $documents = $query->documents();

        $products = [];
        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $data = $doc->data();

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
                        'rating' => $rating,
                        'reviews' => $reviews,
                        'section' => $data['section'] ?? 'General',
                        'subcategoryTitle' => $data['subcategoryTitle'] ?? 'category',
                    ];
                }

            }
        }

        // =========================
        // 3️⃣ Featured Products
        // =========================
        // Fetch featured products based on isFeature field
        $featuredProducts = [];
        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $data = $doc->data();

                // Filter for featured and available products
                if (($data['isFeature'] ?? false) && ($data['isAvailable'] ?? false)) {
                    $featuredProducts[] = [
                        'id' => $doc->id(),
                        'disPrice' => $data['disPrice'] ?? 0,
                        'name' => $data['name'] ?? 'Product',
                        'description' => $data['description'] ?? 'Product description',
                        'grams' => $data['grams'] ?? '200g',
                        'photo' => $data['photo'] ?? '',
                        'price' => $data['price'] ?? 0,
                        'rating' => $data['rating'] ?? 4.5,
                        'reviews' => $data['reviews'] ?? 100,
                        'section' => $data['section'] ?? 'General',
                        'subcategoryTitle' => $data['subcategoryTitle'] ?? 'category',
                        'categoryTitle' => $data['categoryTitle'] ?? 'Category',
                    ];
                }
            }
        }
            // =========================
// 7️⃣ Trending Products
// =========================
            $trendingProducts = [];
            foreach ($documents as $doc) {
                if ($doc->exists()) {
                    $data = $doc->data();

                    if (($data['isTrending'] ?? false) && ($data['isAvailable'] ?? false)) {
                        $trendingProducts[] = [
                            'id' => $doc->id(),
                            'disPrice' => $data['disPrice'] ?? 0,
                            'name' => $data['name'] ?? 'Product',
                            'description' => $data['description'] ?? 'Product description',
                            'grams' => $data['grams'] ?? '200g',
                            'photo' => $data['photo'] ?? '',
                            'price' => $data['price'] ?? 0,
                            'rating' => $data['rating'] ?? 4.5,
                            'reviews' => $data['reviews'] ?? 100,
                            'section' => $data['section'] ?? 'General',
                            'subcategoryTitle' => $data['subcategoryTitle'] ?? 'category',
                            'categoryTitle' => $data['categoryTitle'] ?? 'Category',
                        ];
                    }
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
                        $bestSellerProducts[] = [
                            'id' => $doc->id(),
                            'disPrice' => $data['disPrice'] ?? 0,
                            'name' => $data['name'] ?? 'Product',
                            'description' => $data['description'] ?? 'Product description',
                            'grams' => $data['grams'] ?? '200g',
                            'photo' => $data['photo'] ?? '',
                            'price' => $data['price'] ?? 0,
                            'rating' => $data['rating'] ?? 4.5,
                            'reviews' => $data['reviews'] ?? 100,
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

        // Reuse the subcategories data we already fetched
        foreach ($subcategoriesSnapshot as $sub) {
            if (!$sub->exists()) continue;

            $subData = $sub->data();
            $sectionName = $subData['section'] ?? 'Others';

            if (!isset($sections[$sectionName])) {
                $sections[$sectionName] = [];
            }

            $sections[$sectionName][] = [
                'id'    => $subData['id'] ?? null,
                'title' => $subData['title'] ?? 'No Title',
                'photo' => $subData['photo'] ?? '/img/pro1.jpg',
            ];
        }
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
                        $stealOfMomentProducts[] = [
                            'id' => $doc->id(),
                            'disPrice' => $data['disPrice'] ?? 0,
                            'name' => $data['name'] ?? 'Product',
                            'description' => $data['description'] ?? 'Product description',
                            'grams' => $data['grams'] ?? '200g',
                            'photo' => $data['photo'] ?? '',
                            'price' => $data['price'] ?? 0,
                            'rating' => $data['rating'] ?? 4.5,
                            'reviews' => $data['reviews'] ?? 100,
                            'section' => $data['section'] ?? 'General',
                            'subcategoryTitle' => $data['subcategoryTitle'] ?? 'category',
                            'categoryTitle' => $data['categoryTitle'] ?? 'Category',
                        ];
                    }

                    if (($data['isNew'] ?? false) && ($data['isAvailable'] ?? false)) {
                        $newArrivalProducts[] = [
                            'id' => $doc->id(),
                            'disPrice' => $data['disPrice'] ?? 0,
                            'name' => $data['name'] ?? 'Product',
                            'description' => $data['description'] ?? 'Product description',
                            'grams' => $data['grams'] ?? '200g',
                            'photo' => $data['photo'] ?? '',
                            'price' => $data['price'] ?? 0,
                            'rating' => $data['rating'] ?? 4.5,
                            'reviews' => $data['reviews'] ?? 100,
                            'section' => $data['section'] ?? 'General',
                            'subcategoryTitle' => $data['subcategoryTitle'] ?? 'category',
                            'categoryTitle' => $data['categoryTitle'] ?? 'Category',
                        ];
                    }

                    if (($data['isSeasonal'] ?? false) && ($data['isAvailable'] ?? false)) {
                        $seasonalProducts[] = [
                            'id' => $doc->id(),
                            'disPrice' => $data['disPrice'] ?? 0,
                            'name' => $data['name'] ?? 'Product',
                            'description' => $data['description'] ?? 'Product description',
                            'grams' => $data['grams'] ?? '200g',
                            'photo' => $data['photo'] ?? '',
                            'price' => $data['price'] ?? 0,
                            'rating' => $data['rating'] ?? 4.5,
                            'reviews' => $data['reviews'] ?? 100,
                            'section' => $data['section'] ?? 'General',
                            'subcategoryTitle' => $data['subcategoryTitle'] ?? 'category',
                            'categoryTitle' => $data['categoryTitle'] ?? 'Category',
                        ];
                    }
                }
            }


            // =========================
            // 6️⃣ Return to Blade
            // =========================
            \Log::info("Mart data loaded: " . count($categoryData) . " categories, " . count($products) . " spotlight products, " . count($featuredProducts) . " featured products, " . count($banners) . " banners");

            return view('mart.index', [
                'categories' => $categoryData,
                'spotlight'  => $products,
                'featured'   => $featuredProducts,
                'banners'    => $banners,
                'sections'   => $sections,
                'trendingProducts' => $trendingProducts,
                'bestSellerProducts' => $bestSellerProducts,
                'stealOfMomentProducts' => $stealOfMomentProducts,
                'newArrivalProducts' => $newArrivalProducts,
                'seasonalProducts' => $seasonalProducts,

            ]);

        } catch (FirebaseException $e) {
            \Log::error('Firebase error in MartController index method: ' . $e->getMessage());
            return view('mart.index', [
                'categories' => [],
                'spotlight' => [],
                'featured' => [],
                'banners' => [],
                'sections' => [],

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

            // First, try to get category info from items
            $itemsRef = $firestore->collection('mart_items');
            $sampleQuery = $itemsRef->where('publish', '=', true)
                                   ->where('isAvailable', '=', true)
                                   ->where('subcategoryTitle', '=', $subcategoryTitle)
                                   ->limit(1);

            $sampleDocuments = $sampleQuery->documents();
            $categoryTitle = '';

            foreach ($sampleDocuments as $doc) {
                if ($doc->exists()) {
                    $data = $doc->data();
                    $categoryTitle = $data['categoryTitle'] ?? '';
                    break;
                }
            }

            // If no items found, try to get category from subcategory document directly
            if (empty($categoryTitle)) {
                $subcategorySnapshot = $firestore->collection('mart_subcategories')
                    ->where('publish', '=', true)
                    ->where('title', '=', $subcategoryTitle)
                    ->documents();

                foreach ($subcategorySnapshot as $subDoc) {
                    if ($subDoc->exists()) {
                        $subData = $subDoc->data();
                        $categoryTitle = $subData['parent_category_title'] ?? '';
                        break;
                    }
                }
            }

            // Fetch all items by subcategoryTitle
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
            \Log::info("Category Title determined: '{$categoryTitle}'");

            // Fetch ALL subcategories that belong to the same category (regardless of item count)
            $subcategories = [];
            if (!empty($categoryTitle)) {
                $subcategoriesSnapshot = $firestore->collection('mart_subcategories')
                    ->where('publish', '=', true)
                    ->documents();

                foreach ($subcategoriesSnapshot as $sub) {
                    if ($sub->exists()) {
                        $subData = $sub->data();
                        // Check if this subcategory belongs to the same category
                        if (($subData['parent_category_title'] ?? '') === $categoryTitle) {
                            // Count items for this subcategory (optional - for display purposes)
                            $itemCount = 0;
                            $itemsQuery = $firestore->collection('mart_items')
                                ->where('publish', '=', true)
                                ->where('isAvailable', '=', true)
                                ->where('subcategoryTitle', '=', $subData['title'] ?? '');

                            $itemDocuments = $itemsQuery->documents();
                            foreach ($itemDocuments as $itemDoc) {
                                if ($itemDoc->exists()) {
                                    $itemCount++;
                                }
                            }

                            $subcategories[] = [
                                'id'    => $subData['id'] ?? null,
                                'title' => $subData['title'] ?? 'No Title',
                                'photo' => $subData['photo'] ?? '/img/pro1.jpg',
                                'isActive' => ($subData['title'] ?? '') === $subcategoryTitle,
                                'itemCount' => $itemCount, // Add item count for reference
                            ];
                        }
                    }
                }

                // Sort subcategories by set_order or title
                usort($subcategories, function($a, $b) {
                    return strcmp($a['title'], $b['title']);
                });
            }

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
}
