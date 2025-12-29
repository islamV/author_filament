<?php

namespace App\Models;

use App\Traits\HasViewsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class book_parts extends Model
{
    use HasFactory,HasViewsTrait;
    protected $table = 'book_parts';
    protected $fillable = [
        'book_id',
        'chapter',
        'is_published',
        'content',
        'is_reviewed',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'is_reviewed' => 'boolean',
    ];

    public function book()
    {
        return $this->belongsTo(book::class,'book_id');
    }

    public function bookPages()
    {
        return $this->hasMany(BookPage::class,'book_part_id');
    }
}

