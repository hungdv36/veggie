<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Refund;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['user', 'refund', 'payment'])
            ->whereHas('payment', fn($q) => $q->where('payment_method', 'momo'))
            ->where('status', 'canceled')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('admin.pages.refund.refunds', compact('orders'));
    }
    public function show($id)
    {
        $refund = Refund::with('order.user', 'order.items.product')->findOrFail($id);
        return view('admin.pages.refund.show', compact('refund'));
    }
    public function updateStatus(Request $request, $id)
    {
        $refund = Refund::findOrFail($id);
        $request->validate([
            'status' => 'required|in:submitted,in_process',
        ]);

        $refund->status = $request->status;
        $refund->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công.');
    }
    public function completeRefund(Request $request, $id)
    {
        $refund = Refund::with(
            'order.items.variant.size',
            'order.items.variant.color',
            'order.user',
            'order.shippingAddress'
        )->findOrFail($id);

        // 1. Tạo PDF biên lai
        $pdf = Pdf::loadView('admin.pages.refund.receipt', compact('refund'))
            ->setPaper('A4')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans', // dùng font có tiếng Việt
            ]);

        $filename = 'refund_' . $refund->id . '.pdf';
        $directory = public_path('assets/refund_receipts/');

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $path = $directory . $filename;

        // Lưu file PDF
        $pdf->save($path);

        // 2. Cập nhật vào DB
        $refund->update([
            'receipt' => 'assets/refund_receipts/' . $filename,
            'status'  => 'refunded',
        ]);

        // 3. Gửi email kèm PDF
        try {
            Mail::send('admin.emails.refund_receipt', compact('refund'), function ($message) use ($refund, $path) {
                $message->to($refund->order->user->email)
                    ->subject('Biên lai hoàn tiền - Đơn hàng #' . $refund->order->order_code)
                    ->attach($path);
            });
        } catch (\Throwable $th) {
            return back()->with('error', 'Hoàn tiền thành công nhưng KHÔNG gửi được email: ' . $th->getMessage());
        }

        return back()->with('success', 'Hoàn tiền thành công, biên lai đã tạo và gửi cho khách hàng!');
    }
}
