<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        // Lấy thông tin người dùng đang đăng nhập
        $user = Auth::user();

        // Truyền biến $user sang view
        return view('clients.pages.account', compact('user'));
    }
}
