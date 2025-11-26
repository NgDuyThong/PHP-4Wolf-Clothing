<?php

namespace App\Models;

use App\Notifications\VerifyUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'google_id',
        'role_id',
        'phone_number',
        'email_verified_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'active',
        'disable_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id', 'user_id')->setEagerLoads([]);
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'id', 'user_id')->setEagerLoads([]);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id')->setEagerLoads([]);
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function resendEmailVerificationNotification($token)
    {
        $this->notify(new VerifyUser($token));
    }

    /**
     * Hash the password before inserting database.
     * Chỉ hash nếu password chưa được hash (không bắt đầu bằng $2y$, $2a$, $2b$)
     *
     * @var string $value
     */
    public function setPasswordAttribute($value)
    {
        // Nếu password rỗng hoặc null, giữ nguyên (cho trường hợp đăng nhập Google)
        if (empty($value)) {
            $this->attributes['password'] = $value;
            return;
        }
        
        // Kiểm tra xem password đã được hash chưa
        // Bcrypt hash có độ dài 60 ký tự và bắt đầu bằng $2y$, $2a$, hoặc $2b$
        $isHashed = strlen($value) === 60 && (
            substr($value, 0, 4) === '$2y$' || 
            substr($value, 0, 4) === '$2a$' || 
            substr($value, 0, 4) === '$2b$'
        );
        
        // Chỉ hash nếu password chưa được hash
        if (!$isHashed) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }
}
