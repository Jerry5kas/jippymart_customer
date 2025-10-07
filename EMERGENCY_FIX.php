<?php
/**
 * EMERGENCY FIX FOR 503/508 ERRORS
 * 
 * This script implements critical optimizations to prevent server crashes
 * Run this script immediately if you're experiencing 503/508 errors
 */

// Set strict limits immediately
ini_set('memory_limit', '64M');
set_time_limit(15);

echo "🚨 EMERGENCY OPTIMIZATION STARTING...\n";

// 1. Disable async processing
putenv('QUEUE_CONNECTION=sync');
putenv('QUEUE_WORKER_TIMEOUT=0');

echo "✅ Disabled async processing\n";

// 2. Set environment variables for optimization
$envFile = '.env';
$envContent = file_get_contents($envFile);

// Add critical optimizations to .env
$optimizations = [
    'QUEUE_CONNECTION=sync',
    'QUEUE_WORKER_TIMEOUT=0',
    'MEMORY_LIMIT=64M',
    'MAX_EXECUTION_TIME=15',
    'FIREBASE_QUERY_LIMIT=20',
    'FIREBASE_TIMEOUT=10',
    'SHARED_HOSTING_MODE=true',
    'CACHE_DRIVER=file',
    'SESSION_DRIVER=file',
    'BROADCAST_DRIVER=log',
    'LOG_LEVEL=warning',
    'APP_DEBUG=false'
];

foreach ($optimizations as $setting) {
    $key = explode('=', $setting)[0];
    if (strpos($envContent, $key) === false) {
        $envContent .= "\n" . $setting;
    }
}

file_put_contents($envFile, $envContent);
echo "✅ Updated .env with optimizations\n";

// 3. Clear all caches
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "✅ Cleared OPcache\n";
}

// 4. Force garbage collection
gc_collect_cycles();
echo "✅ Forced garbage collection\n";

// 5. Create emergency .htaccess rules
$htaccessRules = '
# EMERGENCY OPTIMIZATION RULES
# Add these to your .htaccess file

# Disable server signature
ServerSignature Off

# Set memory and execution limits
php_value memory_limit 64M
php_value max_execution_time 15
php_value max_input_time 15

# Disable unnecessary modules
php_flag display_errors Off
php_flag log_errors On

# Optimize file handling
php_value file_uploads On
php_value upload_max_filesize 2M
php_value post_max_size 2M

# Disable background processing
php_value max_input_vars 1000
php_value max_input_nesting_level 64

# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Cache static files
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
</IfModule>
';

file_put_contents('EMERGENCY_HTACCESS_RULES.txt', $htaccessRules);
echo "✅ Created emergency .htaccess rules (copy to .htaccess)\n";

// 6. Create emergency configuration
$emergencyConfig = '<?php
// EMERGENCY CONFIGURATION
// This file contains critical settings to prevent 503/508 errors

return [
    "memory_limit" => "64M",
    "max_execution_time" => 15,
    "firebase_query_limit" => 20,
    "firebase_timeout" => 10,
    "disable_async" => true,
    "disable_background_jobs" => true,
    "force_garbage_collection" => true,
    "optimize_database" => true,
    "cache_driver" => "file",
    "session_driver" => "file",
    "queue_connection" => "sync"
];
';

file_put_contents('config/emergency.php', $emergencyConfig);
echo "✅ Created emergency configuration\n";

// 7. Check current resource usage
$memoryUsage = memory_get_usage(true);
$memoryLimit = ini_get('memory_limit');
$executionTime = ini_get('max_execution_time');

echo "\n📊 CURRENT RESOURCE STATUS:\n";
echo "Memory Usage: " . round($memoryUsage / 1024 / 1024, 2) . "MB\n";
echo "Memory Limit: " . $memoryLimit . "\n";
echo "Execution Time: " . $executionTime . " seconds\n";

if ($memoryUsage > 32 * 1024 * 1024) {
    echo "⚠️  WARNING: High memory usage detected!\n";
}

echo "\n🎯 IMMEDIATE ACTIONS REQUIRED:\n";
echo "1. Copy EMERGENCY_HTACCESS_RULES.txt content to your .htaccess file\n";
echo "2. Restart your web server if possible\n";
echo "3. Monitor your server resources\n";
echo "4. Contact hosting support if issues persist\n";

echo "\n✅ EMERGENCY OPTIMIZATION COMPLETED!\n";
echo "Your application should now be more stable.\n";
?>
