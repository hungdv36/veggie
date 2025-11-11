<div class="ltn__product-tab-content-inner ltn__product-grid-view">
    <div class="row">
        @foreach ($products as $product)
            <div class="col-xl-4 col-sm-6 col-6">
                <div class="ltn__product-item ltn__product-item-3 text-center">
                    <div class="product-img">
                        <a href="{{ route('products.detail', $product->slug) }}">
                            @if ($product->image)
                                <img src="{{ asset('assets/admin/img/product/' . $product->image) }}"
                                    alt="{{ $product->name }}" style="height:100px;width:100px; object-fit:cover;">
                            @else
                                <img src="{{ asset('assets/img/product/default.png') }}" alt="Default" width="80">
                            @endif
                        </a>
                        @if ($product->is_flash_sale ?? false)
                            <div
                                style="
        position: absolute;
        top: 8px; left: 8px;
        background: linear-gradient(45deg, #ff0000, #ff6600);
        color: white;
        font-weight: bold;
        font-size: 11px;
        padding: 2px 6px;
        border-radius: 4px;">
                                FLASH SALE
                            </div>
                        @endif

                        <div class="product-hover-action">
                            <ul>
                                <li>
                                    <a href="javascript:void(0)" title="Xem nhanh" data-bs-toggle="modal"
                                        data-bs-target="#quick_view_modal-{{ $product->id }}">
                                        <i class="far fa-eye"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" title="Thêm vào giỏ hàng" class="add-to-cart-btn"
                                        data-id="{{ $product->id }}">
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" title="Yêu thích" data-bs-toggle="modal"
                                        class="add-to-wishlist"
                                        data-id="{{ $product->id }}">
                                    <a href="javascript:void(0)" title="Yêu thích" class="add-to-wishlist"
                                        data-id="{{ $product->id }}">
                                        <i class="far fa-heart"></i>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-ratting">
                            @include('clients.components.includes.rating', [
                                'product' => $product,
                            ])
                        </div>
                        <h2 class="product-title"><a
                                href="{{ route('products.detail', $product->slug) }}">{{ $product->name }}</a></h2>
                        <div class="product-price">
                            @if ($product->is_flash_sale ?? false)
                                <span class="text-danger fw-bold">
                                    {{ number_format($product->sale_price, 0, ',', '.') }} VNĐ
                                </span>
                                <span class="text-muted text-decoration-line-through ms-2" style="font-size: 14px;">
                                    {{ number_format($product->price, 0, ',', '.') }} VNĐ
                                </span>
                                <span class="badge bg-danger ms-1">-{{ $product->discount_price }}%</span>
                            @else
                                <span>{{ number_format($product->price, 0, ',', '.') }} VNĐ</span>
                            @endif
                        </div>
@if ($product->is_flash_sale ?? false)
    <div class="flash-countdown text-danger small" 
         data-end="{{ $product->flash_end_time }}">
         ⏰ Sale kết thúc trong: <span class="time-left"></span>
    </div>
@endif

                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


@foreach ($products as $product)
    @include('clients.components.includes.include-modals')
@endforeach
