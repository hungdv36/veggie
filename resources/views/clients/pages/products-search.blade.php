@extends('layouts.client')

@section('title', 'Tìm kiếm sản phẩm')
@section('breadcrumb', 'Tìm kiếm sản phẩm')

@section('content')

    <style>
        .search-title {
            font-size: 26px;
            font-weight: 700;
            color: #333;
        }

        .product-card {
            background: #fff;
            border-radius: 14px;
            padding: 12px;
            transition: 0.25s;
            height: 100%;
            box-shadow: 0 3px 14px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f1f1;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.08);
        }

        .product-card img {
            border-radius: 12px;
            transition: 0.3s;
        }

        .product-card img:hover {
            transform: scale(1.05);
        }

        .product-title {
            font-size: 15px;
            font-weight: 600;
            color: #333;
            height: 40px;
            overflow: hidden;
        }

        .product-price {
            color: #e74c3c;
            font-size: 18px;
            font-weight: 700;
        }

        .product-category {
            font-size: 13px;
            color: #888;
        }

        .btn-view {
            background: #3498db;
            border-radius: 10px;
            color: #fff !important;
            padding: 6px 10px;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: 0.25s;
        }

        .btn-view:hover {
            background: #2980b9;
        }

        .product-hover-action {
            opacity: 0;
            transition: 0.25s;
        }

        .product-card:hover .product-hover-action {
            opacity: 1;
        }
    </style>

    <div class="ltn__product-area ltn__product-gutter mb-120">
        <div class="container">

            <h2 class="mb-4 text-center search-title">
                @if (!empty($query))
                    Kết quả tìm kiếm cho: <strong>"{{ $query }}"</strong>
                @else
                    Tìm kiếm sản phẩm
                @endif
            </h2>

            <div class="row">

                @if (!empty($query) && $products->count() > 0)

                    @foreach ($products as $product)
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 mb-4">
                            <div class="product-card">

                                <!-- IMAGE -->
                                <div class="position-relative">
                                    <a href="{{ route('products.detail', $product->slug) }}">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                            class="img-fluid w-100">
                                    </a>

                                    <div class="product-hover-action position-absolute top-0 end-0 m-2">
                                        <a href="{{ route('products.detail', $product->slug) }}" class="btn-view">
                                            <i class="far fa-eye"></i> Xem
                                        </a>
                                    </div>
                                </div>

                                <!-- INFO -->
                                <div class="mt-3">
                                    <h6 class="product-title text-truncate">
                                        <a href="{{ route('products.detail', $product->slug) }}">{{ $product->name }}</a>
                                    </h6>

                                    <div class="product-price">
                                        {{ number_format($product->price, 0, ',', '.') }}₫
                                    </div>

                                    <div class="product-category">
                                        {{ $product->category->name ?? 'Không có danh mục' }}
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                @elseif(!empty($query))
                    <div class="text-center mt-5">
                        <p>Không tìm thấy sản phẩm nào phù hợp với từ khóa <strong>"{{ $query }}"</strong>.</p>
                    </div>
                @else
                    <div class="text-center mt-5">
                        <p>Vui lòng nhập từ khóa để tìm kiếm sản phẩm.</p>
                    </div>
                @endif

            </div>

        </div>
    </div>
    <style>
        /* Pagination container */
        .custom-pagination {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .pagination-info {
            color: #6b6b6b;
            font-size: 14px;
        }

        .pagination-list {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .pagination-list .page-item {
            list-style: none;
        }

        .pagination-list .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            border-radius: 10px;
            border: 1px solid #eef0f3;
            background: #fff;
            color: #333;
            text-decoration: none;
            font-size: 14px;
            transition: all .15s;
        }

        .pagination-list .page-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.06);
        }

        .pagination-list .active .page-link {
            background: #2d9cdb;
            color: #fff;
            border-color: #2d9cdb;
        }

        .pagination-list .disabled .page-link {
            opacity: 0.55;
            cursor: default;
        }

        .pagination-ellipsis {
            min-width: 36px;
            text-align: center;
            color: #9aa0a6;
        }

        @media (max-width: 576px) {
            .custom-pagination {
                padding: 10px;
                gap: 8px;
            }

            .pagination-info {
                font-size: 13px;
            }

            .pagination-list .page-link {
                min-width: 32px;
                height: 32px;
                font-size: 13px;
            }
        }
    </style>

    @php
        $current = $products->currentPage();
        $last = $products->lastPage();
        // window of pages around current
        $start = max(1, $current - 2);
        $end = min($last, $current + 2);
    @endphp

    <div class="custom-pagination mt-3">
        <!-- Info: Showing X to Y of Z results -->
        <div class="pagination-info">
            @if ($products->total() > 0)
                Hiển thị <strong>{{ $products->firstItem() }}</strong> đến <strong>{{ $products->lastItem() }}</strong>
                của
                <strong>{{ $products->total() }}</strong> kết quả
            @else
                Không có kết quả
            @endif
        </div>

        <!-- Pagination controls -->
        <nav aria-label="Page navigation">
            <ul class="pagination-list">

                {{-- Previous --}}
                @if ($products->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">&laquo; Prev</span></li>
                @else
                    <li class="page-item"><a class="page-link"
                            href="{{ $products->previousPageUrl() }}&query={{ urlencode($query ?? '') }}">&laquo; Prev</a>
                    </li>
                @endif

                {{-- First page + ellipsis if needed --}}
                @if ($start > 1)
                    <li class="page-item"><a class="page-link"
                            href="{{ $products->url(1) }}&query={{ urlencode($query ?? '') }}">1</a></li>
                    @if ($start > 2)
                        <li class="page-item"><span class="page-link pagination-ellipsis">…</span></li>
                    @endif
                @endif

                {{-- Page numbers window --}}
                @for ($i = $start; $i <= $end; $i++)
                    <li class="page-item {{ $i == $current ? 'active' : '' }}">
                        @if ($i == $current)
                            <span class="page-link">{{ $i }}</span>
                        @else
                            <a class="page-link"
                                href="{{ $products->url($i) }}&query={{ urlencode($query ?? '') }}">{{ $i }}</a>
                        @endif
                    </li>
                @endfor

                {{-- Last page + ellipsis if needed --}}
                @if ($end < $last)
                    @if ($end < $last - 1)
                        <li class="page-item"><span class="page-link pagination-ellipsis">…</span></li>
                    @endif
                    <li class="page-item"><a class="page-link"
                            href="{{ $products->url($last) }}&query={{ urlencode($query ?? '') }}">{{ $last }}</a>
                    </li>
                @endif

                {{-- Next --}}
                @if ($current == $last || $last == 0)
                    <li class="page-item disabled"><span class="page-link">Next &raquo;</span></li>
                @else
                    <li class="page-item"><a class="page-link"
                            href="{{ $products->nextPageUrl() }}&query={{ urlencode($query ?? '') }}">Next &raquo;</a>
                    </li>
                @endif

            </ul>
        </nav>
    </div>

@endsection
