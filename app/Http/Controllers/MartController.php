<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class MartController extends Controller
{
    public function index()
    {
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials.file'));
        $firestore = $factory->createFirestore()->database();

        $categories = $firestore->collection('mart_categories')
            ->where('publish', '=', true)
            ->documents();

        $categoryData = [];

        foreach ($categories as $category) {
            $cat = $category->data();

            $subcategories = $firestore->collection('mart_subcategories')
                ->where('parent_category_id', '=', $cat['id'])
                ->where('publish', '=', true)
                ->documents();

            $cat['subcategories'] = [];
            foreach ($subcategories as $sub) {
                $cat['subcategories'][] = $sub->data();
            }

            $categoryData[] = $cat;
        }

        $itemsRef = $firestore->collection('mart_items');
        $query = $itemsRef
            ->where('publish', '=', true)
            ->where('isSpotlight', '=', true)
            ->where('isAvailable', '=', true);

        $documents = $query->documents();

        $products = [];
        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $products[] = $doc->data();
            }
        }

        // Fetch published top-position mart banners from Firestore
        $bannersSnapshot = $firestore->collection('mart_banners')
            ->where('position', '=', 'top')
            ->where('is_publish', '=', true)
            ->documents();

        $banners = [];
        foreach ($bannersSnapshot as $doc) {
            if ($doc->exists()) {
                $data = $doc->data();
                // Normalize expected fields and provide safe defaults
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

        // Sort banners by set_order asc in PHP to avoid requiring composite index
        usort($banners, function($a, $b) {
            return ($a['set_order'] ?? 0) <=> ($b['set_order'] ?? 0);
        });

        return view('mart.index', [
            'categories' => $categoryData, 'spotlight' => $products, 'banners' => $banners
        ]);
    }


    public function spotLight()
    {
        $firestore = app('firebase.firestore')->database();

        $itemsRef = $firestore->collection('mart_items');
        $query = $itemsRef
            ->where('publish', '=', true)
            ->where('isSpotlight', '=', true)
            ->where('isAvailable', '=', true);

        $documents = $query->documents();

        $products = [];
        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $products[] = $doc->data();
            }
        }

        return view('mart.index', compact('products'));
    }

}
