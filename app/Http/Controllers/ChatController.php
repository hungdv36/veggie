<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use Illuminate\Http\Request;
use App\Models\ChatLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Wishlist;
use App\Models\Order;

class ChatController extends Controller
{
    // Lấy lịch sử chat
    public function history(Request $request)
    {
        $userId = Auth::check() ? Auth::id() : null;
        $sessionId = session()->getId();

        $logs = ChatLog::where(function ($q) use ($userId, $sessionId) {
            if ($userId) $q->where('user_id', $userId);
            else $q->where('session_id', $sessionId);
        })->orderBy('created_at', 'asc')->get();

        $userName = Auth::check() ? Auth::user()->name : null;

        return response()->json([
            'logs' => $logs,
            'user_name' => $userName,
        ]);
    }

    public function send(Request $request)
    {
        $message = $request->input('message');
        $user = Auth::user();
        $guestName = $user ? $user->name : 'Khách';
        $messageLower = strtolower($message);

        // 🧩 Lấy giỏ hàng
        $cartItems = $user ? CartItem::with(['product', 'variant.color', 'variant.size'])
            ->where('user_id', $user->id)->get() : collect();

        // 🧩 Lấy wishlist
        $wishlistItems = $user ? Wishlist::with('product')->where('user_id', $user->id)->get() : collect();



        // 🧩 Lấy đơn hàng
        $orders = $user
            ? Order::with([
                'orderItems.product',
                'orderItems.variant.color',
                'orderItems.variant.size'
            ])
            ->where('user_id', $user->id)
            ->latest()
            ->get()
            : collect();

        $orderSummaries = $orders->map(function ($order) {
            $total = $order->orderItems->sum(function ($item) {
                $price = $item->price ?? $item->variant->price ?? $item->product->price ?? 0;
                return $price * $item->quantity;
            }) + 25000;

            return [
                'order_id' => $order->id,
                'status' => $order->status,
                'total' => $total,
                'items' => $order->orderItems->map(function ($item) {
                    $variant = $item->variant;
                    return [
                        'product' => $item->product->name ?? 'N/A',
                        'color' => $variant?->color?->name ?? 'Không có',
                        'size' => $variant?->size?->name ?? 'Không có',
                        'quantity' => $item->quantity,
                    ];
                }),
            ];
        });

        $highestOrderTotal = $orderSummaries->max('total') ?? 0;

        // 🧩 Lấy sản phẩm
        $products = Product::all(['id', 'name', 'price', 'category_id']);
        $topProducts = Product::withSum('orderItems', 'quantity')
            ->orderByDesc('order_items_sum_quantity')
            ->take(5)
            ->get(['id', 'name', 'price']);

        // 🧩 Xác định loại câu hỏi
        $orderKeywords = ['đơn hàng', 'tổng tiền', 'hóa đơn', 'mua trước đây', 'đặt hàng', 'đơn của tôi'];
        $productKeywords = ['sản phẩm', 'giá', 'loại', 'màu', 'size', 'còn hàng', 'sản phẩm hiện có'];
        $hotProductKeywords = ['bán chạy', 'hot', 'được mua nhiều', 'top sản phẩm'];
        $cartKeywords = ['giỏ hàng', 'giỏ hàng của tôi', 'của tôi', 'mua rồi', 'có sản phẩm nào', 'có trong giỏ hàng'];
        $wishlistKeywords = ['yêu thích', 'wishlist', 'thích', 'sản phẩm yêu thích', 'danh sách yêu thích'];
        $flashSaleKeywords = ['flash sale', 'sale', 'giảm giá', 'khuyến mãi', 'đang sale', 'đang giảm'];

        $isOrderQuestion = false;
        $isProductQuestion = false;
        $isHotProductQuestion = false;
        $isCartQuestion = false;
        $isWishlistQuestion = false;
        $isFlashSaleQuestion = false;

        foreach ($orderKeywords as $kw) if (str_contains($messageLower, $kw)) $isOrderQuestion = true;
        foreach ($productKeywords as $kw) if (str_contains($messageLower, $kw)) $isProductQuestion = true;
        foreach ($hotProductKeywords as $kw) if (str_contains($messageLower, $kw)) $isHotProductQuestion = true;
        foreach ($cartKeywords as $kw) if (str_contains($messageLower, $kw)) $isCartQuestion = true;
        foreach ($wishlistKeywords as $kw) if (str_contains($messageLower, $kw)) $isWishlistQuestion = true;
        foreach ($flashSaleKeywords as $kw) if (str_contains($messageLower, $kw)) $isFlashSaleQuestion = true;


        // 🧩 Chuẩn bị phản hồi
        if ($isOrderQuestion) {
            $orderList = $orderSummaries->take(5)->map(function ($order) {
                $items = $order['items']->map(fn($i) => "{$i['product']} (màu: {$i['color']}, size: {$i['size']}, SL: {$i['quantity']})")
                    ->implode('; ');
                return "Đơn #{$order['order_id']} - Trạng thái: {$order['status']} - Tổng: " . number_format($order['total']) . " VNĐ\n  Sản phẩm: $items";
            })->implode("\n\n");

            $response = $orderList
                ? "5 đơn hàng gần nhất của bạn:\n$orderList\n\nTổng tiền đơn hàng cao nhất: " . number_format($highestOrderTotal) . " VNĐ"
                : "Bạn chưa có đơn hàng nào.";
        } elseif ($isHotProductQuestion) {
            $response = $topProducts->isEmpty()
                ? "Hiện chưa có sản phẩm bán chạy."
                : "Top 5 sản phẩm bán chạy:\n" . $topProducts->map(fn($p) => "{$p->name} - Giá: " . number_format($p->price) . " VNĐ")->implode("\n");
        } elseif ($isProductQuestion) {
            $response = $products->isEmpty()
                ? "Hiện chưa có sản phẩm nào."
                : "Danh sách sản phẩm hiện có:\n" . $products->map(fn($p) => "{$p->name} - Giá: " . number_format($p->price) . " VNĐ")->implode("\n");
        } elseif ($isCartQuestion) {
            $response = $cartItems->isEmpty()
                ? "Giỏ hàng của bạn hiện trống."
                : "Giỏ hàng của bạn đang có:\n" . $cartItems->map(function ($item) {
                    $productName = $item->product->name ?? 'N/A';
                    $color = $item->variant->color->name ?? 'Không có';
                    $size = $item->variant->size->name ?? 'Không có';
                    $qty = $item->quantity ?? 0;
                    return "$productName (màu: $color, size: $size, SL: $qty)";
                })->implode("\n");
        } elseif ($isWishlistQuestion) {
            $response = $wishlistItems->isEmpty()
                ? "Danh sách yêu thích của bạn hiện trống."
                : "Danh sách yêu thích:\n" . $wishlistItems->map(fn($item) => $item->product->name ?? '')->implode("\n");
        } elseif ($isFlashSaleQuestion) {
            $flashSales = FlashSale::with(['items.product'])
                ->where('start_time', '<=', now())
                ->where('end_time', '>=', now())
                ->get();

            if ($flashSales->isEmpty()) {
                $response = "Hiện không có chương trình Flash Sale nào đang diễn ra.";
            } else {
                $response = "🔥 Sản phẩm đang trong Flash Sale:\n";
                foreach ($flashSales as $sale) {
                    $response .= "⏰ {$sale->name} (từ {$sale->start_time->format('d/m')} đến {$sale->end_time->format('d/m')}):\n";
                    foreach ($sale->items as $item) {
                        $product = $item->product;
                        $discountPercent = $item->discount_price; // % giảm
                        $originalPrice = $product->price;
                        $finalPrice = $originalPrice * (1 - $discountPercent / 100);

                        $response .= "- {$product->name}: giảm {$discountPercent}% còn "
                            . number_format($finalPrice) . " VNĐ (từ "
                            . number_format($originalPrice) . " VNĐ)\n";
                    }
                }
            }
        } else {
            $response = "Chào $guestName! Hôm nay tôi có thể giúp gì cho bạn!.";
        }
        // 🧩 Lưu log chat
        ChatLog::create([
            'user_id'    => $user?->id,
            'session_id' => session()->getId(),
            'message'    => $message,
            'reply'      => $response,
        ]);

        return response()->json(['reply' => $response]);
    }


    // Merge chat guest vào user khi login
    public static function mergeGuestChatToUser($userId)
    {
        $sessionId = session()->getId();
        ChatLog::where('session_id', $sessionId)
            ->update(['user_id' => $userId, 'session_id' => null]);
    }
}
