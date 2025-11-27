<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'value',
        'user_id',
        'is_used',
        'used_by',
        'used_at',
        'expires_at',
        'message'
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // Người nhận
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Người sử dụng
    public function usedBy()
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    // Kiểm tra còn hiệu lực không
    public function isValid()
    {
        if ($this->is_used) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }
}
