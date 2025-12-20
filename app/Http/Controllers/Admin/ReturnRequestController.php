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
        $return = ReturnRequest::with('orderItem.variant')->findOrFail($id);
        $oldStatus = $return->status;

        $statusFlow = [
            'requested'      => ['approved', 'rejected'],
            'approved'       => ['returning'],
            'returning'      => ['returned_goods'],
            'returned_goods' => ['done'],
            'done'           => [],
            'rejected'       => [],
        ];

        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys($statusFlow)),
            'staff_note' => 'nullable|string',
            'reject_reason' => 'nullable|string',
        ]);

        $newStatus = $request->status;

        if (!in_array($newStatus, $statusFlow[$oldStatus])) {
            return back()->with('error', "Không thể chuyển trạng thái từ [$oldStatus] sang [$newStatus]");
        }

        if ($newStatus === 'rejected' && empty($request->reject_reason)) {
            return back()->with('error', 'Vui lòng chọn lý do từ chối hoàn hàng');
        }

        $return->update([
            'status' => $newStatus,
            'staff_note' => $request->staff_note,
            'reject_reason' => $newStatus === 'rejected' ? $request->reject_reason : null,
        ]);

        if ($newStatus === 'done') {
            $variant = $return->orderItem->variant;
            if ($variant) {
                $variant->quantity += $return->orderItem->quantity;
                $variant->save();
            }
        }

        DB::table('order_status_history')->insert([
            'order_id' => $return->order_id,
            'role_id' => 1, // admin
            'old_status' => $oldStatus,
            'status' => $newStatus,
            'changed_at' => now(),
            'notes' => $newStatus === 'rejected'
                ? 'Từ chối hoàn hàng: ' . $request->reject_reason
                : ($request->staff_note ?? 'Admin cập nhật trạng thái hoàn hàng'),
        ]);

        return back()->with('success', 'Cập nhật trạng thái hoàn hàng thành công.');
    }
}
