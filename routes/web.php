<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PunchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeveloperController;

Route::get('/report', [ReportController::class, 'index'])->name('report');
Route::get('/report/download', [ReportController::class, 'download'])->name('report.download');

Route::get('/', fn () => redirect()->route(token() ? 'home' : 'login'));
Route::view('/offline', 'offline')->name('offline');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.store');

Route::middleware('api.auth')->group(function (): void {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/punch', [PunchController::class, 'index'])->name('punch');
    Route::post('/punch', [PunchController::class, 'store'])->name('punch.store');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::get('/settings/documents', [SettingsController::class, 'documents'])->name('settings.documents');
    Route::get('/settings/salary', [SettingsController::class, 'salaryDetails'])->name('settings.salary');
    Route::get('/notifications', [SettingsController::class, 'notifications'])->name('notifications');
    Route::get('/leave', [SettingsController::class, 'leaveLanding'])->name('leave');
    Route::get('/leave/apply', [SettingsController::class, 'leaveApply'])->name('leave.apply');
    Route::get('/leave/history', [SettingsController::class, 'leaveHistory'])->name('leave.history');
    Route::post('/leave', [SettingsController::class, 'storeLeave'])->name('leave.store');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('password.update');

    Route::get('/settings/email-reports', [SettingsController::class, 'emailReports'])->name('settings.email-reports');
    Route::post('/settings/email-reports', [SettingsController::class, 'sendEmailReport'])->name('settings.email-reports.send');

    Route::get('/report', [ReportController::class, 'index'])->name('report');
    Route::get('/report/download', [ReportController::class, 'download'])->name('report.download');

    Route::get('/developer-options', [DeveloperController::class, 'index'])->name('developer.index');

    // Sanket
});