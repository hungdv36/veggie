<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnRequestController extends Controller
{
    public function index()
    {
        $returns = ReturnRequest::with([
            'order',
            'orderItem.product',
            'orderItem.variant.color',
            'orderItem.variant.size',
            'user'
        ])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.pages.return_request.index', compact('returns'));
    }

    public function show($id)
    {
        $return = ReturnRequest::with(['order', 'orderItem'])
            ->findOrFail($id);

        return view('admin.pages.return_request.show', compact('return'));
    }

    public function updateStatus(Request $request, $id)
    {
        $return = ReturnRequest::findOrFail($id);
        $oldStatus = $return->status;

        // Xác định luồng trạng thái hợp lệ
        $statusFlow = [
            'requested' => ['reviewing', 'rejected'],
            'reviewing' => ['approved', 'rejected'],
            'approved' => ['received_from_customer', 'packaging'], // theo luồng hoàn hoặc đổi
            'received_from_customer' => ['inspected'],
            'inspected' => ['packaging', 'done'], // hoàn hoặc đổi
            'packaging' => ['shipped_to_customer'],
            'shipped_to_customer' => ['completed_run'],
            'completed_run' => ['done'],
            'rejected' => ['done'],
            'done' => [],
        ];

        $newStatus = $request->status;

        // Kiểm tra trạng thái mới có hợp lệ
        if (!in_array($newStatus, $statusFlow[$oldStatus])) {
            return back()->with('error', 'Không thể chuyển trạng thái từ "' . $oldStatus . '" sang "' . $newStatus . '"');
        }

        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys($statusFlow)),
            'staff_note' => 'nullable|string'
        ]);

        // Cập nhật trạng thái và ghi chú
        $return->update([
            'status' => $newStatus,
            'staff_note' => $request->staff_note
        ]);

        // Nếu trạng thái là "shipped_to_customer" tức là hàng đổi/hoàn đã được gửi → tăng tồn kho biến thể trả về
        if ($newStatus === 'shipped_to_customer') {
            $variant = $return->orderItem->variant;
            if ($variant) {
                $variant->quantity += $return->orderItem->quantity;
                $variant->save();
            }
        }

        // Lưu lịch sử trạng thái
        DB::table('order_status_history')->insert([
            'order_id' => $return->order_id,
            'role_id' => auth()->guard('admin')->id() ? 1 : 0,
            'old_status' => $oldStatus,
            'status' => $newStatus,
            'changed_at' => now(),
            'notes' => $request->staff_note ?? 'Admin cập nhật trạng thái hoàn hàng cho sản phẩm #' . $return->order_item_id,
        ]);

        return back()->with('success', 'Cập nhật trạng thái thành công.');
    }
}
