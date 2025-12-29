<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatType extends Model
{
    protected $fillable = ['type'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
