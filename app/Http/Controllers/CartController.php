<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $price = $variant->sale_price ?? $variant->price ?? $product->sale_price ?? $product->price ?? 0;

        if ($price <= 0) {
            return response()->json(['message' => 'Giá sản phẩm không hợp lệ'], 400);
        }

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
            $cartCount = CartItem::where('user_id', Auth::id())->count();
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Sản phẩm đã được thêm vào giỏ hàng!',
            'cart_count' => $cartCount,
        ]);
    }

    /**
     * Load mini cart
     */
    public function loadMiniCart(): JsonResponse
    {
        if (Auth::check()) {
            $cartItems = CartItem::with('product', 'variant.color', 'variant.size')
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $cartItems = session()->get('cart', []);
        }

        return response()->json([
            'status' => true,
            'html'   => view('clients.components.includes.mini_cart', compact('cartItems'))->render(),
        ]);
    }

    public function getCartCount(): int
    {
        if (Auth::check()) {
            // Đếm số dòng CartItem, mỗi biến thể 1 dòng
            return CartItem::where('user_id', Auth::id())->count();
        } else {
            $cart = session()->get('cart', []);
            return count($cart); // mỗi key = product_id_variant_id, đúng số biến thể
        }
    }



    /**
     * Xóa sản phẩm trong mini cart
     */
    public function removeFromMiniCart(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required',
            'variant_id' => 'required',
        ]);

        $key = $request->product_id . '_' . $request->variant_id;

        if (Auth::check()) {
            // Xóa sản phẩm
            CartItem::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->where('variant_id', $request->variant_id)
                ->delete();

            // Lấy lại danh sách giỏ hàng
            $cartItems = CartItem::with('product', 'variant.color', 'variant.size')
                ->where('user_id', Auth::id())
                ->get();

            // Tổng số lượng
            $cartCount = $cartItems->sum('quantity');
        } else {
            $cart = session()->get('cart', []);
            unset($cart[$key]);
            session()->put('cart', $cart);

            $cartItems = collect($cart)->map(function ($item) {
                $item['product'] = \App\Models\Product::find($item['product_id']);
                $item['variant'] = isset($item['variant_id'])
                    ? \App\Models\ProductVariant::with('color', 'size')->find($item['variant_id'])
                    : null;
                return (object) $item;
            });

            $cartCount = $cartItems->sum(fn($i) => $i->quantity);
        }

        // Tính subtotal theo giá hiển thị (sale_price nếu có)
        $subtotal = $cartItems->sum(function ($item) {
            $price = $item->variant
                ? ($item->variant->sale_price && $item->variant->sale_price > 0 ? $item->variant->sale_price : $item->variant->price)
                : ($item->product->sale_price && $item->product->sale_price > 0 ? $item->product->sale_price : $item->product->price);
            return $price * $item->quantity;
        });

        // Render lại mini cart HTML
        $mini_cart_html = view('clients.components.includes.mini_cart', compact('cartItems'))->render();

        return response()->json([
            'status'     => true,
            'cart_count' => $cartCount,
            'subtotal'   => $subtotal,
            'html'       => $mini_cart_html,
        ]);
    }


    /**
     * View giỏ hàng
     */
    public function viewCart(): View
    {
        if (Auth::check()) {
            $cartItems = CartItem::with('product', 'variant.color', 'variant.size')
                ->where('user_id', Auth::id())
                ->get()
                ->map(function (CartItem $item) {
                    $product = $item->product;
                    $variant = $item->variant;

                    return [
                        'product_id' => $product->id,
                     'variant_id' => $variant->id ?? null,
                        'name'       => $product->name,
                        'price'      => $item->price,
                        'quantity'   => $item->quantity,
                        'stock'      => $product->stock ?? 0,
                        'image'      => 'assets/admin/img/product/' . ($product->image ?? 'default-product.png'),
                        'color_name' => $variant->color->name ?? null,
                        'size_name'  => $variant->size->name ?? null,
                    ];
                })->toArray();
        } else {
            $cartItems = session()->get('cart', []);
            foreach ($cartItems as &$item) {
                $item['image'] = 'assets/admin/img/product/' . ($item['image'] ?? 'default-product.png');
                $item['color_name'] = isset($item['color']) ? \App\Models\Color::find($item['color'])->name ?? null : null;
                $item['size_name']  = isset($item['size']) ? \App\Models\Size::find($item['size'])->name ?? null : null;
            }
        }

        return view('clients.pages.cart', compact('cartItems'));
    }


    public function removeCartItem(Request $request)
    {
        $productId = $request->product_id;
        $variantId = $request->variant_id ?? null;

        if (Auth::check()) {
            $query = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId);

            // Nếu có variant_id, xóa đúng variant
            if ($variantId !== null) {
                $query->where('variant_id', $variantId);
            } else {
                $query->whereNull('variant_id');
            }

            $query->delete();
        } else {
            $cart = session()->get('cart', []);
            $key = $productId . '_' . ($variantId ?? 0); // key duy nhất cho sản phẩm + variant
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        // Tính lại tổng
        $total = 0;
        if (Auth::check()) {
            $cartItems = CartItem::where('user_id', Auth::id())->get();
            foreach ($cartItems as $item) {
                $total += $item->quantity * $item->price;
            }
            $cartCount = $cartItems->sum('quantity');
        } else {
            $cart = session()->get('cart', []);
            foreach ($cart as $item) {
                $total += $item['quantity'] * $item['price'];
            }
            $cartCount = array_sum(array_column($cart, 'quantity'));
        }

        return response()->json([
            'cart_total' => $total,
            'cart_count' => $cartCount,
        ]);
    }


    public function updateCart(Request $request): JsonResponse
    {
        $productId = $request->product_id;
        $variantId = $request->variant_id ?? null;
        $quantity = (int) $request->quantity;

        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->when($variantId, fn($q) => $q->where('variant_id', $variantId))
                ->first();

            if (!$cartItem) {
                return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
            }

            $stock = $cartItem->variant_id
                ? $cartItem->variant->quantity
                : $cartItem->product->stock;

            if ($quantity > $stock) {
                return response()->json(['error' => 'Số lượng vượt quá tồn kho'], 400);
            }

            $cartItem->quantity = $quantity;
            $cartItem->save();

            $cartItems = CartItem::where('user_id', Auth::id())->get();
            $cartTotal = $cartItems->sum(fn($i) => $i->price * $i->quantity);
            $cartCount = $cartItems->sum('quantity');
        } else {
            $cart = session()->get('cart', []);
            $key = $productId . '_' . ($variantId ?? 0);

            if (!isset($cart[$key])) {
                return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
            }

            $product = Product::find($productId);
            $stock = $variantId
                ? optional($product->variants->where('id', $variantId)->first())->quantity
                : $product->stock;

            if ($quantity > $stock) {
                return response()->json(['error' => 'Số lượng vượt quá tồn kho'], 400);
            }

            $cart[$key]['quantity'] = $quantity;
            session()->put('cart', $cart);

            $cartTotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
            $cartCount = array_sum(array_column($cart, 'quantity'));
        }

        return response()->json([
            'success' => true,
            'cart_total' => $cartTotal,
            'cart_count' => $cartCount,
            'quantity' => $quantity
        ]);
    }


    function calculateCartTotal()
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id())->with('product')->get()->sum(fn($item) => $item->quantity * $item->product->price);
        } else {
            $cart = session()->get('cart', []);
            return collect($cart)->sum(fn($item) => $item['quantity'] * $item['price']);
        }
    }
}
