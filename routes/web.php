<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Clients\AccountController;
use App\Http\Controllers\Clients\AuthController;
use App\Http\Controllers\Clients\ContactController;
use App\Http\Controllers\Clients\ForgotPasswordController;
use App\Http\Controllers\Clients\HomeController;
use App\Http\Controllers\Clients\OrderController;
use App\Http\Controllers\Clients\ResetPasswordController;
use App\Http\Controllers\Clients\WishListController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clients\ProductController;

use App\Http\Controllers\PaymentController;

use App\Http\Controllers\Clients\ReviewController;

use App\Http\Controllers\Clients\SearchController;
use App\Http\Controllers\Clients\WishController;

Route::get('/', [HomeController::class, 'index'])->name('home');
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
   Route::put('/account/update', [AccountController::class, 'update'])->name('account.update');


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
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::get('/checkout/get-address', [CheckoutController::class, 'getAddress'])->name('checkout.getAddress');
    Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');

    Route::get('/order/{id}', [OrderController::class, 'showOrder'])->name('order.show');
    Route::post('/order/{id}/cancel', [OrderController::class, 'cancelOrder'])->name('order.cancel');

    

    Route::post('/review', [ReviewController::class, 'createReview']);
      Route::get('/review/{product}', [ReviewController::class, 'index']);

    // WishList
    Route::get('/wishlist', [WishListController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/add', [WishListController::class, 'addToWishlist'])->name('wishlist.add');
    Route::post('/wishlist/remove', [WishListController::class, 'remove'])->name('wishlist.remove');
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/filter', [ProductController::class, 'filter'])->name('products.filter');

// Detail
Route::get('/products/{slug}', [ProductController::class, 'detail'])->name('products.detail');

Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'removeFromMiniCart'])->name('cart.remove');
Route::get('/mini-cart', [CartController::class, 'loadMiniCart'])->name('cart.mini');

Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.index');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove-cart', [CartController::class, 'removeCartItem'])->name('cart.removeItem');


Route::post('checkout/paypal', [CheckoutController::class, 'handlePayPal'])->name('checkout.paypal');
Route::get('/checkout/paypal/success', [PaymentController::class, 'success'])->name('paypal.success');
Route::get('/checkout/paypal/cancel', [PaymentController::class, 'cancel'])->name('paypal.cancel');

Route::middleware(['auth.custom'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/cod', [CheckoutController::class, 'handleCOD'])->name('checkout.cod');
    Route::post('/checkout/paypal', [CheckoutController::class, 'handlePayPal'])->name('checkout.paypal');
});

// Search
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
// Liên hệ
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'sendContact'])->name('contact');
require __DIR__ . '/admin.php';
