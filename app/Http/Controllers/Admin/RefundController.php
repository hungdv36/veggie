<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Refund;
use Illuminate\Support\Facades\Mail;
use App\Models\RefundHistory;
use Illuminate\Support\Facades\Auth;

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
        $refund = Refund::with('order.user', 'order.items.product', 'histories.admin')->findOrFail($id);
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

        // Tạo lịch sử hoàn tiền
        RefundHistory::create([
            'refund_id' => $refund->id,
            'admin_id'  => Auth::guard('admin')->id(),
            'status'    => $request->status,
            'receipt'   => null,
            'note'      => 'Cập nhật trạng thái hoàn tiền',
        ]);

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công và lưu lịch sử!');
    }

    public function completeRefund(Request $request, $id)
    {
        $refund = Refund::with('order.user')->findOrFail($id);

        $request->validate([
            'receipt_image' => 'required|image|max:2048',
        ]);

        $file = $request->file('receipt_image');
        $filename = 'refund_' . $refund->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $directory = public_path('assets/refund_receipts/');
        if (!file_exists($directory)) mkdir($directory, 0777, true);
        $file->move($directory, $filename);
        $filePath = 'assets/refund_receipts/' . $filename;

        $refund->update([
            'receipt' => $filePath,
            'status'  => 'refunded',
        ]);

        RefundHistory::create([
            'refund_id' => $refund->id,
            'admin_id'  => Auth::guard('admin')->id(),
            'status'    => $refund->status,
            'receipt'   => $filePath,
            'note'      => 'Hoàn tiền thủ công upload bill',
        ]);

        try {
            Mail::send('admin.emails.refund_receipt', compact('refund'), function ($message) use ($refund, $filePath) {
                $message->to($refund->order->user->email)
                    ->subject('Biên lai hoàn tiền - Đơn hàng #' . $refund->order->order_code)
                    ->attach(public_path($filePath));
            });
        } catch (\Throwable $th) {
            return back()->with('error', 'Hoàn tiền thành công nhưng KHÔNG gửi được email: ' . $th->getMessage());
        }

        return back()->with('success', 'Hoàn tiền thành công, biên lai đã upload và gửi email cho khách hàng!');
    }
}
