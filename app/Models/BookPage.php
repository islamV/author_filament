<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookPage extends Model
{
    protected $fillable = [
        'page_number',
        'content',
        'comment',
        'book_part_id',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function bookPart()
    {
        return $this->belongsTo(book_parts::class,'book_part_id');
    }
}
