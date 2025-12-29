<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminPaymentDetail extends Model
{
    protected $fillable = [
        'id',
        'phone_number',
        'receiver_name',
        'account_number',
        'bank_name',
    ];
}
