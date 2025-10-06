<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\CateringRequest;
use App\Mail\CateringRequestNotification;
use App\Mail\CateringRequestConfirmation;
use App\Services\CateringService;
use App\Services\EmailService;
use App\Services\AuditService;

class CateringController extends Controller
{
    protected $cateringService;
    protected $emailService;
    protected $auditService;
    
    public function __construct(
        CateringService $cateringService,
        EmailService $emailService,
        AuditService $auditService
    ) {
        $this->cateringService = $cateringService;
        $this->emailService = $emailService;
        $this->auditService = $auditService;
    }
    
    /**
     * Store a new catering request
     * POST /api/catering-requests
     */
    public function store(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate request with custom messages
            $validator = Validator::make(
                $request->all(), 
                CateringRequest::validationRules(),
                CateringRequest::validationMessages()
            );
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed. Please check the errors below.',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $data = $validator->validated();
            
            // Custom validation for meal preference
            $mealValidation = CateringRequest::validateMealPreference($data);
            if ($mealValidation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meal preference validation failed.',
                    'errors' => $mealValidation
                ], 422);
            }
            
            // Check for spam patterns
            if ($this->isSpamRequest($request)) {
                $this->auditService->logSecurityEvent('spam_detected', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'data' => $data
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Request blocked due to suspicious activity'
                ], 429);
            }
            
            // Store in Firestore with fallback
            try {
                $requestId = $this->cateringService->storeRequest($data);
                $firestoreSuccess = true;
            } catch (\Exception $e) {
                Log::error('Firestore storage failed: ' . $e->getMessage());
                // Fallback: generate a temporary ID and continue
                $requestId = 'temp_' . time() . '_' . rand(1000, 9999);
                $firestoreSuccess = false;
            }
            
            // Get stored data with reference number
            $referenceNumber = 'CAT-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            if ($firestoreSuccess) {
                try {
                    $storedRequest = $this->cateringService->getRequest($requestId);
                    $referenceNumber = $storedRequest['reference_number'] ?? $referenceNumber;
                } catch (\Exception $e) {
                    Log::warning('Could not retrieve stored request: ' . $e->getMessage());
                }
            }
            
            $dataWithReference = array_merge($data, [
                'reference_number' => $referenceNumber
            ]);
            
            // Send emails with timeout protection for shared hosting
            $adminEmailSent = false;
            $customerEmailSent = false;
            
            // Check if we have time for emails (shared hosting optimization)
            $emailStartTime = microtime(true);
            $maxEmailTime = 20; // Allow max 20 seconds for emails (increased for reliability)
            
            try {
                $adminEmailSent = $this->emailService->sendAdminNotificationAsync($requestId, $dataWithReference);
                $emailTime = microtime(true) - $emailStartTime;
                
                if ($emailTime > $maxEmailTime) {
                    Log::warning('Admin email took too long, skipping customer email', [
                        'request_id' => $requestId,
                        'email_time' => $emailTime
                    ]);
                    $customerEmailSent = false;
                } else {
                    try {
                        $customerEmailSent = $this->emailService->sendCustomerConfirmationAsync($requestId, $dataWithReference);
                    } catch (\Exception $e) {
                        Log::warning('Failed to send customer email: ' . $e->getMessage());
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Failed to send admin email: ' . $e->getMessage());
            }
            
            // Log audit with error handling
            try {
                $this->auditService->logRequest('catering_request_created', $data, [
                    'request_id' => $requestId,
                    'admin_email_sent' => $adminEmailSent,
                    'customer_email_sent' => $customerEmailSent,
                    'firestore_success' => $firestoreSuccess,
                    'status' => 'success'
                ]);
            } catch (\Exception $e) {
                Log::warning('Failed to log audit: ' . $e->getMessage());
            }
            
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            Log::info('Catering request processed', [
                'request_id' => $requestId,
                'response_time_ms' => $responseTime,
                'firestore_success' => $firestoreSuccess
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Catering request submitted successfully',
                'data' => [
                    'id' => $requestId,
                    'reference_number' => $referenceNumber,
                    'status' => 'pending',
                    'created_at' => now()->toISOString(),
                    'email_notifications' => [
                        'admin_notification_sent' => $adminEmailSent,
                        'customer_confirmation_sent' => $customerEmailSent
                    ]
                ]
            ], 201);
            
        } catch (\Exception $e) {
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            Log::error('Catering request creation failed: ' . $e->getMessage(), [
                'response_time_ms' => $responseTime,
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            
            try {
                $this->auditService->logRequest('catering_request_failed', $request->all(), [
                    'error' => $e->getMessage(),
                    'response_time_ms' => $responseTime
                ]);
            } catch (\Exception $auditException) {
                Log::error('Failed to log audit for failed request: ' . $auditException->getMessage());
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit catering request. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Get a specific catering request
     * GET /api/catering-requests/{id}
     */
    public function show($id)
    {
        try {
            $request = $this->cateringService->getRequest($id);
            
            if (!$request) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catering request not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $request
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get catering request failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve catering request'
            ], 500);
        }
    }
    
    /**
     * Get all catering requests (Admin only)
     * GET /api/catering-requests
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['status', 'date_from', 'date_to', 'per_page']);
            
            $requests = $this->cateringService->getAllRequests($filters);
            
            return response()->json([
                'success' => true,
                'data' => $requests,
                'count' => count($requests)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get all catering requests failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve catering requests'
            ], 500);
        }
    }
    
    /**
     * Update catering request status (Admin only)
     * PUT /api/catering-requests/{id}
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,confirmed,cancelled'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $status = $request->input('status');
            
            $updated = $this->cateringService->updateRequestStatus($id, $status);
            
            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catering request not found or update failed'
                ], 404);
            }
            
            // Log status update
            $this->auditService->logRequest('catering_request_status_updated', [
                'request_id' => $id,
                'status' => $status
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Catering request status updated successfully',
                'data' => [
                    'id' => $id,
                    'status' => $status,
                    'updated_at' => now()->toISOString()
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Update catering request failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update catering request'
            ], 500);
        }
    }
    
    /**
     * Get catering analytics (Admin only)
     * GET /api/catering-analytics
     */
    public function analytics(Request $request)
    {
        try {
            $dateRange = $request->only(['date_from', 'date_to']);
            
            $analytics = $this->cateringService->getAnalytics($dateRange);
            
            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get catering analytics failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve analytics'
            ], 500);
        }
    }
    
    
    /**
     * Check for spam patterns
     */
    private function isSpamRequest(Request $request)
    {
        // More intelligent spam detection
        $suspiciousPatterns = [
            'spam', 'fake', 'bot', 'automated', 'xxx', 'porn', 'scam'
        ];
        
        $content = strtolower($request->input('name') . ' ' . $request->input('place') . ' ' . $request->input('special_requirements', ''));
        
        // Only flag if multiple suspicious patterns are found
        $suspiciousCount = 0;
        foreach ($suspiciousPatterns as $pattern) {
            if (strpos($content, $pattern) !== false) {
                $suspiciousCount++;
            }
        }
        
        // Also check for obvious spam indicators
        if (strpos($content, 'test user') !== false && strpos($content, 'test venue') !== false) {
            return true; // Multiple "test" occurrences
        }
        
        if ($suspiciousCount >= 2) {
            return true;
        }
        
        // Check for excessive requests from same IP
        $ip = $request->ip();
        $recentRequests = $this->cateringService->getRecentRequestsByIp($ip, 5); // Last 5 minutes
        
        if (count($recentRequests) >= 3) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Process request asynchronously
     */
    private function processRequestAsync($requestId, $data, $referenceNumber)
    {
        // Use fastcgi_finish_request() to send response immediately
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
        
        // Process everything in background
        try {
            $dataWithReference = array_merge($data, [
                'reference_number' => $referenceNumber,
                'id' => $requestId
            ]);
            
            // Store in Firestore
            $cateringRequest = new \App\Models\CateringRequest();
            $cateringRequest->storeInFirestore($dataWithReference);
            
            // Send emails
            $emailService = new \App\Services\EmailService();
            $emailService->sendAdminNotification($requestId, $dataWithReference);
            $emailService->sendCustomerConfirmation($requestId, $dataWithReference);
            
            // Log audit
            $auditService = new \App\Services\AuditService();
            $auditService->logRequest('catering_request_created', $dataWithReference, [
                'request_id' => $requestId,
                'status' => 'success'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Async processing failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Store request asynchronously
     */
    private function storeRequestAsync($data)
    {
        // Use fastcgi_finish_request() to send response immediately
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
        
        try {
            $cateringRequest = new \App\Models\CateringRequest();
            $cateringRequest->storeInFirestore($data);
        } catch (\Exception $e) {
            Log::error('Async store failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Send emails asynchronously
     */
    private function sendEmailsAsync($requestId, $data)
    {
        // Use fastcgi_finish_request() to send response immediately
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
        
        try {
            $emailService = new \App\Services\EmailService();
            $emailService->sendAdminNotification($requestId, $data);
            $emailService->sendCustomerConfirmation($requestId, $data);
        } catch (\Exception $e) {
            Log::error('Async email failed: ' . $e->getMessage());
        }
    }
}
