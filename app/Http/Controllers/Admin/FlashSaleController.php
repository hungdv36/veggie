<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\Product;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::latest()->paginate(10);
        return view('admin.pages.flash_sale.index', compact('flashSales'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.pages.flash_sale.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $flashSale = FlashSale::create($request->only(['name', 'start_time', 'end_time', 'status']));

        if ($request->has('product_id')) {
            foreach ($request->product_id as $key => $productId) {
                FlashSaleItem::create([
                    'flash_sale_id' => $flashSale->id,
                    'product_id' => $productId,
                    'discount_price' => $request->discount_price[$key],
                    'quantity' => $request->quantity[$key],
                ]);
            }
        }

        return redirect()->route('admin.flash_sales.index')->with('success', 'Tạo flash sale thành công!');
    }

    public function edit($id)
    {
        $flashSale = FlashSale::with('items.product')->findOrFail($id);
        $products = Product::all();
        return view('admin.pages.flash_sale.edit', compact('flashSale', 'products'));
    }

    public function update(Request $request, $id)
    {
        $flashSale = FlashSale::findOrFail($id);
        $flashSale->update($request->only(['name', 'start_time', 'end_time', 'status']));

        FlashSaleItem::where('flash_sale_id', $id)->delete();

        if ($request->has('product_id')) {
            foreach ($request->product_id as $key => $productId) {
                FlashSaleItem::create([
                    'flash_sale_id' => $id,
                    'product_id' => $productId,
                    'discount_price' => $request->discount_price[$key],
                    'quantity' => $request->quantity[$key],
                ]);
            }
        }

        return redirect()->route('admin.flash_sales.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        FlashSale::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa flash sale!');
    }
}

