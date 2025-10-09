<?php

use App\Http\Controllers\Admin\AdminAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\CategoryController;

Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.pages.dashboard');
    })->name('dashboard');

    // Login
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout'); // sửa ở đây

    // Users
    Route::middleware(['permission:manage_users'])->group(function () {
        Route::get('/users', [UsersController::class, 'index'])->name('users.index');
        Route::post('/user/upgrade', [UsersController::class, 'upgrade'])->name('user.upgrade');
        Route::post('/user/updateStatus', [UsersController::class, 'updateStatus'])->name('user.updateStatus');
        Route::post('/user/toggleDelete', [UsersController::class, 'toggleDelete'])->name('user.toggleDelete');
    });

    // Categories
    Route::middleware(['auth', 'check.admin'])->group(function () {
        Route::resource('categories', CategoryController::class);
    });
});
