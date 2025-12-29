<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_gateway',
        'phone_number',
        'account_number',
        'bank_name',
        'receiver_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}

