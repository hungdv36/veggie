<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function showOrder($id)
    {
        $order = Order::with([
            'orderItems.product',
            'orderItems.variant.color',
            'orderItems.variant.size',
            'user',
            'shippingAddress',
            'payment'
        ])
            ->where('user_id', auth()->id())
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
        $order = Order::with([
            'orderItems.product',
            'orderItems.variant',
            'orderCoupons.coupon',
            'payment',
            'status_logs.user',
            'status_logs.role',
        ])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'processing'])
            ->firstOrFail();

        $request->validate([
            'cancel_reason' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Hoàn kho chính xác
            foreach ($order->orderItems as $item) {

                // Nếu có variation thì hoàn vào variation
                if ($item->variation_id) {
                    if ($item->variation) {
                        $item->variation->increment('stock', $item->quantity);
                    } elseif ($item->product) {
                        // fallback khi variation bị xoá
                        $item->product->increment('stock', $item->quantity);
                    }
                } else {
                    // Không có variation -> hoàn vào product
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }

            // Trả lại lượt dùng mã giảm giá
            foreach ($order->orderCoupons as $orderCoupon) {
                $coupon = $orderCoupon->coupon;
                if ($coupon && $coupon->used > 0) {
                    $coupon->decrement('used');
                }
            }

            $oldStatus = $order->status;

            // Cập nhật đơn
            $order->update([
                'status' => 'canceled',
                'cancel_reason' => $request->cancel_reason,
            ]);

            // Lưu log thay đổi trạng thái
            $order->status_logs()->create([
                'old_status' => $oldStatus,
                'status' => 'canceled',
                'role_id' => 3,
                'user_id' => auth()->id(),
                'notes' => $request->cancel_reason,
            ]);

            // Nếu thanh toán MoMo -> thêm yêu cầu hoàn tiền
            if ($order->payment && $order->payment->payment_method === 'momo') {
                Refund::create([
                    'order_id' => $order->id,
                    'status' => 'waiting_info',
                ]);

                DB::commit();
                return redirect()->route('refund.bank-info', $order->id)
                    ->with('success', 'Đơn hàng đã hủy. Vui lòng nhập thông tin ngân hàng để được hoàn tiền.');
            }

            DB::commit();
            return redirect()->back()->with('success', 'Đơn hàng đã hủy thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }
}
