<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\FlashSale;
use App\Models\Banner;


use function Laravel\Prompts\select;

class HomeController extends Controller
{
    public function index()
    {
        DB::listen(function ($query) {
            Log::info('🔥 SQL: ' . $query->sql);
        });
        $categories = Category::with('products.firstImage')->get();

        foreach ($categories as $category) {
            foreach ($category->products as $product) {
                $product->image_url = $product->firstImage?->image
                    ? asset('storage/uploads/' . $product->firstImage->image)
                    : asset('clients/images/no-image.png');
            }
        }
        $bestSellingProducts = Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy(
                'products.id',
                'products.name',
                'products.slug',
                'products.category_id',
                'products.description',
                'products.price',
                'products.stock',
                'products.status',
                'products.unit',
                'products.created_at',
                'products.updated_at'
            )
            ->orderByDesc('total_sold')
            ->limit(8)
            ->get();
// ✅ Lấy Flash Sale đang hoạt động
    $flashSale = FlashSale::with(['items.product'])
        ->where('start_time', '<=', now())
        ->where('end_time', '>=', now())
        ->first();

    // Banner
    $banners = Banner::where('status', 1)->get();

    // Return 1 lần thôi!
    return view('clients.pages.home', compact(
        'categories',
        'bestSellingProducts',
        'flashSale',
        'banners'
    ));
}
}