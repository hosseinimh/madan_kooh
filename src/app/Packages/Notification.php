<?php

namespace App\Packages;

use App\Constants\NotificationCategory;
use App\Constants\NotificationPriority;
use App\Constants\NotificationSubCategory;
use App\Constants\NotificationType;
use App\Models\TFactor;
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

    public function onLoginFailed(string $username): void
    {
        $username = str_replace('|', '', $username);
        $device = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile")) ? 'mobile' : 'pc';
        $messageFields = $username . '|' . $device;
        $service = new NotificationService();
        $service->store(null, NotificationType::USER, NotificationCategory::SYSTEM, NotificationSubCategory::LOGIN_FAILED, $messageFields, NotificationPriority::NORMAL, date('Y-m-d H:i:s'));
    }

    public function onLogout(User $user): void
    {
        $service = new NotificationService();
        $service->store($user->id, NotificationType::USER, NotificationCategory::ACCOUNT, NotificationSubCategory::LOGOUT, null, NotificationPriority::NORMAL, date('Y-m-d H:i:s'));
    }

    public function onSearchTFactors(User $user): void
    {
        $service = new NotificationService();
        $messageFields = $user->username;
        $service->store($user->id, NotificationType::USER, NotificationCategory::TFACTOR, NotificationSubCategory::SEARCH_TFACTORS, $messageFields, NotificationPriority::NORMAL, date('Y-m-d H:i:s'));
    }

    public function onDeleteTFactors(User $user, string $factorId): void
    {
        $service = new NotificationService();
        $messageFields = $user->username . '|' . $factorId;
        $service->store($user->id, NotificationType::USER, NotificationCategory::TFACTOR, NotificationSubCategory::DELETE_TFACTORS, $messageFields, NotificationPriority::NORMAL, date('Y-m-d H:i:s'));
    }

    public function onEditTFactor(User $user, string $factorId): void
    {
        $service = new NotificationService();
        $messageFields = $factorId . '|' . $user->username;
        $service->store($user->id, NotificationType::USER, NotificationCategory::TFACTOR, NotificationSubCategory::EDIT_FACTOR_DESCRIPTION, $messageFields, NotificationPriority::NORMAL, date('Y-m-d H:i:s'));
    }
}
