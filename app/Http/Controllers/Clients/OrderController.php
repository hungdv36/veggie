<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function showOrder($id)
    {
        $order = Order::with(['orderItems.product', 'user', 'shippingAddress', 'payment'])
            ->where('user_id', auth()->id()) // ✅ chỉ xem được đơn của mình
            ->findOrFail($id);

        return view('clients.pages.order-detail', compact('order'));
    }
    public function confirmReceived(Order $order)
    {
        // Chỉ chủ đơn hàng mới được xác nhận
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Không có quyền xác nhận đơn hàng này.');
        }

        // Chỉ được xác nhận khi đơn đã giao thành công
        if ($order->status !== 'completed') {
            return back()->with('error', 'Đơn hàng chưa được giao, không thể xác nhận.');
        }

        // Lưu trạng thái cũ
        $oldStatus = $order->status;

        // Cập nhật trạng thái mới
        $order->update(['status' => 'received']);

        // Ghi vào bảng lịch sử
        OrderStatusHistory::create([
            'order_id'   => $order->id,
            'old_status' => $oldStatus ?? 'pending',
            'status'     => 'received', // ✅ đổi từ 'completed' sang 'received'
            'changed_by' => auth()->id(),
            'role_id'    => Auth::user()->role_id,
            'notes'       => 'Người dùng xác nhận đã nhận hàng',
        ]);

        return back()->with('success', 'Xác nhận đã nhận hàng thành công!');
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
