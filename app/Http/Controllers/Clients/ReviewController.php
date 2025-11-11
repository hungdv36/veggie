<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index(Product $product)
    {
        return view('clients.components.includes.review-list', compact('product'))->render();
    }
 public function createReview(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string',
    ]);

    $userId = Auth::id();

    // ✅ Kiểm tra xem user này đã đánh giá sản phẩm này chưa
    $existingReview = Review::where('user_id', $userId)
        ->where('product_id', $request->product_id)
        ->first();

    if ($existingReview) {
        return response()->json([
            'status' => false,
            'message' => 'Bạn đã đánh giá sản phẩm này rồi!',
        ], 400);
    }

    // ✅ Nếu chưa đánh giá -> tạo mới
    $review = new Review();
    $review->user_id = $userId;
    $review->product_id = $request->product_id;
    $review->rating = $request->rating;
    $review->comment = $request->comment;
    $review->role_id = Auth::user()->role_id ?? 2; // giữ lại role nếu có
    $review->save();

    return response()->json([
        'status' => true,
        'message' => 'Đánh giá đã được gửi!',
    ], 200);
}


}
