@extends('layouts.client')

@section('title', 'Đăng nhập')

@section('breadcrumb', 'Đăng nhập')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center align-items-center">
        <div class="col-lg-10">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="row g-0">
                    <!-- Cột hình minh họa -->
                    <div class="col-lg-6 d-none d-lg-block bg-light position-relative">
                        <div class="h-100 w-100 d-flex flex-column justify-content-center align-items-center p-4">
                            <i class="fas fa-user-circle mb-4" style="font-size: 90px; color: #1eb980;"></i>
                            <h4 class="fw-bold text-success">Chào mừng trở lại!</h4>
                            <p class="text-muted text-center px-4">Đăng nhập để theo dõi đơn hàng, nhận ưu đãi và trải nghiệm mua sắm nhanh chóng.</p>
                        </div>
                    </div>

                    <!-- Cột form đăng nhập -->
                    <div class="col-lg-6 bg-white">
                        <div class="p-5">
                            <div class="text-center mb-4">
                                <h3 class="fw-bold text-success">Đăng nhập tài khoản</h3>
                                <p class="text-muted small">Nhập thông tin của bạn để tiếp tục mua sắm</p>
                            </div>

                            {{-- Hiển thị lỗi --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('login') }}" method="POST" id="login-form">
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Địa chỉ Email</label>
                                    <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Nhập email của bạn" required>
                                    @error('email')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                                    <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Nhập mật khẩu" required>
                                    @error('password')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                        <label class="form-check-label small text-muted" for="remember">Ghi nhớ đăng nhập</label>
                                    </div>
                                    <a href="{{ route('password.request') }}" class="small text-success text-decoration-none">Quên mật khẩu?</a>
                                </div>

                                <button type="submit" class="btn btn-success w-100 py-2 fw-semibold shadow-sm">
                                    <i class="fas fa-sign-in-alt me-2"></i> ĐĂNG NHẬP
                                </button>
                            </form>

                            <hr class="my-4">

                            <div class="text-center">
                                <p class="mb-1 text-muted">Chưa có tài khoản?</p>
                                <a href="{{ route('register') }}" class="btn btn-outline-success mt-2 px-4 fw-semibold">
                                    <i class="fas fa-user-plus me-2"></i> TẠO TÀI KHOẢN
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CSS tùy chỉnh --}}
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

    @media (max-width: 992px) {
        .card {
            border-radius: 15px;
        }
    }
</style>
@endsection
