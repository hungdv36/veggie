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
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'variant_id' => 'nullable|integer|exists:variants,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::with('images','variants')->findOrFail($data['product_id']);
        $quantity = (int) $data['quantity'];
        $variant = null;

        if (!empty($data['variant_id'])) {
            $variant = Variant::find($data['variant_id']);
            if (!$variant || $variant->product_id != $product->id) {
                return response()->json(['success' => false, 'message' => 'Biến thể không hợp lệ.'], 422);
            }
            if ($quantity > $variant->stock) {
                return response()->json(['success' => false, 'message' => 'Số lượng vượt quá tồn kho biến thể.'], 422);
            }
        } else {
            // Nếu product có biến thể, bắt buộc chọn biến thể
            if ($product->variants->count() > 0) {
                return response()->json(['success' => false, 'message' => 'Vui lòng chọn biến thể sản phẩm.'], 422);
            }
            if ($quantity > ($product->stock ?? 0)) {
                return response()->json(['success' => false, 'message' => 'Số lượng vượt quá tồn kho sản phẩm.'], 422);
            }
        }

        if (Auth::check()) {
            $cartItem = CartItem::firstOrNew([
                'user_id'    => Auth::id(),
                'product_id' => $product->id,
                'variant_id' => $variant ? $variant->id : null,
            ]);

            $cartItem->quantity = ($cartItem->exists ? $cartItem->quantity : 0) + $quantity;
            $cartItem->price = $variant ? $variant->price : $product->price;
            $cartItem->save();

            $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
        } else {
            $cart = session()->get('cart', []);
            $key = $product->id . '_' . ($variant ? $variant->id : '0');

            if (isset($cart[$key])) {
                $cart[$key]['quantity'] += $quantity;
            } else {
                $cart[$key] = [
                    'product_id' => $product->id,
                    'variant_id' => $variant ? $variant->id : null,
                    'name'       => $product->name,
                    'price'      => $variant ? $variant->price : $product->price,
                    'quantity'   => $quantity,
                    'stock'      => $variant ? $variant->stock : ($product->stock ?? null),
                    'color'      => $variant ? $variant->color : null,
                    'size'       => $variant ? $variant->size : null,
                    'image'      => $product->images->first()?->image
                                    ? asset('storage/'.$product->images->first()->image)
                                    : asset('storage/uploads/products/default-product.png'),
                ];
            }

            session()->put('cart', $cart);
            $cartCount = array_sum(array_column($cart, 'quantity'));
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Sản phẩm đã được thêm vào giỏ hàng!',
            'cart_count' => $cartCount,
        ]);
    }
}