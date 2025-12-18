<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with([
            'orderItems.product',
            'orderItems.variant.color',
            'orderItems.variant.size',
            'shippingAddress',
            'user',
            'payment'
        ])
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view("admin.pages.order.orders", compact('orders'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function confirmOrder(Request $request)
    {
        $order = Order::find($request->id);
        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Đơn hàng không tồn tại',
            ]);
        }

        // Lưu trạng thái cũ trước khi update
        $oldStatus = $order->status ?? 'pending'; // nếu null thì mặc định pending
        $newStatus = 'processing';

        // Cập nhật trạng thái mới
        $order->status = $newStatus;
        $order->save();

        $admin = Auth::guard('admin')->user();
        // Lưu lịch sử trạng thái
        $order->status_logs()->create([
            'role_id' => $admin ? $admin->role_id : 1,
            'old_status' => $oldStatus, // dùng biến đã lưu
            'status' => $newStatus,
            'changed_at' => now(),
            'notes' => 'Xác nhận đơn hàng'
        ]);

        return response()->json([
            'status' => true,
            'message' => "Trạng thái đã thay đổi từ '$oldStatus' → '$newStatus'",
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function showOrderDetail(string $id)
    {
        $order = Order::with('orderItems.product', 'orderItems.variant.size', 'orderItems.variant.color', 'shippingAddress', 'user', 'payment', 'status_logs.role', 'status_logs.user', 'refund.histories.admin')->find($id);
        $refund = $order->refund;
        return view('admin.pages.order.orders-detail', compact('order', 'refund'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'status' => 'required|string',
            'note' => 'nullable|string|max:500',
        ]);

        $order = Order::with('payment')->findOrFail($request->order_id);
        $oldStatus = $order->status;
        $newStatus = $request->status;
        $note = $request->note;

        // Không cho nhảy trạng thái tùy tiện
        $validFlow = [
            'pending'         => ['processing'],
            'processing'      => ['shipped', 'canceled'],
            'shipped'         => ['completed', 'failed_delivery'],
            'completed'       => ['received'],
            'failed_delivery' => ['canceled'],
        ];

        if (isset($validFlow[$oldStatus]) && !in_array($newStatus, $validFlow[$oldStatus])) {
            return response()->json([
                'status' => false,
                'message' => "Không thể chuyển từ '$oldStatus' sang '$newStatus'!"
            ]);
        }

        // Cập nhật trạng thái đơn hàng
        $order->update(['status' => $newStatus]);

        // 🔹 Xử lý thanh toán COD
        if ($order->payment_method === 'cod' || $order->payment_method === 'cash') {
            $payment = $order->payment;

            if (!$payment) {
                // Tạo mới payment COD nếu chưa có
                $order->payment()->create([
                    'amount' => $order->total_amount,
                    'status' => in_array($newStatus, ['completed', 'received']) ? 'completed' : 'pending',
                    'paid_at' => in_array($newStatus, ['completed', 'received']) ? now() : null,
                    'transaction_id' => null,
                ]);
            } else {
                // Cập nhật payment COD hiện có
                if (in_array($newStatus, ['completed', 'received'])) {
                    $payment->update([
                        'status' => 'completed',
                        'paid_at' => now(),
                    ]);
                } elseif (in_array($newStatus, ['canceled', 'failed_delivery'])) {
                    $payment->update([
                        'status' => 'failed',
                    ]);
                }
            }

            // Reload relation để Blade nhận đúng
            $order->load('payment');
        }

        // 🔹 Xử lý online payment (giữ nguyên logic)
        elseif ($order->payment && $order->payment_method !== 'cod') {
            // Chỉ cần giữ status đã thanh toán
            if ($newStatus === 'completed' && $order->payment->status !== 'completed') {
                $order->payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);
            }
        }

        // Lưu lịch sử trạng thái
        $admin = Auth::guard('admin')->user();
        $order->status_logs()->create([
            'role_id' => $admin ? $admin->role_id : 1,
            'old_status' => $oldStatus,
            'status' => $newStatus,
            'changed_at' => now(),
            'notes' => $note ?? $this->generateStatusNote($newStatus),
        ]);

        return response()->json([
            'status' => true,
            'message' => "Cập nhật trạng thái thành công!"
        ]);
    }


    /**
     * Gợi ý ghi chú mặc định nếu không nhập tay.
     */
    private function generateStatusNote($status)
    {
        return match ($status) {
            'processing' => 'Đơn hàng đã được xác nhận',
            'shipped' => 'Đơn hàng đang được giao',
            'completed' => 'Đơn hàng đã giao thành công',
            'failed_delivery' => 'Giao hàng thất bại, khách không nhận hoặc không liên lạc được',
            'received' => 'Khách đã xác nhận đã nhận hàng',
            'canceled' => 'Đơn hàng đã bị hủy',
            default => null,
        };
    }
    public function cancelOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'note' => 'nullable|string|max:500',
        ]);

        $order = Order::find($request->order_id);

        $cancellableStatuses = ['pending', 'processing'];
        if (!in_array($order->status, $cancellableStatuses)) {
            return response()->json([
                'status' => false,
                'message' => 'Đơn hàng này không thể hủy!',
            ]);
        }

        $oldStatus = $order->status;
        $order->update(['status' => 'canceled']);
        $admin = Auth::guard('admin')->user();

        // Lưu lịch sử trạng thái
        $order->status_logs()->create([
            'role_id' => $admin->role_id,
            'old_status' => $oldStatus,
            'status' => 'canceled',
            'changed_at' => now(),
            'notes' => $request->note ?? 'Admin hủy đơn hàng',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Hủy đơn hàng thành công!',
        ]);
    }
    public function sendMailInvoice(Request $request)
    {
        $order = Order::with('orderItems.product', 'shippingAddress', 'user', 'payment')
            ->find($request->order_id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Đơn hàng không tồn tại.',
            ]);
        }

        // Kiểm tra nếu đã gửi hóa đơn
        if ($order->invoice_sent) {
            return response()->json([
                'status' => false,
                'message' => 'Hóa đơn đã được gửi trước đó!',
            ]);
        }

        try {
            Mail::send('admin.emails.invoice', compact('order'), function ($message) use ($order) {
                $message->to($order->user->email)
                    ->subject('Hóa đơn đặt hàng của khách hàng ' . $order->shippingAddress->full_name);
            });

            // Cập nhật trạng thái đã gửi
            $order->update([
                'invoice_sent' => true,
                'invoice_sent_at' => now(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Gửi hóa đơn qua email thành công!',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Không thể gửi hóa đơn qua email. Vui lòng thử lại sau. ' . $th->getMessage(),
            ]);
        }
    }
}
