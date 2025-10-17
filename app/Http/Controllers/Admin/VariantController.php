<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Variant;
use App\Models\Product;

class VariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $variants = Variant::with('product')->paginate(10);
        $products = Product::orderBy('name')->get(); //
        return view('admin.pages.variants', compact('variants', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function showVariantForm()
    {
        $products = Product::all();
        return view('admin.pages.variants-add', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addVariant(Request $request)
    {

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variants.*.size' => 'required|string|max:50',
            'variants.*.color' => 'required|string|max:50',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.price' => 'required|numeric|min:0',
        ]);

        $product_id = $request->input('product_id');

        foreach ($request->variants as $variant) {
            Variant::create([
                'product_id' => $product_id,
                'size' => $variant['size'],
                'color' => $variant['color'],
                'stock' => $variant['stock'],
                'price' => $variant['price'],
            ]);
        }

        session()->flash('success', 'Các biến thể đã được thêm thành công!');
        return redirect()->route('admin.variants.index');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateVariant(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:variants,id',
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            $variant = Variant::findOrFail($request->variant_id);

            $variant->update([
                'product_id' => $request->product_id,
                'size' => $request->size,
                'color' => $request->color,
                'stock' => $request->stock,
                'price' => $request->price,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Cập nhật biến thể thành công!',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Cập nhật biến thể thất bại!',
            ], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function deleteVariant(Request $request)
    {
        $variant = Variant::findOrFail($request->variant_id);

        try {
            $variant->delete(); // soft delete
            return response()->json([
                'status' => true,
                'message' => 'Đã xóa biến thể (thùng rác).'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Xóa thất bại!'
            ], 500);
        }
    }
    public function restoreVariant(Request $request)
    {
        $variant = Variant::withTrashed()->findOrFail($request->variant_id);

        try {
            $variant->restore();
            return response()->json([
                'status' => true,
                'message' => 'Đã khôi phục biến thể.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Khôi phục thất bại!'
            ], 500);
        }
    }
    public function forceDeleteVariant(Request $request)
    {
        $variant = Variant::withTrashed()->findOrFail($request->variant_id);

        try {
            $variant->forceDelete();
            return response()->json([
                'status' => true,
                'message' => 'Đã xóa vĩnh viễn biến thể.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Xóa vĩnh viễn thất bại!'
            ], 500);
        }
    }
    public function trash()
    {
        $variants = Variant::onlyTrashed()->with('product')->paginate(10);
        return view('admin.pages.variants-trash', compact('variants'));
    }
}
