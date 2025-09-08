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

        return view('mart.index', [
            'categories' => $categoryData, 'spotlight' => $products
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
