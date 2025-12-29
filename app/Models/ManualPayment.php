<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ManualPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'user_id',
        'bank_name',
        'account_number',
        'beneficiary_name',
        'wallet_id',
        'wallet_name',
        'amount',
        'payment_screen_shot',
        'payment_date',
        'payment_address',
        'sender_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
