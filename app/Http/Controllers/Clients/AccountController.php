<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function index()
    {
        // Lấy thông tin người dùng đang đăng nhập
        $user = Auth::user();

        $addresses = ShippingAddress::where('user_id', Auth::id())->get();
        $orders = Order::with([
            'refund',
            'payment',
            'orderItems.returnRequest'
        ])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        // Truyền biến $user sang view
        return view('clients.pages.account', compact('user', 'addresses', 'orders'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'ltn_name' => 'required|string|max:255',
            'ltn_phone_number' => 'nullable|string|max:15',
            'ltn_email' => 'nullable|email',
            'ltn_address' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        // Xử lý ảnh đại diện
        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu có
            if ($user->avatar && Storage::disk('public')->exists('uploads/users/' . $user->avatar)) {
                Storage::disk('public')->delete('uploads/users/' . $user->avatar);
            }

            $file = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Lưu file vào storage/app/public/uploads/users
            $file->storeAs('uploads/users', $filename, 'public');

            // Lưu tên file vào database
            $user->avatar = $filename;
        }

        // Cập nhật thông tin khác
        $user->name = $request->input('ltn_name');
        $user->phone_number = $request->input('ltn_phone_number');
        $user->address = $request->input('ltn_address');
        $user->save();

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }


    public function changePassword(Request $request)
    {
        $request->validate(
            [
                'current_password' => 'required',
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|same:new_password',
            ],
            [
                'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
                'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
                'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
                'confirm_password.required' => 'Vui lòng xác nhận mật khẩu mới.',
                'confirm_password.same' => 'Xác nhận mật khẩu không khớp với mật khẩu mới.',
            ]
        );
        /** @var \App\Models\User $user */
        $user = Auth::user();

        //Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'error' => ['current_password' => ['Mật khẩu hiện tại không đúng.']]
            ], 422);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json([
            'success' => true,
            'message' => 'Mật khẩu đã được thay đổi thành công.',
        ]);
    }
    public function addAddress(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'province' => 'required|string|max:100',
            'district' => 'required|string|max:100', // Đảm bảo cột này có trong DB
            'ward' => 'required|string|max:100', // Đảm bảo cột này có trong DB
        ]);

        // Nếu đánh dấu là mặc định, unset các địa chỉ mặc định trước
        if ($request->has('default')) {
            ShippingAddress::where('user_id', Auth::id())->update(['default' => 0]);
        }

        ShippingAddress::create([
            'user_id' => Auth::id(),
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address' => $request->address,       // e.g., 96 Nguyễn Biểu B
            'province' => $request->province,     // Lưu tỉnh/thành phố vào cột 'province' (hoặc 'city')
            'district' => $request->district,     // LƯU Quận/Huyện riêng
            'ward' => $request->ward,             // LƯU Phường/Xã riêng
            'default' => $request->has('default') ? 1 : 0,
        ]);

        return back()->with('success', 'Địa chỉ đã được thêm thành công.');
    }

    public function showAddresses()
    {
        // Lấy tất cả địa chỉ của user hiện tại
        $addresses = ShippingAddress::where('user_id', Auth::id())->get();

        // Trả về view với biến $addresses

    }
}
