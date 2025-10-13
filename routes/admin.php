<?php


use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UsersController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.pages.dashboard');
    })->name('dashboard');

    // Users 
    Route::middleware(['permission:manage_users'])->group(function () { Route::get('/users', [UsersController::class, 'index'])->name('users.index'); Route::post('/user/upgrade', [UsersController::class, 'upgrade'])->name('user.upgrade'); Route::post('/user/updateStatus', [UsersController::class, 'updateStatus'])->name('user.updateStatus'); Route::post('/user/toggleDelete', [UsersController::class, 'toggleDelete'])->name('user.toggleDelete'); });

    // Login
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Category
    Route::get('/categories/add', [CategoryController::class, 'showFormAddCate'])->name('categories.add');
    Route::post('/categories/add', [CategoryController::class, 'showFormAddCate'])->name('categories.store');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

    // Product
    Route::get('/product/add', [ProductController::class, 'ShowFormAddProduct'])->name('product.add');
    Route::post('/product/add', [ProductController::class, 'addProduct'])->name('product.add');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
});
