<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class AdminAuthController extends Controller
{
    public function ShowLoginForm()
    {
        return view('admin.pages.login');
    }
    public function login(Request $request): RedirectResponse
    {
        // 1️⃣ Validate dữ liệu đầu vào
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // 2️⃣ Thử đăng nhập với guard 'admin'
        if (Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            $user = Auth::guard('admin')->user();

            // 3️⃣ Kiểm tra user tồn tại và quan hệ role
            if ($user && $user->role && in_array($user->role->name, ['admin', 'staff'])) {
                // ✅ Thành công
                $request->session()->regenerate();
                toastr()->success('Đăng nhập admin thành công!');
                return redirect()->route('admin.dashboard');
            }

            // 4️⃣ Nếu user không có quyền
            Auth::guard('admin')->logout();
            toastr()->error('Bạn không có quyền truy cập admin.');
            return back()->withInput();
        }

        // 5️⃣ Nếu login thất bại
        toastr()->error('Email hoặc mật khẩu không đúng!');
        return back()->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout(); // ✅ logout đúng guard

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login'); // quay về login admin
    }
}
