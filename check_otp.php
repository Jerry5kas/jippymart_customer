<?php
/**
 * Check OTP Script
 * 
 * This script checks the actual OTP that was generated and stored in the database.
 */

// Include Laravel bootstrap
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Otp;
use Carbon\Carbon;

$phone = '919885394334';

echo "=== Checking OTP for phone: {$phone} ===\n\n";

// Find the most recent OTP for this phone
$otpRecord = Otp::where('phone', $phone)
    ->orderBy('created_at', 'desc')
    ->first();

if ($otpRecord) {
    echo "Found OTP Record:\n";
    echo "Phone: " . $otpRecord->phone . "\n";
    echo "OTP: " . $otpRecord->otp . "\n";
    echo "Created: " . $otpRecord->created_at . "\n";
    echo "Expires: " . $otpRecord->expires_at . "\n";
    echo "Verified: " . ($otpRecord->verified ? 'Yes' : 'No') . "\n";
    echo "Attempts: " . $otpRecord->attempts . "\n";
    
    // Check if OTP is still valid
    $isExpired = $otpRecord->expires_at->isPast();
    echo "Is Expired: " . ($isExpired ? 'Yes' : 'No') . "\n";
    
    if (!$isExpired && !$otpRecord->verified) {
        echo "\n✅ OTP is valid and can be used for verification!\n";
        echo "Use OTP: " . $otpRecord->otp . "\n";
    } else {
        echo "\n❌ OTP cannot be used (expired or already verified)\n";
    }
} else {
    echo "No OTP record found for phone: {$phone}\n";
}

echo "\n=== All OTP Records for this phone ===\n";
$allOtps = Otp::where('phone', $phone)
    ->orderBy('created_at', 'desc')
    ->get();

foreach ($allOtps as $otp) {
    echo "OTP: {$otp->otp} | Created: {$otp->created_at} | Expires: {$otp->expires_at} | Verified: " . ($otp->verified ? 'Yes' : 'No') . " | Attempts: {$otp->attempts}\n";
}
?> 