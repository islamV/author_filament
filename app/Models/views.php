<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class views extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'user_id',
        'viewable_id',
        'viewable_type',
        'country',
        'country_id',
        'counted',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function viewable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
