<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivationMail;
use Illuminate\Support\Facades\Auth;

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
            'password' => 'required|string|min:6',
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
            return redirect()->route('register');
        }

        // Create token active
        $token = Str::random(64);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'pending',
            'role_id' => 1,
            'activation_token' => $token,
        ]);

        Mail::to($user->email)->send(new ActivationMail($token, $user));

       toastr()->success('Đăng ký thành công! Vui lòng kiểm tra email để kích hoạt tài khoản.');
        return redirect()->route('login');
    }

    public function activate($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            $user->status = 'active';
            $user->activation_token = null;
            $user->save();

            toastr()->success('Tài khoản của bạn đã được kích hoạt. Bạn có thể đăng nhập ngay bây giờ.');
            return redirect()->route('login');
        }

        toastr()->error('Tài khoản đã được kích hoạt trước đó.');
        return redirect()->back();
    }

    public function showLoginForm()
    {
        return view('clients.pages.login');
    }

   public function login(Request $request)
{
    // Validate dữ liệu đầu vào
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ], [
        'email.required' => 'Email là bắt buộc',
        'email.email' => 'Email không hợp lệ',
        'password.required' => 'Mật khẩu là bắt buộc',
        'password.min' => 'Mật khẩu phải ít nhất 6 ký tự',
    ]);

    // Lấy user theo email
    $user = \App\Models\User::where('email', $request->email)->first();

    // Không tồn tại user
    if (! $user) {
        toastr()->error('Email không tồn tại trong hệ thống');
        return redirect()->back();
    }

    // Kiểm tra trạng thái tài khoản
    if ($user->status !== 'active') {
        toastr()->warning('Tài khoản chưa được kích hoạt hoặc bị khóa');
        return redirect()->back();
    }

    // Kiểm tra mật khẩu
    if (! \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        toastr()->error('Mật khẩu không đúng');
        return redirect()->back();
    }

    // Đăng nhập
    \Illuminate\Support\Facades\Auth::login($user);
    $request->session()->regenerate();

    toastr()->success('Đăng nhập thành công');
    return redirect()->route('home');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        toastr()->success('Đăng xuất thành công');
        return redirect()->route('home');
    }
}
