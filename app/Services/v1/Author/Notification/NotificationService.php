<?php

namespace App\Services\v1\Author\Notification;

use App\Repositories\v1\Interface\Author\Notification\INotification;

class NotificationService
{
    protected INotification $notification;

    public function __construct(INotification $notification)
    {
        $this->notification = $notification;
    }

    public function getUnread()
    {
        return $this->notification->getUnread();
    }


    public function delete($model)
    {
        return $this->notification->delete($model);
    }

    public function getNotifications($limit)
    {
        return $this->notification->getNotifications($limit);
    }

}
