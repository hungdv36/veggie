@extends('layouts.client')

@section('title', 'Đăng nhập')

@section('breadcrumb', 'Đăng nhập')

@section('content')
 <!-- KHU VỰC ĐĂNG NHẬP BẮT ĐẦU -->
<div class="ltn__login-area pb-65">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area text-center">
                    <h1 class="section-title">Đăng nhập <br>Vào tài khoản của bạn</h1>
                    <p>Vui lòng đăng nhập để tiếp tục mua sắm, theo dõi đơn hàng và trải nghiệm dịch vụ tốt nhất.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Form đăng nhập -->
            <div class="col-lg-6">
                <div class="account-login-inner">
                    <form action="#" class="ltn__form-box contact-form-box" method="POST" id="login-form">
                        @csrf
                        <input type="email" name="email" placeholder="Email*" required>
                        @error('email')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <input type="password" name="password" placeholder="Mật khẩu*" required>
                        @error('password')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="btn-wrapper mt-0">
                            <button class="theme-btn-1 btn btn-block" type="submit">ĐĂNG NHẬP</button>
                        </div>
                        <div class="go-to-btn mt-20">
                            <a href="#"><small>BẠN QUÊN MẬT KHẨU?</small></a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Tạo tài khoản -->
            <div class="col-lg-6">
                <div class="account-create text-center pt-50">
                    <h4>BẠN CHƯA CÓ TÀI KHOẢN?</h4>
                    <p>Đăng ký ngay để thêm sản phẩm vào danh sách yêu thích, 
                        nhận gợi ý cá nhân hóa, thanh toán nhanh hơn và dễ dàng theo dõi đơn hàng.</p>
                    <div class="btn-wrapper">
                        <a href="{{ route('register') }}" class="theme-btn-1 btn black-btn">TẠO TÀI KHOẢN</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- KHU VỰC ĐĂNG NHẬP KẾT THÚC -->

@endsection