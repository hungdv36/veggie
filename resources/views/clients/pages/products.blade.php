@extends('layouts.client_home')

@section('title', 'Sản phẩm')

@section('breadcrumb', 'Sản phẩm')

@section('content')
<div class="ltn__product-area ltn__product-gutter">
    <div class="container">
        <div class="row">
            <!-- DANH SÁCH SẢN PHẨM -->
            <div class="col-lg-8 order-lg-2 mb-120">
                <div class="ltn__shop-options">
                    <ul>
                        <li>
                            <div class="ltn__grid-list-tab-menu">
                                <div class="nav">
                                    <a class="active show" data-bs-toggle="tab" href="#liton_product_grid">
                                        <i class="fas fa-th-large"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="short-by text-center">
                                <select id="sort-by" class="nice-select">
                                    <option value="default">Sắp xếp mặc định</option>
                                    <option value="latest">Sắp xếp theo hàng mới</option>
                                    <option value="price_asc">Giá: thấp đến cao</option>
                                    <option value="price_desc">Giá: cao đến thấp</option>
                                </select>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">
                    <div id="liton_product_grid" class="tab-pane fade active show">
                        @include('clients.components.products_grid', ['products' => $products])
                    </div>
                </div>

                <div class="ltn__pagination-area text-center">
                    <div class="ltn__pagination">
                        {!! $products->links('clients.components.pagination.pagination_custom') !!}
                    </div>
                </div>
            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-4 mb-120">
                <aside class="sidebar ltn__shop-sidebar">

                    <!-- DANH MỤC -->
                    <div class="widget ltn__menu-widget">
                        <h4 class="ltn__widget-title ltn__widget-title-border">Danh mục sản phẩm</h4>
                        <ul>
                            @foreach ($categories as $category)
                                <li>
                                    <a href="javascript:void(0)" class="category-filter" data-id="{{ $category->id }}">
                                        {{ $category->name }}
                                        <span><i class="fas fa-long-arrow-alt-right"></i></span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- LỌC GIÁ -->
                    <div class="widget ltn__price-filter-widget">
                        <h4 class="ltn__widget-title ltn__widget-title-border">Lọc theo giá</h4>
                        <div class="price_filter">
                            <div class="price_slider_amount">
                                <input type="text" class="amount" name="price" placeholder="Nhập khoảng giá..." />
                            </div>
                            <div class="slider-range"></div>
                        </div>
                    </div>

                    <!-- TÌM KIẾM -->
                    <div class="widget ltn__search-widget">
                        <h4 class="ltn__widget-title ltn__widget-title-border">Tìm kiếm</h4>
                        <form action="#">
                            <input type="text" name="search" placeholder="Tìm kiếm sản phẩm...">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>

                    <!-- SẢN PHẨM ĐƯỢC ĐÁNH GIÁ CAO -->
                    <div class="widget ltn__top-rated-product-widget">
                        <h4 class="ltn__widget-title ltn__widget-title-border">Sản phẩm được đánh giá cao</h4>
                        <ul>
                            @foreach ($topRatedProducts as $item)
                                <li>
                                    <div class="top-rated-product-item clearfix">
                                        <div class="top-rated-product-img">
                                            <a href="{{ route('products.detail', $item->slug) }}">
                                                <img src="{{ $item->firstImage ? asset('storage/uploads/' . $item->firstImage->image) : asset('storage/uploads/products/no-image.png') }}"
                                                    alt="{{ $item->name }}">
                                            </a>
                                        </div>
                                        <div class="top-rated-product-info">
                                            <div class="product-ratting">
                                                <ul>
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <li>
                                                            <i class="fas fa-star {{ $i <= round($item->avg_rating) ? '' : 'text-muted' }}"></i>
                                                        </li>
                                                    @endfor
                                                </ul>
                                            </div>
                                            <h6>
                                                <a href="{{ route('products.detail', $item->slug) }}">{{ $item->name }}</a>
                                            </h6>
                                            <div class="product-price">
                                                <span>{{ number_format($item->price, 0, ',', '.') }}₫</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>
    <div class="ltn__product-area ltn__product-gutter">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 order-lg-2 mb-120">
                    <div class="ltn__shop-options">
                        <ul>
                            <li>
                                <div class="ltn__grid-list-tab-menu ">
                                    <div class="nav">
                                        <a class="active show" data-bs-toggle="tab" href="#liton_product_grid"><i
                                                class="fas fa-th-large"></i></a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="short-by text-center">
                                    <select id="sort-by" class="nice-select">
                                        <option value="default">Sắp xếp mặc định</option>
                                        <option value="latest">Sắp xếp theo hàng mới</option>
                                        <option value="price_asc">Sắp xếp theo giá: thấp đến cao</option>
                                        <option value="price_desc">Sắp xếp theo giá: cao đến thấp</option>
                                    </select>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div id="loading-spinner">
                            <div class="loader"></div>
                        </div>
                        <div class="tab-pane fade active show" id="liton_product_grid">
                            @include('clients.components.products_grid', ['products' => $products])
                        </div>
                    </div>
                    <div class="ltn__pagination-area text-center">
                        <div class="ltn__pagination">
                            {!! $products->links('clients.components.pagination.pagination_custom') !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4  mb-120">
                    <aside class="sidebar ltn__shop-sidebar">
                        <!-- Category Widget -->
                        <div class="widget ltn__menu-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Danh mục sản phẩm</h4>
                            <ul>
                                @foreach ($categories as $category)
                                    <li>
                                        <a href="javascript:void(0)" class="category-filter"
                                            data-id="{{ $category->id }}">{{ $category->name }} <span><i
                                                    class="fas fa-long-arrow-alt-right"></i></span></a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- Price Filter Widget -->
                        <div class="widget ltn__price-filter-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Lọc theo giá</h4>
                            <div class="price_filter">
                                <div class="price_slider_amount">
                                    <input type="submit" value="Your range:" />
                                    <input type="text" class="amount" name="price" placeholder="Add Your Price" />
                                </div>
                                <div class="slider-range"></div>
                            </div>
                        </div>
                        <!-- Search Widget -->
                        <div class="widget ltn__search-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Tìm kiếm</h4>
                            <form action="#">
                                <input type="text" name="search" placeholder="Tìm kiếm từ khóa của bạn...">
                                <button type="submit"><i class="fas fa-search"></i></button>
                            </form>
                        </div>
                        <div class="widget ltn__top-rated-product-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Sản phẩm được đánh giá cao</h4>
                            <ul>
                                @foreach ($topRatedProducts as $item)
                                    <li>
                                        <div class="top-rated-product-item clearfix">
                                            <div class="top-rated-product-img">
                                                <a href="{{ route('products.detail', $item->slug) }}">
                                                    <img src="{{ $item->firstImage ? asset('storage/uploads/' . $item->firstImage->image) : asset('storage/uploads/products/no-image.png') }}"
                                                        alt="#">
                                                </a>
                                            </div>
                                            <div class="top-rated-product-info">
                                                <div class="product-ratting">
                                                    <ul>
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <li><i
                                                                    class="fas fa-star {{ $i <= round($item->avg_rating) ? '' : 'text-muted' }}"></i>
                                                            </li>
                                                        @endfor
                                                    </ul>
                                                </div>
                                                <h6><a
                                                        href="{{ route('products.detail', $item->slug) }}">{{ $item->name }}</a>
                                                </h6>
                                                <div class="product-price">
                                                    <span>{{ number_format($item->price, 0, ',', '.') }}₫</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    </aside>
                </div>
            </div>
        </div>
    </div>
                                    
                            </div>                           
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    <div class="ltn__product-area ltn__product-gutter">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 order-lg-2 mb-120">
                    <div class="ltn__shop-options">
                        <ul>
                            <li>
                                <div class="ltn__grid-list-tab-menu ">
                                    <div class="nav">
                                        <a class="active show" data-bs-toggle="tab" href="#liton_product_grid"><i
                                                class="fas fa-th-large"></i></a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="short-by text-center">
                                    <select id="sort-by" class="nice-select">
                                        <option value="default">Sắp xếp mặc định</option>
                                        <option value="latest">Sắp xếp theo hàng mới</option>
                                        <option value="price_asc">Sắp xếp theo giá: thấp đến cao</option>
                                        <option value="price_desc">Sắp xếp theo giá: cao đến thấp</option>
                                    </select>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div id="loading-spinner">
                            <div class="loader"></div>
                        </div>
                        <div class="tab-pane fade active show" id="liton_product_grid">
                            @include('clients.components.products_grid', ['products' => $products])
                        </div>
                    </div>
                    <div class="ltn__pagination-area text-center">
                        <div class="ltn__pagination">
                            {!! $products->links('clients.components.pagination.pagination_custom') !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4  mb-120">
                    <aside class="sidebar ltn__shop-sidebar">
                        <!-- Category Widget -->
                        <div class="widget ltn__menu-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Danh mục sản phẩm</h4>
                            <ul>
                                @foreach ($categories as $category)
                                    <li>
                                        <a href="javascript:void(0)" class="category-filter"
                                            data-id="{{ $category->id }}">{{ $category->name }} <span><i
                                                    class="fas fa-long-arrow-alt-right"></i></span></a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- Price Filter Widget -->
                        <div class="widget ltn__price-filter-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Lọc theo giá</h4>
                            <div class="price_filter">
                                <div class="price_slider_amount">
                                    <input type="submit" value="Your range:" />
                                    <input type="text" class="amount" name="price" placeholder="Add Your Price" />
                                </div>
                                <div class="slider-range"></div>
                            </div>
                        </div>
                        <!-- Top Rated Product Widget -->
                        <div class="widget ltn__top-rated-product-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Sản phẩm hàng đầu</h4>
                            <ul>
                                <li>
                                    <div class="top-rated-product-item clearfix">
                                        <div class="top-rated-product-img">
                                            <a href="#"><img src="img/product/1.png" alt="#"></a>
                                        </div>
                                        <div class="top-rated-product-info">
                                            <div class="product-ratting">
                                                <ul>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                </ul>
                                            </div>
                                            <h6><a href="product-details.html">Mixel Solid Seat Cover</a></h6>
                                            <div class="product-price">
                                                <span>$49.00</span>
                                                <del>$65.00</del>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                        </div>
                        <!-- Search Widget -->
                        <div class="widget ltn__search-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Tìm kiếm</h4>
                            <form method="GET" action="{{ route('search.index') }}">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Tìm kiếm..." />

                                <button type="submit"><i class="fas fa-search"></i></button>
                            </form>

                        </div>
                        <!-- Tagcloud Widget -->
                        <div class="widget ltn__tagcloud-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Popular Tags</h4>
                            <ul>
                                <li><a href="#">Popular</a></li>
                                <li><a href="#">desgin</a></li>
                                <li><a href="#">ux</a></li>
                                <li><a href="#">usability</a></li>
                                <li><a href="#">develop</a></li>
                                <li><a href="#">icon</a></li>
                                <li><a href="#">Car</a></li>
                                <li><a href="#">Service</a></li>
                                <li><a href="#">Repairs</a></li>
                                <li><a href="#">Auto Parts</a></li>
                                <li><a href="#">Oil</a></li>
                                <li><a href="#">Dealer</a></li>
                                <li><a href="#">Oil Change</a></li>
                                <li><a href="#">Body Color</a></li>
                            </ul>
                        </div>
                        <!-- Size Widget -->
                        <div class="widget ltn__tagcloud-widget ltn__size-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Product Size</h4>
                            <ul>
                                <li><a href="#">S</a></li>
                                <li><a href="#">M</a></li>
                                <li><a href="#">L</a></li>
                                <li><a href="#">XL</a></li>
                                <li><a href="#">XXL</a></li>
                            </ul>
                        </div>
                        <!-- Color Widget -->
                        <div class="widget ltn__color-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Product Color</h4>
                            <ul>
                                <li class="black"><a href="#"></a></li>
                                <li class="white"><a href="#"></a></li>
                                <li class="red"><a href="#"></a></li>
                                <li class="silver"><a href="#"></a></li>
                                <li class="gray"><a href="#"></a></li>
                                <li class="maroon"><a href="#"></a></li>
                                <li class="yellow"><a href="#"></a></li>
                                <li class="olive"><a href="#"></a></li>
                                <li class="lime"><a href="#"></a></li>
                                <li class="green"><a href="#"></a></li>
                                <li class="aqua"><a href="#"></a></li>
                                <li class="teal"><a href="#"></a></li>
                                <li class="blue"><a href="#"></a></li>
                                <li class="navy"><a href="#"></a></li>
                                <li class="fuchsia"><a href="#"></a></li>
                                <li class="purple"><a href="#"></a></li>
                                <li class="pink"><a href="#"></a></li>
                                <li class="nude"><a href="#"></a></li>
                                <li class="orange"><a href="#"></a></li>

                                <li><a href="#" class="orange"></a></li>
                                <li><a href="#" class="orange"></a></li>
                            </ul>
                        </div>
                        <!-- Banner Widget -->
                        <div class="widget ltn__banner-widget">
                            <a href="{{ route('products.index') }}"><img
                                    src="{{ asset('assets/clients/img/banner/banner-1.jpg') }}" alt="#"></a>
                        </div>

                    </aside>
                </div>
            </div>
        </div>
    </div>
@endsection

