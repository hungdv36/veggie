@extends('layouts.client')

@section('title', 'Đặt hàng')

@section('breadcrumb', 'Đặt hàng')

@section('content')
    <!-- WISHLIST AREA START -->
    <div class="ltn__checkout-area mb-105">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__checkout-inner">
                        <div class="ltn__checkout-single-content mt-50">
                            <h4 class="title-2">Chi tiết thanh toán</h4>
                            <div class="select-address"
                                style="display: flex; justify-content:space-between; align-items:center; margin: 10px 0;">
                                <div>
                                    <h6>Chọn địa chỉ khác:</h6>
                                </div>
                                <div>
                                    <select name="address_id" id="list_address" class="input-item">
                                        @foreach ($addresses as $address)
                                            <option value="{{ $address->id }}" {{ $address->default ? 'selected' : '' }}>
                                                {{ $address->full_name }}, {{ $address->address }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <a href="{{ route('account') }}"
                                        class="btn theme-btn-1 btn-effect-1 text-uppercase">Thêm địa chỉ mới</a>
                                </div>
                            </div>
                            <div class="ltn__checkout-single-content-info">
                                <h6>Thông tin cá nhân</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-item input-item-name ltn__custom-icon">
                                            <input type="text" name="ltn__name" placeholder="Họ và tên"
                                                value="{{ $defaultAddress->full_name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-item input-item-phone ltn__custom-icon">
                                            <input type="text" name="ltn__phone" placeholder="Số điện thoại"
                                                value="{{ $defaultAddress->phone }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <h6>Địa chỉ</h6>
                                        <div class="input-item">
                                            <input type="text" name="ltn__address" placeholder="Số nhà và tên đường"
                                                value="{{ $defaultAddress->address }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <h6>Thành phố</h6>
                                        <div class="input-item">
                                            <input type="text" name="ltn__city" placeholder="Thành phố"
                                                value="{{ $defaultAddress->city }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ltn__checkout-payment-method mt-50">
                        <h4 class="title-2">Phương thức thanh toán</h4>
                        <form action="{{ route('checkout.placeOrder') }}" method="POST">
                            @csrf
                            <input type="hidden" name="address_id" value="{{ $defaultAddress->id }}">
                            <div id="checkout_payment">
                                <div class="card">
                                    <h5 class="ltn__card-title">
                                        <input type="radio" name="payment_method" value="cash" id="payment_cod">
                                        <label for="payment_cod">
                                            Thanh toán khi nhận hàng <img
                                                src="{{ asset('assets/clients/img/icons/cash.webp') }}" alt="#">
                                        </label>
                                    </h5>
                                </div>
                                <div class="card">
                                    <h5 class="collapsed ltn__card-title">
                                        <input type="radio" name="payment_method" value="paypal" id="payment_paypal">
                                        <label for="payment_paypal">
                                            PayPal <img src="{{ asset('assets/clients/img/icons/payment.jfif') }}"
                                                alt="#">
                                        </label>
                                    </h5>
                                </div>
                            </div>
                            <div class="ltn__payment-note mt-30 mb-30">
                                <p>Dữ liệu cá nhân của bạn sẽ được sử dụng để xử lý đơn hàng của bạn, hỗ trợ trải nghiệm của
                                    bạn trên toàn bộ trang web này và cho các mục đích khác được mô tả trong chính sách bảo
                                    mật của chúng tôi.</p>
                            </div>
                            <button class="btn theme-btn-1 btn-effect-1 text-uppercase" type="submit" id="order_button_cash">Đặt hàng</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="shoping-cart-total mt-50">
                        <h4 class="title-2">Tổng sản phẩm</h4>
                        <table class="table">
                            <tbody>
                                @foreach ($cartItems as $item)
                                    <tr>
                                        <td>{{ $item->product->name }} <strong>x {{ $item->quantity }}</strong></td>
                                        <td>{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }} đ</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>Vận chuyển và xử lý</td>
                                    <td>{{ number_format(25000, 0, ',', '.') }} đ</td>
                                </tr>
                                <tr>
                                    <td><strong>Tổng tiền</strong></td>
                                    <td><strong>{{ number_format($totalPrice + 25000, 0, ',', '.') }} đ</strong></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- WISHLIST AREA START -->
@endsection
