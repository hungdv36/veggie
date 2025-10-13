<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\VariantController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.pages.dashboard');
    })->name('dashboard');

    // Login
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Users (cần permission: manage_users)
    Route::middleware(['permission:manage_users'])->group(function () {
        Route::get('/users', [UsersController::class, 'index'])->name('users.index');
        Route::post('/user/upgrade', [UsersController::class, 'upgrade'])->name('user.upgrade');
        Route::post('/user/updateStatus', [UsersController::class, 'updateStatus'])->name('user.updateStatus');
        Route::post('/user/toggleDelete', [UsersController::class, 'toggleDelete'])->name('user.toggleDelete');
    });

    // Categories (cần permission: manage_categories)
    // Route::middleware(['permission:manage_categories'])->group(function () {
    //     Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    //     Route::get('/categories/add', [CategoryController::class, 'showFormAddCate'])->name('categories.add');
    //     Route::post('/categories/add', [CategoryController::class, 'addCategory'])->name('categories.store');
    //     Route::post('/categories/update', [CategoryController::class, 'updateCategory'])->name('categories.update');
    //     Route::post('/categories/delete', [CategoryController::class, 'deleteCategory'])->name('categories.delete');
    //     Route::get('/categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');
    //     Route::post('/categories/restore', [CategoryController::class, 'restoreCategory'])->name('categories.restore');
    //     Route::post('/categories/force-delete', [CategoryController::class, 'forceDeleteCategory'])->name('categories.forceDelete');
    // });

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/add', [CategoryController::class, 'showFormAddCate'])->name('categories.add');
    Route::post('/categories/add', [CategoryController::class, 'addCategory'])->name('categories.store');
    Route::post('/categories/update', [CategoryController::class, 'updateCategory'])->name('categories.update');
    Route::post('/categories/delete', [CategoryController::class, 'deleteCategory'])->name('categories.delete');
    Route::get('/categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');
    Route::post('/categories/restore', [CategoryController::class, 'restoreCategory'])->name('categories.restore');
    Route::post('/categories/force-delete', [CategoryController::class, 'forceDeleteCategory'])->name('categories.forceDelete');


    Route::get('/product/add', [ProductController::class, 'ShowFormAddProduct'])->name('product.add');
    Route::post('/product/add', [ProductController::class, 'addProduct'])->name('product.add');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::post('/product/update', [ProductController::class, 'updateProduct']);


    // Variants (cần permission: manage_variants)
    // ROUTES FOR VARIANTS (No middleware)
    Route::get('/variants', [VariantController::class, 'index'])->name('variants.index');
    Route::get('/variants/add', [VariantController::class, 'showVariantForm'])->name('variants.add');
    Route::post('/variants/add', [VariantController::class, 'addVariant'])->name('variants.store');
    Route::post('/variants/update', [VariantController::class, 'updateVariant'])->name('variants.update');
    Route::post('/variants/delete', [VariantController::class, 'deleteVariant'])->name('variants.delete');
    Route::get('/variants/trash', [VariantController::class, 'trash'])->name('variants.trash');
    Route::post('/variants/restore', [VariantController::class, 'restoreVariant'])->name('variants.restore');
    Route::post('/variants/force-delete', [VariantController::class, 'forceDeleteVariant'])->name('variants.forceDelete');
});
