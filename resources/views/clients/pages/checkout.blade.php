@extends('layouts.client')

@section('title', 'Đặt hàng')

@section('breadcrumb', 'Đặt hàng')

@section('content')
    <div class="ltn__checkout-area mb-105">
        <div class="container">
            <div class="row">
                <!-- CHI TIẾT THANH TOÁN -->
                <div class="col-lg-12">
                    <div class="ltn__checkout-inner">
                        <div class="ltn__checkout-single-content mt-50">
                            <h4 class="title-2">Chi tiết thanh toán</h4>

                            <div class="select-address d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>Chọn địa chỉ khác:</h6>
                                </div>
                                <div>
                                    <select name="address_id" id="list_address" class="input-item">
                                        @forelse ($addresses as $address)
                                            <option value="{{ $address->id }}"
                                                {{ $defaultAddress && $defaultAddress->id === $address->id ? 'selected' : '' }}>
                                                {{ $address->full_name }}, {{ $address->address }}
                                            </option>
                                        @empty
                                            <option value="">Chưa có địa chỉ nào</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div>
                                    <a href="{{ route('account') }}" class="btn theme-btn-1 btn-effect-1 text-uppercase">
                                        Thêm địa chỉ mới
                                    </a>
                                </div>
                            </div>

                            <div class="ltn__checkout-single-content-info">
                                <h6>Thông tin cá nhân</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-item input-item-name ltn__custom-icon">
                                            <input type="text" id="name_field" name="ltn__name" placeholder="Họ và tên"
                                                value="{{ $defaultAddress->full_name ?? '' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-item input-item-phone ltn__custom-icon">
                                            <input type="text" id="phone_field" name="ltn__phone"
                                                placeholder="Số điện thoại" value="{{ $defaultAddress->phone ?? '' }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <h6>Địa chỉ</h6>
                                        <div class="input-item">
                                            <input type="text" id="address_field" name="ltn__address"
                                                placeholder="Số nhà và tên đường"
                                                value="{{ $defaultAddress->address ?? '' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <h6>Thành phố</h6>
                                        <div class="input-item">
                                            <input type="text" id="city_field" name="ltn__city" placeholder="Thành phố"
                                                value="{{ $defaultAddress->city ?? '' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PHƯƠNG THỨC THANH TOÁN -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="ltn__checkout-payment-method mt-50">
                        <h4 class="title-2">Phương thức thanh toán</h4>
                        <form id="checkout-form" action="{{ route('checkout.placeOrder') }}" method="POST">
                            @csrf
                            <input type="hidden" name="address_id" id="address_hidden"
                                value="{{ $defaultAddress->id ?? '' }}">

                            <div id="checkout_payment">
                                <div class="card">
                                    <h5 class="ltn__card-title">
                                        <input type="radio" name="payment_method" value="cash" id="payment_cod" checked>
                                        <label for="payment_cod">
                                            Thanh toán khi nhận hàng
                                            <img src="{{ asset('assets/clients/img/icons/cash.webp') }}" alt="#">
                                        </label>
                                    </h5>
                                </div>
                                <div class="card">
                                    <h5 class="collapsed ltn__card-title">
                                        <input type="radio" name="payment_method" value="paypal" id="payment_paypal">
                                        <label for="payment_paypal">
                                            PayPal
                                            <img src="{{ asset('assets/clients/img/icons/payment.jfif') }}" alt="#">
                                        </label>
                                    </h5>
                                </div>
                                <div class="card">
                                    <h5 class="collapsed ltn__card-title">
                                        <input type="radio" name="payment_method" value="momo" id="payment_momo">
                                        <label for="payment_momo">
                                            Thanh toán qua MoMo
                                            <img src="{{ asset('assets/clients/img/icons/momo.webp') }}" alt="MoMo">
                                        </label>
                                    </h5>
                                </div>

                            </div>

                            <div class="ltn__payment-note mt-30 mb-30">
                                <p>Dữ liệu cá nhân của bạn sẽ được sử dụng để xử lý đơn hàng của bạn, hỗ trợ trải nghiệm của
                                    bạn
                                    trên toàn bộ trang web này và cho các mục đích khác được mô tả trong chính sách bảo mật
                                    của
                                    chúng tôi.</p>
                            </div>
                            <button id="order_button_cash" class="btn theme-btn-1 btn-effect-1 text-uppercase"
                                type="submit">
                                <span class="spinner-border spinner-border-sm me-2 d-none" id="loadingSpinner"></span>
                                Đặt hàng
                            </button>
                        </form>
                    </div>
                </div>

                <!-- TỔNG SẢN PHẨM -->
                <div class="col-lg-6">
                    <div class="shoping-cart-total mt-50">
                        <h4 class="title-2">Tổng sản phẩm</h4>
                        <table class="table">
                            <tbody>
                                @foreach ($cartItems as $item)
                                    <tr>
                                        <td>{{ $item->product->name }} <strong>x {{ $item->quantity }}</strong></td>
                                        <td>{{ number_format($item->variant->sale_price * $item->quantity, 0, ',', '.') }}
                                            đ</td>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>Vận chuyển và xử lý</td>
                                    <td>{{ number_format(25000, 0, ',', '.') }} đ</td>
                                </tr>
                                <tr>
                                    <td><strong>Tổng tiền</strong></td>
                                    <td><strong>{{ number_format($cartItems->sum(fn($item) => ($item->variant->sale_price ?? $item->product->price) * $item->quantity) + 25000, 0, ',', '.') }}
                                            đ</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkout-form');
    const button = document.getElementById('order_button_cash');
    const totalAmount = {{ $totalPrice + 25000 }};

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        button.disabled = true;
        button.innerText = "Đang xử lý...";

        const method = document.querySelector('input[name="payment_method"]:checked').value;
        const formData = new FormData(form);
        formData.append('amount', totalAmount);

        try {
            // ✅ PAYPAL
            if (method === 'paypal') {
                const res = await fetch('{{ route('checkout.paypal') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const data = await res.json();
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    alert('Không thể khởi tạo thanh toán PayPal.');
                }

            // ✅ MOMO SANDBOX
            } else if (method === 'momo') {
                // Gửi form đến placeOrder để tạo đơn hàng trước
                const res = await fetch('{{ route('checkout.placeOrder') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });

                const data = await res.json();

                if (data.redirect) {
                    const url = data.redirect;
                    const params = new URLSearchParams(url.split('?')[1]);
                    const order_id = params.get('order_id');
                    const amount = params.get('amount');

                    // Gửi POST thật đến handleMoMo (đúng chuẩn API)
                    const momoForm = new FormData();
                    momoForm.append('order_id', order_id);
                    momoForm.append('amount', amount);

                    const momoRes = await fetch('{{ route('checkout.momo') }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: momoForm
                    });

                    const momoData = await momoRes.json();
                    if (momoData.redirect_url) {
                        // Redirect đến trang thanh toán sandbox của MoMo
                        window.location.href = momoData.redirect_url;
                    } else {
                        alert('Không thể khởi tạo thanh toán MoMo.');
                        console.error(momoData);
                    }
                } else {
                    console.error(data);
                    alert('Không thể tạo đơn hàng cho MoMo.');
                }

            // ✅ COD
            } else if (method === 'cash') {
                form.submit();
            }

        } catch (err) {
            console.error(err);
            alert('Có lỗi xảy ra khi xử lý thanh toán.');
        } finally {
            button.disabled = false;
            button.innerText = "Đặt hàng";
        }
    });
});
</script>


@endsection
