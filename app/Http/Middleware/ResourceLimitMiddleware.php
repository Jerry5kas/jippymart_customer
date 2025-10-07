<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResourceLimitMiddleware
{
    /**
     * Handle an incoming request with strict resource limits for shared hosting
     */
    public function handle(Request $request, Closure $next)
    {
        // Set strict limits for shared hosting
        ini_set('memory_limit', '64M');
        set_time_limit(15);
        
        // Monitor resource usage
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);
        
        // Check if we're already using too much memory
        if ($startMemory > 32 * 1024 * 1024) { // 32MB
            Log::warning('High memory usage detected at request start', [
                'memory' => round($startMemory / 1024 / 1024, 2) . 'MB',
                'url' => $request->fullUrl()
            ]);
        }
        
        $response = $next($request);
        
        // Log resource usage
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        
        $executionTime = round(($endTime - $startTime) * 1000, 2);
        $memoryUsed = round(($endMemory - $startMemory) / 1024 / 1024, 2);
        $totalMemory = round($endMemory / 1024 / 1024, 2);
        
        // Log warnings for high resource usage
        if ($executionTime > 10000 || $totalMemory > 50) { // 10 seconds or 50MB
            Log::warning('High resource usage detected', [
                'url' => $request->fullUrl(),
                'execution_time_ms' => $executionTime,
                'memory_used_mb' => $memoryUsed,
                'total_memory_mb' => $totalMemory,
                'user_agent' => $request->userAgent(),
            ]);
        }
        
        // Force garbage collection to free memory
        if ($totalMemory > 40) { // If using more than 40MB
            gc_collect_cycles();
        }
        
        return $response;
    }
}
