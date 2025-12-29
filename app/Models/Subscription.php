<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Plan;

class Subscription extends Model
{
    protected $fillable = [
        'plan_id',
        'user_id',
        'status',
        'payment_gateway_id',
        'downloads',
        'end_date',
    ];

    protected $casts = [
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }


    public function paymentGateway()
    {
        return $this->belongsTo(PaymentGateway::class);
    }


    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    
    public function manualPayment()
    {
        return $this->hasOne(ManualPayment::class);
    }
}
