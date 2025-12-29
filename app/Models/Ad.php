<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];
}

