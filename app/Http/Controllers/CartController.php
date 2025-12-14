<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\FlashSale;
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
        // =========================
        // VERIFY REQUEST
        // =========================
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:product_variants,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $request->merge(['quantity' => (int) $request->quantity]);

        $product = Product::with('images')->findOrFail($request->product_id);
        $variant = ProductVariant::with(['color', 'size'])->findOrFail($request->variant_id);

        // =========================
        // GIÁ GỐC
        // =========================
        $basePrice = $variant->price ?? $product->price;

        // Giá thường (sale_price nếu có)
        $normalPrice = ($variant->sale_price && $variant->sale_price > 0)
            ? $variant->sale_price
            : $basePrice;

        // Mặc định dùng giá thường
        $price = $normalPrice;

        // =========================
        // FLASH SALE (KHÔNG KIỂM TRA SỐ LƯỢNG)
        // =========================
        $flashSale = FlashSale::with('items')
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();

        if ($flashSale) {
            $flashItem = $flashSale->items->firstWhere('product_id', $product->id);

            if ($flashItem) {
                // FLASH SALE ƯU TIÊN CAO NHẤT
                $price = round($basePrice * (1 - $flashItem->discount_price / 100));
            }
        }

        // =========================
        // GIÁ CLIENT GỬI ƯU TIÊN CAO NHẤT
        // =========================
        if ($request->filled('flash_price')) {
            $price = (float) $request->flash_price;
        } elseif ($request->filled('price')) {
            $price = (float) $request->price;
        }

        if ($price <= 0) {
            return response()->json(['message' => 'Giá sản phẩm không hợp lệ'], 400);
        }

        // =========================
        // TỒN KHO
        // =========================
        $stock = $variant->quantity;

        // ===================================
        // 🧩 USER ĐĂNG NHẬP
        // ===================================
        if (Auth::check()) {

            // TÌM ITEM CÙNG GIÁ → TÁCH FLASH SALE / GIÁ THƯỜNG
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->where('variant_id', $request->variant_id)
                ->where('price', $price)
                ->first();

            $currentQty = $cartItem ? $cartItem->quantity : 0;

            $remaining = $stock - $currentQty;

            if ($remaining <= 0) {
                return response()->json(['message' => 'Bạn đã thêm tối đa số lượng có sẵn cho sản phẩm này.'], 400);
            }

            if ($request->quantity > $remaining) {
                return response()->json(['message' => 'Bạn chỉ có thể thêm tối đa ' . $remaining . ' sản phẩm nữa.'], 400);
            }

            if (!$cartItem) {
                $cartItem = new CartItem([
                    'user_id'    => Auth::id(),
                    'product_id' => $request->product_id,
                    'variant_id' => $request->variant_id,
                ]);
            }

            $cartItem->quantity = $currentQty + $request->quantity;
            $cartItem->price = $price;
            $cartItem->total_price = $cartItem->quantity * $price;
            $cartItem->save();

            $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
        }

        // ===================================
        // 🧩 USER KHÔNG ĐĂNG NHẬP — SESSION
        // ===================================
        else {
            $cart = session()->get('cart', []);

            $key = $request->product_id . '_' . $request->variant_id . '_' . $price;

            $currentQty = isset($cart[$key]) ? $cart[$key]['quantity'] : 0;

            $remaining = $stock - $currentQty;

            if ($remaining <= 0 || $request->quantity > $remaining) {
                $maxCanAdd = max(0, $remaining);
                return response()->json([
                    'message' => $maxCanAdd == 0
                        ? 'Bạn đã thêm tối đa số lượng có sẵn cho sản phẩm này.'
                        : 'Bạn chỉ có thể thêm tối đa ' . $maxCanAdd . ' sản phẩm nữa.',
                ], 400);
            }

            if (isset($cart[$key])) {
                $cart[$key]['quantity'] = $currentQty + $request->quantity;
                $cart[$key]['price'] = $price;
            } else {
                $cart[$key] = [
                    'product_id' => $product->id,
                    'variant_id' => $variant->id,
                    'name'       => $product->name,
                    'price'      => $price,
                    'quantity'   => $request->quantity,
                    'stock'      => $stock,
                    'color'      => $variant->color_id,
                    'size'       => $variant->size_id,
                    'image'      => $product->images->first()->image ?? 'assets/admin/img/product/default.png',
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
                $item['product'] = Product::find($item['product_id']);
                $item['variant'] = isset($item['variant_id'])
                    ? ProductVariant::with('color', 'size')->find($item['variant_id'])
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
        $flashSale = FlashSale::with('items')
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();

        if (Auth::check()) {

            $cartItems = CartItem::with('product', 'variant.color', 'variant.size')
                ->where('user_id', Auth::id())
                ->get()
                ->map(function (CartItem $item) use ($flashSale) {

                    $product = $item->product;
                    $variant = $item->variant;

                    $basePrice = $variant->price ?? $product->price;
                    $salePrice = $variant->sale_price ?? $product->sale_price ?? $basePrice;

                    // ======================
                    // KHÔNG KIỂM TRA SỐ LƯỢNG FLASH SALE
                    // ======================

                    $flashItem = null;
                    $price = $salePrice;

                    if ($flashSale) {
                        $flashItem = $flashSale->items->firstWhere('product_id', $product->id);

                        if ($flashItem) {
                            // Chỉ áp dụng flash_price – KHÔNG kiểm tra số lượng
                            $price = round($basePrice * (1 - $flashItem->discount_price / 100));
                        }
                    }

                    return [
                        'product_id' => $product->id,
                        'variant_id' => $variant->id ?? null,
                        'name'       => $product->name,
                        'price'      => $price,
                        'quantity'   => $item->quantity,
                        'stock'      => $variant ? $variant->quantity : $product->stock,
                        'image'      => 'assets/admin/img/product/' . ($product->image ?? 'default-product.png'),
                        'color_name' => $variant->color->name ?? null,
                        'size_name'  => $variant->size->name ?? null,
                    ];
                })->toArray();
        } else {

            $cart = session()->get('cart', []);

            foreach ($cart as &$item) {
                $product = \App\Models\Product::find($item['product_id']);
                $variant = isset($item['variant_id']) ? \App\Models\ProductVariant::find($item['variant_id']) : null;

                $basePrice = $variant->price ?? $product->price;
                $salePrice = $variant->sale_price ?? $product->sale_price ?? $basePrice;

                $flashItem = null;
                $price = $salePrice;

                if ($flashSale) {
                    $flashItem = $flashSale->items->firstWhere('product_id', $product->id);

                    if ($flashItem) {
                        // Áp dụng flash sale toàn bộ – KHÔNG kiểm tra số lượng
                        $price = round($basePrice * (1 - $flashItem->discount_price / 100));
                    }
                }

                $item['price'] = $price;
                $item['image'] = 'assets/admin/img/product/' . ($item['image'] ?? 'default-product.png');
                $item['color_name'] = $variant->color->name ?? null;
                $item['size_name']  = $variant->size->name ?? null;
            }

            $cartItems = $cart;
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

        $cartItem = null;
        $userId = Auth::id();

        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', $userId)
                ->where('product_id', $productId)
                ->when($variantId, fn($q) => $q->where('variant_id', $variantId))
                ->first();

            if (!$cartItem) {
                return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
            }
        } else {
            $cart = session()->get('cart', []);
            $key = $productId . '_' . ($variantId ?? 0);
            if (!isset($cart[$key])) {
                return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
            }
            $cartItem = (object) $cart[$key];
        }

        // Lấy product & variant
        $product = Product::find($productId);
        $variant = $variantId ? $product->variants->where('id', $variantId)->first() : null;
        $stock = $variant ? $variant->quantity : $product->stock;

        if ($quantity > $stock) {
            return response()->json(['error' => 'Số lượng vượt quá tồn kho'], 400);
        }

        // Kiểm tra Flash Sale đang diễn ra
        $flashSale = \App\Models\FlashSale::with('items')
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();

        $basePrice = $variant ? $variant->price : $product->price;
        $salePrice = $variant ? $variant->sale_price : $product->sale_price;
        $flashSalePrice = $basePrice;

        $flashSaleQty = 0; // số lượng còn áp dụng flash_sale

        if ($flashSale) {
            $flashItem = $flashSale->items->firstWhere('product_id', $product->id);
            if ($flashItem) {
                $flashQty = min($quantity, $flashItem->quantity); // flash_sale áp dụng
                $normalQty = $quantity - $flashQty; // phần còn lại

                $flashPrice = round($basePrice * (1 - $flashItem->discount_price / 100));
                $normalPrice = $salePrice ?: $basePrice;

                $subtotal = $flashQty * $flashPrice + $normalQty * $normalPrice;

                // Cập nhật cart: lưu giá **giá trị thực của flashPrice cho flashQty**
                $cartItem->price = $flashPrice; // lưu giá flash sale
            } else {
                $price = $salePrice ?: $basePrice;
                $subtotal = $quantity * $price;
                $cartItem->price = $price;
            }
        } else {
            $subtotal = $quantity * ($salePrice ?: $basePrice);
        }

        // Cập nhật cart
        if (Auth::check()) {
            $cartItem->price = $subtotal / $quantity; // giá trung bình từng sản phẩm
            $cartItem->quantity = $quantity;
            $cartItem->save();

            $cartItems = CartItem::where('user_id', $userId)->get();
            $cartTotal = $cartItems->sum(fn($i) => $i->price * $i->quantity);
            $cartCount = $cartItems->sum('quantity');
        } else {
            $cart[$key]['price'] = $subtotal / $quantity;
            $cart[$key]['quantity'] = $quantity;
            session()->put('cart', $cart);

            $cartTotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
            $cartCount = array_sum(array_column($cart, 'quantity'));
        }

        return response()->json([
            'success' => true,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
            'cart_total' => $cartTotal,
            'cart_count' => $cartCount,
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
