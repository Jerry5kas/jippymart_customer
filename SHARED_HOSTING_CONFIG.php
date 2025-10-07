<?php

/**
 * Shared Hosting Configuration
 * Apply these settings to prevent 503/508 errors on shared hosting
 */

// Set environment variables for shared hosting
if (!defined('SHARED_HOSTING_MODE')) {
    define('SHARED_HOSTING_MODE', true);
}

// Memory and execution limits for shared hosting
if (SHARED_HOSTING_MODE) {
    // Reduce memory limit for shared hosting
    ini_set('memory_limit', '64M');
    
    // Reduce execution time limit
    set_time_limit(15);
    
    // Disable async processing
    putenv('QUEUE_CONNECTION=sync');
    
    // Disable background jobs
    putenv('QUEUE_WORKER_TIMEOUT=0');
}

/**
 * Shared Hosting Optimizations
 */
class SharedHostingOptimizer
{
    /**
     * Apply shared hosting optimizations
     */
    public static function optimize()
    {
        if (!SHARED_HOSTING_MODE) {
            return;
        }
        
        // Set memory limit
        ini_set('memory_limit', '64M');
        
        // Set execution time limit
        set_time_limit(15);
        
        // Disable output buffering to save memory
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Disable error reporting in production
        if (app()->environment('production')) {
            error_reporting(0);
            ini_set('display_errors', 0);
        }
    }
    
    /**
     * Check if request is within resource limits
     */
    public static function checkResourceLimits()
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');
        $memoryLimitBytes = self::convertToBytes($memoryLimit);
        
        // If using more than 80% of memory limit, trigger cleanup
        if ($memoryUsage > ($memoryLimitBytes * 0.8)) {
            self::cleanup();
        }
        
        return [
            'memory_usage' => $memoryUsage,
            'memory_limit' => $memoryLimitBytes,
            'usage_percentage' => ($memoryUsage / $memoryLimitBytes) * 100
        ];
    }
    
    /**
     * Cleanup resources
     */
    private static function cleanup()
    {
        // Clear any output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Force garbage collection
        gc_collect_cycles();
        
        // Clear any cached data
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
    }
    
    /**
     * Convert memory limit string to bytes
     */
    private static function convertToBytes($value)
    {
        $value = trim($value);
        $last = strtolower($value[strlen($value)-1]);
        $value = (int) $value;
        
        switch($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }
        
        return $value;
    }
}

// Auto-optimize if in shared hosting mode
if (SHARED_HOSTING_MODE) {
    SharedHostingOptimizer::optimize();
}

