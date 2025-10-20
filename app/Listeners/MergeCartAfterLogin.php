<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\CartItem;
use Illuminate\Support\Facades\Session;

class MergeCartAfterLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        $sessionCart = Session::get('cart', []);

        if (!empty($sessionCart)) {
            foreach ($sessionCart as $productId => $cartItem) {
                // Kiểm tra sản phẩm đã tồn tại trong cart của user (cùng product_id + variant_id)
                $existingCartItem = CartItem::where('user_id', $user->id)
                    ->where('product_id', $productId)
                    ->where('variant_id', $cartItem['variant_id'] ?? null)
                    ->first();

                if ($existingCartItem) {
                    // Nếu đã có -> gộp số lượng + cập nhật tổng tiền
                    $existingCartItem->quantity += $cartItem['quantity'];
                    $existingCartItem->total_price = $existingCartItem->price * $existingCartItem->quantity;
                    $existingCartItem->save();
                } else {
                    // Nếu chưa có -> tạo mới
                    CartItem::create([
                        'user_id' => $user->id,
                        'session_id' => null, // Không cần giữ session_id sau khi đăng nhập
                        'product_id' => $productId,
                        'variant_id' => $cartItem['variant_id'] ?? null,
                        'price' => $cartItem['price'],
                        'total_price' => $cartItem['price'] * $cartItem['quantity'],
                        'quantity' => $cartItem['quantity'],
                    ]);
                }
            }

            // Xóa session cart sau khi merge xong
            Session::forget('cart');
        }
    }
}
