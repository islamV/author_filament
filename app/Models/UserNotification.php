<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $table = 'user_notifications';

    protected $fillable = ['title', 'content', 'is_read', 'user_id', 'type', 'notifiable_type', 'notifiable_id', 'notification_title', 'data', 'read_at'];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    protected $hidden = ['pivot', 'updated_at', 'created_at'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_notification_user', 'notification_id', 'user_id')
            ->withPivot('is_read')
            ->withTimestamps();
    }
}

