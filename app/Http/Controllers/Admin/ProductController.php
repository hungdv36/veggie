<?php

namespace App\Http\Controllers\Admin;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
        // Lấy danh sách sản phẩm kèm category
        $products = Product::with('category')->orderBy('created_at', 'DESC')->paginate(10);
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
        // Bước 1: Validate dữ liệu
        $rules = [
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'status' => 'required|in:in_stock,out_of_stock',
            'unit' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'variations' => 'required|array|min:1',
            'variations.*.size_id' => 'required|exists:sizes,id',
            'variations.*.color_id' => 'required|exists:colors,id',
            'variations.*.quantity' => 'required|integer|min:0',
            'variations.*.price' => 'required|numeric|min:0',
            'variations.*.sale_price' => 'nullable|numeric|min:0',
        ];

        $messages = [
            'name.required' => 'Tên sản phẩm không được để trống',
            'name.min' => 'Tên sản phẩm tối thiểu phải 5 ký tự',
            'brand.required' => 'Tên thương hiệu không được để trống',
            'category_id.exists' => 'Danh mục không hợp lệ',
            'variations.required' => 'Bạn phải thêm ít nhất 1 biến thể',
            'variations.*.size_id.required' => 'Size không được để trống',
            'variations.*.color_id.required' => 'Màu sắc không được để trống',
            'variations.*.price.required' => 'Giá gốc không được để trống',
            'variations.*.quantity.required' => 'Số lượng không được để trống',
            'variations.*.sale_price.lte' => 'Giá khuyến mãi không được cao hơn giá gốc',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Bước 2: Transaction
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
                'price' => 0,
                'stock' => 0,
                'status' => $request->status,
                'unit' => $request->unit ?? 'cái',
            ]);

            // Upload ảnh đại diện
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('assets/admin/img/product'), $filename);
                $product->image = $filename;
                $product->save();
            }

            // Upload album ảnh
            if ($request->hasFile('images')) {
                $imagesData = [];
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('assets/admin/img/product'), $imageName);
                    $imagesData[] = [
                        'product_id' => $product->id,
                        'image_path' => 'assets/admin/img/product/' . $imageName,
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

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Tạo sản phẩm thành công ✅');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function showProduct(Request $request, $id)
    {
        $product = Product::with(['category', 'images', 'variants.size', 'variants.color'])
            ->findOrFail($id);

        return view('admin.pages.product.products-show', compact('product'));
    }
    public function updateProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048'
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
            'status' => 'in_stock'
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path('assets/admin/img/product/' . $product->image))) {
                unlink(public_path('assets/admin/img/product/' . $product->image));
            }

            $imageName = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path('assets/admin/img/product'), $imageName);
            $product->update(['image' => $imageName]);
        }

        // Album ảnh
        if ($request->hasFile('images')) {
            foreach ($product->images as $oldImage) {
                if (file_exists(public_path($oldImage->image_path))) unlink(public_path($oldImage->image_path));
                $oldImage->delete();
            }

            foreach ($request->file('images') as $img) {
                $path = 'assets/admin/img/product/' . time() . '_' . $img->getClientOriginalName();
                $img->move(public_path('assets/admin/img/product'), basename($path));
                $product->images()->create(['image_path' => $path]);
            }
        }


        return response()->json(['status' => true, 'message' => 'Cập nhật sản phẩm thành công', 'data' => $product->load('category', 'images')]);
    }
    public function deleteProduct(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product) return response()->json(['status' => false, 'message' => 'Sản phẩm không tồn tại.']);

        // Check sản phẩm có trong đơn hàng không
        $orderCount = DB::table('order_items')->where('product_id', $product->id)->count();
        if ($orderCount > 0) {
            return response()->json(['status' => false, 'message' => 'Sản phẩm đang thuộc đơn hàng, không thể xóa.']);
        }

        $product->delete(); // soft delete
        return response()->json(['status' => true, 'message' => 'Đã xóa sản phẩm.']);
    }
    public function trash()
    {
        $products = Product::onlyTrashed()->orderBy('deleted_at', 'DESC')->paginate(10);
        return view('admin.pages.product.products-trash', compact('products'));
    }
    public function restoreProduct(Request $request)
    {
        $product = Product::withTrashed()->find($request->product_id);
        if (!$product) {
            return response()->json(['status' => false, 'message' => 'Sản phẩm không tồn tại.']);
        }

        $product->restore();
        return response()->json(['status' => true, 'message' => 'Khôi phục sản phẩm thành công.']);
    }
    public function forceDeleteProduct(Request $request)
    {
        $product = Product::withTrashed()->find($request->product_id);
        if (!$product) {
            return response()->json(['status' => false, 'message' => 'Sản phẩm không tồn tại.']);
        }

        // Xóa ảnh đại diện và album trước khi xóa vĩnh viễn
        if ($product->image) {
            Storage::disk('public')->delete('uploads/products/' . $product->image);
        }
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image_path);
            $img->delete();
        }

        $product->forceDelete();
        return response()->json(['status' => true, 'message' => 'Đã xóa vĩnh viễn sản phẩm.']);
    }
}
