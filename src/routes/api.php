<?php

use App\Constants\Role;
use App\Http\Controllers\User\PermissionController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ErrorController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

// all users
Route::middleware(['cors'])->group(function () {
    Route::post('errors/store', [ErrorController::class, 'store']);
});

// logged in users
Route::middleware(['auth:sanctum', 'auth.logged'])->group(function () {
    Route::post('dashboard', [DashboardController::class, 'index']);

    Route::post('notifications', [NotificationController::class, 'index']);
    Route::post('notifications/review', [NotificationController::class, 'review']);
    Route::post('notifications/seen/{model}', [NotificationController::class, 'seen']);
    Route::post('notifications/seen_review', [NotificationController::class, 'seenReview']);

    Route::post('users/show_auth', [UserController::class, 'show']);
    Route::post('users/update_auth', [UserController::class, 'update']);
    Route::post('users/change_password_auth', [UserController::class, 'changePassword']);
    Route::post('users/logout', [UserController::class, 'logout']);
});

// logged in users, permission: admin
Route::middleware(['auth:sanctum', 'auth.logged', 'role:' . Role::ADMIN])->group(function () {
    Route::post('errors', [ErrorController::class, 'index']);

    Route::post('users', [UserController::class, 'index']);
    Route::post('users/show/{model}', [UserController::class, 'showWithAdmin']);
    Route::post('users/store', [UserController::class, 'store']);
    Route::post('users/update/{model}', [UserController::class, 'updateWithAdmin']);
    Route::post('users/change_password/{model}', [UserController::class, 'changePasswordWithAdmin']);

    Route::post('permissions', [PermissionController::class, 'index']);
});

// not logged in users
Route::middleware(['cors'])->group(function () {
    Route::post('users/login', [UserController::class, 'login']);
});
