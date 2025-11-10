<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishListController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('product')->where('user_id', Auth::id())->get();
        return view('clients.pages.wishlist');
    }
    public function addToWishList(Request $request)
    {
        $user = Auth::user();
        $product_id = $request->product_id;

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product_id,
        ]);
        return response()->json([
            'status' => true
        ]);
    }
}
