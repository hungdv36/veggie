<?php

use App\Http\Controllers\Admin\AdminAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UsersController;

Route::prefix('admin')->group(function () {
    // Trang dashboard
    Route::get('/dashboard', function () {
        return view('admin.pages.dashboard');
    })->name('admin.dashboard');

    // Login
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    // Users
    Route::middleware(['permission:manage_users'])->group(function () {
        Route::get('/users', [UsersController::class, 'index'])->name('admin.users.index');
        Route::post('/user/upgrade', [UsersController::class, 'upgrade'])->name('admin.user.upgrade');
        Route::post('/user/updateStatus', [UsersController::class, 'updateStatus'])->name('admin.user.updateStatus');
        Route::post('/user/toggleDelete', [UsersController::class, 'toggleDelete'])
            ->name('admin.user.toggleDelete');
    });
});
