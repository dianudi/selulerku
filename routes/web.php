<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication
Route::get('/login', [AuthController::class, 'login'])->middleware('guest')->name('auth.login');
Route::post('/login', [AuthController::class, 'auth'])->middleware('guest')->name('auth.auth');
Route::delete('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('auth.logout');

// Account Recovery
Route::get('/forgot-password', [ResetPasswordController::class, 'forgotPassword'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLinkEmail'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'resetForm'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'updatePassword'])->middleware('guest')->name('password.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return;
    })->name('dashboard.index');
});
