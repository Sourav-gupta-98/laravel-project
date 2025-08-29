<?php

use App\Http\Controllers\CustomerController;
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
    });
});
