<?php

namespace App\Http\Controllers\Clients;

use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WishListController extends Controller
{
    public function index()
    {
        $role_id = Auth::user()->role_id;

        // Lấy các sản phẩm trong wishlist và thông tin sản phẩm tương ứng
        $wishlistItems = Wishlist::where('role_id', $role_id)
            ->with('product.firstImage')
            ->get();
            

        return view('clients.pages.wishlist', compact('wishlistItems'));
    }
    public function addToWishList(Request $request)
{
    $role_id = Auth::user()->role_id; // lấy role_id của user đăng nhập
    $product_id = $request->product_id;

    Wishlist::create([
        'role_id' => $role_id,
        'product_id' => $product_id,
    ]);

    return response()->json(['status' => true]);
}

public function remove(Request $request)
{
    $productId = $request->input('product_id');
    $roleId = Auth::user()->role_id; // 👈 đúng nếu wishlist lưu role_id

    $wish = WishList::where('role_id', $roleId)
                    ->where('product_id', $productId)
                    ->first();

    if (!$wish) {
        return response()->json(['error' => 'Sản phẩm không tồn tại trong danh sách yêu thích.'], 404);
    }

    $wish->delete();

    return response()->json(['success' => 'Đã xóa sản phẩm khỏi danh sách yêu thích.']);
}



}
