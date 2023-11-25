<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void onLoginSuccess(User $user)
 * @method static void onLoginFailed(string $username)
 * @method static void onLogout(User $user)
 * @method static void onSearchTFactors(User $user)
 * @method static void onDeleteTFactors(User $user, string $factorId)
 * @method static void onEditTFactor(User $user, string $factorId)
 */
class Notification extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'notification';
    }
}
