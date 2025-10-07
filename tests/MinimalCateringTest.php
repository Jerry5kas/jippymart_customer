<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MinimalCateringTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful catering request
     */
    public function test_create_catering_request_success()
    {
        $data = [
            'name' => 'John Doe',
            'mobile' => '9876543210',
            'email' => 'john@example.com',
            'place' => 'Grand Hotel, Mumbai',
            'date' => '2024-12-25',
            'guests' => 50,
            'function_type' => 'Wedding',
            'meal_preference' => 'veg'
        ];

        $response = $this->postJson('/api/catering/requests', $data);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Catering request submitted successfully'
                ])
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'reference_number',
                        'status',
                        'created_at',
                        'email_sent'
                    ]
                ]);
    }

    /**
     * Test validation errors
     */
    public function test_validation_errors()
    {
        $data = [
            'name' => 'J', // Too short
            'mobile' => '1234567890', // Invalid format
            'date' => '2023-01-01', // Past date
            'guests' => 0, // Too few
            'meal_preference' => 'invalid' // Invalid option
        ];

        $response = $this->postJson('/api/catering/requests', $data);

        $response->assertStatus(422)
                ->assertJson([
                    'success' => false,
                    'message' => 'Validation failed'
                ]);
    }

    /**
     * Test spam detection
     */
    public function test_spam_detection()
    {
        $data = [
            'name' => 'Spam User',
            'mobile' => '9876543210',
            'place' => 'Test Venue for spam',
            'date' => '2024-12-25',
            'guests' => 10,
            'function_type' => 'Wedding',
            'meal_preference' => 'veg'
        ];

        $response = $this->postJson('/api/catering/requests', $data);

        $response->assertStatus(400)
                ->assertJson([
                    'success' => false,
                    'message' => 'Request blocked'
                ]);
    }

    /**
     * Test get request by ID
     */
    public function test_get_request_by_id()
    {
        // First create a request
        $data = [
            'name' => 'John Doe',
            'mobile' => '9876543210',
            'place' => 'Grand Hotel, Mumbai',
            'date' => '2024-12-25',
            'guests' => 50,
            'function_type' => 'Wedding',
            'meal_preference' => 'veg'
        ];

        $createResponse = $this->postJson('/api/catering/requests', $data);
        $requestId = $createResponse->json('data.id');

        // Then get the request
        $response = $this->getJson("/api/catering/requests/{$requestId}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true
                ]);
    }
}
