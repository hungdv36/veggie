<?php

use App\Http\Controllers\Clients\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;


Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');

Route::get('/', function () {
    return view('clients.pages.home');
});
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

require __DIR__.'/admin.php';
