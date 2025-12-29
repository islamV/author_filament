<?php

namespace App\Repositories\v1\Interface\Author\Notification;

interface INotification
{
    public function getUnread();
    public function getNotifications($limit);

    public function delete($model);
}
