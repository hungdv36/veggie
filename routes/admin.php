<?php

// use App\Http\Controllers\Admin\AdminAuthController;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Admin\UsersController;

// Route::prefix('admin')->group(function () {
//     // Trang dashboard
//     Route::get('/dashboard', function () {
//         return view('admin.pages.dashboard');
//     })->name('admin.dashboard');

//     Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
//     Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');

//     // Users
//     Route::middleware(['permission:manage_users'])->group(function () {
//         Route::get('/users', [UsersController::class, 'index'])->name('admin.users.index');
//         Route::post('/user/upgrade', [UsersController::class, 'upgrade'])->name('admin.user.upgrade');
//         Route::post('/user/updateStatus', [UsersController::class, 'updateStatus'])->name('admin.user.updateStatus');
//         Route::post('/user/toggleDelete', [UsersController::class, 'toggleDelete'])
//             ->name('admin.user.toggleDelete');
//     });
// });


use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {

    // ====== Trang Dashboard ======
    Route::get('/dashboard', function () {
        return view('admin.pages.dashboard');
    })->name('admin.dashboard');

    // ====== Đăng nhập Admin ======
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');


    Route::get('/users', [UsersController::class, 'index'])->name('admin.users.index');
    Route::post('/user/upgrade', [UsersController::class, 'upgrade'])->name('admin.user.upgrade');
    Route::post('/user/updateStatus', [UsersController::class, 'updateStatus'])->name('admin.user.updateStatus');
    Route::post('/user/toggleDelete', [UsersController::class, 'toggleDelete'])->name('admin.user.toggleDelete');

    Route::get('/categories/add', [CategoryController::class, 'ShowForm'])->name('admin.categories.add');
    Route::post('/categories/add', [CategoryController::class, 'addCategory'])->name('admin.categories.add');
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');

      Route::get('/product/add', [ProductController::class, 'ShowFormAddProduct'])->name('admin.product.add');
    Route::post('/product/add', [ProductController::class, 'addProduct'])->name('admin.product.add');
    Route::get('/products', [ProductController::class, 'index'])->name('admin.categories.index');


});
