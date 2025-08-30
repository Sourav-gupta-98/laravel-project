<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('register', [AdminController::class, 'registerPage']);
    Route::get('login', [AdminController::class, 'loginPage'])->name('admin.login');
    Route::post('register', [AdminController::class, 'register']);
    Route::post('login', [AdminController::class, 'login']);

    Route::middleware(AuthAdmin::class)->group(function () {
        Route::post('logout', [AdminController::class, 'logout'])->name('admin.logout');
        Route::get('dashboard', [AdminController::class, 'dashboard']);

        Route::post('product', [ProductController::class, 'add']);
        Route::get('product', [ProductController::class, 'get']);
        Route::get('product/{unique_id}/detail', [ProductController::class, 'detail']);
        Route::put('product/{unique_id}/edit', [ProductController::class, 'update']);
        Route::delete('product/{unique_id}', [ProductController::class, 'delete']);

        Route::get('orders', [OrderController::class, 'get']);
        Route::put('orders/{unique_id}', [OrderController::class, 'update']);
    });

});
