<?php

namespace App\Models;

use App\Services\v1\Dashboard\Notification\NotificationService;
use App\Traits\HasViewsTrait;
use App\Traits\Rateable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Book extends Model
{
    use HasFactory, HasViewsTrait, Rateable;

    protected $fillable = [
        'title',
        'user_id',
        'description',
        'image',
        'pdf_path',
        'category_id',
        'section_id',
        'status', 
        'refusal_reason',
        'publish_status',
        'scheduled_until',

    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'scheduled_until' => 'datetime:Y-m-d H:i:s',
    ];

    protected static function booted()
    {
        static::creating(function ($book) {
            // Only set user_id if it's not already set (e.g., from Filament form)
            if (!isset($book->user_id) && Auth::check()) {
                $book->user_id = Auth::id();
            }
            
            // Validate that user_id exists
            if (isset($book->user_id)) {
                $user = User::find($book->user_id);
                if (!$user) {
                    throw new \Exception("User with ID {$book->user_id} does not exist.");
                }
            }
            
            // Only set is_published if user_id was auto-set (API context)
           /* if (!isset($book->is_published) && Auth::check() && isset($book->user_id) && $book->user_id == Auth::id()) {
                if (Auth::user()->role_id == 2)
                    $book->is_published = 1;
                else
                    $book->is_published = 0;
            }*/
        });
        static::created(function ($book) {
            if ($book->is_published == 1) {
                // $notificationService = app()->make(NotificationService::class);
                // $notificationService->sendNotificationToFollowers($book->user, $book);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function book_parts()
    {
        return $this->hasMany(book_parts::class,'book_id');
    }

    public function favorites()
    {
        return $this->morphMany(Favourite::class, 'favoritable');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewer_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'book_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class); 
    }


    
}

