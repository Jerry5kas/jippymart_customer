<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\DeliveryChargeService;

class DeliveryChargeServiceTest extends TestCase
{
    protected $deliveryChargeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->deliveryChargeService = new DeliveryChargeService();
    }

    /**
     * Test delivery charge calculation for item total below threshold
     */
    public function test_delivery_charge_below_threshold()
    {
        $itemTotal = 250; // Below 299 threshold
        $distance = 5; // Within 7km free distance
        
        $result = $this->deliveryChargeService->calculateDeliveryCharge($itemTotal, $distance);
        
        $this->assertEquals(23, $result['actual_fee']); // Base charge
        $this->assertEquals(23, $result['original_fee']); // Same as actual
        $this->assertFalse($result['is_free_delivery']);
        $this->assertEquals(0, $result['savings']);
    }

    /**
     * Test delivery charge calculation for item total below threshold with extra distance
     */
    public function test_delivery_charge_below_threshold_extra_distance()
    {
        $itemTotal = 250; // Below 299 threshold
        $distance = 10; // Beyond 7km free distance
        
        $result = $this->deliveryChargeService->calculateDeliveryCharge($itemTotal, $distance);
        
        $expectedOriginalFee = 23 + (3 * 8); // Base + 3km extra
        $this->assertEquals($expectedOriginalFee, $result['actual_fee']);
        $this->assertEquals($expectedOriginalFee, $result['original_fee']);
        $this->assertFalse($result['is_free_delivery']);
        $this->assertEquals(0, $result['savings']);
    }

    /**
     * Test free delivery for item total above threshold within free distance
     */
    public function test_free_delivery_above_threshold_within_distance()
    {
        $itemTotal = 350; // Above 299 threshold
        $distance = 5; // Within 7km free distance
        
        $result = $this->deliveryChargeService->calculateDeliveryCharge($itemTotal, $distance);
        
        $this->assertEquals(0, $result['actual_fee']); // Free delivery
        $this->assertEquals(23, $result['original_fee']); // Original fee
        $this->assertTrue($result['is_free_delivery']);
        $this->assertEquals(23, $result['savings']);
    }

    /**
     * Test delivery charge for item total above threshold beyond free distance
     */
    public function test_delivery_charge_above_threshold_beyond_distance()
    {
        $itemTotal = 350; // Above 299 threshold
        $distance = 10; // Beyond 7km free distance
        
        $result = $this->deliveryChargeService->calculateDeliveryCharge($itemTotal, $distance);
        
        $expectedOriginalFee = 23 + (3 * 8); // Base + 3km extra
        $expectedActualFee = 3 * 8; // Only extra km charge
        
        $this->assertEquals($expectedActualFee, $result['actual_fee']);
        $this->assertEquals($expectedOriginalFee, $result['original_fee']);
        $this->assertFalse($result['is_free_delivery']);
        $this->assertEquals(23, $result['savings']); // Base charge saved
    }

    /**
     * Test UI components for different scenarios
     */
    public function test_ui_components()
    {
        // Test normal fee
        $result = $this->deliveryChargeService->calculateDeliveryCharge(250, 5);
        $uiComponents = $result['ui_components'];
        $this->assertEquals('normal', $uiComponents['type']);
        $this->assertEquals('₹23.00', $uiComponents['main_text']);
        $this->assertFalse($uiComponents['strikethrough']);

        // Test free delivery
        $result = $this->deliveryChargeService->calculateDeliveryCharge(350, 5);
        $uiComponents = $result['ui_components'];
        $this->assertEquals('free_delivery', $uiComponents['type']);
        $this->assertEquals('Free Delivery', $uiComponents['main_text']);
        $this->assertEquals('₹23.00', $uiComponents['sub_text']);
        $this->assertEquals('₹0.00', $uiComponents['charged_amount']);
        $this->assertTrue($uiComponents['strikethrough']);

        // Test extra distance
        $result = $this->deliveryChargeService->calculateDeliveryCharge(350, 10);
        $uiComponents = $result['ui_components'];
        $this->assertEquals('extra_distance', $uiComponents['type']);
        $this->assertEquals('Free Delivery', $uiComponents['main_text']);
        $this->assertEquals('₹47.00', $uiComponents['sub_text']);
        $this->assertEquals('₹24.00', $uiComponents['charged_amount']);
        $this->assertTrue($uiComponents['strikethrough']);
    }

    /**
     * Test cart integration
     */
    public function test_cart_integration()
    {
        $cart = [
            'item' => [
                'restaurant1' => [
                    'item1' => [
                        'item_price' => 100,
                        'extra_price' => 10,
                        'quantity' => 2
                    ],
                    'item2' => [
                        'item_price' => 50,
                        'extra_price' => 5,
                        'quantity' => 1
                    ]
                ]
            ],
            'deliverykm' => 5
        ];

        $updatedCart = $this->deliveryChargeService->updateCartDeliveryCharge($cart);
        
        $this->assertArrayHasKey('deliverycharge', $updatedCart);
        $this->assertArrayHasKey('deliverychargemain', $updatedCart);
        $this->assertArrayHasKey('delivery_charge_calculation', $updatedCart);
        
        // Item total: (100+10)*2 + (50+5)*1 = 220 + 55 = 275
        // Distance: 5km, below threshold, so should be base charge
        $this->assertEquals(23, $updatedCart['deliverycharge']);
    }
}
