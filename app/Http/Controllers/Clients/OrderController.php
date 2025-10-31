<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function showOrder($id)
    {
        $order = Order::with(['orderItems.product', 'user', 'shippingAddress', 'payment'])
            ->where('user_id', auth()->id()) // ✅ chỉ xem được đơn của mình
            ->findOrFail($id);

        return view('clients.pages.order-detail', compact('order'));
    }

    public function cancelOrder(Request $request, $id): RedirectResponse
    {
        $order = Order::with('orderItems.product') // ✅ tránh N+1 query
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        $request->validate([
            'cancel_reason' => 'required|string|max:255',
        ]);

        // ✅ Hoàn kho
        foreach ($order->orderItems as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        // ✅ Cập nhật trạng thái & lý do hủy
        $order->update([
            'status' => 'canceled',
            'cancel_reason' => $request->cancel_reason,
        ]);

        return redirect()->back()->with('success', 'Đơn hàng đã được hủy và sản phẩm được hoàn kho.');
    }
}
