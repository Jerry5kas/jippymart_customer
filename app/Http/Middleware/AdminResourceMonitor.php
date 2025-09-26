<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminResourceMonitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        // Set memory limit for admin panel
        ini_set('memory_limit', '128M');
        
        // Set execution time limit
        set_time_limit(30);

        $response = $next($request);

        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        
        $executionTime = round(($endTime - $startTime) * 1000, 2);
        $memoryUsed = round(($endMemory - $startMemory) / 1024 / 1024, 2);

        // Log performance metrics
        if ($executionTime > 5000 || $memoryUsed > 50) { // 5 seconds or 50MB
            Log::warning('Admin panel performance issue detected', [
                'url' => $request->fullUrl(),
                'execution_time_ms' => $executionTime,
                'memory_used_mb' => $memoryUsed,
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }
}
