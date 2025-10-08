<?php

use App\Http\Controllers\Clients\AccountController;
use App\Http\Controllers\Clients\AuthController;
use App\Http\Controllers\Clients\ForgotPasswordController;
use App\Http\Controllers\Clients\HomeController;
use App\Http\Controllers\Clients\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clients\ProductController;

Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/about', function () {
    return view('clients.pages.about');
});
Route::get('/service', function () {
    return view('clients.pages.service');
});
Route::get('/team', function () {
    return view('clients.pages.team');
});
Route::get('/faq', function () {
    return view('clients.pages.faq');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('post-register');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('post-login');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');
});

Route::get('/activate/{token}', [AuthController::class, 'activate'])->name('activate');

Route::middleware(['auth.custom'])->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Account
    Route::prefix('account')->group(function () {
        // Trang chính account
        Route::get('/', [AccountController::class, 'index'])->name('account');

        // Đổi mật khẩu
        Route::post('/change-password', [AccountController::class, 'changePassword'])
            ->name('account.change-password');

        // Thêm địa chỉ
        Route::post('/addresses', [AccountController::class, 'addAddress'])
            ->name('account.addresses.add');

        // Cập nhật địa chỉ mặc định
        Route::put('/addresses/{id}/default', [AccountController::class, 'updatePrimaryAddress'])
            ->name('account.addresses.update');

        // Xóa địa chỉ
        Route::delete('/addresses/{id}', [AccountController::class, 'deleteAddress'])
            ->name('account.addresses.delete');
    });
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/filter', [ProductController::class, 'filter'])->name('products.filter');

// Detail
Route::get('/products/{slug}', [ProductController::class, 'detail'])->name('products.detail');

require __DIR__ . '/admin.php';
