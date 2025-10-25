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
            ->join('products', 'wishlists.product_id', '=', 'products.id')
            ->select(
                'products.id as product_id',
                'products.name',
                'products.image',
                'products.price',
                'products.brand',
                'products.stock',
                'products.status'
            )
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

}
