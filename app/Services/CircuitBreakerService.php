<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CircuitBreakerService
{
    const FAILURE_THRESHOLD = 5; // Number of failures before opening circuit
    const TIMEOUT = 60; // Seconds to wait before trying again
    const SUCCESS_THRESHOLD = 3; // Number of successes needed to close circuit
    
    /**
     * Check if circuit is open for a service
     *
     * @param string $service
     * @return bool
     */
    public function isOpen(string $service): bool
    {
        $key = "circuit_breaker_{$service}";
        $state = Cache::get($key);
        
        if (!$state) {
            return false; // Circuit is closed
        }
        
        // Check if timeout has passed
        if (time() - $state['opened_at'] > self::TIMEOUT) {
            Log::info("Circuit breaker timeout passed for service: {$service}");
            $this->reset($service);
            return false;
        }
        
        return $state['state'] === 'open';
    }
    
    /**
     * Record a failure
     *
     * @param string $service
     * @return void
     */
    public function recordFailure(string $service): void
    {
        $key = "circuit_breaker_{$service}";
        $state = Cache::get($key, ['failures' => 0, 'successes' => 0, 'state' => 'closed', 'opened_at' => 0]);
        
        $state['failures']++;
        $state['successes'] = 0; // Reset successes on failure
        
        if ($state['failures'] >= self::FAILURE_THRESHOLD) {
            $state['state'] = 'open';
            $state['opened_at'] = time();
            Log::warning("Circuit breaker opened for service: {$service} after {$state['failures']} failures");
        }
        
        Cache::put($key, $state, 3600); // Cache for 1 hour
    }
    
    /**
     * Record a success
     *
     * @param string $service
     * @return void
     */
    public function recordSuccess(string $service): void
    {
        $key = "circuit_breaker_{$service}";
        $state = Cache::get($key, ['failures' => 0, 'successes' => 0, 'state' => 'closed', 'opened_at' => 0]);
        
        $state['successes']++;
        $state['failures'] = 0; // Reset failures on success
        
        if ($state['successes'] >= self::SUCCESS_THRESHOLD && $state['state'] === 'open') {
            $state['state'] = 'closed';
            $state['opened_at'] = 0;
            Log::info("Circuit breaker closed for service: {$service} after {$state['successes']} successes");
        }
        
        Cache::put($key, $state, 3600);
    }
    
    /**
     * Reset circuit breaker
     *
     * @param string $service
     * @return void
     */
    public function reset(string $service): void
    {
        $key = "circuit_breaker_{$service}";
        Cache::forget($key);
        Log::info("Circuit breaker reset for service: {$service}");
    }
    
    /**
     * Get circuit breaker status
     *
     * @param string $service
     * @return array
     */
    public function getStatus(string $service): array
    {
        $key = "circuit_breaker_{$service}";
        $state = Cache::get($key, ['failures' => 0, 'successes' => 0, 'state' => 'closed', 'opened_at' => 0]);
        
        return [
            'service' => $service,
            'state' => $state['state'],
            'failures' => $state['failures'],
            'successes' => $state['successes'],
            'opened_at' => $state['opened_at'],
            'is_open' => $this->isOpen($service)
        ];
    }
}
