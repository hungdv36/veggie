@extends('layouts.client')

@section('title', 'Yêu thích')

@section('breadcrumb', 'Yêu thích')

@section('content')
    <div class="liton__shoping-cart-area mb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping-cart-inner">
                        <div class="shoping-cart-table table-responsive">
                            <table class="table">
                                <tbody>
                                    @foreach ($wishlistItems as $item)
                                        <tr>
                                            <td class="wishlist-product-remove">
                                                <button class="remove-from-wishlist" data-id="{{ $item->id }}">x</button>
                                            </td>

                                            <td class="wishlist-product-image">
                                                <a href="#">
                                                    <img src="{{ asset($item->product->image) }}"
                                                        alt="{{ $item->product->name }}">
                                                </a>
                                            </td>

                                            <td class="wishlist-product-info">
                                                <h4><a href="javascript:void(0)">{{ $item->product->name }}</a></h4>
                                                <p>Thương hiệu: {{ $item->product->brand ?? 'Không có' }}</p>
                                            </td>

                                            <td class="wishlist-product-price">
                                                {{ number_format($item->product->price, 0, ',', '.') }}đ
                                            </td>

                                            <td class="wishlist-product-actions">
                                                <!-- Nút thêm vào giỏ hàng -->
                                                <a href="javascript:void(0)"
                                                    class="theme-btn-1 btn btn-effect-1 add-to-cart-btn"
                                                    title="Thêm vào giỏ hàng" data-id="{{ $item->product->id }}">
                                                    <i class="fas fa-shopping-cart"></i>
                                                    <span>Thêm vào giỏ hàng</span>
                                                </a>

                                                <!-- Nút xóa khỏi wishlist -->
                                                <a href="javascript:void(0)"
                                                    class="theme-btn-2 btn btn-effect-2 remove-from-wishlist"
                                                    title="Xóa khỏi danh sách yêu thích" data-id="{{ $item->id }}">
                                                    <i class="far fa-trash-alt"></i>
                                                    <span>Xóa</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

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
                                        <tr>
                                            <a href="javascript:void(0)"
                                                class="theme-btn-1 btn btn-effect-1 add-to-cart-btn"
                                                title="T

                                        </tr>
                                    </tbody>
                                </table>
                                <div class="btn-wrapper
                                                text-right text-end">
                                                <a href="{{ route('checkout') }}" class="theme-btn-1 btn btn-effect-1">Tiến
                                                    hành thanh
                                                    toán</a>
                            </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
