<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'subscribed_at'
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
    ];

    public $timestamps = true;
}
