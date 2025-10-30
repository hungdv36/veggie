<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\ReviewDeletion;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pages.reviews.index', compact('reviews'));
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:reviews,id',
            'reason' => 'required|string|max:255',
        ]);

        $review = Review::findOrFail($request->id);
        $adminId = Auth::guard('admin')->id();

        DB::transaction(function () use ($review, $adminId, $request) {
            // 🟩 Lưu log TRƯỚC khi xóa review
            \App\Models\ReviewDeletion::create([
                'admin_id' => $adminId,
                'review_id' => $review->id,
                'reason' => $request->reason,
            ]);

            // 🟥 Sau đó mới xóa review
            $review->delete();
        });

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Đã xóa bình luận và lưu lý do thành công!');
    }

    public function deletionLogs()
    {
        $logs = ReviewDeletion::with(['review.product', 'admin'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pages.reviews.logs', compact('logs'));
    }
    public function restore($id)
    {
        $log = \App\Models\ReviewDeletion::with('review')->findOrFail($id);
        $review = $log->review;

        if (!$review) {
            return redirect()->back()->with('error', 'Không tìm thấy bình luận để khôi phục.');
        }

        if ($review->trashed()) {
            $review->restore(); // ✅ Khôi phục lại
            return redirect()->back()->with('success', 'Đã khôi phục bình luận thành công!');
        }

        return redirect()->back()->with('info', 'Bình luận này chưa bị xóa hoặc đã được khôi phục.');
    }
}
