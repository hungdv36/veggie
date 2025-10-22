@extends('layouts.client')

@section('title', 'Giỏ hàng')

@section('breadcrumb', 'Giỏ hàng')

@section('content')
    <!-- SHOPING CART AREA START -->
    <div class="liton__shoping-cart-area mb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping-cart-inner">
                        <div class="shoping-cart-table table-responsive">
                            <table class="table">
                                <tbody>
                                    @php
                                        $cartTotal = 0;
                                    @endphp
                                    @forelse ($cartItems as $item)
                                        @php
                                            $subtotal = $item['price'] * $item['quantity'];
                                            $cartTotal = $cartTotal ?? 0;
                                            $cartTotal += $subtotal;
                                        @endphp
                                        <tr>
                                            <td class="cart-product-remove">
                                                <button class="remove-from-cart" data-id="{{ $item['product_id'] }}"
                                                    data-variant-id="{{ $item['variant_id'] ?? 0 }}">x</button>
                                            </td>
                                            <td class="cart-product-image">
                                                <a href="#">
                                                    <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}">
                                                </a>
                                            </td>
                                            <td class="cart-product-info">
                                                <h4><a href="javascript:void(0)">{{ $item['name'] }}</a></h4>
                                                @if ($item['color_name'] || $item['size_name'])
                                                    <p class="product-variant">
                                                        @if ($item['color_name'])
                                                            Màu: {{ $item['color_name'] }}
                                                        @endif
                                                        @if ($item['size_name'])
                                                            | Size: {{ $item['size_name'] }}
                                                        @endif
                                                    </p>
                                                @endif
                                            </td>
                                            <td class="cart-product-quantity">
                                                <div class="cart-plus-minus">
                                                    <div class="dec qtybutton">-</div>
                                                    <input type="text" value="{{ $item['quantity'] }}"
                                                        class="cart-plus-minus-box" readonly data-max="{{ $item['stock'] }}"
                                                        data-id="{{ $item['product_id'] }}"
                                                        data-variant-id="{{ $item['variant_id'] ?? 0 }}">
                                                    <div class="inc qtybutton">+</div>
                                                </div>
                                            </td>

                                            <td class="cart-product-subtotal" data-price="{{ $item['price'] }}">
                                                {{ number_format($subtotal, 0, ',', '.') }}đ
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Giỏ hàng trống</td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                        @if (!empty($cartItems))
                            <div class="shoping-cart-total mt-50">
                                <h4>Tổng giỏ hàng</h4>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>Tổng tiền hàng</td>
                                            <td id="cart-total" data-total="{{ $cartTotal }}">
                                                {{ number_format($cartTotal, 0, ',', '.') }}đ
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Phí vận chuyển</td>
                                            <td id="shipping-fee" data-fee="25000">
                                                25.000 đ
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tổng thanh toán</strong></td>
                                            <td id="cart-grand-total">
                                                <strong>{{ number_format($cartTotal + 25000, 0, ',', '.') }}đ</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="btn-wrapper text-right text-end">
                                    <a href="checkout.html" class="theme-btn-1 btn btn-effect-1">Tiến hành thanh toán</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SHOPING CART AREA END -->
@endsection
