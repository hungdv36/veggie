<div class="ltn__product-tab-content-inner ltn__product-grid-view">
    <div class="row">
        @foreach ($products as $product)
            <div class="col-xl-4 col-sm-6 col-6">
                <div class="ltn__product-item ltn__product-item-3 text-center">
                    <div class="product-img">
                        <a href="{{ route('products.detail', $product->slug) }}"><img src="{{ $product->image_url }}" alt="{{ $product->name }}"></a>
                        <div class="product-hover-action">
                            <ul>
                                <li>
                                    <a href="javascript:void(0)" title="Xem nhanh" data-bs-toggle="modal"
                                        data-bs-target="#quick_view_modal-{{ $product->id }}">
                                        <i class="far fa-eye"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" title="Thêm vào giỏ hàng" data-bs-toggle="modal"
                                        data-bs-target="#add_to_cart_modal-{{ $product->id }}">
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" title="Yêu thích" data-bs-toggle="modal"
                                        data-bs-target="#liton_wishlist_modal-{{ $product->id }}">
                                        <i class="far fa-heart"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-ratting">
                            <ul>
                                <li><a href="#"><i class="fas fa-star"></i></a></li>
                            </ul>
                        </div>
                        <h2 class="product-title"><a href="{{ route('products.detail', $product->slug) }}">{{ $product->name }}</a></h2>
                        <div class="product-price">
                            <span>{{ number_format($product->price, 0 , ',', '.') }} VNĐ</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


@foreach ($products as $product)
    @include('clients.components.includes.include-modals')
@endforeach