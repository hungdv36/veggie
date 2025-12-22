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
                                                value="{{ $defaultAddress->province ?? '' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <h6>Quận</h6>
                                        <div class="input-item">
                                            <input type="text" id="city_field" name="ltn__city" placeholder="Quận"
                                                value="{{ $defaultAddress->district ?? '' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <h6>Phường</h6>
                                        <div class="input-item">
                                            <input type="text" id="city_field" name="ltn__city" placeholder="Phường"
                                                value="{{ $defaultAddress->ward ?? '' }}" readonly>
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
                                        <input type="radio" name="payment_method" value="momo" id="payment_momo">
                                        <label for="payment_momo">
                                            Thanh toán qua MoMo
                                            <img src="{{ asset('assets/clients/img/icons/momo.webp') }}" alt="MoMo">
                                        </label>
                                    </h5>
                                </div>
                                <div class="card">
                                    <h5 class="collapsed ltn__card-title">
                                        <input type="radio" name="payment_method" value="vnpay" id="payment_vnpay">
                                        <label for="payment_vnpay">
                                            Thanh toán qua VNPAY
                                            <img src="{{ asset('assets/clients/img/icons/vnpay.png') }}" alt="VNPAY">
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

                        @php
                            $total = 0;

                            $flashSale = \App\Models\FlashSale::with('items')
                                ->where('start_time', '<=', now())
                                ->where('end_time', '>=', now())
                                ->first();
                        @endphp

                        <table class="table">
                            <tbody>
                                @foreach ($cartItems as $item)
                                    @php
                                        $product = $item->product;
                                        $variant = $item->variant ?? null;

                                        // Giá gốc
                                        $basePrice = $variant->price ?? $product->price;

                                        // Giá sale (nếu có)
                                        $salePrice = $variant->sale_price ?? ($product->sale_price ?? $basePrice);

                                        $quantity = $item->quantity;

                                        // Mặc định dùng salePrice
                                        $price = $salePrice;

                                        // Kiểm tra Flash Sale — KHÔNG kiểm tra số lượng!
                                        $flashItem = $flashSale
                                            ? $flashSale->items->firstWhere('product_id', $product->id)
                                            : null;

                                        if ($flashItem) {
                                            $price = round($basePrice * (1 - ($flashItem->discount_price ?? 0) / 100));
                                        }

                                        $subtotal = $price * $quantity;
                                        $total += $subtotal;
                                    @endphp

                                    <tr>
                                        <td>
                                            {{ $product->name }}
                                            <strong>x {{ $quantity }}</strong>
                                        </td>
                                        <td>{{ number_format($subtotal, 0, ',', '.') }} đ</td>
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
                                    <td><strong id="total-amount">
                                            {{ number_format($total + 25000, 0, ',', '.') }} đ
                                        </strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="coupon-section mt-3">

                            <button id="open-coupon-modal" class="btn btn-outline-secondary mt-2">
                                Chọn mã giảm giá
                            </button>

                            <div id="coupon-message" class="text-danger mt-1"></div>
                        </div>
                        <!-- Modal Voucher -->
                        <div class="modal fade" id="couponModal" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Voucher của shop</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="input-group mb-3">
                                            <div class="d-flex mb-3" style="gap: 8px;">
                                                <input type="text" id="coupon-code" class="form-control flex-grow-1"
                                                    placeholder="Nhập mã voucher" style="height: 42px; font-size: 14px;">
                                                <button class="btn btn-primary" id="apply-coupon"
                                                    style="height: 42px; padding: 0 18px; font-size: 14px; white-space: nowrap;">
                                                    Áp dụng
                                                </button>
                                            </div>
                                        </div>
                                        <div id="coupon-message" class="text-danger mb-3"></div>
                                        <h6 class="mb-2">Voucher khả dụng</h6>
                                        <div id="coupon-list"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Mở modal và load voucher
            document.getElementById('open-coupon-modal').addEventListener('click', function() {
                const couponList = document.getElementById('coupon-list');
                couponList.innerHTML = '<p class="text-center text-muted">Đang tải...</p>';

                fetch('/coupons/list')
                    .then(res => res.json())
                    .then(data => {
                        couponList.innerHTML = '';
                        data.forEach(coupon => {
                            couponList.innerHTML += `
                    <div class="border rounded p-3 mb-2 coupon-item ${coupon.usable ? '' : 'opacity-50'}"
                         style="cursor:${coupon.usable ? 'pointer':'not-allowed'}"
                         data-code="${coupon.code}"
                         data-usable="${coupon.usable}">
                        <h6 class="mb-1">${coupon.name}</h6>
                        <p class="text-muted mb-0" style="font-size:14px">
                            Giảm: ${coupon.value_text}<br>
                            Đơn tối thiểu: ${coupon.min_text}
                        </p>
                        ${coupon.usable ? '' : `<small class="text-danger">${coupon.reason}</small>`}
                    </div>
                `;
                        });
                    });

                new bootstrap.Modal(document.getElementById('couponModal')).show();
            });

            // Click trực tiếp vào voucher
            $(document).on('click', '.coupon-item', function() {
                const code = $(this).data('code');
                const usable = $(this).data('usable') === true || $(this).data('usable') === 'true';

                if (!usable || !code) return;

                $('#coupon-code').val(code);

                // đóng modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('couponModal'));
                modal.hide();

                // trigger apply
                $('#apply-coupon').trigger('click');
            });

            // Áp dụng voucher
            $('#apply-coupon').click(function() {
                const couponCode = $('#coupon-code').val().trim();
                if (!couponCode) {
                    $('#coupon-message').removeClass('text-success').addClass('text-danger')
                        .text("Vui lòng nhập mã giảm giá");
                    return;
                }

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
                                .discount) + " đ");
                            $('#total-amount').text(new Intl.NumberFormat().format(res
                                .total_after_discount) + " đ");
                            $('#coupon_id').val(res.coupon_id || '');
                        }
                    },
                    error: function(xhr) {
                        const err = xhr.responseJSON?.error || "Lỗi không xác định";
                        $('#coupon-message').removeClass('text-success').addClass('text-danger')
                            .text(err);
                    }
                });
            });

            /* ============================================================
            4) THỰC HIỆN THANH TOÁN
            ============================================================ */
            const form = document.getElementById('checkout-form');
            const button = document.getElementById('order_button_cash');
            const totalAmount = {{ $totalPrice + 25000 }};

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                button.disabled = true;
                button.innerHTML =
                    `<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...`;

                const method = document.querySelector('input[name="payment_method"]:checked').value;
                const formData = new FormData(form);
                formData.append('amount', totalAmount);

                try {

                    /* ================= PAYPAL ================= */
                    if (method === 'paypal') {
                        const res = await fetch('{{ route('checkout.paypal') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        });

                        const data = await res.json();
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            alert('Không thể khởi tạo thanh toán PayPal.');
                        }
                    }

                    /* ================= MOMO ================= */
                    else if (method === 'momo') {

                        // 1. Tạo đơn hàng
                        const res = await fetch('{{ route('checkout.placeOrder') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        });

                        const data = await res.json();
                        if (!data.redirect) {
                            alert('Không thể tạo đơn hàng cho MoMo.');
                            return;
                        }

                        // 2. Gọi MoMo
                        const params = new URLSearchParams(data.redirect.split('?')[1]);
                        const momoForm = new FormData();
                        momoForm.append('order_id', params.get('order_id'));
                        momoForm.append('amount', params.get('amount'));

                        const momoRes = await fetch('{{ route('checkout.momo') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: momoForm
                        });

                        const momoData = await momoRes.json();
                        if (momoData.redirect_url) {
                            window.location.href = momoData.redirect_url;
                        } else {
                            alert('Không thể khởi tạo thanh toán MoMo.');
                        }
                    }

                    /* ================= VNPAY ================= */
                    else if (method === 'vnpay') {
                        const res = await fetch('{{ route('checkout.placeOrder') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: new FormData(form)
                        });

                        const data = await res.json();

                        if (data.error) {
                            alert(data.error);
                            return;
                        }

                        // Redirect sang handleVnpay
                        window.location.href = "{{ route('checkout.vnpay') }}" + "?order_id=" + data
                            .order_id;
                    }

                    /* ================= COD ================= */
                    else {
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
