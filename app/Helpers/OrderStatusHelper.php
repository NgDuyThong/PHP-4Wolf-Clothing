<?php

namespace App\Helpers;

use App\Models\Order;

class OrderStatusHelper
{
    /**
     * Lấy tên hiển thị của trạng thái đơn hàng
     *
     * @param int $status
     * @return string
     */
    public static function getStatusName($status)
    {
        $statusNames = [
            Order::STATUS_ORDER['pending'] => 'Chờ xử lý',
            Order::STATUS_ORDER['confirmed'] => 'Đã xác nhận',
            Order::STATUS_ORDER['cancelled'] => 'Đã hủy',
            Order::STATUS_ORDER['completed'] => 'Đã nhận hàng',
            Order::STATUS_ORDER['shipping'] => 'Đang giao hàng',
            Order::STATUS_ORDER['preparing'] => 'Đang chuẩn bị hàng',
            Order::STATUS_ORDER['shipped'] => 'Đã giao cho ĐVVC',
            Order::STATUS_ORDER['delivery_failed'] => 'Giao hàng thất bại',
            Order::STATUS_ORDER['payment_pending'] => 'Chờ thanh toán',
            Order::STATUS_ORDER['paid'] => 'Đã thanh toán',
            Order::STATUS_ORDER['returning'] => 'Hoàn trả/Đổi hàng',
            Order::STATUS_ORDER['refunded'] => 'Đã hoàn tiền',
            Order::STATUS_ORDER['cancel_pending'] => 'Chờ xác nhận hủy',
        ];

        return $statusNames[$status] ?? 'Không xác định';
    }

    /**
     * Lấy class badge của trạng thái
     *
     * @param int $status
     * @return string
     */
    public static function getStatusBadgeClass($status)
    {
        $badgeClasses = [
            Order::STATUS_ORDER['pending'] => 'badge bg-warning',
            Order::STATUS_ORDER['confirmed'] => 'badge bg-info',
            Order::STATUS_ORDER['cancelled'] => 'badge bg-danger',
            Order::STATUS_ORDER['completed'] => 'badge bg-success',
            Order::STATUS_ORDER['shipping'] => 'badge bg-primary',
            Order::STATUS_ORDER['preparing'] => 'badge bg-info',
            Order::STATUS_ORDER['shipped'] => 'badge bg-primary',
            Order::STATUS_ORDER['delivery_failed'] => 'badge bg-warning',
            Order::STATUS_ORDER['payment_pending'] => 'badge bg-secondary',
            Order::STATUS_ORDER['paid'] => 'badge bg-success',
            Order::STATUS_ORDER['returning'] => 'badge bg-warning',
            Order::STATUS_ORDER['refunded'] => 'badge bg-info',
            Order::STATUS_ORDER['cancel_pending'] => 'badge bg-secondary',
        ];

        return $badgeClasses[$status] ?? 'badge bg-secondary';
    }

    /**
     * Lấy danh sách tất cả trạng thái
     *
     * @return array
     */
    public static function getAllStatuses()
    {
        return [
            Order::STATUS_ORDER['pending'] => [
                'name' => 'Chờ xử lý',
                'badge' => 'badge bg-warning',
                'description' => 'Đơn hàng mới được tạo, chờ xử lý'
            ],
            Order::STATUS_ORDER['confirmed'] => [
                'name' => 'Đã xác nhận',
                'badge' => 'badge bg-info',
                'description' => 'Admin đã xác nhận đơn hàng'
            ],
            Order::STATUS_ORDER['preparing'] => [
                'name' => 'Đang chuẩn bị hàng',
                'badge' => 'badge bg-info',
                'description' => 'Đang đóng gói sản phẩm'
            ],
            Order::STATUS_ORDER['shipped'] => [
                'name' => 'Đã giao cho ĐVVC',
                'badge' => 'badge bg-primary',
                'description' => 'Đã chuyển cho đơn vị vận chuyển'
            ],
            Order::STATUS_ORDER['shipping'] => [
                'name' => 'Đang giao hàng',
                'badge' => 'badge bg-primary',
                'description' => 'Đơn hàng đang được giao'
            ],
            Order::STATUS_ORDER['delivery_failed'] => [
                'name' => 'Giao hàng thất bại',
                'badge' => 'badge bg-warning',
                'description' => 'Không liên lạc được khách hoặc địa chỉ sai'
            ],
            Order::STATUS_ORDER['completed'] => [
                'name' => 'Đã nhận hàng',
                'badge' => 'badge bg-success',
                'description' => 'Khách hàng đã nhận hàng thành công'
            ],
            Order::STATUS_ORDER['payment_pending'] => [
                'name' => 'Chờ thanh toán',
                'badge' => 'badge bg-secondary',
                'description' => 'Chờ khách hàng thanh toán'
            ],
            Order::STATUS_ORDER['paid'] => [
                'name' => 'Đã thanh toán',
                'badge' => 'badge bg-success',
                'description' => 'Đã xác nhận thanh toán'
            ],
            Order::STATUS_ORDER['returning'] => [
                'name' => 'Hoàn trả/Đổi hàng',
                'badge' => 'badge bg-warning',
                'description' => 'Khách yêu cầu đổi/trả hàng'
            ],
            Order::STATUS_ORDER['refunded'] => [
                'name' => 'Đã hoàn tiền',
                'badge' => 'badge bg-info',
                'description' => 'Đã hoàn tiền cho khách hàng'
            ],
            Order::STATUS_ORDER['cancel_pending'] => [
                'name' => 'Chờ xác nhận hủy',
                'badge' => 'badge bg-secondary',
                'description' => 'Khách yêu cầu hủy, chờ admin xác nhận'
            ],
            Order::STATUS_ORDER['cancelled'] => [
                'name' => 'Đã hủy',
                'badge' => 'badge bg-danger',
                'description' => 'Đơn hàng đã bị hủy'
            ],
        ];
    }

    /**
     * Kiểm tra xem có thể chuyển từ trạng thái này sang trạng thái khác không
     *
     * @param int $currentStatus
     * @param int $newStatus
     * @return bool
     */
    public static function canTransition($currentStatus, $newStatus)
    {
        $allowedTransitions = [
            Order::STATUS_ORDER['pending'] => [
                Order::STATUS_ORDER['confirmed'],
                Order::STATUS_ORDER['payment_pending'],
                Order::STATUS_ORDER['cancel_pending'],
                Order::STATUS_ORDER['cancelled']
            ],
            Order::STATUS_ORDER['payment_pending'] => [
                Order::STATUS_ORDER['paid'],
                Order::STATUS_ORDER['cancelled']
            ],
            Order::STATUS_ORDER['confirmed'] => [
                Order::STATUS_ORDER['preparing'],
                Order::STATUS_ORDER['cancelled']
            ],
            Order::STATUS_ORDER['preparing'] => [
                Order::STATUS_ORDER['shipped'],
                Order::STATUS_ORDER['cancelled']
            ],
            Order::STATUS_ORDER['shipped'] => [
                Order::STATUS_ORDER['shipping']
            ],
            Order::STATUS_ORDER['shipping'] => [
                Order::STATUS_ORDER['completed'],
                Order::STATUS_ORDER['delivery_failed']
            ],
            Order::STATUS_ORDER['delivery_failed'] => [
                Order::STATUS_ORDER['shipping'],
                Order::STATUS_ORDER['cancelled']
            ],
            Order::STATUS_ORDER['completed'] => [
                Order::STATUS_ORDER['returning']
            ],
            Order::STATUS_ORDER['returning'] => [
                Order::STATUS_ORDER['refunded']
            ],
            Order::STATUS_ORDER['cancel_pending'] => [
                Order::STATUS_ORDER['cancelled'],
                Order::STATUS_ORDER['confirmed']
            ],
        ];

        return isset($allowedTransitions[$currentStatus]) && 
               in_array($newStatus, $allowedTransitions[$currentStatus]);
    }
}
