<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

@php
    $subtotal = 0;
@endphp

<div class="ltn__utilize-menu-head">
    <span class="ltn__utilize-menu-title">Giỏ hàng</span>
    <button class="ltn__utilize-close">×</button>
</div>

<div class="mini-cart-product-area ltn__scrollbar">
    @if (!empty($cartItems) && count($cartItems) > 0)
        @foreach ($cartItems as $item)
            @php
                // Lấy dữ liệu sản phẩm
                $product = auth()->check() ? $item->product : \App\Models\Product::find($item['product_id']);
                $variant = auth()->check() ? $item->variant ?? null : $item['variant'] ?? null;
                $quantity = auth()->check() ? $item->quantity : $item['quantity'];

                // ===== GIÁ GỐC =====
                $basePrice = $variant->price ?? $product->price;

                // ===== SALE THƯỜNG =====
                $salePrice = $variant->sale_price ?? $product->sale_price;

                // ===== FLASH SALE =====
                $flashSale = \App\Models\FlashSale::with('items')
                    ->where('start_time', '<=', now())
                    ->where('end_time', '>=', now())
                    ->first();

                $flashItem = $flashSale ? $flashSale->items->firstWhere('product_id', $product->id) : null;

                // ===== CHỌN GIÁ CUỐI CÙNG =====
                if ($flashItem) {
                    $price = round($basePrice * (1 - $flashItem->discount_price / 100));
                } else {
                    $price = $salePrice && $salePrice > 0 ? $salePrice : $basePrice;
                }

                // Tính subtotal
                $subtotal += $price * $quantity;
            @endphp

            <div class="mini-cart-item clearfix">
                <div class="mini-cart-img">
                    <a href="javascript:void(0)">
                        <img src="{{ $product->image ? asset('assets/admin/img/product/' . $product->image) : asset('assets/img/product/default.png') }}"
                            alt="{{ $product->name }}" style="height:100px;width:100px; object-fit:cover;">
                    </a>
                    <span class="mini-cart-item-delete" data-product-id="{{ $product->id }}"
                        data-variant-id="{{ $variant->id ?? 0 }}" data-price="{{ $price }}">
                        <i class="icon-cancel"></i>
                    </span>
                </div>

                <div class="mini-cart-info d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1">
                            <a href="#">{{ $product->name }}</a>
                        </h6>

                        @if ($variant)
                            <small class="text-muted">
                                @if ($variant->color)
                                    {{ $variant->color->name }}
                                @endif
                                @if ($variant->size)
                                    , {{ $variant->size->name }}
                                @endif
                            </small>
                        @endif
                    </div>

                    <!-- HIỂN THỊ GIÁ -->
                    <span class="mini-cart-quantity">
                        {{ $quantity }} x {{ number_format($price, 0, ',', '.') }}₫
                    </span>
                </div>
            </div>
        @endforeach
    @else
        <div class="text-center p-3">
            <p>Giỏ hàng của bạn đang trống.</p>
        </div>
    @endif
</div>

<div class="mini-cart-footer">
    <div class="mini-cart-sub-total">
        <h5>Tổng cộng: <span>{{ number_format($subtotal, 0, ',', '.') }}₫</span></h5>
    </div>
    <div class="btn-wrapper">
        <a href="{{ route('cart.index') }}" class="theme-btn-1 btn btn-effect-1">Xem giỏ hàng</a>
        <a href="{{ route('checkout') }}" class="theme-btn-2 btn btn-effect-2">Thanh toán</a>
    </div>
</div>
