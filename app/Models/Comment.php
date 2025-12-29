<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory , SoftDeletes ;

    protected $fillable = [
        'book_id', 'user_id',
        'comment', 'parent_id', 
        'is_author_reply' 
    ];


    protected static function booted()
    {
        static::deleting(function ($comment) {
            foreach ($comment->replies as $reply) {
                $reply->delete(); 
            }
        });
    }


    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }


    public function isReply()
    {
        return !is_null($this->parent_id);
    }
}
