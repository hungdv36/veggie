<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        // LOGIC KHÔI PHỤC: Lấy đối tượng người dùng đã xác thực thông qua 'admin' guard.
        // Dòng này an toàn vì Middleware đã đảm bảo người dùng đã đăng nhập.
        $user = Auth::guard('admin')->user();

        // Trả về view hồ sơ và truyền dữ liệu người dùng.
        return view('admin.pages.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('admin')->user(); // dùng đúng guard

        if (!$user) {
            return response()->json(['error' => 'Bạn chưa đăng nhập!'], 401);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone;
        $user->address = $request->address;

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $fileName = time() . '_' . $avatar->getClientOriginalName();
            $avatar->storeAs('public/avatars', $fileName);
            $user->avatar = $fileName;
        }

        $user->save();

        return response()->json(['success' => true]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'same:new_password'
        ]);

        /** @var \App\Models\User $user */
       $user = Auth::guard('admin')->user();


        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Mật khẩu hiện tại không đúng'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save(); // ✅ Không báo lỗi save()

        return response()->json(['success' => true]);
    }
}
