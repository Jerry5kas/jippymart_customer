<?php

/**
 * Script to check if Firebase index is working for homepage categories
 * Run this script to monitor when the index becomes available
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Load Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\FirebaseService;

echo "Checking Firebase index for homepage categories...\n";
echo "================================================\n\n";

try {
    $firebaseService = new FirebaseService();
    
    // Test the exact query that requires the index
    $filters = [
        'publish' => true,
        'show_in_homepage' => true
    ];
    
    echo "Testing query with filters: " . json_encode($filters) . "\n";
    
    $startTime = microtime(true);
    $categories = $firebaseService->getMartCategoriesWithPagination(
        $filters,
        null,
        1,
        10,
        'title',
        'asc'
    );
    $endTime = microtime(true);
    
    $executionTime = round(($endTime - $startTime) * 1000, 2);
    
    echo "✅ Query executed successfully!\n";
    echo "⏱️  Execution time: {$executionTime}ms\n";
    echo "📊 Results: " . count($categories['data']) . " categories found\n";
    echo "📈 Total: {$categories['total']}\n";
    
    if (count($categories['data']) > 0) {
        echo "\n📋 Categories found:\n";
        foreach ($categories['data'] as $category) {
            echo "  - {$category['title']} (ID: {$category['id']})\n";
        }
    }
    
    echo "\n🎉 Firebase index is working correctly!\n";
    echo "The homepage categories endpoint should now work without fallback.\n";
    
} catch (Exception $e) {
    echo "❌ Query failed with error:\n";
    echo "   " . $e->getMessage() . "\n\n";
    
    if (strpos($e->getMessage(), 'requires an index') !== false) {
        echo "🔧 The Firebase index is still being built or not created yet.\n";
        echo "Please create the composite index for:\n";
        echo "  - Collection: mart_categories\n";
        echo "  - Fields: publish (Ascending), show_in_homepage (Ascending), title (Ascending)\n\n";
        
        echo "📝 Instructions:\n";
        echo "1. Go to: https://console.firebase.google.com/project/jippymart-27c08/firestore/indexes\n";
        echo "2. Click 'Create Index'\n";
        echo "3. Set Collection ID to 'mart_categories'\n";
        echo "4. Add the three fields mentioned above\n";
        echo "5. Wait for the index to build (may take a few minutes)\n";
        echo "6. Run this script again to verify\n";
    } else {
        echo "🔍 This appears to be a different error. Check the logs for more details.\n";
    }
}

echo "\n================================================\n";
echo "Script completed.\n";
