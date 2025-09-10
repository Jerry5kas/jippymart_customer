<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Factory;

class MartController extends Controller
{
    public function index()
    {
        try {
            $factory = (new Factory)->withServiceAccount(config('firebase.credentials.file'));
            $firestore = $factory->createFirestore()->database();

            // Fetch categories
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

            // Fetch banners - Modified to avoid composite index requirement
            $bannersCollection = $firestore->collection('mart_banners');

            // First, get all published banners
            $bannersQuery = $bannersCollection->where('is_publish', '=', true);
            $bannersDocuments = $bannersQuery->documents();

            $banners = [];
            $bannerCount = 0;

            foreach ($bannersDocuments as $document) {
                if ($document->exists()) {
                    $data = $document->data();
                    $bannerCount++;

                    $banners[] = [
                        'id' => $document->id(),
                        'title' => $data['title'] ?? '-',
                        'text' => $data['text'] ?? '-',
                        'description' => $data['description'] ?? '-',
                        'photo' => $data['photo'] ?? '',
                        'set_order' => $data['set_order'] ?? 0,
                    ];
                }
            }

            // Sort banners by set_order in PHP (to avoid composite index requirement)
            usort($banners, function ($a, $b) {
                return $a['set_order'] <=> $b['set_order'];
            });

            // Log total banners fetched
            \Log::info("Total banners fetched: {$bannerCount}");

            return view('mart.index', [
                'categories' => $categoryData,
                'products' => $products,
                'banners' => $banners
            ]);

        } catch (FirebaseException $e) {
            \Log::error("Firebase error: " . $e->getMessage());
            return response()->view('errors.firebase', [], 500);
        }
    }
}

