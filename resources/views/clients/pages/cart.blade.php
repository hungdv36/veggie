@extends('layouts.client')

@section('title', 'Giỏ hàng')

@section('breadcrumb', 'Giỏ hàng')

@section('content')
    <!-- SHOPPING CART AREA START -->
    <div class="liton__shoping-cart-area py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h4 class="mb-0"><i class="fa fa-shopping-cart me-2 text-success"></i> Giỏ hàng của bạn</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th></th>
                                            <th>Sản phẩm</th>
                                            <th class="text-center">Số lượng</th>
                                            <th class="text-end">Tạm tính</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $cartTotal = 0; @endphp
                                        @forelse ($cartItems as $item)
                                            @php
                                                $subtotal = $item['price'] * $item['quantity'];
                                                $cartTotal += $subtotal;
                                            @endphp
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <button class="btn btn-sm btn-outline-danger remove-from-cart"
                                                        data-id="{{ $item['product_id'] }}"
                                                        data-variant-id="{{ $item['variant_id'] ?? 0 }}">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3" style="width: 70px;">
                                                            <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}"
                                                                class="img-fluid rounded">
                                                        </div>
                                                        <div>
                                                            <h6 class="fw-bold mb-1">{{ $item['name'] }}</h6>
                                                            @if ($item['color_name'] || $item['size_name'])
                                                                <small class="text-muted">
                                                                    @if ($item['color_name'])
                                                                        Màu: {{ $item['color_name'] }}
                                                                    @endif
                                                                    @if ($item['size_name'])
                                                                        | Size: {{ $item['size_name'] }}
                                                                    @endif
                                                                </small>
                                                            @endif
                                                            <div class="text-success fw-semibold mt-1">
                                                                {{ number_format($item['price'], 0, ',', '.') }}đ
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div
                                                        class="cart-plus-minus d-inline-flex align-items-center border rounded-pill px-2">
                                                        <div class="dec qtybutton px-2 text-secondary">−</div>

                                                        <input type="number" value="{{ $item['quantity'] }}"
                                                            class="cart-plus-minus-box border-0 text-center bg-transparent fw-bold cart-qty-input"
                                                            min="1" data-max="{{ $item['stock'] }}"
                                                            data-id="{{ $item['product_id'] }}"
                                                            data-variant-id="{{ $item['variant_id'] ?? 0 }}"
                                                            style="width: 55px;">

                                                        <div class="inc qtybutton px-2 text-secondary">+</div>
                                                    </div>
                                                </td>
                                                <td class="text-end fw-semibold text-dark cart-product-subtotal"
                                                    data-price="{{ $item['price'] }}">
                                                    {{ number_format($subtotal, 0, ',', '.') }}đ
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">
                                                    <i class="fa fa-shopping-basket fa-2x mb-2"></i>
                                                    <div>Giỏ hàng trống</div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if (!empty($cartItems))
                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0 position-sticky" style="top: 90px; z-index: 10;">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0 d-flex align-items-center">
                                    <i class="fa fa-credit-card me-2 text-success"></i>
                                    Tổng giỏ hàng
                                </h5>
                            </div>
                            <div class="card-body">
                                <table class="table mb-3">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted">Tổng tiền hàng</td>
                                            <td class="text-end fw-semibold" id="cart-total"
                                                data-total="{{ $cartTotal }}">
                                                {{ number_format($cartTotal, 0, ',', '.') }}đ
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <a href="{{ route('checkout') }}"
                                    class="btn btn-success w-100 py-2 fw-semibold d-flex justify-content-center align-items-center">
                                    Tiến hành thanh toán
                                    <i class="fa fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- SHOPPING CART AREA END -->
@endsection
