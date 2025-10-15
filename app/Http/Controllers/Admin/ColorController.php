<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use App\Models\ProductVariant;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::orderBy('id', 'desc')->paginate(10);
        return view('admin.pages.color.colors', compact('colors'));
    }
    public function showColorForm()
    {
        return view('admin.pages.color.colors-add');
    }
    public function addColor(Request $request)
    {
        // Chuẩn hoá dữ liệu: trim trước khi validate
        $colors = array_map(function ($c) {
            return [
                'name' => trim($c['name'] ?? ''),
                'hex_code' => trim($c['hex_code'] ?? ''),
            ];
        }, $request->input('colors', []));

        $request->merge(['colors' => $colors]);

        // Validate mảng colors
        $request->validate([
            'colors' => 'required|array|min:1',
            'colors.*.name' => 'required|string|max:50',
            'colors.*.hex_code' => ['required', 'regex:/^#([0-9A-Fa-f]{6})$/'],
        ], [
            'colors.required' => 'Bạn phải thêm ít nhất một màu.',
            'colors.*.name.required' => 'Tên màu không được để trống.',
            'colors.*.name.max' => 'Tên màu tối đa 50 ký tự.',
            'colors.*.hex_code.required' => 'Mã màu không được để trống.',
            'colors.*.hex_code.regex' => 'Mã màu phải là định dạng HEX, ví dụ #FF0000.',
        ]);

        $added = [];
        $skipped = [];

        foreach ($colors as $color) {
            $name = $color['name'];
            $code = strtoupper($color['hex_code']); // chuẩn hoá chữ hoa

            // Kiểm tra đã tồn tại
            $exists = Color::where('name', $name)
                ->orWhere('hex_code', $code)
                ->first();

            if ($exists) {
                $skipped[] = $name;
                continue;
            }

            // Thêm mới
            Color::create([
                'name' => $name,
                'hex_code' => $code,
            ]);

            $added[] = $name;
        }

        // Chuẩn bị thông báo
        $message = '';
        if (!empty($added)) {
            $message .= 'Đã thêm màu: ' . implode(', ', $added) . '. ';
        }
        if (!empty($skipped)) {
            $message .= 'Bỏ qua màu đã tồn tại: ' . implode(', ', $skipped) . '.';
        }

        return redirect()->route('admin.colors.index')->with('success', $message);
    }
    public function updateColor(Request $request)
    {
        $request->validate([
            'color_id' => 'required|exists:colors,id',
            'color' => 'required|string|max:50',
            'hex_code' => ['required', 'regex:/^#([0-9A-Fa-f]{6})$/'],
        ], [
            'color_id.required' => 'ID màu không hợp lệ.',
            'color_id.exists' => 'Màu không tồn tại.',
            'color.required' => 'Tên màu không được để trống.',
            'color.max' => 'Tên màu tối đa 50 ký tự.',
            'hex_code.required' => 'Mã màu không được để trống.',
            'hex_code.regex' => 'Mã màu phải là định dạng HEX, ví dụ #FF0000.',
        ]);

        $color = Color::findOrFail($request->color_id);

        // Kiểm tra trùng với màu khác
        $exists = Color::where('id', '!=', $color->id)
            ->where(function ($q) use ($request) {
                $q->where('name', $request->color)
                    ->orWhere('hex_code', strtoupper($request->hex_code));
            })
            ->first();

        if ($exists) {
            return redirect()->route('admin.colors.index')
                ->with('error', 'Tên màu hoặc mã màu đã tồn tại.');
        }

        $color->update([
            'name' => $request->color,
            'hex_code' => strtoupper($request->hex_code),
        ]);

        return redirect()->route('admin.colors.index')
            ->with('success', 'Cập nhật màu thành công.');
    }
    public function deleteColor(Request $request)
    {
        $request->validate([
            'color_id' => 'required|exists:colors,id'
        ]);

        $color = Color::find($request->color_id);

        // Kiểm tra tồn tại trong product_variants
        $exists = ProductVariant::where('color_id', $color->id)->exists();
        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Màu này đang được sử dụng trong sản phẩm, không thể xóa.'
            ]);
        }

        $color->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xóa màu thành công.'
        ]);
    }
}
