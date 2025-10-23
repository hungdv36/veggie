<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
   public function index(Request $request)
    {
        $query = $request->input('query');

        $products = [];

        if ($query) {
            $products = Product::where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->get();
        }

        return view('clients.pages.products-search', compact('products', 'query'));
    }

}
