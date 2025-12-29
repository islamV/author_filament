<?php

namespace App\Http\Controllers\v1\Author\Notification;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Author\Notification\NotificationResource;
use App\Models\UserNotification;
use App\Services\v1\Author\Notification\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private NotificationService $notificationService;
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    

    public function getUnread(Request $request)
    {
        return $this->returnData(__('messages.notification.unread'),200,
            ['unread' => $this->notificationService->getUnread()]);
    }

    public function getNotifications(Request $request)
    {
        return $this->returnData(__('messages.notification.list'),200,
            $this->notificationService->getNotifications($request->get('per_page',10)));
    }
    public function delete(UserNotification $notification)
    {
        $this->notificationService->delete($notification);
        return $this->success(__('messages.notification.deleted'),200);
    }
}
