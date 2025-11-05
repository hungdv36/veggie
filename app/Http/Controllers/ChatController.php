<?php

namespace App\Http\Controllers;

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

        // 🧩 Lấy tên khách
        $guestName = $user ? $user->name : 'Khách';

        // 🧩 Lấy danh sách sản phẩm
        $products = Product::with('category')->get(['id', 'name', 'price']);

        // 🧩 Lấy giỏ hàng
        $cartItems = CartItem::with(['product', 'variant.color', 'variant.size'])
            ->when($user, fn($q) => $q->where('user_id', $user->id))
            ->get();

        $cartDescription = $cartItems->map(function ($item) {
            $productName = $item->product->name ?? 'N/A';
            $color = $item->variant->color->name ?? 'Không có';
            $size = $item->variant->size->name ?? 'Không có';
            $qty = $item->quantity ?? 0;
            return "$productName (màu: $color, size: $size, SL: $qty)";
        })->implode(', ');

        // 🧩 Lấy danh sách yêu thích
        $wishlistItems = Wishlist::with('product')->get();
        $wishlistDescription = $wishlistItems->map(fn($item) => $item->product->name ?? '')->implode(', ');

        // 🧩 Lấy 5 đơn hàng gần nhất + tính tổng tiền từng đơn
        $orders = Order::with(['orderItems.product', 'orderItems.variant.color', 'orderItems.variant.size'])
            ->when($user, fn($q) => $q->where('user_id', $user->id))
            ->latest()
            ->get();

        $orderSummaries = $orders->map(function ($order) {
            $total = $order->orderItems->sum(function ($item) {
                $price = $item->price ?? $item->variant->price ?? $item->product->price ?? 0;
                return $price * $item->quantity;
            });
            $shippingFee = 25000;
            $total += $shippingFee;

            return [
                'order_id' => $order->id,
                'total' => $total,
                'items' => $order->orderItems->map(function ($item) {
                    return [
                        'product' => $item->product->name ?? 'N/A',
                        'size' => $item->variant->size->name ?? 'N/A',
                        'color' => $item->variant->color->name ?? 'N/A',
                        'quantity' => $item->quantity,
                        'price' => $item->product->price ?? 0,
                    ];
                }),
            ];
        });

        // 🧩 Tìm đơn hàng có tổng tiền cao nhất
        $highestOrderTotal = $orderSummaries->max('total') ?? 0;

        // 🧩 Chuẩn bị dữ liệu gửi tới Gemini
        $messages = [
            ['role' => 'system', 'content' => 'Bạn là trợ lý ảo của ClotheStore, tên là ClotheBot.'],
            ['role' => 'system', 'content' => 'Người dùng hiện tại tên là ' . $guestName . '.'],
            ['role' => 'system', 'content' => 'Danh sách sản phẩm hiện có: ' . json_encode($products)],
            ['role' => 'system', 'content' => 'Giỏ hàng hiện tại của người dùng: ' . ($cartDescription ?: 'Trống')],
            ['role' => 'system', 'content' => 'Danh sách yêu thích: ' . ($wishlistDescription ?: 'Trống')],
            ['role' => 'system', 'content' => 'Danh sách 5 đơn hàng gần nhất của người dùng: ' . json_encode($orderSummaries)],
            ['role' => 'system', 'content' => 'Đơn hàng có tổng tiền cao nhất là ' . number_format($highestOrderTotal) . ' VNĐ.'],
            ['role' => 'user', 'content' => $message],
        ];

        // 🧩 Gọi API Gemini (hoặc mô phỏng trả lời)
        $response = "Tổng tiền đơn hàng cao nhất là " . number_format($highestOrderTotal) . " VNĐ.";

        // Lưu log chat
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
