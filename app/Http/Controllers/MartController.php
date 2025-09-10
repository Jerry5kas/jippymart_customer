<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class MartController extends Controller
{
    public function index()
    {
        // Initialize Firebase
        $factory = (new Factory)->withServiceAccount(
            base_path('storage/app/firebase/credentials.json')
        );
        $firestore = $factory->createFirestore()->database();

        // =========================
        // 1️⃣ Categories & Subcategories
        // =========================
        $categoriesSnapshot = $firestore->collection('mart_categories')
            ->where('publish', '=', true)
            ->documents();

        $categoryData = [];

        foreach ($categoriesSnapshot as $category) {
            if (!$category->exists()) continue;

            $cat = $category->data();

            // Fetch subcategories for this category
            $subcategoriesSnapshot = $firestore->collection('mart_subcategories')
                ->where('parent_category_id', '=', $cat['id'])
                ->where('publish', '=', true)
                ->documents();

            $subcategories = [];
            foreach ($subcategoriesSnapshot as $sub) {
                if ($sub->exists()) {
                    $subData = $sub->data();
                    $subcategories[] = [
                        'id'    => $subData['id'] ?? null,
                        'title' => $subData['title'] ?? 'No Title',
                        'photo' => $subData['photo'] ?? 'https://via.placeholder.com/150',
                    ];
                }
            }

            $cat['subcategories'] = $subcategories;
            $categoryData[] = $cat;
        }

        // =========================
        // 2️⃣ Spotlight Products
        // =========================
        // Fetch spotlight products
        $itemsRef = $firestore->collection('mart_items');
        $query = $itemsRef
            ->where('publish', '=', true)
            ->where('isSpotlight', '=', true)
            ->where('isAvailable', '=', true);

        $documents = $query->documents();

        $products = [];
        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $data = $doc->data();
                $products[] = [
                    'id' => $doc->id(),
                    'disPrice' => $data['disPrice'] ?? '-',
                    'name' => $data['name'] ?? '-',
                    'description' => $data['description'] ?? '-',
                    'grams' => $data['grams'] ?? '-',
                    'photo' => $data['photo'] ?? '',
                    'price' => $data['price'] ?? 0,
                    'rating' => $data['rating'] ?? 0,
                    'reviews' => $data['reviews'] ?? 0,
                    'section' => $data['section'] ?? '-',
                    'subcategoryTitle' => $data['subcategoryTitle'] ?? '-',
                ];
            }
        }

        // =========================
        // 3️⃣ Banners (Top Position)
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
        // 4️⃣ Sections (Grouped Subcategories)
        // =========================
        $subcategoriesSnapshot = $firestore->collection('mart_subcategories')
            ->where('publish', '=', true)
            ->documents();

        $sections = [];

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
        // 5️⃣ Return to Blade
        // =========================
        return view('mart.index', [
            'categories' => $categoryData,
            'spotlight'  => $products,
            'banners'    => $banners,
            'sections'   => $sections,
        ]);
    }
}
