<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// logged in users
Route::middleware(['auth:sanctum', 'auth.logged'])->prefix('panel')->group(
    function () {
        Route::get('users/logout', [UserController::class, 'logout']);
    }
);

Route::get('{path}', function () {
    return view('index');
})->where('path', '^((?!api).)*$');
