<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();
        $products = Product::with(['firstImage', 'variants'])
            ->where('status', 'in-stock')
            ->paginate(9);

        foreach ($products as $product) {
            $product->image_url = $product->firstImage?->image
                ? asset('storage/uploads/' . $product->firstImage->image)
                : asset('storage/uploads/products/no-image.png');
        }

        return view('clients.pages.products', compact('categories', 'products'));
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

        foreach ($products as $product) {
            foreach ($products as $product) {
                $product->image_url = $product->firstImage?->image
                    ? asset('storage/uploads/' . $product->firstImage->image)
                    : asset('storage/uploads/products/no-image.png');
            }
        }
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
        $product = Product::with(['category', 'images', 'variants'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(6)
            ->get();

        // prepare JS-safe variants array (no closure in blade)
        $jsVariants = $product->variants->map(function ($v) {
            return [
                'id'    => $v->id,
                'color' => $v->color ?? null,
                'size'  => $v->size ?? null,
                'price' => (float) ($v->price ?? 0),
                'stock' => (int) ($v->stock ?? 0),
                'image' => $v->image ?? null,
            ];
        })->toArray();

        return view('clients.pages.product-detail', compact('product', 'relatedProducts', 'jsVariants'));
    }
}
