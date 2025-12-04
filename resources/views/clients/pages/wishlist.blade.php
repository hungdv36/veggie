@extends('layouts.client')

@section('title', 'Yêu thích')
@section('breadcrumb', 'Yêu thích')

@section('content')
<style>
    .wishlist-container {
        background: #fff;
        border-radius: 14px;
        padding: 30px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.07);
    }

    .wishlist-item {
        display: flex;
        align-items: center;
        padding: 18px 0;
        border-bottom: 1px solid #f1f1f1;
    }

    .wishlist-item:last-child {
        border-bottom: none;
    }

    .wishlist-image img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 12px;
        transition: 0.3s;
    }

    .wishlist-image img:hover {
        transform: scale(1.05);
    }

    .wishlist-info h4 {
        font-size: 18px;
        margin-bottom: 6px;
        font-weight: 600;
    }

    .wishlist-info p {
        margin: 0;
        color: #777;
    }

    .wishlist-price {
        font-size: 18px;
        color: #ff4d4f;
        font-weight: 600;
    }

    .wishlist-actions .btn {
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 14px;
        margin-right: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: 0.25s;
    }

    .wishlist-actions .btn i {
        font-size: 16px;
    }

    .btn-add-cart {
        background: #2ecc71;
        color: #fff;
    }

    .btn-add-cart:hover {
        background: #27ae60;
    }

    .btn-remove {
        background: #e74c3c;
        color: #fff;
    }

    .btn-remove:hover {
        background: #c0392b;
    }

    @media (max-width: 768px) {
        .wishlist-item {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }
        .wishlist-actions {
            display: flex;
            justify-content: center;
        }
    }
</style>

<div class="container mb-120">
    <div class="wishlist-container">
        @foreach ($wishlistItems as $item)
            <div class="wishlist-item">

                <!-- Image -->
                <div class="wishlist-image">
                    <a href="{{ route('products.detail', $item->product->slug) }}">
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                    </a>
                </div>

                <!-- Info -->
                <div class="wishlist-info flex-grow-1 ms-4">
                    <h4>{{ $item->product->name }}</h4>
                    <p>Thương hiệu: {{ $item->product->brand ?? 'Không có' }}</p>
                </div>

                <!-- Price -->
                <div class="wishlist-price me-4">
                    {{ number_format($item->product->price, 0, ',', '.') }}đ
                </div>

                <!-- Actions -->
                <div class="wishlist-actions">

                    <a href="javascript:void(0)"
                       class="btn btn-add-cart add-to-cart-btn"
                       data-id="{{ $item->product->id }}"
                       title="Thêm vào giỏ hàng">
                        <i class="fas fa-shopping-cart"></i> Thêm
                    </a>

                    <a href="javascript:void(0)"
                       class="btn btn-remove remove-from-wishlist"
                       data-id="{{ $item->product->id }}"
                       title="Xóa khỏi danh sách yêu thích">
                        <i class="far fa-trash-alt"></i> Xóa
                    </a>

                </div>

            </div>
        @endforeach
    </div>
</div>
@endsection
