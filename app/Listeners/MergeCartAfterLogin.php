<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Session;
use App\Models\CartItem;

class MergeCartAfterLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        $sessionCart = Session::get('cart', []);

        if (empty($sessionCart)) {
            return;
        }

        foreach ($sessionCart as $key => $cartItem) {
            // Tách key dạng "productId_variantId"
            $parts = explode('_', $key);
            $productId = (int) $parts[0];
            $variantId = isset($parts[1]) ? (int) $parts[1] : null;

            // Kiểm tra item đã tồn tại trong DB chưa
            $existingCartItem = CartItem::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->first();

            if ($existingCartItem) {
                // Nếu đã tồn tại, gộp số lượng và cập nhật tổng tiền
                $existingCartItem->quantity += $cartItem['quantity'];
                $existingCartItem->total_price = $existingCartItem->price * $existingCartItem->quantity;
                $existingCartItem->save();
            } else {
                // Nếu chưa có, tạo mới
                CartItem::create([
                    'user_id' => $user->id,
                    'session_id' => null,
                    'product_id' => $productId,
                    'variant_id' => $variantId,
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
