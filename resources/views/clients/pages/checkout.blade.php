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
                            <input type="hidden" name="coupon_id" id="coupon_id" value="">
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
                            </div>

                            <div class="ltn__payment-note mt-30 mb-30">
                                <p>Dữ liệu cá nhân của bạn sẽ được sử dụng để xử lý đơn hàng của bạn, hỗ trợ trải nghiệm của
                                    bạn
                                    trên toàn bộ trang web này và cho các mục đích khác được mô tả trong chính sách bảo mật
                                    của
                                    chúng tôi.</p>
                            </div>
                            <button class="btn theme-btn-1 btn-effect-1 text-uppercase" type="submit"
                                id="order_button_cash">
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
                                    <td>Giảm giá</td>
                                    <td><strong id="discount-amount">0 đ</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Tổng tiền</strong></td>
                                    <td><strong
                                            id="total-amount">{{ number_format($cartItems->sum(fn($item) => ($item->variant->sale_price ?? $item->product->price) * $item->quantity) + 25000, 0, ',', '.') }}
                                            đ</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="coupon-section mt-3">
                            <input type="text" id="coupon-code" placeholder="Nhập mã giảm giá" class="form-control" />
                            <button type="button" id="apply-coupon" class="btn btn-primary mt-2">Áp dụng</button>
                            <div id="coupon-message" class="text-danger mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- AJAX cập nhật địa chỉ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAddress = document.getElementById('list_address');
            const hiddenAddress = document.getElementById('address_hidden');

            const nameField = document.getElementById('name_field');
            const phoneField = document.getElementById('phone_field');
            const addressField = document.getElementById('address_field');
            const cityField = document.getElementById('city_field');

            selectAddress?.addEventListener('change', function() {
                const id = this.value;
                hiddenAddress.value = id;

                if (!id) return;

                fetch(`/addresses/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        nameField.value = data.full_name || '';
                        phoneField.value = data.phone || '';
                        addressField.value = data.address || '';
                        cityField.value = data.city || '';
                    })
                    .catch(() => alert('Không thể tải thông tin địa chỉ!'));
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('checkout-form');
            const paypalRadio = document.getElementById('payment_paypal');
            const codRadio = document.getElementById('payment_cod');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const method = document.querySelector('input[name="payment_method"]:checked').value;

                if (method === 'paypal') {
                    // Nếu chọn PayPal, chuyển hướng sang route PayPal
                    const amount = {{ $totalPrice + 25000 }};
                    const formData = new FormData();
                    formData.append('amount', amount);

                    fetch('{{ route('checkout.paypal') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.redirect_url) {
                                window.location.href = data.redirect_url;
                            } else {
                                alert('Không thể tạo thanh toán PayPal.');
                            }
                        })
                        .catch(() => alert('Lỗi khi kết nối với PayPal.'));

                } else {
                    form.submit();
                }
            });
        });
        $(document).ready(function() {
            $('#apply-coupon').click(function() {
                let couponCode = $('#coupon-code').val();

                $.ajax({
                    url: "{{ route('checkout.applyCoupon') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        coupon_code: couponCode
                    },
                    success: function(res) {
                        if (res.success) {
                            $('#coupon-message').removeClass('text-danger').addClass(
                                    'text-success')
                                .text("Mã giảm giá áp dụng thành công!");
                            $('#discount-amount').text(new Intl.NumberFormat().format(res
                                .discount_amount) + " đ");
                            $('#total-amount').text(new Intl.NumberFormat().format(res
                                .new_total) + " đ");
                            $('#coupon_id').val(res.coupon_id); // <-- lưu coupon_id
                        }
                    },
                    error: function(xhr) {
                        let err = xhr.responseJSON?.error || "Lỗi không xác định";
                        $('#coupon-message').removeClass('text-success').addClass('text-danger')
                            .text(err);
                         $('#discount-row').hide();
                    }
                });
            });
        });
    </script>
@endsection
