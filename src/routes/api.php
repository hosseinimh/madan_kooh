<?php

use App\Constants\Permission;
use App\Constants\Role;
use App\Http\Controllers\User\PermissionController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ErrorController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\TFactorController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

// all users
Route::middleware(['cors'])->group(function () {
    Route::post('errors/store', [ErrorController::class, 'store']);

    Route::post('tfactors/store', [TFactorController::class, 'store']);
    Route::get('tfactors/last_factor_id', [TFactorController::class, 'getLastFactorId']);
});

// logged in users
Route::middleware(['auth:sanctum', 'auth.logged'])->group(function () {
    Route::post('notifications', [NotificationController::class, 'index']);
    Route::post('notifications/review', [NotificationController::class, 'review']);
    Route::post('notifications/seen/{model}', [NotificationController::class, 'seen']);
    Route::post('notifications/seen_review', [NotificationController::class, 'seenReview']);

    Route::post('users/show', [UserController::class, 'show']);
    Route::post('users/update', [UserController::class, 'update']);
    Route::post('users/change_password', [UserController::class, 'changePassword']);
    Route::post('users/logout', [UserController::class, 'logout']);
});

// logged in users, role:admin
Route::middleware(['auth:sanctum', 'auth.logged', 'role:' . Role::ADMIN])->group(function () {
    Route::post('errors', [ErrorController::class, 'index']);

    Route::post('dashboard', [DashboardController::class, 'index']);

    Route::post('users', [UserController::class, 'index']);
    Route::post('users/show/admin/{model}', [UserController::class, 'showWithAdmin']);
    Route::post('users/store', [UserController::class, 'store']);
    Route::post('users/update/admin/{model}', [UserController::class, 'updateWithAdmin']);
    Route::post('users/change_password/admin/{model}', [UserController::class, 'changePasswordWithAdmin']);

    Route::post('permissions', [PermissionController::class, 'index']);

    Route::post('tfactors/delete', [TFactorController::class, 'deleteTFactors']);
});

// logged in users, role:admin|permission:read_wb_1|read_wb_2|read_all_wbs
Route::middleware(['auth:sanctum', 'auth.logged', 'role_or_permission:' . Role::ADMIN . '|' . Permission::READ_WB_1 . '|' . Permission::READ_WB_2 . '|' . Permission::READ_ALL_WBS])->group(function () {
    Route::post('tfactors', [TFactorController::class, 'index']);
    Route::post('tfactors/props', [TFactorController::class, 'indexWithProps']);
    Route::post('tfactors/update', [TFactorController::class, 'update']);
});

// logged in users, role:admin|permission:edit_factor_description
Route::middleware(['auth:sanctum', 'auth.logged', 'role_or_permission:' . Role::ADMIN . '|' . Permission::EDIT_FACTOR_DESCRIPTION])->group(function () {
    Route::post('tfactors/update', [TFactorController::class, 'update']);
});

// not logged in users
Route::middleware(['cors'])->group(function () {
    Route::post('users/login', [UserController::class, 'login']);
});
