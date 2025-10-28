<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
public function index()
{
    $categories = Category::with('products')->get();

    // Tạm thời bỏ điều kiện where để chắc chắn có dữ liệu
    $products = Product::with(['firstImage', 'variants'])->paginate(9);

    return view('clients.pages.products', compact('categories', 'products'));
}


        // 🔥 Lấy sản phẩm đánh giá cao nhất
    $topRatedProducts = Product::select('products.*', DB::raw('AVG(reviews.rating) as avg_rating'))
        ->join('reviews', 'products.id', '=', 'reviews.product_id')
        ->groupBy('products.id')
        ->orderByDesc('avg_rating')
        ->take(5)<<<
        ->with('firstImage')
        ->get();

        return view('clients.pages.products', compact('categories', 'products', 'topRatedProducts'));
    }



    public function filter(Request $request)
    {
        $query = Product::query();

        // Filter Category if exist
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Filter Price if exist
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        // Filter SortBy if exist
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
            }
        }

        // Trả về kết quả (tuỳ mục đích có thể là JSON hoặc view)
        $products = $query->paginate(9);

        // foreach ($products as $product) {
        //     foreach ($products as $product) {
        //         $product->image_url = $product->firstImage?->image
        //             ? asset('storage/uploads/' . $product->firstImage->image)
        //             : asset('storage/uploads/products/no-image.png');
        //     }
        // }
        return response()->json(
            [
                'products' => view('clients.components.products_grid', compact('products'))->render(),
                'pagination' => $products->links('clients.components.pagination.pagination_custom')
            ]
        );
    }

    public function detail($slug)
    {
        // load product with relations
        $product = Product::with(['category', 'images', 'variants' , 'reviews.user'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(6)
            ->get();
       
        //Tính điểm đánh giá trung bình, đảm bảo không có giá trị null    
        $averageRating = round($product->reviews()->avg('rating') ?? 0, 1);  
       
        
        $hasPurchased = false;
        $hasReviewed = false;

if (Auth::check()) {
    $user = Auth::user();

    $hasPurchased = OrderItem::whereHas('order', function ($query) use ($user) {
        $query->where('user_id', $user->id)
              ->where('status', 'completed');
    })
    ->where('product_id', $product->id)
    ->exists();

    $hasReviewed = Review::where('user_id', $user->id)
        ->where('product_id', $product->id)
        ->exists();
}
  

        // prepare JS-safe variants array (no closure in blade)
        $jsVariants = $product->variants->map(function ($v) {
            return [
                'id'         => $v->id,
                'color_id'   => $v->color_id,
                'size_id'    => $v->size_id,
                'price'      => (float) ($v->price ?? 0),
                'sale_price' => (float) ($v->sale_price ?? 0),
                'quantity'   => (int) ($v->quantity ?? 0),
                'image'      => $v->image ?? null,
            ];
        })->toArray();

        return view('clients.pages.product-detail', compact('product', 'relatedProducts', 'jsVariants', 'hasPurchased', 'hasReviewed', 'averageRating' ));
    }
}
