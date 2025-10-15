<?php

namespace App\Http\Controllers\Admin;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\ProductVariant;


class ProductController extends Controller
{
    // 1. Hiển thị form
    public function index()
    {
        // Lấy danh sách sản phẩm kèm category và ảnh
        $products = Product::with(['category', 'images'])->orderBy('created_at', 'DESC')->paginate(10);
        $categories = Category::all();
        return view('admin.pages.product.products', compact('products', 'categories'));
    }
    public function showFormAddProduct()
    {
        $categories = Category::all();
        $sizes = Size::all();
        $colors = Color::all();
        $products = Product::with(['category', 'variants.size', 'variants.color'])->paginate(10);
        return view('admin.pages.product.products-add', compact('categories', 'sizes', 'colors'));
    }

    // 2. Xử lý POST (thêm sản phẩm)
    public function addProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'status' => 'required|in:in_stock,out_of_stock',
            'unit' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'variations' => 'required|array',
            'variations.*.size_id' => 'required|exists:sizes,id',
            'variations.*.color_id' => 'required|exists:colors,id',
            'variations.*.quantity' => 'required|integer|min:0',
            'variations.*.price' => 'required|numeric|min:0',
            'variations.*.sale_price' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $slug = Str::slug($request->name) . '-' . time();

            // Tạo sản phẩm
            $product = Product::create([
                'name' => $request->name,
                'slug' => $slug,
                'brand' => $request->brand,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'price' => 0,   // cập nhật sau từ biến thể
                'stock' => 0,
                'status' => $request->status,
                'unit' => $request->unit ?? 'cái',
            ]);

            // Upload ảnh đại diện
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('assets/img/product'), $filename);
                $product->image = 'assets/img/product/' . $filename;
                $product->save();
            }

            // Upload album ảnh
            if ($request->hasFile('images')) {
                $imagesData = [];
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('assets/img/product'), $imageName);
                    $imagesData[] = [
                        'product_id' => $product->id,
                        'image_path' => 'assets/img/product/' . $imageName,
                        'is_primary' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                if (!empty($imagesData)) {
                    ProductImage::insert($imagesData);
                }
            }

            // Thêm biến thể
            $variantsData = [];
            $totalStock = 0;
            $minPrice = null;

            foreach ($request->variations as $var) {
                $variantsData[] = [
                    'product_id' => $product->id,
                    'size_id' => $var['size_id'],
                    'color_id' => $var['color_id'],
                    'quantity' => $var['quantity'],
                    'price' => $var['price'],
                    'sale_price' => $var['sale_price'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $totalStock += $var['quantity'];

                if ($minPrice === null || $var['price'] < $minPrice) {
                    $minPrice = $var['price'];
                }
            }

            ProductVariant::insert($variantsData);

            // Cập nhật stock, price và status của sản phẩm
            $product->update([
                'stock' => $totalStock,
                'price' => $minPrice ?? 0,
                'status' => $totalStock > 0 ? 'in_stock' : 'out_of_stock',
            ]);
            dd($request->variations);
            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Tạo sản phẩm thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Lỗi: ' . $e->getMessage())->withInput();
        }
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
