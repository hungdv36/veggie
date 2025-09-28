<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('clients.pages.register');
    }
    public function register(Request $request)
    {
        // Validate 
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Họ tên là bắt buộc',
            'email.required' => 'Email là bắt buộc',
            'email.unique' => 'Email này đã được sử dụng',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.min' => 'Mật khẩu phải ít nhất 8 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ]);

        // Check If email exists
        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) 
        {
            if($existingUser->isPending())
            {
                toastr()->error('Email nay đã được đăng ký, vui lòng kiểm tra email');
                return redirect()->route('register');
            }
            return redirect()->route('post-register');
        }

        // Create token active
        $token = Str::random(64);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'pending',
            'role_id' => 2,
            'activation_token' => $token,
        ]);

       return redirect()->route('register')->with('success', 'Đăng ký tài khoản thành công. Vui lòng kiểm tra email để kích hoạt');
    }
}
