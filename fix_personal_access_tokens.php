<?php
/**
 * Fix Personal Access Tokens Table
 * 
 * This script manually creates the personal_access_tokens table with the correct schema.
 */

// Include Laravel bootstrap
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "=== Fixing Personal Access Tokens Table ===\n";

try {
    // Drop the table if it exists
    Schema::dropIfExists('personal_access_tokens');
    echo "✅ Dropped existing personal_access_tokens table\n";
    
    // Create the table with correct schema
    Schema::create('personal_access_tokens', function (Blueprint $table) {
        $table->id(); // This creates BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        $table->morphs('tokenable');
        $table->string('name');
        $table->string('token', 64)->unique();
        $table->text('abilities')->nullable();
        $table->timestamp('expires_at')->nullable();
        $table->timestamp('last_used_at')->nullable();
        $table->timestamps();
    });
    
    echo "✅ Created personal_access_tokens table with correct schema\n";
    echo "✅ id column is now AUTO_INCREMENT\n";
    echo "✅ expires_at column is included\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "=== Done ===\n";
?> 