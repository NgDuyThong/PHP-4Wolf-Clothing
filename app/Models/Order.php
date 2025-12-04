<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Order extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'payment_id',
        'promotion_id',
        'user_id',
        'total_money',
        'discount_amount',
        'order_status',
        'transport_fee',
        'note',
        'payment_status',
        'address',
        'phone',
        'email',
        'name'
    ];

    // Trạng thái đơn hàng
    const STATUS_ORDER = [
        'pending' => 0,              // Chờ xử lý
        'confirmed' => 1,            // Đã xác nhận
        'cancelled' => 2,            // Đã hủy
        'completed' => 3,            // Đã nhận hàng
        'shipping' => 4,             // Đang giao hàng
        'preparing' => 5,            // Đang chuẩn bị hàng
        'shipped' => 6,              // Đã giao cho đơn vị vận chuyển
        'delivery_failed' => 7,      // Giao hàng thất bại
        'payment_pending' => 8,      // Chờ thanh toán
        'paid' => 9,                 // Đã thanh toán
        'returning' => 10,           // Hoàn trả/Đổi hàng
        'refunded' => 11,            // Đã hoàn tiền
        'cancel_pending' => 12,      // Chờ xác nhận hủy
    ];

    // Trạng thái thanh toán
    const PAYMENT_STATUS = [
        'unpaid' => 0,               // Chưa thanh toán
        'paid' => 1,                 // Đã thanh toán
        'refunded' => 2,             // Đã hoàn tiền
    ];

    const ORDER_NUMBER_ITEM = [
        'history' => 10,
    ];

    // Relationships
    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
