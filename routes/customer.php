<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\AuthCustomer;
use Illuminate\Support\Facades\Route;

Route::prefix('customer')->group(function () {
    Route::get('register', [CustomerController::class, 'registerPage']);
    Route::get('login', [CustomerController::class, 'loginPage']);
    Route::post('register', [CustomerController::class, 'register']);
    Route::post('login', [CustomerController::class, 'login'])->name('customer.login');

    Route::middleware(AuthCustomer::class)->group(function () {
        Route::post('logout', [CustomerController::class, 'logout'])->name('customer.logout');
        Route::get('dashboard', [CustomerController::class, 'dashboard']);

        Route::get('product', [ProductController::class, 'get']);
        Route::get('product/{unique_id}/detail', [ProductController::class, 'detail']);

        Route::get('cart', [CartController::class, 'get']);
        Route::put('cart/{unique_id}', [CartController::class, 'update']);
        Route::delete('cart/{unique_id}', [CartController::class, 'delete']);

        Route::post('orders', [OrderController::class, 'add']);
        Route::get('orders', [OrderController::class, 'get']);
    });
});
