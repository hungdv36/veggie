<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        // Validate input
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = Variant::findOrFail($request->variant_id);

        // Kiểm tra tồn kho biến thể
        if ($request->quantity > $variant->stock) {
            return response()->json(['success' => false, 'message' => 'Số lượng vượt quá tồn kho'], 400);
        }

        // Nếu người dùng đã đăng nhập → lưu vào DB
        if (Auth::check()) {
            $cartItem = CartItem::firstOrNew([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'variant_id' => $variant->id,
            ]);

            // Cộng dồn số lượng nếu đã tồn tại
            $cartItem->quantity = ($cartItem->exists ? $cartItem->quantity : 0) + $request->quantity;
            $cartItem->save();

            $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
        } 
        // Nếu chưa đăng nhập → lưu vào session
        else {
            $cart = session()->get('cart', []);
            $key = $product->id . '_' . $variant->id;

            if (isset($cart[$key])) {
                $cart[$key]['quantity'] += $request->quantity;
            } else {
                $cart[$key] = [
                    'product_id' => $product->id,
                    'variant_id' => $variant->id,
                    'name' => $product->name,
                    'price' => $variant->price ?? $product->price,
                    'quantity' => $request->quantity,
                    'stock' => $variant->stock,
                    'color' => $variant->color,
                    'size' => $variant->size,
                    'image' => $product->images->first()->image ?? 'uploads/products/default-product.png',
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
