<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.pages.coupon.coupons', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function showCouponForm()
    {
        $coupons = Coupon::all();
        return view('admin.pages.coupon.coupons-add', compact('coupons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|min:3|max:20|unique:coupons,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:percent,amount',
            'value' => 'required|numeric|gt:0',
            'min_order' => 'required|numeric|gte:10000',
            'usage_limit' => 'required|integer|gt:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'code.required' => 'Mã giảm giá không được để trống',
            'code.unique' => 'Mã giảm giá đã tồn tại',
            'code.min' => 'Mã giảm giá phải có ít nhất 3 ký tự',
            'code.max' => 'Mã giảm giá không được vượt quá 20 ký tự',
            'name.required' => 'Tên mã giảm giá không được để trống',
            'name.max' => 'Tên mã giảm giá không được vượt quá 255 ký tự',
            'name.string' => 'Tên mã giảm giá phải là chuỗi ký tự',
            'name.min' => 'Tên mã giảm giá phải có ít nhất 3 ký tự',
            'type.required' => 'Loại mã giảm giá chưa chọn',
            'type.in' => 'Loại mã giảm giá không hợp lệ',
            'value.required' => 'Giá trị không được để trống',
            'value.gt' => 'Giá trị phải lớn hơn 0',
            'min_order.required' => 'Đơn hàng tối thiểu không được để trống',
            'min_order.gte' => 'Đơn hàng tối thiểu phải lớn hơn hoặc bằng 10000',
            'usage_limit.required' => 'Giới hạn sử dụng không được để trống',
            'usage_limit.gt' => 'Giới hạn sử dụng phải lớn hơn 0',
            'start_date.required' => 'Ngày bắt đầu không được để trống',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ',
            'end_date.required' => 'Ngày kết thúc không được để trống',
            'end_date.date' => 'Ngày kết thúc không hợp lệ',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu',
        ]);
        Coupon::create($request->all());

        return redirect()->route('admin.coupons.index')->with('success', 'Thêm mã giảm giá thành công!');
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
    public function updateCoupon(Request $request)
    {
        $coupon = Coupon::findOrFail($request->id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|min:3|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:percent,amount',
            'value' => 'required|numeric|gt:0',
            'min_order' => 'nullable|numeric|gte:10000',
            'usage_limit' => 'nullable|numeric|gte:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:0,1'
        ]);

        // Chỉ cập nhật các key hợp lệ, bỏ key số thừa
        $coupon->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'type' => $validated['type'],
            'value' => $validated['value'],
            'min_order' => $validated['min_order'] ?? null,
            'usage_limit' => $validated['usage_limit'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Cập nhật mã giảm giá thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteCoupon(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:coupons,id',
        ]);

        $coupon = Coupon::find($request->id);
        if ($coupon) {
            $coupon->delete();
            return redirect()->route('admin.coupons.index')
                ->with('success', 'Xóa mã giảm giá thành công');
        }

        return redirect()->route('admin.coupons.index')
            ->with('error', 'Mã giảm giá không tồn tại');
    }
}
