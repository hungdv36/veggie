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
                                                    title="Xóa khỏi danh sách yêu thích" data-id="{{ $item->product->id }}">
                                                    <i class="far fa-trash-alt"></i>
                                                    <span>Xóa</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
