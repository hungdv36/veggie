@extends('layouts.client')

@section('title', 'Đăng ký')

@section('breadcrumb', 'Đăng ký')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-success mb-2">Tạo tài khoản mới</h2>
                        <p class="text-muted">Đăng ký ngay để mua sắm dễ dàng và nhận ưu đãi hấp dẫn!</p>
                    </div>

                    {{-- Hiển thị thông báo lỗi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Hiển thị thông báo thành công --}}
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('post-register') }}" method="POST" id="register-form">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Họ và tên</label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg" placeholder="Nhập họ và tên" required>
                            @error('name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Địa chỉ Email</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Nhập email của bạn" required>
                            @error('email')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Nhập mật khẩu" required>
                                @error('password')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-lg" placeholder="Nhập lại mật khẩu" required>
                                @error('password_confirmation')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-check mb-2">
                            <input type="checkbox" name="checkbox1" id="checkbox1" class="form-check-input" value="checkbox1" required>
                            <label for="checkbox1" class="form-check-label">
                                Tôi đồng ý cho shop sử dụng thông tin cá nhân để gửi khuyến mãi và ưu đãi thời trang.
                            </label>
                            @error('checkbox1')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input type="checkbox" name="checkbox2" id="checkbox2" class="form-check-input" value="checkbox2" required>
                            <label for="checkbox2" class="form-check-label">
                                Bằng cách nhấn "Tạo tài khoản", tôi đồng ý với <a href="#" class="text-decoration-underline text-success">chính sách bảo mật</a> của shop.
                            </label>
                            @error('checkbox2')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-semibold shadow-sm">
                            <i class="fas fa-user-plus me-2"></i> TẠO TÀI KHOẢN
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-1 text-muted">Khi tạo tài khoản, bạn đồng ý với:</p>
                        <a href="#" class="text-decoration-underline text-success">Điều khoản sử dụng</a> &nbsp; | &nbsp;
                        <a href="#" class="text-decoration-underline text-success">Chính sách bảo mật</a>
                    </div>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-0 text-muted">Đã có tài khoản?</p>
                        <a href="{{ route('login') }}" class="btn btn-outline-success mt-2 px-4 fw-semibold">
                            <i class="fas fa-sign-in-alt me-2"></i> Đăng nhập ngay
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: #f8f9fa;
    }

    .card {
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 10px 25px rgba(0, 128, 0, 0.1);
    }

    .btn-success {
        background: linear-gradient(90deg, #0f9d58, #1eb980);
        border: none;
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        background: linear-gradient(90deg, #1eb980, #0f9d58);
        transform: translateY(-1px);
    }

    .btn-outline-success {
        border-color: #1eb980;
        color: #1eb980;
    }

    .btn-outline-success:hover {
        background-color: #1eb980;
        color: #fff;
    }

    .form-check-input:checked {
        background-color: #1eb980;
        border-color: #1eb980;
    }

    a.text-success:hover {
        color: #0f9d58 !important;
    }
</style>
@endsection
