@extends('layouts.client')

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
@endsection
