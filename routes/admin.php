<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('register', [AdminController::class, 'registerPage']);
Route::get('login', [AdminController::class, 'loginPage']);
Route::post('register', [AdminController::class, 'register']);
Route::post('login', [AdminController::class, 'login']);
