<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->merge(['quantity' => (int)$request->quantity]);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = Variant::findOrFail($request->variant_id);

        // ✅ Kiểm tra tồn kho biến thể
        if ($request->quantity > $variant->stock) {
            return response()->json(['message' => 'Số lượng vượt quá tồn kho'], 400);
        }

        // ✅ Nếu người dùng đã đăng nhập
        if (Auth::check()) {
            $cartItem = CartItem::firstOrNew([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'variant_id' => $request->variant_id,
            ]);

            // Nếu sản phẩm đã có trong giỏ → cộng dồn
            $cartItem->quantity = ($cartItem->exists ? $cartItem->quantity : 0) + $request->quantity;
            $cartItem->save();

            $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
        } 
        // ✅ Nếu chưa đăng nhập → lưu vào session
        else {
            $cart = session()->get('cart', []);
            $key = $request->product_id . '_' . $request->variant_id;

            if (isset($cart[$key])) {
                $cart[$key]['quantity'] += $request->quantity;
            } else {
                $product = Product::find($request->product_id);

                $cart[$key] = [
                    'product_id' => $product->id,
                    'variant_id' => $variant->id,
                    'name' => $product->name,
                    'price' => $variant->price ?? $product->price,
                    'quantity' => $request->quantity,
                    'stock' => $variant->stock,
                    'color' => $variant->color,
                    'size' => $variant->size,
                    'image' => $product->images->first()->image ?? 'uploads/products/default-product.png'
                ];
            }

            session()->put('cart', $cart);
            $cartCount = array_sum(array_column($cart, 'quantity'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được thêm vào giỏ hàng!',
            'cart_count' => $cartCount
        ]);
    }
}
