<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function addToCart(Request $request): JsonResponse
    {
        $request->merge(['quantity' => (int) $request->quantity]);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:product_variants,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::with('images')->findOrFail($request->product_id);
        $variant = ProductVariant::with(['color', 'size'])->findOrFail($request->variant_id);

        // Ưu tiên giá biến thể, fallback về giá sản phẩm
        $price = $variant->sale_price ?? $variant->price ?? $product->sale_price ?? $product->price ?? 0;
        if ($price <= 0) {
            return response()->json(['message' => 'Giá sản phẩm không hợp lệ'], 400);
        }

        // Kiểm tra tồn kho biến thể
        if ($request->quantity > $variant->quantity) {
            return response()->json(['message' => 'Số lượng vượt quá tồn kho'], 400);
        }

        // Nếu người dùng đã đăng nhập
        if (Auth::check()) {
            $cartItem = CartItem::firstOrNew([
                'user_id'    => Auth::id(),
                'product_id' => $request->product_id,
                'variant_id' => $request->variant_id,
            ]);

            // Cộng dồn nếu sản phẩm đã có
            $cartItem->quantity = ($cartItem->exists ? $cartItem->quantity : 0) + $request->quantity;
            $cartItem->price = $price;
            $cartItem->total_price = $cartItem->price * $cartItem->quantity;
            $cartItem->save();

            $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
        }
        // Nếu chưa đăng nhập → lưu session
        else {
            $cart = session()->get('cart', []);
            $key = $request->product_id . '_' . $request->variant_id;

            if (isset($cart[$key])) {
                $cart[$key]['quantity'] += $request->quantity;
                $cart[$key]['price'] = $price;
            } else {
                $cart[$key] = [
                    'product_id' => $product->id,
                    'variant_id' => $variant->id,
                    'name'       => $product->name,
                    'price'      => $price,
                    'quantity'   => $request->quantity,
                    'stock'      => $variant->quantity,
                    'color'      => $variant->color_id,
                    'size'       => $variant->size_id,
                    'image'      => $product->images->first()->image ?? 'uploads/products/default-product.png',
                ];
            }

            session()->put('cart', $cart);
            $cartCount = array_sum(array_column($cart, 'quantity'));
        }

        return response()->json([
            'success'     => true,
            'message'     => 'Sản phẩm đã được thêm vào giỏ hàng!',
            'cart_count'  => $cartCount,
        ]);
    }

    /**
     * Load mini cart (ajax)
     */
    public function loadMiniCart(): JsonResponse
    {
        if (auth()->check()) {
            $cartItems = CartItem::with('product')
                ->where('user_id', auth()->id())
                ->get();
        } else {
            $cartItems = session('cart', []);
        }

        return response()->json([
            'status' => true,
            'html'   => view('clients.components.includes.mini_cart', compact('cartItems'))->render(),
        ]);
    }

    public function removeFromMiniCart(Request $request): JsonResponse
    {
        $request->validate(['product_id' => 'required']);

        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->where('product_id', $request->product_id)->delete();
            $cartCount = CartItem::where('user_id', Auth::id())->count();
        } else {
            // If not logged in, save to session
            $cart = session()->get('cart', []);
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
            $cartCount = count($cart);
        }
        return response()->json([
            'status' => true,
            'cart_count' => $cartCount
        ]);
    }

    // View Cart
    public function viewCart(): View
    {
        if (Auth::check()) {
            // Get cart from database
            $cartItems = CartItem::where(column: 'user_id', operator: Auth::id())
                ->with(relations: 'product')
                ->get()
                ->map(callback: function (CartItem $item) {
                    return [
                        'product_id' => $item->product->id,
                        'name' => $item->product->name,
                        'price' => $item->product->price,
                        'quantity' => $item->quantity,
                        'stock' => $item->product->stock,
                        'image' => $item->product->images->first()->image ?? 'uploads/products/default-product.png',
                    ];
                })
                ->toArray();
        } else {
            // Get cart from session
            $cartItems = session()->get(key: 'cart', default: []);
        }
        return view(view: 'clients.pages.cart', data: compact(var_name: 'cartItems'));
    }
}
