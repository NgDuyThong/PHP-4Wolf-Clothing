<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_order_value',
        'max_discount',
        'usage_limit',
        'usage_count',
        'usage_per_user',
        'start_date',
        'end_date',
        'is_active',
        'image'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Kiểm tra mã có hợp lệ không
    public function isValid()
    {
        $now = Carbon::now();
        
        if (!$this->is_active) {
            return false;
        }

        if ($now->lt($this->start_date) || $now->gt($this->end_date)) {
            return false;
        }

        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    // Kiểm tra user đã dùng mã này bao nhiêu lần
    public function getUserUsageCount($userId)
    {
        return $this->usages()->where('user_id', $userId)->count();
    }

    // Kiểm tra user có thể dùng mã này không
    public function canBeUsedByUser($userId)
    {
        return $this->getUserUsageCount($userId) < $this->usage_per_user;
    }

    // Tính số tiền được giảm
    public function calculateDiscount($orderTotal)
    {
        if ($orderTotal < $this->min_order_value) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = ($orderTotal * $this->value) / 100;
            
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
            
            return $discount;
        }

        // Fixed amount
        return min($this->value, $orderTotal);
    }

    // Relationships
    public function usages()
    {
        return $this->hasMany(PromotionUsage::class);
    }

    // Scope để lấy các mã đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('start_date', '<=', Carbon::now())
                    ->where('end_date', '>=', Carbon::now());
    }
}
