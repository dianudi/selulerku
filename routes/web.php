<?php

use App\Http\Controllers\AccountSettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportAnalysisController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ServiceHistoryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard.index'))->name('home');

// Authentication
Route::get('/login', [AuthController::class, 'login'])->middleware('guest')->name('auth.login');
Route::post('/login', [AuthController::class, 'auth'])->middleware('guest')->name('auth.auth');
Route::delete('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('auth.logout');

// Account Recovery
Route::get('/forgot-password', [ResetPasswordController::class, 'forgotPassword'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLinkEmail'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'resetForm'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'updatePassword'])->middleware('guest')->name('password.update');

// Main
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard.index');
    Route::get('/account', [AccountSettingController::class, 'index'])->name('account.index');
    Route::patch('/account', [AccountSettingController::class, 'update'])->name('account.update');
    Route::patch('/account/password', [AccountSettingController::class, 'updatePassword'])->name('account.password');
    Route::patch('/users/{user}/active', [UserController::class, 'activate'])->name('users.activate');
    Route::resource('users', UserController::class)->except(['show', 'edit', 'update']);
    Route::resource('productcategories', ProductCategoryController::class)->except(['show', 'edit'])->parameter('productcategories', 'productCategory');
    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class)->except(['create']);
    Route::resource('servicehistories', ServiceHistoryController::class)->parameter('servicehistories', 'serviceHistory');
    Route::get('servicehistories/{serviceHistory}/print', [ServiceHistoryController::class, 'print'])->name('servicehistories.print');
    Route::resource('orders', OrderController::class)->parameter('orders', 'order');
    Route::get('/orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/report-analysis/product-sales', [ReportAnalysisController::class, 'productSales'])->name('report-analysis.product-sales');
    Route::get('/report-analysis/service-history', [ReportAnalysisController::class, 'serviceHistory'])->name('report-analysis.service-history');
    Route::resource('expenses', ExpenseController::class);
});
