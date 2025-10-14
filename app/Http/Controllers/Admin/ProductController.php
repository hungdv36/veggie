<?php

namespace App\Http\Controllers\Admin;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    // 1. Hiển thị form
    public function showFormAddProduct()
    {
        $categories = Category::all(); // Lấy danh mục để chọn
        return view('admin.pages.product-add', compact('categories'));
    }

    // 2. Xử lý POST (thêm sản phẩm)
    public function addProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
            // 'variants.*.size' => 'nullable|string|max:255',
            // 'variants.*.color' => 'nullable|string|max:255',
            // 'variants.*.price' => 'nullable|numeric|min:0',
            // 'variants.*.stock' => 'nullable|integer|min:0',
        ]);

        $slug = Str::slug($request->name) . '-' . time();

        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock ?? 0,
            'unit' => $request->unit ?? 'cái',
            'status' => 'in_stock',
        ]);

        // Upload ảnh
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = 'uploads/products/' . $imageName;
                $resizedImage = Image::make($image)->resize(600, 600)->encode();
                Storage::disk('public')->put($path, $resizedImage);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                ]);
            }
        }

        // Thêm biến thể
        // if ($request->filled('variants')) {
        //     foreach ($request->variants as $variant) {
        //         if (!empty($variant['size']) || !empty($variant['color'])) {
        //             Variant::create([
        //                 'product_id' => $product->id,
        //                 'size' => $variant['size'] ?? null,
        //                 'color' => $variant['color'] ?? null,
        //                 'stock' => $variant['stock'] ?? 0,
        //                 'price' => $variant['price'] ?? $product->price,
        //             ]);
        //         }
        //     }
        // }

        return redirect()
            ->route('admin.product.add')
            ->with('success', 'Thêm sản phẩm và biến thể thành công!');
    }


    public function index()
    {
        // Lấy danh sách sản phẩm kèm category và ảnh
        $products = Product::with(['category', 'images'])->get();
        $categories = Category::all();

        return view('admin.pages.products', compact('products', 'categories'));
    }

    public function updateProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $product = Product::findOrFail($request->product_id);

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . $product->id,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock ?? 0,
            'unit' => $request->unit ?? 'cái',
            'status' => 'in_stock',
        ]);

        if ($request->hasFile('images')) {
            foreach ($product->images as $oldImage) {
                Storage::disk('public')->delete($oldImage->image_path);
                $oldImage->delete();
            }

            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = 'uploads/products/' . $imageName;

                $resizedImage = Image::make($image)->resize(600, 600)->encode();
                Storage::disk('public')->put($path, $resizedImage);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật sản phẩm thành công!',
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'stock' => $product->stock,
                'unit' => $product->unit,
                'status' => $product->status,
                'description' => $product->description,
                'category' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                ],
                'image' => $product->images->first()
                    ? asset('storage/' . $product->images->first()->image_path)
                    : asset('images/no-image.png'),
            ]
        ]);
    }
}
