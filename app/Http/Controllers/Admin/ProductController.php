<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'images')->orderBy('id', 'desc')->paginate(10);

        return view('admin.pages.products', compact('products'));
    }
}
