<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Firestore;
use Illuminate\Support\Facades\Log;

class AuditService
{
    protected $firestore;
    
    public function __construct()
    {
        try {
            // Try multiple credential sources
            $credentials = $this->getFirebaseCredentials();
            
            if (is_string($credentials)) {
                // File path
                $factory = (new Factory)->withServiceAccount($credentials);
            } else {
                // Array credentials
                $factory = (new Factory)->withServiceAccount($credentials);
            }
            
            $this->firestore = $factory->createFirestore();
        } catch (\Exception $e) {
            Log::error('Firebase connection failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Get Firebase credentials from multiple sources
     */
    private function getFirebaseCredentials()
    {
        // 1. Try file path from config
        $credentialsPath = config('firebase.credentials');
        if ($credentialsPath && file_exists($credentialsPath)) {
            return $credentialsPath;
        }
        
        // 2. Try environment variables
        $projectId = env('FIREBASE_PROJECT_ID');
        $privateKey = env('FIREBASE_PRIVATE_KEY');
        $clientEmail = env('FIREBASE_CLIENT_EMAIL');
        
        if ($projectId && $privateKey && $clientEmail) {
            return [
                'type' => 'service_account',
                'project_id' => $projectId,
                'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID', ''),
                'private_key' => str_replace('\\n', "\n", $privateKey),
                'client_email' => $clientEmail,
                'client_id' => env('FIREBASE_CLIENT_ID', ''),
                'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
                'token_uri' => 'https://oauth2.googleapis.com/token',
                'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
                'client_x509_cert_url' => env('FIREBASE_CLIENT_X509_CERT_URL', ''),
            ];
        }
        
        // 3. Try default paths
        $defaultPaths = [
            storage_path('app/firebase/credentials.json'),
            storage_path('app/keys/credentials.json'),
            base_path('storage/app/firebase/credentials.json'),
        ];
        
        foreach ($defaultPaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        throw new \Exception('Firebase credentials not found in any location');
    }
    
    /**
     * Log request/response data asynchronously
     */
    public function logRequestAsync($action, $requestData, $responseData = null)
    {
        // Queue the logging for background processing
        dispatch(function() use ($action, $requestData, $responseData) {
            $this->logRequest($action, $requestData, $responseData);
        })->afterResponse();
    }
    
    /**
     * Log request/response data
     */
    public function logRequest($action, $requestData, $responseData = null)
    {
        try {
            $docId = 'audit_' . time() . '_' . rand(1000, 9999);
            $database = $this->firestore->database();
            $collection = $database->collection('audit_logs');
            $docRef = $collection->document($docId);
            $docRef->set([
                'action' => $action,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'request_data' => $requestData,
                'response_data' => $responseData,
                'timestamp' => now()->toISOString(),
                'user_id' => auth()->id() ?? null,
                'url' => request()->url(),
                'method' => request()->method()
            ]);
        } catch (\Exception $e) {
            Log::error('Audit log failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Log security events
     */
    public function logSecurityEvent($event, $details)
    {
        try {
            $docId = 'security_' . time() . '_' . rand(1000, 9999);
            $database = $this->firestore->database();
            $collection = $database->collection('security_logs');
            $docRef = $collection->document($docId);
            $docRef->set([
                'event' => $event,
                'details' => $details,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()->toISOString(),
                'user_id' => auth()->id() ?? null
            ]);
        } catch (\Exception $e) {
            Log::error('Security log failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Log performance metrics
     */
    public function logPerformance($endpoint, $responseTime, $memoryUsage)
    {
        try {
            $docId = 'perf_' . time() . '_' . rand(1000, 9999);
            $database = $this->firestore->database();
            $collection = $database->collection('performance_logs');
            $docRef = $collection->document($docId);
            $docRef->set([
                'endpoint' => $endpoint,
                'response_time' => $responseTime,
                'memory_usage' => $memoryUsage,
                'timestamp' => now()->toISOString(),
                'ip_address' => request()->ip()
            ]);
        } catch (\Exception $e) {
            Log::error('Performance log failed: ' . $e->getMessage());
        }
    }
}
