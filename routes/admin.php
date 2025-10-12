<?php


use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

    Route::middleware(['permission:manage_categories'])->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('categories.index');
        Route::get('/categories/add', [CategoryController::class, 'showFormAddCate'])
            ->name('categories.add');
        Route::post('/categories/add', [CategoryController::class, 'addCategory'])
            ->name('categories.store');
        Route::post('/categories/update', [CategoryController::class, 'updateCategory'])
            ->name('categories.update');
        Route::post('/categories/delete', [CategoryController::class, 'deleteCategory'])
            ->name('categories.delete');
        Route::get('/categories/trash', [CategoryController::class, 'trash'])
            ->name('categories.trash');
        Route::post('/categories/restore', [CategoryController::class, 'restoreCategory'])
            ->name('categories.restore');
        Route::post('/categories/force-delete', [CategoryController::class, 'forceDeleteCategory'])
            ->name('categories.forceDelete');
    });
    Route::middleware(['permission:manage_products'])->group(function () {
        Route::get('/products', [ProductController::class, 'index'])
            ->name('products.index');
    });
});
