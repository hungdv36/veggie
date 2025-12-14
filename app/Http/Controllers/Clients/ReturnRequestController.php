<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnRequestController extends Controller
{
    public function create(OrderItem $orderItem)
    {
        return view('clients.pages.return_request', ['item' => $orderItem]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'reason' => 'required|string',
            'images.*' => 'nullable|image',
            'videos.*' => 'nullable|mimetypes:video/mp4,video/quicktime'
        ]);

        $orderItem = OrderItem::findOrFail($request->order_item_id);
        $order = $orderItem->order;

        // Xử lý ảnh
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $name = time() . '_' . $img->getClientOriginalName();
                $img->move(public_path('assets/returns/images'), $name);
                $images[] = 'assets/returns/images/' . $name;
            }
        }

        // Xử lý video
        $videos = [];
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $vid) {
                $name = time() . '_' . $vid->getClientOriginalName();
                $vid->move(public_path('assets/returns/videos'), $name);
                $videos[] = 'assets/returns/videos/' . $name;
            }
        }

        // Tạo yêu cầu hoàn hàng
        ReturnRequest::create([
            'order_id' => $order->id,
            'order_item_id' => $orderItem->id,
            'user_id' => $order->user_id,
            'reason' => $request->reason,
            'images' => $images,
            'videos' => $videos,
            'status' => 'requested',
        ]);

        // Lưu lịch sử trạng thái
        DB::table('order_status_history')->insert([
            'order_id' => $order->id,
            'role_id' => 3, // khách hàng
            'old_status' => $order->status, // trạng thái cũ của đơn hàng, ví dụ 'completed'
            'status' => 'requested',       // trạng thái mới là 'requested'
            'changed_at' => now(),
            'notes' => 'Khách gửi yêu cầu hoàn hàng cho sản phẩm #' . $orderItem->id,
        ]);

        return redirect()
            ->route('order.show', $order->id)
            ->with('success', 'Yêu cầu hoàn hàng đã được gửi, vui lòng chờ shop xử lý.');
    }
}
