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

    $review = new Review();
    $review->user_id = Auth::id();
    $review->product_id = $request->product_id;
    $review->rating = $request->rating;
    $review->comment = $request->comment;

    // 🟩 Thêm dòng này để tránh lỗi role_id không có giá trị:
    $review->role_id = Auth::user()->role_id ?? 2; // hoặc giá trị mặc định 2 (user)

    $review->save();

    return response()->json([
        'status' => true,
        'message' => 'Đánh giá đã được gửi!'
    ], 200);
}

}
