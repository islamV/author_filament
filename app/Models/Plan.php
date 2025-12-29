<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'features',
        'description',
        'max_downloads',
        'reward_points',
        'duration',
        'discount_value',
        'discount_type',
    ];

    protected $casts = [
        'features'=>'array',
        'duration' => 'integer',
        'discount_value' => 'decimal:2',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
