<?php

namespace App\Packages;

use App\Constants\NotificationCategory;
use App\Constants\NotificationPriority;
use App\Constants\NotificationSubCategory;
use App\Constants\NotificationType;
use App\Models\User;
use App\Services\NotificationService;

class Notification
{
    public function onLoginSuccess(User $user): void
    {
        $device = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile")) ? 'mobile' : 'pc';
        $messageFields = $device;
        $service = new NotificationService();
        $service->store($user->id, NotificationType::USER, NotificationCategory::ACCOUNT, NotificationSubCategory::LOGIN_SUCCEED, $messageFields, NotificationPriority::NORMAL, date('Y-m-d H:i:s'));
    }

    public function onLogout(User $user): void
    {
        $service = new NotificationService();
        $service->store($user->id, NotificationType::USER, NotificationCategory::ACCOUNT, NotificationSubCategory::LOGOUT, null, NotificationPriority::NORMAL, date('Y-m-d H:i:s'));
    }
}
