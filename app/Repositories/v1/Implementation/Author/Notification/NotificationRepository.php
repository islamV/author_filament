<?php

namespace App\Repositories\v1\Implementation\Author\Notification;

use App\Models\UserNotification;
use App\Models\User;
use App\Repositories\v1\Interface\Author\Notification\INotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationRepository implements INotification
{
    public function getUnread()
    {
        $user = Auth::user();
        return $user->notifications()
            ->wherePivot('is_read', false)
            ->count();
    }

    public function delete($model)
    {
        DB::transaction(function () use ($model) {
            $user = Auth::user();

            // Delete the notification reference from the pivot table
            DB::table('user_notification_user')
                ->where('user_id', $user->id)
                ->where('notification_id', $model->id)
                ->delete();

            // Optionally delete the notification record itself if no users are linked
            if (!DB::table('user_notification_user')->where('notification_id', $model->id)->exists()) {
                UserNotification::findOrFail($model->id)->delete();
            }
        });
    }

    public function getNotifications($limit)
    {
        $user = Auth::user();

        $notifications = $user->notifications()
            ->orderBy('created_at', 'DESC')
            ->paginate($limit);

        $user->notifications()
            ->wherePivot('is_read', false)
            ->whereIn('id', $notifications->pluck('id')->toArray())
            ->updateExistingPivot(
                $notifications->pluck('id')->toArray(),
                ['is_read' => true]
            );

        return $notifications;
    }
}
