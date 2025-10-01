<?php

use App\Http\Controllers\Clients\AuthController;
use App\Http\Controllers\Clients\ForgotPasswordController;
use App\Http\Controllers\Clients\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('clients.pages.home');
})->name('home');
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
Route::get('/register', [AuthController::class,'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class,'register'])->name('post-register');

Route::get('/activate/{token}', [AuthController::class,'activate'])->name('activate');

Route::get('/login', [AuthController::class,'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class,'login'])->name('post-login');

Route::get('/logout', [AuthController::class,'logout'])->name('logout');

Route::get('/forgot-password', [ForgotPasswordController::class,'showForgotPasswordForm'])->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class,'sendResetLink'])->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class,'showResetForm'])->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class,'resetPassword'])->name('password.update');

require __DIR__.'/admin.php';
