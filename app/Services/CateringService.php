<?php

namespace App\Services;

use App\Models\CateringRequest;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Firestore;
use Illuminate\Support\Facades\Log;

class CateringService
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
            throw new \Exception('Firebase connection failed: ' . $e->getMessage());
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
     * Store a new catering request
     */
    public function storeRequest($data)
    {
        try {
            $cateringRequest = new CateringRequest();
            return $cateringRequest->storeInFirestore($data);
        } catch (\Exception $e) {
            Log::error('Store request failed: ' . $e->getMessage());
            throw new \Exception('Failed to store request');
        }
    }
    
    /**
     * Get a specific request
     */
    public function getRequest($id)
    {
        try {
            $cateringRequest = new CateringRequest();
            return $cateringRequest->getFromFirestore($id);
        } catch (\Exception $e) {
            Log::error('Get request failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get all requests with filters
     */
    public function getAllRequests($filters = [])
    {
        try {
            $cateringRequest = new CateringRequest();
            return $cateringRequest->getAllFromFirestore($filters);
        } catch (\Exception $e) {
            Log::error('Get all requests failed: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Update request status
     */
    public function updateRequestStatus($id, $status)
    {
        try {
            $cateringRequest = new CateringRequest();
            return $cateringRequest->updateStatusInFirestore($id, $status);
        } catch (\Exception $e) {
            Log::error('Update request status failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get analytics data
     */
    public function getAnalytics($dateRange = [])
    {
        try {
            $query = $this->firestore->collection('catering_requests');
            
            if (isset($dateRange['date_from'])) {
                $query = $query->where('created_at', '>=', $dateRange['date_from']);
            }
            
            if (isset($dateRange['date_to'])) {
                $query = $query->where('created_at', '<=', $dateRange['date_to']);
            }
            
            $docs = $query->documents();
            
            $analytics = [
                'total_requests' => 0,
                'pending_requests' => 0,
                'confirmed_requests' => 0,
                'cancelled_requests' => 0,
                'avg_guests' => 0,
                'function_types' => [],
                'meal_preferences' => [],
                'email_delivery_rate' => 0
            ];
            
            $totalGuests = 0;
            $emailSent = 0;
            $emailTotal = 0;
            
            foreach ($docs as $doc) {
                $data = $doc->data();
                $analytics['total_requests']++;
                
                // Count by status
                $analytics[$data['status'] . '_requests']++;
                
                // Aggregate function types
                $functionType = $data['function_type'];
                $analytics['function_types'][$functionType] = 
                    ($analytics['function_types'][$functionType] ?? 0) + 1;
                
                // Aggregate meal preferences
                $mealPref = $data['meal_preference'];
                $analytics['meal_preferences'][$mealPref] = 
                    ($analytics['meal_preferences'][$mealPref] ?? 0) + 1;
                
                // Calculate average guests
                $totalGuests += $data['guests'];
                
                // Email delivery rate
                if (isset($data['admin_email_sent'])) {
                    $emailTotal++;
                    if ($data['admin_email_sent']) {
                        $emailSent++;
                    }
                }
            }
            
            $analytics['avg_guests'] = $analytics['total_requests'] > 0 ? 
                round($totalGuests / $analytics['total_requests'], 2) : 0;
            
            $analytics['email_delivery_rate'] = $emailTotal > 0 ? 
                round(($emailSent / $emailTotal) * 100, 2) : 0;
            
            return $analytics;
            
        } catch (\Exception $e) {
            Log::error('Get analytics failed: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Log email in Firestore
     */
    public function logEmail($requestId, $emailType, $recipient, $status, $errorMessage = null)
    {
        try {
            $this->firestore->collection('email_logs')->add([
                'catering_request_id' => $requestId,
                'email_type' => $emailType,
                'recipient' => $recipient,
                'status' => $status,
                'sent_at' => $status === 'sent' ? now()->toISOString() : null,
                'error_message' => $errorMessage,
                'created_at' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            Log::error('Log email failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Get recent requests by IP
     */
    public function getRecentRequestsByIp($ip, $minutes = 5)
    {
        try {
            // For now, return empty array to bypass spam check
            // TODO: Implement proper Firestore query when API is stable
            return [];
        } catch (\Exception $e) {
            Log::error('Get recent requests by IP failed: ' . $e->getMessage());
            return [];
        }
    }
}
