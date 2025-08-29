<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('register', [AdminController::class, 'registerPage']);
    Route::get('login', [AdminController::class, 'loginPage'])->name('admin.login');
    Route::post('register', [AdminController::class, 'register']);
    Route::post('login', [AdminController::class, 'login']);

    Route::middleware(\App\Http\Middleware\AuthAdmin::class)->group(function () {
        Route::post('logout', [AdminController::class, 'logout'])->name('admin.logout');
        Route::get('dashboard', [AdminController::class, 'dashboard']);
    });

});
