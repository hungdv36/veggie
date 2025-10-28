@extends('layouts.client')

@section('title', 'Sản phẩm')

@section('breadcrumb', 'Sản phẩm')

@section('content')
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

    <div class="top-rated-product-img">
        <a href="#"><img src="{{ asset('img/product/1.png') }}" alt=""></a>
    </div>
    <div class="top-rated-product-info">
        <div class="product-rating">
            <ul>
                <li><i class="fas fa-star"></i></li>
                <li><i class="fas fa-star"></i></li>
                <li><i class="fas fa-star"></i></li>
                <li><i class="fas fa-star"></i></li>
                <li><i class="fas fa-star"></i></li>
            </ul>
        </div>

        @foreach ($products as $product)
            <h6>
                <a href="{{ route('products.detail', $product->slug) }}">
                    {{ $product->name }}
                </a>
            </h6>
            <div class="product-price">
                <span>${{ $product->price }}</span>
                @if ($product->old_price)
                    <del>${{ $product->old_price }}</del>
                @endif
            </div>
        @endforeach
    </div>
</div>




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
