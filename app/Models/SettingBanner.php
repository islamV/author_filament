<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingBanner extends Model
{
    protected $fillable = [
        'setting_id',
        'image',
        'order',
    ];

    public function setting()
    {
        return $this->belongsTo(Setting::class);
    }
}
