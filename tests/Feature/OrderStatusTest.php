<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Helpers\OrderStatusHelper;

class OrderStatusTest extends TestCase
{
    /**
     * Test all status constants are defined
     */
    public function test_all_status_constants_defined()
    {
        $this->assertIsInt(Order::STATUS_ORDER['pending']);
        $this->assertIsInt(Order::STATUS_ORDER['confirmed']);
        $this->assertIsInt(Order::STATUS_ORDER['cancelled']);
        $this->assertIsInt(Order::STATUS_ORDER['completed']);
        $this->assertIsInt(Order::STATUS_ORDER['shipping']);
        $this->assertIsInt(Order::STATUS_ORDER['preparing']);
        $this->assertIsInt(Order::STATUS_ORDER['shipped']);
        $this->assertIsInt(Order::STATUS_ORDER['delivery_failed']);
        $this->assertIsInt(Order::STATUS_ORDER['payment_pending']);
        $this->assertIsInt(Order::STATUS_ORDER['paid']);
        $this->assertIsInt(Order::STATUS_ORDER['returning']);
        $this->assertIsInt(Order::STATUS_ORDER['refunded']);
        $this->assertIsInt(Order::STATUS_ORDER['cancel_pending']);
    }

    /**
     * Test status values are correct
     */
    public function test_status_values_are_correct()
    {
        $this->assertEquals(0, Order::STATUS_ORDER['pending']);
        $this->assertEquals(1, Order::STATUS_ORDER['confirmed']);
        $this->assertEquals(2, Order::STATUS_ORDER['cancelled']);
        $this->assertEquals(3, Order::STATUS_ORDER['completed']);
        $this->assertEquals(4, Order::STATUS_ORDER['shipping']);
        $this->assertEquals(5, Order::STATUS_ORDER['preparing']);
        $this->assertEquals(6, Order::STATUS_ORDER['shipped']);
        $this->assertEquals(7, Order::STATUS_ORDER['delivery_failed']);
        $this->assertEquals(8, Order::STATUS_ORDER['payment_pending']);
        $this->assertEquals(9, Order::STATUS_ORDER['paid']);
        $this->assertEquals(10, Order::STATUS_ORDER['returning']);
        $this->assertEquals(11, Order::STATUS_ORDER['refunded']);
        $this->assertEquals(12, Order::STATUS_ORDER['cancel_pending']);
    }

    /**
     * Test OrderStatusHelper returns correct names
     */
    public function test_status_helper_returns_correct_names()
    {
        $this->assertEquals('Chờ xử lý', OrderStatusHelper::getStatusName(0));
        $this->assertEquals('Đã xác nhận', OrderStatusHelper::getStatusName(1));
        $this->assertEquals('Đã hủy', OrderStatusHelper::getStatusName(2));
        $this->assertEquals('Đã nhận hàng', OrderStatusHelper::getStatusName(3));
        $this->assertEquals('Đang giao hàng', OrderStatusHelper::getStatusName(4));
        $this->assertEquals('Đang chuẩn bị hàng', OrderStatusHelper::getStatusName(5));
    }

    /**
     * Test OrderStatusHelper returns correct badge classes
     */
    public function test_status_helper_returns_correct_badge_classes()
    {
        $this->assertEquals('badge bg-warning', OrderStatusHelper::getStatusBadgeClass(0));
        $this->assertEquals('badge bg-info', OrderStatusHelper::getStatusBadgeClass(1));
        $this->assertEquals('badge bg-danger', OrderStatusHelper::getStatusBadgeClass(2));
        $this->assertEquals('badge bg-success', OrderStatusHelper::getStatusBadgeClass(3));
        $this->assertEquals('badge bg-primary', OrderStatusHelper::getStatusBadgeClass(4));
    }

    /**
     * Test status transitions
     */
    public function test_status_transitions()
    {
        // Pending can transition to confirmed
        $this->assertTrue(OrderStatusHelper::canTransition(
            Order::STATUS_ORDER['pending'],
            Order::STATUS_ORDER['confirmed']
        ));

        // Confirmed can transition to preparing
        $this->assertTrue(OrderStatusHelper::canTransition(
            Order::STATUS_ORDER['confirmed'],
            Order::STATUS_ORDER['preparing']
        ));

        // Preparing can transition to shipped
        $this->assertTrue(OrderStatusHelper::canTransition(
            Order::STATUS_ORDER['preparing'],
            Order::STATUS_ORDER['shipped']
        ));

        // Shipped can transition to shipping
        $this->assertTrue(OrderStatusHelper::canTransition(
            Order::STATUS_ORDER['shipped'],
            Order::STATUS_ORDER['shipping']
        ));

        // Shipping can transition to completed
        $this->assertTrue(OrderStatusHelper::canTransition(
            Order::STATUS_ORDER['shipping'],
            Order::STATUS_ORDER['completed']
        ));

        // Completed cannot transition to pending (invalid)
        $this->assertFalse(OrderStatusHelper::canTransition(
            Order::STATUS_ORDER['completed'],
            Order::STATUS_ORDER['pending']
        ));
    }

    /**
     * Test getAllStatuses returns all statuses
     */
    public function test_get_all_statuses()
    {
        $allStatuses = OrderStatusHelper::getAllStatuses();
        
        $this->assertIsArray($allStatuses);
        $this->assertCount(13, $allStatuses);
        
        // Check structure
        foreach ($allStatuses as $status => $info) {
            $this->assertArrayHasKey('name', $info);
            $this->assertArrayHasKey('badge', $info);
            $this->assertArrayHasKey('description', $info);
        }
    }
}
