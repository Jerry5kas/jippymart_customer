<?php

require_once 'vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\FirebaseException;

// Initialize Firebase
$factory = (new Factory)->withServiceAccount('storage/app/firebase/credentials.json');
$firestore = $factory->createFirestore()->database();

try {
    // Get all products from mart_items collection
    $productsRef = $firestore->collection('mart_items');
    $documents = $productsRef->documents();

    $updatedCount = 0;
    
    foreach ($documents as $document) {
        if ($document->exists()) {
            $data = $document->data();
            $docId = $document->id();
            
            // Generate random rating between 4.0 and 5.0
            $rating = round(4.0 + (mt_rand() / mt_getrandmax()) * 1.0, 1);
            
            // Generate random reviews between 10 and 500
            $reviews = mt_rand(10, 500);
            
            // Update the document with new rating and reviews
            $firestore->collection('mart_items')->document($docId)->update([
                ['path' => 'rating', 'value' => $rating],
                ['path' => 'reviews', 'value' => $reviews]
            ]);
            
            $updatedCount++;
            echo "Updated product ID: $docId with rating: $rating and reviews: $reviews\n";
        }
    }
    
    echo "\nTotal products updated: $updatedCount\n";
    
} catch (FirebaseException $e) {
    echo "Firebase error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "General error: " . $e->getMessage() . "\n";
}
