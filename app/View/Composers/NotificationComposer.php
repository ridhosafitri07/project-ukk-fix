<?php

namespace App\View\Composers;

use App\Services\NotificationService;
use Illuminate\View\View;

class NotificationComposer
{
    public function compose(View $view)
    {
        $userNotifications = NotificationService::getUserNotifications();
        $unreadNotificationsCount = NotificationService::getUnreadCount();

        $view->with([
            'userNotifications' => $userNotifications,
            'unreadNotificationsCount' => $unreadNotificationsCount
        ]);
    }
}
