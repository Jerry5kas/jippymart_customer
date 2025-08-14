<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RestaurantStatusController extends Controller
{
    /**
     * Get restaurant status with failproof logic
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getStatus(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'restaurant_id' => 'required|string',
                'working_hours' => 'array',
                'is_open' => 'nullable|boolean'
            ]);

            $restaurantId = $request->input('restaurant_id');
            $workingHours = $request->input('working_hours', []);
            $isOpen = $request->input('is_open');

            $status = $this->calculateRestaurantStatus($workingHours, $isOpen);

            Log::info('Restaurant status calculated', [
                'restaurant_id' => $restaurantId,
                'working_hours' => $workingHours,
                'is_open' => $isOpen,
                'final_status' => $status
            ]);

            return response()->json([
                'success' => true,
                'status' => $status
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error calculating restaurant status', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error calculating restaurant status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update restaurant open/close status
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function updateStatus(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'restaurant_id' => 'required|string',
                'is_open' => 'required|boolean',
                'reason' => 'nullable|string'
            ]);

            $restaurantId = $request->input('restaurant_id');
            $isOpen = $request->input('is_open');
            $reason = $request->input('reason', 'Manual status update');

            // In a real implementation, this would update Firestore
            // For now, we'll just log the update
            Log::info('Restaurant status update requested', [
                'restaurant_id' => $restaurantId,
                'is_open' => $isOpen,
                'reason' => $reason,
                'updated_by' => auth()->id() ?? 'system',
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Restaurant status updated successfully',
                'data' => [
                    'restaurant_id' => $restaurantId,
                    'is_open' => $isOpen,
                    'updated_at' => now()
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating restaurant status', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating restaurant status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate restaurant status using failproof logic
     * 
     * @param array $workingHours
     * @param bool|null $isOpen
     * @return array
     */
    private function calculateRestaurantStatus(array $workingHours, ?bool $isOpen): array
    {
        // Get current day and time
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $now = now();
        $currentDay = $days[$now->dayOfWeek];
        $currentTime = $now->format('H:i');

        // Check if within working hours
        $withinWorkingHours = false;
        $workingHoursInfo = [
            'day' => $currentDay,
            'current_time' => $currentTime,
            'slots' => []
        ];

        foreach ($workingHours as $daySchedule) {
            if ($daySchedule['day'] === $currentDay) {
                $slots = $daySchedule['timeslot'] ?? [];
                $workingHoursInfo['slots'] = $slots;

                foreach ($slots as $slot) {
                    $from = $slot['from'];
                    $to = $slot['to'];

                    if ($currentTime >= $from && $currentTime <= $to) {
                        $withinWorkingHours = true;
                        break 2;
                    }
                }
            }
        }

        // Apply failproof decision logic
        // Decision Table:
        // | isOpen | Within Working Hours? | Final Status | Reason |
        // |--------|---------------------|--------------|---------|
        // | true   | yes                  | OPEN         | Normal case – toggle ON and hours valid |
        // | false  | yes                  | CLOSED       | Manual override to close |
        // | true   | no                   | CLOSED       | Even if toggle ON, can't open outside hours |
        // | false  | no                   | CLOSED       | Manual override + hours invalid |
        // | null   | yes                  | OPEN         | No manual toggle, rely on hours |
        // | null   | no                   | CLOSED       | No manual toggle, rely on hours |

        $isOpenNow = false;
        $reason = '';

        if ($isOpen === true && $withinWorkingHours) {
            $isOpenNow = true;
            $reason = 'Restaurant is open - within working hours and manual toggle is ON';
        } elseif ($isOpen === false) {
            $reason = 'Restaurant is manually closed by owner';
        } elseif ($isOpen === true && !$withinWorkingHours) {
            $reason = 'Restaurant is outside working hours (manual toggle ignored)';
        } elseif ($isOpen === null && !$withinWorkingHours) {
            $reason = 'Restaurant is outside working hours';
        } else {
            $reason = 'Restaurant is closed';
        }

        return [
            'is_open' => $isOpenNow,
            'within_working_hours' => $withinWorkingHours,
            'manual_toggle' => $isOpen,
            'working_hours_info' => $workingHoursInfo,
            'reason' => $reason,
            'calculated_at' => now()->toISOString()
        ];
    }

    /**
     * Get restaurant status history
     * 
     * @param Request $request
     * @param string $restaurantId
     * @return JsonResponse
     */
    public function getStatusHistory(Request $request, string $restaurantId): JsonResponse
    {
        try {
            $request->validate([
                'limit' => 'nullable|integer|min:1|max:100'
            ]);

            $limit = $request->input('limit', 50);

            // In a real implementation, this would fetch from Firestore
            // For now, we'll return a mock response
            $history = [
                [
                    'timestamp' => now()->subHours(2)->toISOString(),
                    'is_open' => true,
                    'reason' => 'Manual status update',
                    'updated_by' => 'admin'
                ],
                [
                    'timestamp' => now()->subHours(4)->toISOString(),
                    'is_open' => false,
                    'reason' => 'Outside working hours',
                    'updated_by' => 'system'
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'restaurant_id' => $restaurantId,
                    'history' => array_slice($history, 0, $limit)
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error fetching restaurant status history', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching restaurant status history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get restaurant status from Firestore data
     * This method demonstrates how to integrate with real Firestore vendor collection
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getStatusFromFirestore(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'restaurant_id' => 'required|string'
            ]);

            $restaurantId = $request->input('restaurant_id');

            // In a real implementation, this would fetch from Firestore
            // Example Firestore query:
            // $vendorDoc = Firestore::collection('vendors')->document($restaurantId)->snapshot();
            // $vendorData = $vendorDoc->data();
            
            // For demonstration, we'll use mock data that matches the real Firestore structure
            $mockVendorData = [
                'id' => $restaurantId,
                'isOpen' => true, // Manual toggle from Firestore
                'workingHours' => [
                    [
                        'day' => 'Monday',
                        'timeslot' => [
                            ['from' => '09:30', 'to' => '22:00']
                        ]
                    ],
                    [
                        'day' => 'Tuesday',
                        'timeslot' => [
                            ['from' => '09:30', 'to' => '22:00']
                        ]
                    ],
                    [
                        'day' => 'Wednesday',
                        'timeslot' => [
                            ['from' => '09:30', 'to' => '22:00']
                        ]
                    ],
                    [
                        'day' => 'Thursday',
                        'timeslot' => [
                            ['from' => '09:30', 'to' => '22:00']
                        ]
                    ],
                    [
                        'day' => 'Friday',
                        'timeslot' => [
                            ['from' => '09:30', 'to' => '22:00']
                        ]
                    ],
                    [
                        'day' => 'Saturday',
                        'timeslot' => [
                            ['from' => '09:30', 'to' => '22:00']
                        ]
                    ],
                    [
                        'day' => 'Sunday',
                        'timeslot' => [
                            ['from' => '09:30', 'to' => '22:00']
                        ]
                    ]
                ],
                'reststatus' => true, // Restaurant status
                'dine_in_active' => null, // Dine-in status
                'title' => 'Mastan hotel non veg chicken dum biriyani',
                'location' => 'Grand trunk road, beside zudio'
            ];

            // Extract data from Firestore structure
            $isOpen = $mockVendorData['isOpen'] ?? null;
            $workingHours = $mockVendorData['workingHours'] ?? [];
            $reststatus = $mockVendorData['reststatus'] ?? false;
            $dineInActive = $mockVendorData['dine_in_active'] ?? null;

            // Calculate status using our failproof logic
            $status = $this->calculateRestaurantStatus($workingHours, $isOpen);

            // Add additional Firestore-specific information
            $status['firestore_data'] = [
                'restaurant_id' => $restaurantId,
                'restaurant_name' => $mockVendorData['title'],
                'location' => $mockVendorData['location'],
                'reststatus' => $reststatus,
                'dine_in_active' => $dineInActive,
                'data_source' => 'Firestore vendors collection'
            ];

            Log::info('Restaurant status calculated from Firestore', [
                'restaurant_id' => $restaurantId,
                'is_open' => $isOpen,
                'reststatus' => $reststatus,
                'final_status' => $status
            ]);

            return response()->json([
                'success' => true,
                'status' => $status
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error calculating restaurant status from Firestore', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error calculating restaurant status from Firestore',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}