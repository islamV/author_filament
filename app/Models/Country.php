<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'view_count',
        'price',
        'desktop_price'
    ];


    protected $casts = [
        'view_count' => 'integer',
        'price' => 'float',
        'desktop_price' => 'float'
    ];
}
