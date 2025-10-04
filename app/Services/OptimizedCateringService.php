<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\CateringRequestNotification;
use App\Mail\CateringRequestConfirmation;

class OptimizedCateringService
{
    private $firestore;
    
    public function __construct()
    {
        try {
            // Lightweight Firebase connection
            $credentials = $this->getFirebaseCredentials();
            $factory = (new \Kreait\Firebase\Factory)->withServiceAccount($credentials);
            $this->firestore = $factory->createFirestore();
        } catch (\Exception $e) {
            Log::error('Firebase connection failed: ' . $e->getMessage());
            throw new \Exception('Firebase connection failed');
        }
    }
    
    /**
     * Store request with minimal operations
     */
    public function storeRequest($data)
    {
        try {
            // Generate ID and reference number
            $docId = 'req_' . time() . '_' . rand(1000, 9999);
            $referenceNumber = 'CAT-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            // Minimal data structure
            $documentData = [
                'name' => $data['name'],
                'mobile' => $data['mobile'],
                'email' => $data['email'] ?? null,
                'alternative_mobile' => $data['alternative_mobile'] ?? null,
                'place' => $data['place'],
                'date' => $data['date'],
                'guests' => (int)$data['guests'],
                'function_type' => $data['function_type'],
                'meal_preference' => $data['meal_preference'],
                'veg_count' => isset($data['veg_count']) ? (int)$data['veg_count'] : null,
                'nonveg_count' => isset($data['nonveg_count']) ? (int)$data['nonveg_count'] : null,
                'special_requirements' => $data['special_requirements'] ?? null,
                'status' => 'pending',
                'reference_number' => $referenceNumber,
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString(),
                'ip_address' => request()->ip(),
                'admin_email_sent' => false,
                'customer_email_sent' => false
            ];
            
            // Single Firestore operation
            $database = $this->firestore->database();
            $collection = $database->collection('catering_requests');
            $docRef = $collection->document($docId);
            $docRef->set($documentData);
            
            // Return data with reference number for immediate response
            return [
                'request_id' => $docId,
                'reference_number' => $referenceNumber,
                'data' => $documentData
            ];
            
        } catch (\Exception $e) {
            Log::error('Firestore store failed: ' . $e->getMessage());
            throw new \Exception('Failed to store request');
        }
    }
    
    /**
     * Send emails asynchronously (non-blocking)
     */
    public function sendEmailsAsync($requestId, $data)
    {
        try {
            // Dispatch email sending to run after response
            dispatch(function() use ($requestId, $data) {
                $this->sendAdminEmail($requestId, $data);
                $this->sendCustomerEmail($requestId, $data);
            })->afterResponse();
            
            return true;
        } catch (\Exception $e) {
            Log::error('Email dispatch failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send admin email
     */
    private function sendAdminEmail($requestId, $data)
    {
        try {
            $adminEmail = config('firebase.email.admin_email', 'jerry@jippymart.in');
            Mail::to($adminEmail)->send(new CateringRequestNotification($data));
            
            // Update status in database
            $this->updateEmailStatus($requestId, 'admin_email_sent', true);
            
            Log::info('Admin email sent', ['request_id' => $requestId]);
        } catch (\Exception $e) {
            Log::error('Admin email failed: ' . $e->getMessage());
            $this->updateEmailStatus($requestId, 'admin_email_sent', false);
        }
    }
    
    /**
     * Send customer email
     */
    private function sendCustomerEmail($requestId, $data)
    {
        try {
            if (empty($data['email'])) {
                $this->updateEmailStatus($requestId, 'customer_email_sent', true);
                return;
            }
            
            Mail::to($data['email'])->send(new CateringRequestConfirmation($data));
            $this->updateEmailStatus($requestId, 'customer_email_sent', true);
            
            Log::info('Customer email sent', ['request_id' => $requestId]);
        } catch (\Exception $e) {
            Log::error('Customer email failed: ' . $e->getMessage());
            $this->updateEmailStatus($requestId, 'customer_email_sent', false);
        }
    }
    
    /**
     * Update email status (lightweight)
     */
    private function updateEmailStatus($requestId, $field, $status)
    {
        try {
            $database = $this->firestore->database();
            $collection = $database->collection('catering_requests');
            $docRef = $collection->document($requestId);
            $docRef->update([
                $field => $status,
                'updated_at' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            Log::error("Email status update failed: " . $e->getMessage());
        }
    }
    
    /**
     * Get Firebase credentials (lightweight)
     */
    private function getFirebaseCredentials()
    {
        $credentialsPath = config('firebase.credentials');
        if ($credentialsPath && file_exists($credentialsPath)) {
            return $credentialsPath;
        }
        
        // Fallback to environment variables
        $projectId = env('FIREBASE_PROJECT_ID');
        $privateKey = env('FIREBASE_PRIVATE_KEY');
        $clientEmail = env('FIREBASE_CLIENT_EMAIL');
        
        if ($projectId && $privateKey && $clientEmail) {
            return [
                'type' => 'service_account',
                'project_id' => $projectId,
                'private_key' => str_replace('\\n', "\n", $privateKey),
                'client_email' => $clientEmail,
            ];
        }
        
        throw new \Exception('Firebase credentials not found');
    }
}
