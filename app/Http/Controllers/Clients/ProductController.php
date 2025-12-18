<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\Color;
use App\Models\Size;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();

        // ✅ Lấy toàn bộ sản phẩm (có ảnh và biến thể)
        $products = Product::with(['firstImage', 'variants'])->paginate(9);

        // ✅ Lấy sản phẩm được đánh giá cao
        $topRatedProducts = Product::with(['firstImage', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->where('status', 'in_stock')
            ->take(3)
            ->get();

        foreach ($topRatedProducts as $item) {
            $item->image_url = $item->firstImage
                ? asset('assets/admin/img/product/' . $item->firstImage->image)
                : asset('assets/admin/img/product/default.png');
        }

        // ✅ Lấy Flash Sale đang hoạt động
        $flashSale = \App\Models\FlashSale::with('items')
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();

        // ✅ Nếu có Flash Sale, xử lý giá giảm
        if ($flashSale) {
            foreach ($products as $product) {
                $flashItem = $flashSale->items->firstWhere('product_id', $product->id);
                if ($flashItem) {
                    $product->is_flash_sale = true;
                    $product->discount_price = $flashItem->discount_price;
                    $product->sale_price = round($product->price * (1 - $flashItem->discount_price / 100), 0);
                    $product->flash_end_time = $flashSale->end_time;
                } else {
                    $product->is_flash_sale = false;
                    $product->sale_price = $product->price;
                }
            }
        }
        // ✅ Trả dữ liệu về view
        return view('clients.pages.products', compact('categories', 'products', 'topRatedProducts', 'flashSale'));
    }

    public function filter(Request $request)
    {
        $query = Product::with('firstImage');

        // ✅ Lọc danh mục nếu có
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // ✅ Lọc theo khoảng giá (nếu người dùng chỉnh thanh trượt)
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $min = (float) $request->min_price;
            $max = (float) $request->max_price;

            // Nếu min/max trùng giá trị ban đầu (0–300000) thì bỏ lọc
            if (!($min == 0 && $max == 300000)) {
                $query->whereBetween('price', [$min, $max]);
            }
        }

        // ✅ Lọc sắp xếp
        if ($request->filled('sort_by')) {
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

        $products = $query->paginate(9);

        return response()->json([
            'products' => view('clients.components.products_grid', compact('products'))->render(),
            'pagination' => $products->links('clients.components.pagination.pagination_custom')
        ]);
    }


    public function detail($slug)
    {
        $product = Product::with(['category', 'images', 'variants', 'reviews.user'])
            ->where('slug', $slug)
            ->firstOrFail();

        // ---------------------------------------------------------
        // 🔥 Thêm trạng thái hết hàng cho màu & size
        // ---------------------------------------------------------
        $colors = Color::all()->map(function ($color) use ($product) {
            $color->is_out_of_stock = $product->variants
                ->where('color_id', $color->id)
                ->sum('quantity') <= 0; // nếu tổng quantity của màu đó = 0 => hết hàng
            return $color;
        });

        $sizes = Size::all()->map(function ($size) use ($product) {
            $size->is_out_of_stock = $product->variants
                ->where('size_id', $size->id)
                ->sum('quantity') <= 0; // tổng quantity của size = 0 => hết hàng
            return $size;
        });

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(6)
            ->get();

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

        // ---------------------------------------------------------
        // 🔥 Lấy giá min từ biến thể (giá gốc duy nhất để hiển thị)
        // ---------------------------------------------------------
        $minPrice = $product->variants->min('price') ?? $product->price;
        $maxPrice = $product->variants->max('price') ?? $product->price;

        // ---------------------------------------------------------
        // 🔥 Kiểm tra Flash Sale và tính giá giảm theo biến thể
        // ---------------------------------------------------------
        $flashSale = \App\Models\FlashSale::with(['items' => function ($q) use ($product) {
            $q->where('product_id', $product->id);
        }])
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();

        $flashItem = null;

        if ($flashSale && $flashSale->items->count() > 0) {
            $flashItem = $flashSale->items->first();

            $product->is_flash_sale = true;
            $product->discount_price = $flashItem->discount_price;      // % giảm

            // 🔥 ÁP GIẢM GIÁ TRÊN GIÁ BIẾN THỂ MIN, KHÔNG PHẢI GIÁ SẢN PHẨM
            $product->flash_sale_price = round($minPrice * (1 - $flashItem->discount_price / 100), 0);

            $product->flash_end_time = $flashSale->end_time;
        } else {
            $product->is_flash_sale = false;
            $product->flash_sale_price = null;
        }

        // ---------------------------------------------------------
        // 🔥 Chuẩn bị dữ liệu JS cho biến thể
        // ---------------------------------------------------------
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

        return view('clients.pages.product-detail', compact(
            'product',
            'relatedProducts',
            'jsVariants',
            'hasPurchased',
            'hasReviewed',
            'averageRating',
            'flashItem',
            'minPrice',
            'maxPrice'
        ));
    }
}
