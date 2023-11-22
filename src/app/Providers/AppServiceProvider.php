<?php

namespace App\Providers;

use App\Constants\Theme;
use App\Http\Controllers\User\PermissionController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ErrorController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\TFactorController;
use App\Http\Controllers\User\UserController;
use App\Http\Resources\Error\ErrorResource;
use App\Http\Resources\Notification\NotificationResource;
use App\Http\Resources\Permission\PermissionResource;
use App\Http\Resources\TFactor\TFactorResource;
use App\Http\Resources\User\UserResource;
use App\Packages\Helper;
use App\Packages\JsonResponse;
use App\Packages\Notification;
use App\Services\ErrorService;
use App\Services\NotificationService;
use App\Services\PermissionService;
use App\Services\TFactorService;
use App\Services\UserService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

require_once __DIR__ . '/../../server-config.php';

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('helper', function () {
            return new Helper();
        });

        $this->app->bind('notification', function () {
            return new Notification();
        });
    }

    public function boot()
    {
        $this->app->bind('path.public', function () {
            return PUBLIC_PATH;
        });

        View::share('THEME', Theme::class);

        $this->app->bind(ErrorController::class, function ($app) {
            return new ErrorController(new JsonResponse(ErrorResource::class), $app->make(ErrorService::class));
        });

        $this->app->bind(PermissionController::class, function ($app) {
            return new PermissionController(new JsonResponse(PermissionResource::class), $app->make(PermissionService::class));
        });

        $this->app->bind(DashboardController::class, function ($app) {
            return new DashboardController($app->make(JsonResponse::class));
        });

        $this->app->bind(NotificationController::class, function ($app) {
            return new NotificationController(new JsonResponse(NotificationResource::class), $app->make(NotificationService::class));
        });

        $this->app->bind(UserController::class, function ($app) {
            return new UserController(new JsonResponse(UserResource::class), $app->make(UserService::class));
        });

        $this->app->bind(TFactorController::class, function ($app) {
            return new TFactorController(new JsonResponse(TFactorResource::class), $app->make(TFactorService::class));
        });
    }
}
