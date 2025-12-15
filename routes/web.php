<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChatbotController;

use App\Http\Controllers\Clients\HomeController;
use App\Http\Controllers\Clients\AuthController;
use App\Http\Controllers\Clients\AccountController;
use App\Http\Controllers\Clients\ForgotPasswordController;
use App\Http\Controllers\Clients\ResetPasswordController;
use App\Http\Controllers\Clients\ProductController;
use App\Http\Controllers\Clients\OrderController;
use App\Http\Controllers\Clients\RefundController;
use App\Http\Controllers\Clients\ReturnRequestController;
use App\Http\Controllers\Clients\WishListController;
use App\Http\Controllers\Clients\ReviewController;
use App\Http\Controllers\Clients\SearchController;
use App\Http\Controllers\Clients\FlashSaleController;
use App\Http\Controllers\Clients\ContactController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/about', 'clients.pages.about');
Route::view('/service', 'clients.pages.service');
Route::view('/team', 'clients.pages.team');
Route::view('/faq', 'clients.pages.faq');

/*
|--------------------------------------------------------------------------
| AUTH (GUEST)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('post-register');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('post-login');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])
        ->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])
        ->name('password.update');
});

Route::get('/activate/{token}', [AuthController::class, 'activate'])->name('activate');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER
|--------------------------------------------------------------------------
*/

Route::middleware('auth.custom')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /* ACCOUNT */
    Route::prefix('account')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('account');
        Route::put('/update', [AccountController::class, 'update'])->name('account.update');
        Route::post('/avatar', [AccountController::class, 'updateAvatar'])->name('account.updateAvatar');

        Route::post('/change-password', [AccountController::class, 'changePassword'])
            ->name('account.change-password');

        Route::get('/addresses', [AccountController::class, 'showAddresses'])
            ->name('account.addresses.index');

        Route::post('/addresses', [AccountController::class, 'addAddress'])
            ->name('account.addresses.add');

        Route::put('/addresses/{id}/default', [AccountController::class, 'updatePrimaryAddress'])
            ->name('account.addresses.update');

        Route::delete('/addresses/{id}', [AccountController::class, 'deleteAddress'])
            ->name('account.addresses.delete');
    });

    /* WISHLIST */
    Route::get('/wishlist', [WishListController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/add', [WishListController::class, 'addToWishlist'])->name('wishlist.add');
    Route::post('/wishlist/remove', [WishListController::class, 'remove'])->name('wishlist.remove');

    /* CHECKOUT */
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
    Route::get('/checkout/get-address', [CheckoutController::class, 'getAddress'])->name('checkout.getAddress');
    Route::get('/coupons/list', [CheckoutController::class, 'listCoupons'])->name('coupons.list');
    Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.applyCoupon');

    Route::post('/checkout/cod', [CheckoutController::class, 'handleCOD'])->name('checkout.cod');
    Route::post('/checkout/paypal', [CheckoutController::class, 'handlePayPal'])->name('checkout.paypal');
    Route::post('/checkout/momo', [CheckoutController::class, 'handleMoMo'])->name('checkout.momo');
    Route::get('/checkout/momo/return', [CheckoutController::class, 'momoReturn'])->name('momo.return');

    /* ORDER */
    Route::get('/order/{id}', [OrderController::class, 'showOrder'])->name('order.show');
    Route::post('/order/{id}/cancel', [OrderController::class, 'cancelOrder'])->name('order.cancel');
    Route::patch('/orders/{order}/confirm-received', [OrderController::class, 'confirmReceived'])
        ->name('orders.confirmReceived');

    /* REFUND / RETURN */
    Route::get('/refund/bank-info/{order}', [RefundController::class, 'bankInfo'])
        ->name('refund.bank-info');
    Route::post('/refund/bank-info/{order}', [RefundController::class, 'updateBankInfo'])
        ->name('refund.bank-info.update');

    Route::get('/return/{orderItem}', [ReturnRequestController::class, 'create'])
        ->name('client.return.form');
    Route::post('/return-request', [ReturnRequestController::class, 'store'])
        ->name('return-request.store');

    /* REVIEW */
    Route::post('/review', [ReviewController::class, 'createReview'])->name('review.create');
});

/*
|--------------------------------------------------------------------------
| PRODUCT / CART
|--------------------------------------------------------------------------
*/

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/filter', [ProductController::class, 'filter'])->name('products.filter');
Route::get('/products/{slug}', [ProductController::class, 'detail'])->name('products.detail');

Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'removeCartItem'])->name('cart.removeItem');
Route::get('/mini-cart', [CartController::class, 'loadMiniCart'])->name('cart.mini');
Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.index');

/*
|--------------------------------------------------------------------------
| REVIEW (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::get('/review/{product}', [ReviewController::class, 'index'])->name('review.index');

/*
|--------------------------------------------------------------------------
| PAYMENT CALLBACK
|--------------------------------------------------------------------------
*/

Route::get('/checkout/paypal/success', [PaymentController::class, 'success'])->name('paypal.success');
Route::get('/checkout/paypal/cancel', [PaymentController::class, 'cancel'])->name('paypal.cancel');

/*
|--------------------------------------------------------------------------
| CHAT / CHATBOT
|--------------------------------------------------------------------------
*/

Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
Route::get('/chat/history', [ChatController::class, 'history'])->name('chat.history');

Route::post('/chatbot/send', [ChatbotController::class, 'chat'])->name('chatbot.send');
Route::post('/chat/vision', [ChatbotController::class, 'vision'])->name('chat.vision');
Route::get('/chat/trending', [ChatbotController::class, 'trending'])->name('chat.trending');

Route::get('/history', [ChatbotController::class, 'history']);
Route::get('/download-history', [ChatbotController::class, 'downloadHistory']);
Route::delete('/delete-history', [ChatbotController::class, 'deleteHistory']);


/*
|--------------------------------------------------------------------------
| SEARCH / FLASH SALE / CONTACT
|--------------------------------------------------------------------------
*/

Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/flash-sale', [FlashSaleController::class, 'index'])->name('flash-sale.index');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'sendContact'])->name('contact');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

require __DIR__ . '/admin.php';
