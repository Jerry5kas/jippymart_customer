<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResourceMonitor
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

        // Set strict limits for shared hosting
        ini_set('memory_limit', '64M'); // Reduced limit
        set_time_limit(15); // 15 seconds max

        $response = $next($request);

        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        
        $executionTime = round(($endTime - $startTime) * 1000, 2);
        $memoryUsed = round(($endMemory - $startMemory) / 1024 / 1024, 2);
        $peakMemory = round(memory_get_peak_usage(true) / 1024 / 1024, 2);

        // Log performance metrics
        if ($executionTime > 3000 || $memoryUsed > 32 || $peakMemory > 48) {
            Log::warning('Resource usage exceeded limits', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'execution_time_ms' => $executionTime,
                'memory_used_mb' => $memoryUsed,
                'peak_memory_mb' => $peakMemory,
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
            ]);
        }

        // Add performance headers
        $response->headers->set('X-Execution-Time', $executionTime . 'ms');
        $response->headers->set('X-Memory-Used', $memoryUsed . 'MB');
        $response->headers->set('X-Peak-Memory', $peakMemory . 'MB');

        return $response;
    }
}
