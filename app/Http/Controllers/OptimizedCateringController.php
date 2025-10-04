<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Services\OptimizedCateringService;
use App\Models\CateringRequest;

class OptimizedCateringController extends Controller
{
    private $cateringService;
    
    public function __construct(OptimizedCateringService $cateringService)
    {
        $this->cateringService = $cateringService;
    }
    
    /**
     * Store catering request (optimized for performance)
     */
    public function store(Request $request)
    {
        try {
            // Quick validation (minimal rules)
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:2|max:255',
                'mobile' => 'required|string|regex:/^[6-9]\d{9}$/|size:10',
                'email' => 'nullable|email|max:255',
                'alternative_mobile' => 'nullable|string|regex:/^[6-9]\d{9}$/|size:10',
                'place' => 'required|string|min:10|max:1000',
                'date' => 'required|date|after:today|before:+1 year',
                'guests' => 'required|integer|min:1|max:10000',
                'function_type' => 'required|string|min:3|max:100',
                'meal_preference' => 'required|in:veg,non_veg,both',
                'veg_count' => 'nullable|integer|min:0|max:10000',
                'nonveg_count' => 'nullable|integer|min:0|max:10000',
                'special_requirements' => 'nullable|string|max:2000'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $data = $request->all();
            
            // Quick meal preference validation
            if ($data['meal_preference'] === 'both') {
                if (!isset($data['veg_count']) || !isset($data['nonveg_count'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Both veg_count and nonveg_count are required when meal_preference is "both"'
                    ], 422);
                }
                
                if (($data['veg_count'] + $data['nonveg_count']) !== $data['guests']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'The sum of veg_count and nonveg_count must equal the total guests count'
                    ], 422);
                }
            }
            
            // Store request (single database operation)
            $result = $this->cateringService->storeRequest($data);
            
            // Send emails asynchronously (non-blocking)
            $emailSent = $this->cateringService->sendEmailsAsync($result['request_id'], $result['data']);
            
            // Immediate response (no waiting for emails)
            return response()->json([
                'success' => true,
                'message' => 'Catering request submitted successfully',
                'data' => [
                    'request_id' => $result['request_id'],
                    'reference_number' => $result['reference_number'],
                    'status' => 'pending',
                    'email_notifications' => [
                        'admin_notification' => 'processing',
                        'customer_confirmation' => 'processing'
                    ],
                    'submitted_at' => now()->toISOString()
                ]
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Catering request failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit catering request',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get request by ID (lightweight)
     */
    public function show($id)
    {
        try {
            $cateringRequest = new CateringRequest();
            $data = $cateringRequest->getFromFirestore($id);
            
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $data
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
     * Get all requests (admin only - lightweight)
     */
    public function index(Request $request)
    {
        try {
            // Simple authentication check
            if (!$request->header('Authorization')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
            
            $cateringRequest = new CateringRequest();
            $filters = $request->only(['status', 'date_from', 'date_to']);
            $data = $cateringRequest->getAllFromFirestore($filters);
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'total' => count($data)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get all requests failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve requests'
            ], 500);
        }
    }
}
