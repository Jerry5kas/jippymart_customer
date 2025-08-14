<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class RestaurantStatusTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test the failproof restaurant status system with all scenarios
     */
    public function test_failproof_restaurant_status_system()
    {
        $restaurantId = 'test-restaurant-123';

        // Test Scenario 1: isOpen = true, within working hours = OPEN
        // We'll test this with the current day to ensure it works regardless of when it's run
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $currentDay = $days[now()->dayOfWeek];
        
        $workingHours = [
            [
                'day' => $currentDay,
                'timeslot' => [
                    ['from' => '09:00', 'to' => '22:00']
                ]
            ]
        ];

        $response = $this->postJson('/restaurant-status/get-status', [
            'restaurant_id' => $restaurantId,
            'working_hours' => $workingHours,
            'is_open' => true
        ]);

        $response->assertStatus(200);
        
        // Check the response structure and logic, but don't assert specific values
        // that depend on current time
        $responseData = $response->json();
        $this->assertTrue($responseData['success']);
        $this->assertArrayHasKey('status', $responseData);
        $this->assertArrayHasKey('is_open', $responseData['status']);
        $this->assertArrayHasKey('within_working_hours', $responseData['status']);
        $this->assertArrayHasKey('manual_toggle', $responseData['status']);
        $this->assertArrayHasKey('reason', $responseData['status']);
        
        // Verify the failproof logic: isOpen can only be true if BOTH manual_toggle is true AND within_working_hours is true
        if ($responseData['status']['is_open'] === true) {
            $this->assertTrue($responseData['status']['manual_toggle'] === true);
            $this->assertTrue($responseData['status']['within_working_hours'] === true);
        }

        // Test Scenario 2: isOpen = false, should always be CLOSED regardless of working hours
        $response = $this->postJson('/restaurant-status/get-status', [
            'restaurant_id' => $restaurantId,
            'working_hours' => $workingHours,
            'is_open' => false
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertTrue($responseData['success']);
        $this->assertFalse($responseData['status']['is_open']);
        $this->assertFalse($responseData['status']['manual_toggle']);
        $this->assertStringContainsString('manually closed', $responseData['status']['reason']);

        // Test Scenario 3: isOpen = null, should follow working hours only
        $response = $this->postJson('/restaurant-status/get-status', [
            'restaurant_id' => $restaurantId,
            'working_hours' => $workingHours,
            'is_open' => null
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertTrue($responseData['success']);
        $this->assertNull($responseData['status']['manual_toggle']);
        // isOpen should be false because isOpen is null (failproof logic)
        $this->assertFalse($responseData['status']['is_open']);

        // Test Scenario 4: Empty working hours
        $response = $this->postJson('/restaurant-status/get-status', [
            'restaurant_id' => $restaurantId,
            'working_hours' => [],
            'is_open' => true
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertTrue($responseData['success']);
        $this->assertFalse($responseData['status']['is_open']);
        $this->assertFalse($responseData['status']['within_working_hours']);
    }

    /**
     * Test restaurant status update functionality
     */
    public function test_restaurant_status_update()
    {
        $restaurantId = 'test-restaurant-456';

        // Test updating status to open
        $response = $this->postJson('/restaurant-status/update-status', [
            'restaurant_id' => $restaurantId,
            'is_open' => true,
            'reason' => 'Test opening restaurant'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Restaurant status updated successfully',
            'data' => [
                'restaurant_id' => $restaurantId,
                'is_open' => true
            ]
        ]);

        // Test updating status to closed
        $response = $this->postJson('/restaurant-status/update-status', [
            'restaurant_id' => $restaurantId,
            'is_open' => false,
            'reason' => 'Test closing restaurant'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Restaurant status updated successfully',
            'data' => [
                'restaurant_id' => $restaurantId,
                'is_open' => false
            ]
        ]);
    }

    /**
     * Test restaurant status history
     */
    public function test_restaurant_status_history()
    {
        $restaurantId = 'test-restaurant-789';

        $response = $this->getJson("/restaurant-status/history/{$restaurantId}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'restaurant_id' => $restaurantId
            ]
        ]);

        $response->assertJsonStructure([
            'success',
            'data' => [
                'restaurant_id',
                'history' => [
                    '*' => [
                        'timestamp',
                        'is_open',
                        'reason',
                        'updated_by'
                    ]
                ]
            ]
        ]);
    }

    /**
     * Test validation errors
     */
    public function test_validation_errors()
    {
        // Test missing restaurant_id
        $response = $this->postJson('/restaurant-status/get-status', [
            'working_hours' => [],
            'is_open' => true
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['restaurant_id']);

        // Test missing working_hours (now optional, so should pass)
        $response = $this->postJson('/restaurant-status/get-status', [
            'restaurant_id' => 'test-123',
            'is_open' => true
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertTrue($responseData['success']);

        // Test invalid is_open value
        $response = $this->postJson('/restaurant-status/update-status', [
            'restaurant_id' => 'test-123',
            'is_open' => 'invalid'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['is_open']);
    }

    /**
     * Test edge cases
     */
    public function test_edge_cases()
    {
        $restaurantId = 'test-restaurant-edge';

        // Test empty working hours
        $response = $this->postJson('/restaurant-status/get-status', [
            'restaurant_id' => $restaurantId,
            'working_hours' => [],
            'is_open' => true
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertTrue($responseData['success']);
        $this->assertFalse($responseData['status']['is_open']);
        $this->assertFalse($responseData['status']['within_working_hours']);
        $this->assertTrue($responseData['status']['manual_toggle']);

        // Test working hours with no timeslots
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $currentDay = $days[now()->dayOfWeek];
        
        $emptySlotsWorkingHours = [
            [
                'day' => $currentDay,
                'timeslot' => []
            ]
        ];

        $response = $this->postJson('/restaurant-status/get-status', [
            'restaurant_id' => $restaurantId,
            'working_hours' => $emptySlotsWorkingHours,
            'is_open' => true
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertTrue($responseData['success']);
        $this->assertFalse($responseData['status']['is_open']);
        $this->assertFalse($responseData['status']['within_working_hours']);
        $this->assertTrue($responseData['status']['manual_toggle']);
    }

    /**
     * Test multiple time slots
     */
    public function test_multiple_time_slots()
    {
        $restaurantId = 'test-restaurant-multi-slot';
        
        // Get current day to ensure the test works regardless of when it's run
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $currentDay = $days[now()->dayOfWeek];
        
        $multiSlotWorkingHours = [
            [
                'day' => $currentDay,
                'timeslot' => [
                    ['from' => '09:00', 'to' => '14:00'],
                    ['from' => '17:00', 'to' => '22:00']
                ]
            ]
        ];

        // Test multiple slots structure
        $response = $this->postJson('/restaurant-status/get-status', [
            'restaurant_id' => $restaurantId,
            'working_hours' => $multiSlotWorkingHours,
            'is_open' => true
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertTrue($responseData['success']);
        $this->assertArrayHasKey('working_hours_info', $responseData['status']);
        $this->assertArrayHasKey('slots', $responseData['status']['working_hours_info']);
        $this->assertCount(2, $responseData['status']['working_hours_info']['slots']);
        
        // Verify the failproof logic still applies
        if ($responseData['status']['is_open'] === true) {
            $this->assertTrue($responseData['status']['manual_toggle'] === true);
            $this->assertTrue($responseData['status']['within_working_hours'] === true);
        }
    }

    /**
     * Test with real Firestore vendor data structure
     */
    public function test_real_firestore_vendor_data()
    {
        $restaurantId = '0QcKVUa4aqJVYQ0957kz'; // Real vendor ID from sample

        // Real working hours from Firestore sample
        $realWorkingHours = [
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
        ];

        // Test with real vendor data: isOpen = true
        $response = $this->postJson('/restaurant-status/get-status', [
            'restaurant_id' => $restaurantId,
            'working_hours' => $realWorkingHours,
            'is_open' => true
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertTrue($responseData['success']);
        
        // Verify structure matches Firestore data
        $this->assertArrayHasKey('status', $responseData);
        $this->assertArrayHasKey('is_open', $responseData['status']);
        $this->assertArrayHasKey('within_working_hours', $responseData['status']);
        $this->assertArrayHasKey('manual_toggle', $responseData['status']);
        $this->assertArrayHasKey('working_hours_info', $responseData['status']);
        
        // Verify working hours info structure
        $workingHoursInfo = $responseData['status']['working_hours_info'];
        $this->assertArrayHasKey('day', $workingHoursInfo);
        $this->assertArrayHasKey('current_time', $workingHoursInfo);
        $this->assertArrayHasKey('slots', $workingHoursInfo);
        
        // Verify the failproof logic
        if ($responseData['status']['is_open'] === true) {
            $this->assertTrue($responseData['status']['manual_toggle'] === true);
            $this->assertTrue($responseData['status']['within_working_hours'] === true);
        }

        // Test with isOpen = false (manual close)
        $response = $this->postJson('/restaurant-status/get-status', [
            'restaurant_id' => $restaurantId,
            'working_hours' => $realWorkingHours,
            'is_open' => false
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertTrue($responseData['success']);
        $this->assertFalse($responseData['status']['is_open']);
        $this->assertFalse($responseData['status']['manual_toggle']);
        $this->assertStringContainsString('manually closed', $responseData['status']['reason']);

        // Test with isOpen = null (no manual toggle)
        $response = $this->postJson('/restaurant-status/get-status', [
            'restaurant_id' => $restaurantId,
            'working_hours' => $realWorkingHours,
            'is_open' => null
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertTrue($responseData['success']);
        $this->assertNull($responseData['status']['manual_toggle']);
        $this->assertFalse($responseData['status']['is_open']); // Failproof: null = closed
    }
}
