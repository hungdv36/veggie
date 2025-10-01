@extends('layouts.client')

@section('title', 'Đăng ký')

@section('breadcrumb', 'Đăng ký')

@section('content')

    <div class="ltn__login-area pb-110">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area text-center">
                        <h1 class="section-title">Đăng ký <br>Tài khoản của bạn</h1>
                        <p>Tạo tài khoản ngay hôm nay để mua sắm nhanh chóng và nhận nhiều ưu đãi hấp dẫn.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="account-login-inner">
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger mb-2" role="alert">
                                    <strong>Lỗi</strong> - {{ $error }}
                                </div>
                            @endforeach
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                            
                        <form action="{{ route('post-register') }}" class="ltn__form-box contact-form-box" method="post"
                            id="register-form">

                            @csrf

                            <input type="text" name="name" placeholder="Họ và tên" required>
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <input type="email" name="email" placeholder="Email*" required>
                            @error('email')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <input type="password" name="password" placeholder="Mật khẩu" required>
                            @error('password')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" required>
                            @error('password_confirmation')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <label class="checkbox-inline">

                                <input type="checkbox" name ="checkbox1" value="checkbox1" required>

                                Tôi đồng ý cho shop sử dụng thông tin cá nhân để gửi khuyến mãi và ưu đãi thời trang.

                            </label>
                            @error('checkbox1')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <label class="checkbox-inline">
                                <input type="checkbox" name="checkbox2" value="checkbox2" required>
                                Bằng cách nhấn "Tạo tài khoản", tôi đồng ý với chính sách bảo mật của shop.
                            </label>
                            @error('checkbox2')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="btn-wrapper">
                                <button class="theme-btn-1 btn reverse-color btn-block" type="submit">TẠO TÀI
                                    KHOẢN</button>
                            </div>
                        </form>
                        <div class="by-agree text-center">
                            <p>Khi tạo tài khoản, bạn đồng ý với:</p>
                            <p><a href="#">Điều khoản sử dụng &nbsp; &nbsp; | &nbsp; &nbsp; Chính sách bảo mật</a></p>
                            <div class="go-to-btn mt-50">
                                <a href="{{ route('login') }}">Bạn đã có tài khoản? Đăng nhập ngay</a>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection
