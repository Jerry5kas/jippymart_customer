<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\CateringRequestNotification;
use App\Mail\CateringRequestConfirmation;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Firestore;

class EmailService
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
     * Send admin notification email
     */
    public function sendAdminNotification($requestId, $data)
    {
        try {
            $adminEmail = config('firebase.email.admin_email', 'jerry@jippymart.in');
            
            Mail::to($adminEmail)->send(new CateringRequestNotification($data));
            
            // Log email in Firestore
            $this->logEmail($requestId, 'admin_notification', $adminEmail, 'sent');
            
            // Update database status
            $this->updateEmailStatus($requestId, 'admin_email_sent', true);
            
            Log::info('Admin notification email sent successfully', [
                'request_id' => $requestId,
                'admin_email' => $adminEmail
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Admin notification email failed: ' . $e->getMessage());
            
            // Log failed email
            $this->logEmail($requestId, 'admin_notification', $adminEmail, 'failed', $e->getMessage());
            
            // Update database status
            $this->updateEmailStatus($requestId, 'admin_email_sent', false);
            
            return false;
        }
    }
    
    /**
     * Send admin notification email asynchronously
     */
    public function sendAdminNotificationAsync($requestId, $data)
    {
        // Queue the email for background processing
        dispatch(function() use ($requestId, $data) {
            $this->sendAdminNotification($requestId, $data);
        })->afterResponse();
    }
    
    /**
     * Send customer confirmation email asynchronously
     */
    public function sendCustomerConfirmationAsync($requestId, $data)
    {
        // Queue the email for background processing
        dispatch(function() use ($requestId, $data) {
            $this->sendCustomerConfirmation($requestId, $data);
        })->afterResponse();
    }
    
    /**
     * Send customer confirmation email
     */
    public function sendCustomerConfirmation($requestId, $data)
    {
        try {
            if (empty($data['email'])) {
                Log::info('Customer confirmation skipped - no email provided', [
                    'request_id' => $requestId
                ]);
                // Update database status
                $this->updateEmailStatus($requestId, 'customer_email_sent', true);
                return true;
            }
            
            Mail::to($data['email'])->send(new CateringRequestConfirmation($data));
            
            // Log email in Firestore
            $this->logEmail($requestId, 'customer_confirmation', $data['email'], 'sent');
            
            // Update database status
            $this->updateEmailStatus($requestId, 'customer_email_sent', true);
            
            Log::info('Customer confirmation email sent successfully', [
                'request_id' => $requestId,
                'customer_email' => $data['email']
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Customer confirmation email failed: ' . $e->getMessage());
            
            // Log failed email
            $this->logEmail($requestId, 'customer_confirmation', $data['email'], 'failed', $e->getMessage());
            
            // Update database status
            $this->updateEmailStatus($requestId, 'customer_email_sent', false);
            
            return false;
        }
    }
    
    /**
     * Log email in Firestore
     */
    private function logEmail($requestId, $emailType, $recipient, $status, $errorMessage = null)
    {
        try {
            $database = $this->firestore->database();
            $collection = $database->collection('email_logs');
            $docId = 'email_' . time() . '_' . rand(1000, 9999);
            $docRef = $collection->document($docId);
            $docRef->set([
                'catering_request_id' => $requestId,
                'email_type' => $emailType,
                'recipient' => $recipient,
                'status' => $status,
                'sent_at' => $status === 'sent' ? now()->toISOString() : null,
                'error_message' => $errorMessage,
                'created_at' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            Log::error('Email logging failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Get email delivery statistics
     */
    public function getEmailStats($dateRange = null)
    {
        try {
            $database = $this->firestore->database();
            $collection = $database->collection('email_logs');
            $query = $collection;
            
            if ($dateRange) {
                $query = $query->where('created_at', '>=', $dateRange['start'])
                              ->where('created_at', '<=', $dateRange['end']);
            }
            
            $docs = $query->documents();
            
            $stats = [
                'total_emails' => 0,
                'sent_emails' => 0,
                'failed_emails' => 0,
                'delivery_rate' => 0,
                'admin_notifications' => 0,
                'customer_confirmations' => 0
            ];
            
            foreach ($docs as $doc) {
                $data = $doc->data();
                $stats['total_emails']++;
                
                if ($data['status'] === 'sent') {
                    $stats['sent_emails']++;
                } else {
                    $stats['failed_emails']++;
                }
                
                if ($data['email_type'] === 'admin_notification') {
                    $stats['admin_notifications']++;
                } else {
                    $stats['customer_confirmations']++;
                }
            }
            
            $stats['delivery_rate'] = $stats['total_emails'] > 0 ? 
                round(($stats['sent_emails'] / $stats['total_emails']) * 100, 2) : 0;
            
            return $stats;
            
        } catch (\Exception $e) {
            Log::error('Get email stats failed: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Retry failed emails
     */
    public function retryFailedEmails($requestId)
    {
        try {
            $database = $this->firestore->database();
            $collection = $database->collection('email_logs');
            $query = $collection
                ->where('catering_request_id', '=', $requestId)
                ->where('status', '=', 'failed');
            
            $docs = $query->documents();
            $retryCount = 0;
            
            foreach ($docs as $doc) {
                $data = $doc->data();
                
                // Retry logic based on email type
                if ($data['email_type'] === 'admin_notification') {
                    // Get request data and retry admin notification
                    $requestData = $this->getRequestData($requestId);
                    if ($requestData) {
                        $this->sendAdminNotification($requestId, $requestData);
                        $retryCount++;
                    }
                } elseif ($data['email_type'] === 'customer_confirmation') {
                    // Get request data and retry customer confirmation
                    $requestData = $this->getRequestData($requestId);
                    if ($requestData) {
                        $this->sendCustomerConfirmation($requestId, $requestData);
                        $retryCount++;
                    }
                }
            }
            
            return $retryCount;
            
        } catch (\Exception $e) {
            Log::error('Retry failed emails failed: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get request data from Firestore
     */
    private function getRequestData($requestId)
    {
        try {
            $database = $this->firestore->database();
            $collection = $database->collection('catering_requests');
            $docRef = $collection->document($requestId);
            $doc = $docRef->snapshot();
            
            if (!$doc->exists()) {
                return null;
            }
            
            return $doc->data();
            
        } catch (\Exception $e) {
            Log::error('Get request data failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Update email status in database
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
            
            Log::info("Email status updated: $field = $status", [
                'request_id' => $requestId
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to update email status: " . $e->getMessage());
            return false;
        }
    }
}
