<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\FlashSaleController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\RefundController;
use App\Http\Controllers\Admin\ReturnRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LogUserVisit;


Route::prefix('admin')
    ->name('admin.')
    ->middleware([LogUserVisit::class]) // <= thêm dòng này
    ->group(function () {



        Route::prefix('banners')->name('banners.')->group(function () {
            Route::get('/', [BannerController::class, 'index'])->name('index');
            Route::get('/create', [BannerController::class, 'create'])->name('create');
            Route::post('/store', [BannerController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [BannerController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [BannerController::class, 'update'])->name('update');
            Route::get('/delete/{id}', [BannerController::class, 'destroy'])->name('delete');
        });
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        //Banners
        Route::resource('banners', App\Http\Controllers\Admin\BannerController::class);

        Route::middleware(['auth.custom'])
            ->prefix('admin')
            ->group(function () {
                Route::get('/visits', [App\Http\Controllers\Admin\UserVisitController::class, 'index'])
                    ->name('admin.visits');
            });


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
        Route::middleware(['permission:manage_categories'])->group(function () {
            Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
            Route::get('/categories/add', [CategoryController::class, 'showFormAddCate'])->name('categories.add');
            Route::post('/categories/add', [CategoryController::class, 'addCategory'])->name('categories.store');
            Route::post('/categories/update', [CategoryController::class, 'updateCategory'])->name('categories.update');
            Route::post('/categories/delete', [CategoryController::class, 'deleteCategory'])->name('categories.delete');
            Route::get('/categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');
            Route::post('/categories/restore', [CategoryController::class, 'restoreCategory'])->name('categories.restore');
            Route::post('/categories/force-delete', [CategoryController::class, 'forceDeleteCategory'])->name('categories.forceDelete');
        });

        // Products (cần permission: manage_products)
        Route::middleware(['permission:manage_products'])->group(function () {
            Route::get('/products', [ProductController::class, 'index'])->name('products.index');
            Route::get('/products/add', [ProductController::class, 'showFormAddProduct'])->name('products.add');
            Route::post('/products/add', [ProductController::class, 'addProduct'])->name('products.store');
            Route::get('/products/{id}/show', [ProductController::class, 'showProduct'])->name('products.show');
            Route::post('/products/update', [ProductController::class, 'updateProduct'])->name('products.update');
            Route::post('/products/delete', [ProductController::class, 'deleteProduct'])->name('products.delete');
            Route::get('/products/trash', [ProductController::class, 'trash'])->name('products.trash');
            Route::post('/products/restore', [ProductController::class, 'restoreProduct'])->name('products.restore');
            Route::post('/products/force-delete', [ProductController::class, 'forceDeleteProduct'])->name('products.forceDelete');
        });

        // Sizes (cần permission: manage_sizes)
        Route::middleware(['permission:manage_sizes'])->group(function () {
            Route::get('/sizes', [SizeController::class, 'index'])->name('sizes.index');
            Route::get('/sizes/add', [SizeController::class, 'showSizeForm'])->name('sizes.add');
            Route::post('/sizes/add', [SizeController::class, 'addSize'])->name('sizes.store');
            Route::post('/sizes/update', [SizeController::class, 'updateSize'])->name('sizes.update');
            Route::post('/sizes/delete', [SizeController::class, 'deleteSize'])->name('sizes.delete');
        });

        // Colors (cần permission: manage_colors)
        Route::middleware(['permission:manage_colors'])->group(function () {
            Route::get('/colors', [ColorController::class, 'index'])->name('colors.index');
            Route::get('/colors/add', [ColorController::class, 'showColorForm'])->name('colors.add');
            Route::post('/colors/add', [ColorController::class, 'addColor'])->name('colors.store');
            Route::post('/colors/update', [ColorController::class, 'updateColor'])->name('colors.update');
            Route::post('/colors/delete', [ColorController::class, 'deleteColor'])->name('colors.delete');
        });

        // Coupons (cần permission: manage_coupons)
        Route::middleware(['permission:manage_coupons'])->group(function () {
            Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
            Route::get('/coupons/add', [CouponController::class, 'showCouponForm'])->name('coupons.add');
            Route::post('/coupons/add', [CouponController::class, 'addCoupon'])->name('coupons.store');
            Route::post('/coupons/update', [CouponController::class, 'updateCoupon'])->name('coupons.update');
            Route::post('/coupons/delete', [CouponController::class, 'deleteCoupon'])->name('coupons.delete');
        });
        Route::middleware(['permission:manage_orders'])->group(function () {
            Route::get('orders/', [OrderController::class, 'index'])->name('orders.index');
            Route::post('orders/confirm', [OrderController::class, 'confirmOrder'])->name('orders.confirm');
            Route::post('orders/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
            Route::get('orders/detail/{id}', [OrderController::class, 'showOrderDetail'])->name('orders.detail');
            Route::post('/orders/send-invoice', [OrderController::class, 'sendMailInvoice'])->name('orders.send-invoice');
            Route::post('/orders/{id}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');
        });

        // Contacts (cần permission: manage_contacts)
        Route::middleware(['permission:manage_contacts'])->group(function () {
            Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
            Route::post('/contacts/reply', [ContactController::class, 'reply'])->name('contacts.reply');
        });

        // Reviews (cần permission: manage_reviews)
        Route::middleware(['permission:manage_reviews'])->group(function () {
            Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
            Route::post('/reviews/delete', [ReviewController::class, 'delete'])->name('reviews.delete');
            Route::get('/reviews/logs', [ReviewController::class, 'deletionLogs'])->name('reviews.logs');
            Route::get('/reviews/deletions', [ReviewController::class, 'deletionLogs'])->name('reviews.deletions');
            Route::post('/reviews/restore/{id}', [ReviewController::class, 'restore'])->name('reviews.restore');
        });



        Route::middleware(['auth:admin'])->group(function () {
            Route::get('/profile', [AccountController::class, 'index'])->name('profile');
            Route::post('/profile/update', [AccountController::class, 'updateProfile'])->name('profile.update');
            Route::post('/profile/change-password', [AccountController::class, 'changePassword'])->name('profile.change-password');
        });

        Route::middleware(['permission:manage_products'])->group(function () {
            Route::get('/flash-sales', [FlashSaleController::class, 'index'])->name('flash_sales.index');
            Route::get('/flash-sales/create', [FlashSaleController::class, 'create'])->name('flash_sales.create');
            Route::post('/flash-sales', [FlashSaleController::class, 'store'])->name('flash_sales.store');
            Route::get('/flash-sales/{id}/edit', [FlashSaleController::class, 'edit'])->name('flash_sales.edit');
            Route::post('/flash-sales/{id}', [FlashSaleController::class, 'update'])->name('flash_sales.update');
            Route::delete('/flash-sales/{id}', [FlashSaleController::class, 'destroy'])->name('flash_sales.destroy');
        });

        Route::middleware(['permission:manage_refunds'])->group(function () {
            Route::get('/refunds', [RefundController::class, 'index'])->name('refunds.index');
            Route::get('/refunds/{id}', [RefundController::class, 'show'])->name('refunds.show');
            Route::put('/refunds/{id}/status', [RefundController::class, 'updateStatus'])->name('refunds.updateStatus');
            Route::post('/refunds/{id}/complete', [RefundController::class, 'completeRefund'])->name('refunds.complete');
        });

        Route::middleware(['permission:manage_return_request'])->group(function () {
            Route::get('/returns', [ReturnRequestController::class, 'index'])->name('returns.index');
            Route::get('/returns/{id}', [ReturnRequestController::class, 'show'])->name('returns.show');
            Route::put('/returns/{id}/status', [ReturnRequestController::class, 'updateStatus'])->name('returns.updateStatus');
        });
});
