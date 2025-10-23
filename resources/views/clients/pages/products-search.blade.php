@extends('layouts.client')

@section('title', 'Tìm kiếm sản phẩm')

@section('breadcrumb', 'Tìm kiếm sản phẩm')

@section('content')

    <!-- PRODUCT DETAILS AREA START -->
    <div class="ltn__product-area ltn__product-gutter mb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="liton_product_grid">
                            <div class="ltn__product-tab-content-inner ltn__product-grid-view">
                                <h2 class="mb-4 text-center">
                                    @if (!empty($query))
                                        Kết quả tìm kiếm cho: <strong>"{{ $query }}"</strong>
                                    @else
                                        Tìm kiếm sản phẩm
                                    @endif
                                </h2>
                                <div class="row">

                                    <!-- ltn__product-item -->
                                    @if (!empty($query) && $products->count() > 0)
                                        <div class="row">
                                            @foreach ($products as $product)
<div class="col-xl-3 col-lg-4 col-sm-6 col-6">
    <div class="ltn__product-item ltn__product-item-3 text-center">
        <div class="product-img">
            <a href="{{ route('products.detail', $product->slug) }}">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
            </a>
            <div class="product-hover-action">
                <ul>
                    <li>
                        <a href="{{ route('products.detail', $product->slug) }}" title="Xem chi tiết">
                            <i class="far fa-eye"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" title="Thêm vào giỏ hàng">
                            <i class="fas fa-shopping-cart"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" title="Yêu thích">
                            <i class="far fa-heart"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="product-info">
            <h2 class="product-title">
                <a href="{{ route('products.detail', $product->slug) }}">{{ $product->name }}</a>
            </h2>
            <div class="product-price">
                <span>{{ number_format($product->price, 0, ',', '.') }}₫</span>
            </div>
        </div>
    </div>
</div>
                                            @endforeach
                                        </div>
                                    @elseif(!empty($query))
                                        <div class="text-center">
                                            <p>Không tìm thấy sản phẩm nào phù hợp với từ khóa
                                                "<strong>{{ $query }}</strong>".</p>
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <p>Vui lòng nhập từ khóa để tìm kiếm sản phẩm.</p>
                                        </div>
                                    @endif
                                </div>
                                <!--  -->
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="liton_product_list">
                        <div class="ltn__product-tab-content-inner ltn__product-list-view">
                            <div class="row">
                                <!-- ltn__product-item -->
                                <div class="col-lg-12">
                                    <div class="ltn__product-item ltn__product-item-3">
                                        <div class="product-img">
                                            <a href="product-details.html"><img src="img/product/1.png" alt="#"></a>
                                            <div class="product-badge">
                                                <ul>
                                                    <li class="sale-badge">New</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-info">
                                            <h2 class="product-title"><a href="product-details.html">Vegetables
                                                    Juices</a></h2>
                                            <div class="product-ratting">
                                                <ul>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star-half-alt"></i></a>
                                                    </li>
                                                    <li><a href="#"><i class="far fa-star"></i></a></li>
                                                </ul>
                                            </div>
                                            <div class="product-price">
                                                <span>$165.00</span>
                                                <del>$1720.00</del>
                                            </div>
                                            <div class="product-brief">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                                    Recusandae asperiores sit odit nesciunt, aliquid, deleniti
                                                    non et ut dolorem!</p>
                                            </div>
                                            <div class="product-hover-action">
                                                <ul>
                                                    <li>
                                                        <a href="#" title="Quick View" data-bs-toggle="modal"
                                                            data-bs-target="#quick_view_modal">
                                                            <i class="far fa-eye"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="Add to Cart" data-bs-toggle="modal"
                                                            data-bs-target="#add_to_cart_modal">
                                                            <i class="fas fa-shopping-cart"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="Wishlist" data-bs-toggle="modal"
                                                            data-bs-target="#liton_wishlist_modal">
                                                            <i class="far fa-heart"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ltn__product-item -->
                                <div class="col-lg-12">
                                    <div class="ltn__product-item ltn__product-item-3">
                                        <div class="product-img">
                                            <a href="product-details.html"><img src="img/product/2.png" alt="#"></a>
                                        </div>
                                        <div class="product-info">
                                            <h2 class="product-title"><a href="product-details.html">Poltry Farm
                                                    Meat</a></h2>
                                            <div class="product-ratting">
                                                <ul>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star-half-alt"></i></a>
                                                    </li>
                                                    <li><a href="#"><i class="far fa-star"></i></a></li>
                                                </ul>
                                            </div>
                                            <div class="product-price">
                                                <span>$165.00</span>
                                                <del>$1720.00</del>
                                            </div>
                                            <div class="product-brief">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                                    Recusandae asperiores sit odit nesciunt, aliquid, deleniti
                                                    non et ut dolorem!</p>
                                            </div>
                                            <div class="product-hover-action">
                                                <ul>
                                                    <li>
                                                        <a href="#" title="Quick View" data-bs-toggle="modal"
                                                            data-bs-target="#quick_view_modal">
                                                            <i class="far fa-eye"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="Add to Cart" data-bs-toggle="modal"
                                                            data-bs-target="#add_to_cart_modal">
                                                            <i class="fas fa-shopping-cart"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="Wishlist" data-bs-toggle="modal"
                                                            data-bs-target="#liton_wishlist_modal">
                                                            <i class="far fa-heart"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ltn__product-item -->
                                <div class="col-lg-12">
                                    <div class="ltn__product-item ltn__product-item-3">
                                        <div class="product-img">
                                            <a href="product-details.html"><img src="img/product/3.png"
                                                    alt="#"></a>
                                            <div class="product-badge">
                                                <ul>
                                                    <li class="sale-badge">New</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-info">
                                            <h2 class="product-title"><a href="product-details.html">Vegetables
                                                    Juices</a></h2>
                                            <div class="product-ratting">
                                                <ul>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star-half-alt"></i></a>
                                                    </li>
                                                    <li><a href="#"><i class="far fa-star"></i></a></li>
                                                </ul>
                                            </div>
                                            <div class="product-price">
                                                <span>$165.00</span>
                                                <del>$1720.00</del>
                                            </div>
                                            <div class="product-brief">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                                    Recusandae asperiores sit odit nesciunt, aliquid, deleniti
                                                    non et ut dolorem!</p>
                                            </div>
                                            <div class="product-hover-action">
                                                <ul>
                                                    <li>
                                                        <a href="#" title="Quick View" data-bs-toggle="modal"
                                                            data-bs-target="#quick_view_modal">
                                                            <i class="far fa-eye"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="Add to Cart" data-bs-toggle="modal"
                                                            data-bs-target="#add_to_cart_modal">
                                                            <i class="fas fa-shopping-cart"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="Wishlist" data-bs-toggle="modal"
                                                            data-bs-target="#liton_wishlist_modal">
                                                            <i class="far fa-heart"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ltn__product-item -->
                                <div class="col-lg-12">
                                    <div class="ltn__product-item ltn__product-item-3">
                                        <div class="product-img">
                                            <a href="product-details.html"><img src="img/product/4.png"
                                                    alt="#"></a>
                                        </div>
                                        <div class="product-info">
                                            <h2 class="product-title"><a href="product-details.html">Red Hot
                                                    Tomato</a></h2>
                                            <div class="product-ratting">
                                                <ul>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star-half-alt"></i></a>
                                                    </li>
                                                    <li><a href="#"><i class="far fa-star"></i></a></li>
                                                </ul>
                                            </div>
                                            <div class="product-price">
                                                <span>$165.00</span>
                                                <del>$1720.00</del>
                                            </div>
                                            <div class="product-brief">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                                    Recusandae asperiores sit odit nesciunt, aliquid, deleniti
                                                    non et ut dolorem!</p>
                                            </div>
                                            <div class="product-hover-action">
                                                <ul>
                                                    <li>
                                                        <a href="#" title="Quick View" data-bs-toggle="modal"
                                                            data-bs-target="#quick_view_modal">
                                                            <i class="far fa-eye"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="Add to Cart" data-bs-toggle="modal"
                                                            data-bs-target="#add_to_cart_modal">
                                                            <i class="fas fa-shopping-cart"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="Wishlist" data-bs-toggle="modal"
                                                            data-bs-target="#liton_wishlist_modal">
                                                            <i class="far fa-heart"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ltn__product-item -->
                                <div class="col-lg-12">
                                    <div class="ltn__product-item ltn__product-item-3">
                                        <div class="product-img">
                                            <a href="product-details.html"><img src="img/product/5.png"
                                                    alt="#"></a>
                                            <div class="product-badge">
                                                <ul>
                                                    <li class="sale-badge">Hot</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-info">
                                            <h2 class="product-title"><a href="product-details.html">Orange
                                                    Sliced Mix</a></h2>
                                            <div class="product-ratting">
                                                <ul>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star-half-alt"></i></a>
                                                    </li>
                                                    <li><a href="#"><i class="far fa-star"></i></a></li>
                                                </ul>
                                            </div>
                                            <div class="product-price">
                                                <span>$165.00</span>
                                                <del>$1720.00</del>
                                            </div>
                                            <div class="product-brief">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                                    Recusandae asperiores sit odit nesciunt, aliquid, deleniti
                                                    non et ut dolorem!</p>
                                            </div>
                                            <div class="product-hover-action">
                                                <ul>
                                                    <li>
                                                        <a href="#" title="Quick View" data-bs-toggle="modal"
                                                            data-bs-target="#quick_view_modal">
                                                            <i class="far fa-eye"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="Add to Cart" data-bs-toggle="modal"
                                                            data-bs-target="#add_to_cart_modal">
                                                            <i class="fas fa-shopping-cart"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="Wishlist" data-bs-toggle="modal"
                                                            data-bs-target="#liton_wishlist_modal">
                                                            <i class="far fa-heart"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ltn__product-item -->
                                <div class="col-lg-12">
                                    <div class="ltn__product-item ltn__product-item-3">
                                        <div class="product-img">
                                            <a href="product-details.html"><img src="img/product/6.png"
                                                    alt="#"></a>
                                            <div class="product-badge">
                                                <ul>
                                                    <li class="sale-badge">New</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-info">
                                            <h2 class="product-title"><a href="product-details.html">Orange
                                                    Sliced Mix</a></h2>
                                            <div class="product-ratting">
                                                <ul>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star-half-alt"></i></a>
                                                    </li>
                                                    <li><a href="#"><i class="far fa-star"></i></a></li>
                                                </ul>
                                            </div>
                                            <div class="product-price">
                                                <span>$165.00</span>
                                                <del>$1720.00</del>
                                            </div>
                                            <div class="product-brief">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                                    Recusandae asperiores sit odit nesciunt, aliquid, deleniti
                                                    non et ut dolorem!</p>
                                            </div>
                                            <div class="product-hover-action">
                                                <ul>
                                                    <li>
                                                        <a href="#" title="Quick View" data-bs-toggle="modal"
                                                            data-bs-target="#quick_view_modal">
                                                            <i class="far fa-eye"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="Add to Cart" data-bs-toggle="modal"
                                                            data-bs-target="#add_to_cart_modal">
                                                            <i class="fas fa-shopping-cart"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" title="Wishlist" data-bs-toggle="modal"
                                                            data-bs-target="#liton_wishlist_modal">
                                                            <i class="far fa-heart"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ltn__pagination-area text-center">
                    <div class="ltn__pagination">
                        <ul>
                            <li><a href="#"><i class="fas fa-angle-double-left"></i></a></li>
                            <li><a href="#">1</a></li>
                            <li class="active"><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">...</a></li>
                            <li><a href="#">10</a></li>
                            <li><a href="#"><i class="fas fa-angle-double-right"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- PRODUCT DETAILS AREA END -->
@endsection
