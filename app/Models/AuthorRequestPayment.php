<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorRequestPayment extends Model
{
    protected $fillable = [
        'requested_amount',
        'user_id',
        'status',
        'payment_method',
        'phone',
        'beneficiary_name',
        'bank_name',
        'iban',
        'wallet_id',
        'wallet_name',
        'email_binance',
        'beneficiary_address',
        'swift_bio_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->status = 'pending';
            $model->user_id = auth()->id();
        });
    }
}
