<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->merge(['quantity' => (int)$request->quantity]);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->quantity > $product->stock) {
            return response()->json(['message' => 'Số lượng vượt quá tồn kho'], 400);
        }

        if (Auth::check()) {
            // Nếu đã đăng nhập, lưu vào DB với đúng số lượng chọn
            CartItem::updateOrCreate(
                ['user_id' => Auth::id(), 'product_id' => $request->product_id],
                ['quantity' => $request->quantity]
            );

            $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
        } else {
            // Nếu chưa đăng nhập, lưu vào session
            $cart = session()->get('cart', []);
            $cart[$request->product_id] = [
                'product_id' => $request->product_id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity,
                'stock' => $product->stock,
                'image' => $product->images->first()->image ?? 'uploads/products/default-product.png'
            ];
            session()->put('cart', $cart);

            $cartCount = array_sum(array_column($cart, 'quantity'));
        }

        return response()->json(['message' => true, 'cart_count' => $cartCount]);
    }
}
