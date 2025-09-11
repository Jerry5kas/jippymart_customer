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
                        'photo' => $subData['photo'] ?? 'https://via.placeholder.com/150',
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
                'photo' => $subData['photo'] ?? 'https://via.placeholder.com/150',
            ];
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
}
