<?php

use App\Http\Controllers\Admin\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.pages.dashboard');
    })->name('admin.dashboard');

    // Hiển thị form đăng nhập
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');

    // Xử lý khi submit form đăng nhập
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
});

