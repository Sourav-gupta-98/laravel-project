<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::prefix('customer')->group(function () {
    Route::get('register', [CustomerController::class, 'registerPage']);
    Route::get('login', [CustomerController::class, 'loginPage']);
    Route::post('register', [CustomerController::class, 'register']);
    Route::post('login', [CustomerController::class, 'login']);
});
