<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Size;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductVariant;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::orderBy('id', 'desc')->paginate(10);
        return view('admin.pages.size.sizes', compact('sizes'));
    }
    public function showSizeForm()
    {
        return view('admin.pages.size.sizes-add');
    }
    public function addSize(Request $request)
    {
        $sizes = array_map(function ($s) {
            return is_string($s) ? trim($s) : '';
        }, $request->input('sizes', []));

        // Validate
        $validator = Validator::make(['sizes' => $sizes], [
            'sizes' => 'required|array|min:1',
            'sizes.*' => 'required|string|max:10',
        ], [
            'sizes.required' => 'Bạn phải thêm ít nhất một kích thước.',
            'sizes.*.required' => 'Kích thước không được để trống.',
            'sizes.*.max' => 'Kích thước tối đa 10 ký tự.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $added = [];
        $skipped = [];

        foreach ($sizes as $size) {
            $exists = Size::where('name', $size)->first();
            if ($exists) {
                $skipped[] = $size;
                continue;
            }

            Size::create(['name' => $size]);
            $added[] = $size;
        }

        $message = '';
        if (!empty($added)) {
            $message .= 'Đã thêm kích thước: ' . implode(', ', $added) . '. ';
        }
        if (!empty($skipped)) {
            $message .= 'Bỏ qua kích thước đã tồn tại: ' . implode(', ', $skipped) . '.';
        }

        return redirect()->route('admin.sizes.index')->with('success', $message);
    }
    public function updateSize(Request $request)
    {
        $request->validate([
            'size_id' => 'required|exists:sizes,id',
            'size' => 'required|string|max:50',
        ]);

        $size = Size::findOrFail($request->size_id);

        if (Size::where('id', '!=', $size->id)->where('name', $request->size)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Kích thước đã tồn tại.'
            ]);
        }

        $size->update(['name' => $request->size]);

        return redirect()->route('admin.sizes.index')
            ->with('success', 'Cập nhật kích thước thành công.');
    }
    public function deleteSize(Request $request)
    {
        $request->validate([
            'size_id' => 'required|exists:sizes,id'
        ]);

        $size = Size::find($request->size_id);

        // Kiểm tra tồn tại trong product_variants
        $exists = ProductVariant::where('size_id', $size->id)->exists();
        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Size đang được sử dụng trong sản phẩm, không thể xóa.'
            ]);
        }

        $size->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xóa size thành công.'
        ]);
    }
}
