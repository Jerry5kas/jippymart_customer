<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\CateringRequest;

class MinimalCateringController extends Controller
{
    /**
     * Create catering request
     */
    public function store(Request $request)
    {
        try {
            // Simple validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:2|max:255',
                'mobile' => 'required|regex:/^[6-9]\d{9}$/',
                'email' => 'nullable|email|max:255',
                'place' => 'required|string|min:10|max:1000',
                'date' => 'required|date|after:today',
                'guests' => 'required|integer|min:1|max:10000',
                'function_type' => 'required|string|min:3|max:100',
                'meal_preference' => 'required|in:veg,non_veg,both',
                'veg_count' => 'nullable|integer|min:0|max:10000',
                'nonveg_count' => 'nullable|integer|min:0|max:10000',
                'special_requirements' => 'nullable|string|max:2000',
                'website' => 'nullable|string|max:0' // Honeypot
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $data = $validator->validated();
            
            // Spam check
            if (!empty($data['website']) || 
                strpos(strtolower($data['name'] . ' ' . $data['place']), 'spam') !== false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request blocked'
                ], 400);
            }
            
            // Meal preference validation
            if ($data['meal_preference'] === 'both') {
                if (empty($data['veg_count']) || empty($data['nonveg_count'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Vegetarian and non-vegetarian counts are required'
                    ], 422);
                }
                if (($data['veg_count'] + $data['nonveg_count']) != $data['guests']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Vegetarian + non-vegetarian count must equal total guests'
                    ], 422);
                }
            }
            
            // Generate IDs
            $requestId = 'REQ' . time() . rand(100, 999);
            $referenceNumber = 'CAT-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            // Store in database
            $requestData = array_merge($data, [
                'id' => $requestId,
                'reference_number' => $referenceNumber,
                'status' => 'pending',
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            $cateringRequest = new CateringRequest();
            $result = $cateringRequest->storeInFirestore($requestData);
            
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store request'
                ], 500);
            }
            
            // Send admin email (simple)
            $adminEmailSent = $this->sendAdminEmail($requestData);
            
            return response()->json([
                'success' => true,
                'message' => 'Catering request submitted successfully',
                'data' => [
                    'id' => $requestId,
                    'reference_number' => $referenceNumber,
                    'status' => 'pending',
                    'created_at' => now()->toISOString(),
                    'email_sent' => $adminEmailSent
                ]
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Catering request failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit request. Please try again'
            ], 500);
        }
    }
    
    /**
     * Get request by ID
     */
    public function show($id)
    {
        try {
            $request = (new CateringRequest())->getFromFirestore($id);
            
            if (!$request) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $request
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get request failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve request'
            ], 500);
        }
    }
    
    /**
     * Get all requests (Admin)
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['status', 'date_from', 'date_to']);
            $requests = (new CateringRequest())->getAllFromFirestore($filters);
            
            return response()->json([
                'success' => true,
                'data' => $requests,
                'count' => count($requests)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get all failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve requests'
            ], 500);
        }
    }
    
    /**
     * Update request status (Admin)
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
                    'message' => 'Invalid status',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $status = $request->input('status');
            $updated = (new CateringRequest())->updateStatusInFirestore($id, $status);
            
            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found or update failed'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Request status updated successfully',
                'data' => [
                    'id' => $id,
                    'status' => $status,
                    'updated_at' => now()->toISOString()
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update request'
            ], 500);
        }
    }
    
    /**
     * Send simple admin email
     */
    private function sendAdminEmail($data)
    {
        try {
            $adminEmail = config('mail.admin_email', 'jerry@jippymart.in');
            
            if (env('MAIL_HOST') && env('MAIL_USERNAME') && env('MAIL_PASSWORD')) {
                Mail::raw($this->getEmailContent($data), function ($message) use ($adminEmail, $data) {
                    $message->to($adminEmail)
                           ->subject("New Catering Request - {$data['name']} - {$data['date']}");
                });
                return true;
            }
        } catch (\Exception $e) {
            Log::warning('Admin email failed: ' . $e->getMessage());
        }
        return false;
    }
    
    /**
     * Simple email content
     */
    private function getEmailContent($data)
    {
        return "
NEW CATERING REQUEST
Reference: {$data['reference_number']}

Name: {$data['name']}
Mobile: {$data['mobile']}
Email: " . ($data['email'] ?? 'N/A') . "
Venue: {$data['place']}
Date: {$data['date']}
Guests: {$data['guests']} people
Event Type: {$data['function_type']}
Meal Preference: " . ucfirst(str_replace('_', ' ', $data['meal_preference'])) . "

" . ($data['meal_preference'] === 'both' ? "Vegetarian: {$data['veg_count']} people\nNon-Vegetarian: {$data['nonveg_count']} people\n" : '') . "
" . (!empty($data['special_requirements']) ? "Special Requirements: {$data['special_requirements']}\n" : '') . "
Submitted: " . now()->format('M j, Y g:i A') . "

Please contact the customer within 24 hours.
JippyMart Catering Service
        ";
    }
}
