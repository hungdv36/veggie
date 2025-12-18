@extends('layouts.client_home')

@section('title', 'Trang chủ')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/clients/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clients/css/slick-theme.css') }}">
    <script src="{{ asset('assets/clients/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/clients/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets/clients/js/main.js') }}"></script>

    <!-- ============ SMOOTH FASHION SLIDER START ============ -->
    <style>
        .fashion-slider {
            position: relative;
            width: 100%;
            height: 90vh;
            overflow: hidden;
            font-family: "Poppins", sans-serif;
        }

        .fashion-slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            visibility: hidden;
            transform: scale(1.05);
            transition: opacity 1.5s ease-in-out, transform 5s ease-in-out;
        }

        .fashion-slide.active {
            opacity: 1;
            visibility: visible;
            transform: scale(1);
            z-index: 2;
        }

        .fashion-slide::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom right, rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.2));
            z-index: 1;
        }

        /* Dots */
        .fashion-dots {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .fashion-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.4s ease;
        }

        .fashion-dot.active {
            background-color: #198754;
            transform: scale(1.3);
            box-shadow: 0 0 10px rgba(25, 135, 84, 0.6);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .fashion-slider {
                height: 60vh;
            }
        }
    </style>

    <div id="homeBanner" class="carousel slide" data-bs-ride="carousel">

        <div class="carousel-inner">

            @foreach ($banners as $key => $banner)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <img src="{{ asset('uploads/banners/' . $banner->image) }}" class="d-block w-100"
                        style="height: 600px; object-fit: cover;">
                </div>
            @endforeach

        </div>

        <!-- Nút trước -->
        <button class="carousel-control-prev" type="button" data-bs-target="#homeBanner" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <!-- Nút sau -->
        <button class="carousel-control-next" type="button" data-bs-target="#homeBanner" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelectorAll('.fashion-slide');
            const dotsContainer = document.querySelector('.fashion-dots');
            let index = 0;
            let autoSlide;

            // Tạo dots
            slides.forEach((_, i) => {
                const dot = document.createElement('div');
                dot.classList.add('fashion-dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => {
                    showSlide(i);
                    resetAutoSlide();
                });
                dotsContainer.appendChild(dot);
            });

            const dots = document.querySelectorAll('.fashion-dot');

            function showSlide(n) {
                slides.forEach(slide => slide.classList.remove('active'));
                dots.forEach(dot => dot.classList.remove('active'));

                index = (n + slides.length) % slides.length;
                slides[index].classList.add('active');
                dots[index].classList.add('active');
            }

            function nextSlide() {
                showSlide(index + 1);
            }

            function startAutoSlide() {
                autoSlide = setInterval(nextSlide, 6000);
            }

            function resetAutoSlide() {
                clearInterval(autoSlide);
                startAutoSlide();
            }

            startAutoSlide();
        });
    </script>
    <!-- ============ SMOOTH FASHION SLIDER END ============ -->

    <!-- BANNER AREA START -->
    <div class="ltn__banner-area mt-120 mb-90">
        <div class="container">
            <div class="row g-3 justify-content-center">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="ltn__banner-item">
                        <div class="ltn__banner-img">
                            <a href="{{ route('products.index') }}"><img
                                    src="{{ asset('assets/clients/img/banner/3.jfif') }}" alt="Banner 1"></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="ltn__banner-item">
                        <div class="ltn__banner-img">
                            <a href="{{ route('products.index') }}"><img
                                    src="{{ asset('assets/clients/img/banner/2.jpeg') }}" alt="Banner 2"></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="ltn__banner-item">
                        <div class="ltn__banner-img">
                            <a href="{{ route('products.index') }}"><img
                                    src="{{ asset('assets/clients/img/banner/1.jpeg') }}" alt="Banner 3"></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="ltn__banner-item">
                        <div class="ltn__banner-img">
                            <a href="{{ route('products.index') }}"><img
                                    src="{{ asset('assets/clients/img/banner/4.jfif') }}" alt="Banner 4"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BANNER AREA END -->

    <!-- FLASH SALE AREA START -->
    @if (isset($flashSale) && $flashSale)
        <section class="flash-sale-section py-5">
            <div class="container">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-danger text-uppercase mb-2">
                        🔥 Flash Sale Hôm Nay
                    </h2>
                    <p class="text-secondary">
                        Kết thúc sau:
                        <span id="countdown" class="fw-semibold text-dark"></span>
                    </p>
                </div>

                <div class="flash-sale-slider d-flex overflow-auto gap-3 pb-2">
                    @foreach ($flashSale->items as $item)
                        @php $product = $item->product; @endphp
                        <div class="flash-sale-card card border-0 shadow-sm flex-shrink-0" style="width: 200px;">
                            <div class="position-relative">
                                <a href="{{ route('products.detail', $product->slug) }}">
                                    <img src="{{ asset('assets/admin/img/product/' . $product->image) }}"
                                        alt="{{ $product->name }}" class="card-img-top"
                                        style="height:200px; object-fit:cover;">
                                </a>
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2 px-2 py-1">
                                    -{{ $item->discount_price }}%
                                </span>
                            </div>
                            <div class="card-body text-center p-2">
                                <h6 class="fw-semibold text-truncate mb-1">{{ $product->name }}</h6>
                                <div class="fw-bold text-danger small mb-1">
                                    {{ number_format($product->price * (1 - $item->discount_price / 100), 0, ',', '.') }}₫
                                </div>
                                <div class="text-muted text-decoration-line-through small mb-2">
                                    {{ number_format($product->price, 0, ',', '.') }}₫
                                </div>
                                <a href="{{ route('products.detail', $product->slug) }}"
                                    class="btn btn-sm btn-success w-100">
                                    Mua ngay
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <script>
                // Countdown Timer
                const endTime = new Date("{{ $flashSale->end_time }}").getTime();
                const countdownEl = document.getElementById("countdown");

                const timer = setInterval(() => {
                    const now = new Date().getTime();
                    const distance = endTime - now;

                    if (distance <= 0) {
                        clearInterval(timer);
                        countdownEl.innerHTML = "Đã kết thúc";
                        document.querySelector(".flash-sale-section").style.display = "none";
                        return;
                    }

                    const h = Math.floor((distance / (1000 * 60 * 60)) % 24);
                    const m = Math.floor((distance / (1000 * 60)) % 60);
                    const s = Math.floor((distance / 1000) % 60);
                    countdownEl.innerHTML = `${h}h ${m}m ${s}s`;
                }, 1000);
            </script>
        </section>
    @endif

    <style>
        .flash-sale-section {
            background: #fff;
            border-top: 3px solid #198754;
            border-bottom: 3px solid #198754;
        }

        .flash-sale-slider {
            scroll-behavior: smooth;
            scrollbar-width: thin;
        }

        .flash-sale-slider::-webkit-scrollbar {
            height: 6px;
        }

        .flash-sale-slider::-webkit-scrollbar-thumb {
            background-color: #198754;
            border-radius: 10px;
        }

        .flash-sale-card img {
            transition: transform 0.3s ease;
        }

        .flash-sale-card:hover img {
            transform: scale(1.05);
        }

        .flash-sale-card .badge {
            font-size: 0.8rem;
            border-radius: 0.5rem;
        }
    </style>
    <!-- FLASH SALE AREA END -->


    <!-- CATEGORY AREA START -->
    <!-- ============ CATEGORY SLIDER START ============ -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        .category-section {
            position: relative;
            background: linear-gradient(135deg, #f9f9f9, #ffffff);
            padding: 100px 0;
        }

        .category-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .category-title h1 {
            font-size: 42px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            display: inline-block;
        }

        .category-title h1::after {
            content: "";
            display: block;
            width: 60px;
            height: 4px;
            background: #198754;
            margin: 14px auto 0;
            border-radius: 2px;
        }

        /* Swiper container */
        .swiper {
            width: 100%;
            height: 400px;
            padding-bottom: 60px;
        }

        .swiper-slide {
            display: flex;
            justify-content: center;
        }

        .category-item {
            position: relative;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            width: 260px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            text-align: center;
            cursor: pointer;
        }

        .category-item:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
        }

        .category-item img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .category-item:hover img {
            transform: scale(1.05);
        }

        .category-content {
            padding: 15px 10px;
        }

        .category-content h5 {
            font-size: 18px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
            transition: color 0.3s ease;
        }

        .category-item:hover .category-content h5 {
            color: #198754;
        }

        .category-content span {
            font-size: 14px;
            color: #777;
        }

        /* Navigation buttons */
        .swiper-button-next,
        .swiper-button-prev {
            color: #198754;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            width: 45px;
            height: 45px;
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 20px;
            font-weight: bold;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: #198754;
            color: #fff;
        }

        /* Pagination dots */
        .swiper-pagination-bullet {
            background: #ccc;
            opacity: 1;
        }

        .swiper-pagination-bullet-active {
            background: #198754;
        }

        @media (max-width: 768px) {
            .category-title h1 {
                font-size: 30px;
            }

            .category-item {
                width: 200px;
            }

            .category-item img {
                height: 180px;
            }
        }
    </style>

    <section class="category-section">
        <div class="container">
            <div class="category-title">
                <h1>Danh mục</h1>
            </div>

            <!-- Swiper -->
            <div class="swiper categorySwiper">
                <div class="swiper-wrapper">
                    @foreach ($categories as $category)
                        <div class="swiper-slide">
                            <div class="category-item">
                                <a href="">
                                    @if (isset($category) && $category->image)
                                        <img src="{{ asset('assets/admin/img/category/' . $category->image) }}"
                                            alt="{{ $category->name }}">
                                    @else
                                        <img src="{{ asset('uploads/categories/default.png') }}" alt="Default">
                                    @endif
                                    <div class="category-content">
                                        <h5>{{ $category->name }}</h5>
                                        <span>{{ $category->products->count() }} sản phẩm</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Pagination dots -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- SwiperJS Script -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper(".categorySwiper", {
            slidesPerView: 4,
            spaceBetween: 30,
            loop: true,
            grabCursor: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                1200: {
                    slidesPerView: 4
                },
                992: {
                    slidesPerView: 3
                },
                768: {
                    slidesPerView: 2
                },
                576: {
                    slidesPerView: 1
                },
            },
        });
    </script>
    <!-- ============ CATEGORY SLIDER END ============ -->


    <!-- CATEGORY AREA END -->

    <!-- PRODUCT SECTION START -->
    <section class="py-100 bg-light">
        <div class="container">
            <!-- Tiêu đề -->
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-2">Sản phẩm nổi bật</h2>
                <p class="text-muted mb-0">Khám phá các sản phẩm mới nhất và được yêu thích nhất</p>
            </div>

            <!-- Tabs danh mục -->
            <div class="text-center mb-4">
                <div class="d-inline-flex flex-wrap justify-content-center gap-2">
                    @foreach ($categories as $index => $category)
                        <button class="btn category-btn {{ $index == 0 ? 'active' : '' }}"
                            data-category="tab_{{ $category->id }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="tab-content">
                @foreach ($categories as $index => $category)
                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="tab_{{ $category->id }}">
                        <div class="row g-4 justify-content-center">
                            @foreach ($category->products as $product)
                                @php
                                    $flashItem = null;
                                    if (isset($flashSale) && $flashSale->end_time > now()) {
                                        $flashItem = $flashSale->items->firstWhere('product_id', $product->id);
                                    }
                                @endphp

                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                                    <div
                                        class="card product-card tabs border-0 shadow-sm h-100 position-relative overflow-hidden">
                                        <!-- Ảnh -->
                                        <div class="product-image position-relative">
                                            <a href="{{ route('products.detail', $product->slug) }}">
                                                <img src="{{ $product->image ? asset('assets/admin/img/product/' . $product->image) : asset('assets/admin/img/product/default.png') }}"
                                                    class="w-100" style="height: 250px; object-fit: cover;"
                                                    alt="{{ $product->name }}">
                                            </a>

                                            @if ($flashItem)
                                                <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                                    -{{ $flashItem->discount_price }}%
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Thông tin sản phẩm -->
                                        <div class="card-body text-center p-3">
                                            <!-- Đánh giá sao -->
                                            <div class="product-rating mb-2">
                                                @include('clients.components.includes.rating', [
                                                    'product' => $product,
                                                ])
                                            </div>

                                            <!-- Tên sản phẩm -->
                                            <h6 class="fw-semibold text-truncate mb-2">
                                                <a href="{{ route('products.detail', $product->slug) }}"
                                                    class="text-dark text-decoration-none">
                                                    {{ $product->name }}
                                                </a>
                                            </h6>

                                            <!-- Giá -->
                                            <div class="product-price mb-3">
                                                @if ($flashItem)
                                                    @php
                                                        $salePrice =
                                                            $product->price * (1 - $flashItem->discount_price / 100);
                                                    @endphp
                                                    <span
                                                        class="fw-bold text-success">{{ number_format($salePrice, 0, ',', '.') }}
                                                        VNĐ</span>
                                                    <del class="text-muted small d-block">{{ number_format($product->price, 0, ',', '.') }}
                                                        VNĐ</del>
                                                @else
                                                    <span
                                                        class="fw-bold text-dark">{{ number_format($product->price, 0, ',', '.') }}
                                                        VNĐ</span>
                                                @endif
                                            </div>

                                            <!-- Icon chức năng -->
                                            <div class="product-actions d-flex justify-content-center gap-3">
                                                <button class="action-btn" title="Xem nhanh" data-bs-toggle="modal"
                                                    data-bs-target="#quick_view_modal-{{ $product->id }}">
                                                    <i class="far fa-eye"></i>
                                                </button>
                                                <button class="action-btn" title="Thêm giỏ hàng" data-bs-toggle="modal"
                                                    data-bs-target="#add_to_cart_modal-{{ $product->id }}">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </button>
                                                <button class="action-btn add-to-wishlist" title="Yêu thích"
                                                    data-id="{{ $product->id }}">
                                                    <i class="far fa-heart"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @foreach ($category->products as $product)
                            @include('clients.components.includes.include-modals')
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CSS -->
    <style>
        /* === PRODUCT CARD - Tabs === */
        .product-card.tabs {
            border-radius: 12px;
            transition: all 0.3s ease;
            background: #fff;
            overflow: hidden;
        }

        .product-card.tabs:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* IMAGE */
        .product-card.tabs .product-image {
            position: relative;
            overflow: hidden;
        }

        .product-card.tabs .product-image img {
            width: 100%;
            transition: transform 0.4s ease;
        }

        .product-card.tabs:hover .product-image img {
            transform: scale(1.05);
            /* chỉ ảnh scale */
        }

        /* BODY */
        .product-card.tabs .card-body {
            position: relative;
            z-index: 2;
            /* đảm bảo click được */
        }

        /* ACTION ICONS */
        .product-card.tabs .product-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .product-card.tabs .action-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #f8f9fa;
            color: #198754;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .product-card.tabs .action-btn:hover {
            background: #198754;
            color: #fff;
            transform: translateY(-3px);
        }
    </style>

    <!-- JS giữ nguyên -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.category-btn');
            const tabs = document.querySelectorAll('.tab-pane');

            buttons.forEach(btn => {
                btn.addEventListener('click', function() {
                    buttons.forEach(b => b.classList.remove('active'));
                    tabs.forEach(t => t.classList.remove('show', 'active'));

                    this.classList.add('active');
                    const id = this.getAttribute('data-category');
                    document.getElementById(id).classList.add('show', 'active');
                });
            });
        });
    </script>


    <!-- COUNTER UP AREA START -->
    <!-- FASHION COUNTER AREA START -->
    <section class="fashion-counter-section">
        <div class="container">
            <div class="row text-center">
                <!-- Item 1 -->
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="fashion-counter-card">
                        <div class="counter-icon">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <h2><span class="counter">1200</span>+</h2>
                        <p>Khách hàng thân thiết</p>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="fashion-counter-card">
                        <div class="counter-icon">
                            <i class="fas fa-tshirt"></i>
                        </div>
                        <h2><span class="counter">500</span>+</h2>
                        <p>Sản phẩm thời trang</p>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="fashion-counter-card">
                        <div class="counter-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h2><span class="counter">250</span>+</h2>
                        <p>Đánh giá 5 sao</p>
                    </div>
                </div>

                <!-- Item 4 -->
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="fashion-counter-card">
                        <div class="counter-icon">
                            <i class="fas fa-globe-asia"></i>
                        </div>
                        <h2><span class="counter">15</span>+</h2>
                        <p>Chi nhánh toàn quốc</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STYLE -->
    <style>
        .fashion-counter-section {
            background: linear-gradient(135deg, #ffffff, #f9f9f9);
            padding: 100px 0;
            position: relative;
        }

        .fashion-counter-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('img/bg/fashion-pattern.png') no-repeat center/cover;
            opacity: 0.05;
        }

        .fashion-counter-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px 20px;
            box-shadow: 0 8px 25px rgba(25, 135, 84, 0.1);
            transition: all 0.3s ease;
        }

        .fashion-counter-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(25, 135, 84, 0.2);
        }

        .fashion-counter-card .counter-icon {
            width: 80px;
            height: 80px;
            background: #198754;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 32px;
            transition: all 0.3s ease;
        }

        .fashion-counter-card:hover .counter-icon {
            background: #146c43;
            transform: scale(1.1);
        }

        .fashion-counter-card h2 {
            font-weight: 700;
            font-size: 36px;
            color: #198754;
            margin-bottom: 10px;
        }

        .fashion-counter-card p {
            font-size: 16px;
            font-weight: 500;
            color: #444;
            letter-spacing: 0.3px;
        }

        @media (max-width: 767px) {
            .fashion-counter-card {
                padding: 30px 15px;
            }

            .fashion-counter-card h2 {
                font-size: 28px;
            }
        }
    </style>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- CounterUp -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>
    <script>
        $('.counter').counterUp({
            delay: 10,
            time: 1200
        });
    </script>

    <!-- COUNTER UP AREA END -->

    <!-- PRODUCT AREA START (product-item-3) -->
    <div class="ltn__product-area pt-100 pb-80 bg-light">
        <div class="container">
            <!-- Tiêu đề -->
            <div class="text-center mb-5">
                <h1 class="fw-bold text-uppercase" style="letter-spacing:1px; color:#111;">Sản phẩm bán chạy</h1>
                <p class="text-muted fs-5">Khám phá những sản phẩm được yêu thích nhất tại <strong>ClotheStore</strong></p>
            </div>

            <!-- Swiper -->
            <div class="swiper bestSellingSwiper position-relative">
                <div class="swiper-wrapper">
                    @foreach ($bestSellingProducts as $product)
                        @php
                            $flashItem = null;
                            if (isset($flashSale) && $flashSale->end_time > now()) {
                                $flashItem = $flashSale->items->firstWhere('product_id', $product->id);
                            }
                        @endphp

                        <div class="swiper-slide">
                            <div class="product-card swiper border-0 shadow-sm bg-white overflow-hidden rounded-4">
                                <div class="position-relative">
                                    <a href="{{ route('products.detail', $product->slug) }}">
                                        <img src="{{ $product->image ? asset('assets/admin/img/product/' . $product->image) : asset('assets/admin/img/product/default.png') }}"
                                            alt="{{ $product->name }}" class="product-img w-100">
                                    </a>

                                    <!-- Badge giảm giá -->
                                    @if ($flashItem)
                                        <div class="sale-badge">-{{ $flashItem->discount_price }}%</div>
                                    @endif

                                    <!-- Overlay action hover chỉ trên ảnh -->
                                    <div class="product-actions d-flex justify-content-center align-items-center gap-3">
                                        <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#quick_view_modal-{{ $product->id }}" class="action-btn"><i
                                                class="far fa-eye"></i></a>
                                        <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#add_to_cart_modal-{{ $product->id }}" class="action-btn"><i
                                                class="fas fa-shopping-cart"></i></a>
                                        <a href="javascript:void(0)" class="action-btn add-to-wishlist"
                                            data-id="{{ $product->id }}"><i class="far fa-heart"></i></a>
                                    </div>
                                </div>

                                <div class="p-3 text-center">
                                    <div class="product-rating mb-2">
                                        @include('clients.components.includes.rating', [
                                            'product' => $product,
                                        ])
                                    </div>
                                    <h6 class="fw-semibold text-dark text-truncate mb-2">
                                        <a href="{{ route('products.detail', $product->slug) }}"
                                            class="text-decoration-none text-dark">{{ $product->name }}</a>
                                    </h6>
                                    <div class="product-price">
                                        @if ($flashItem)
                                            @php
                                                $salePrice = $product->price * (1 - $flashItem->discount_price / 100);
                                            @endphp
                                            <span class="text-danger fw-bold">{{ number_format($salePrice, 0, ',', '.') }}
                                                VNĐ</span><br>
                                            <del class="text-muted small">{{ number_format($product->price, 0, ',', '.') }}
                                                VNĐ</del>
                                        @else
                                            <span
                                                class="fw-bold text-dark">{{ number_format($product->price, 0, ',', '.') }}
                                                VNĐ</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        new Swiper(".bestSellingSwiper", {
            slidesPerView: 4,
            spaceBetween: 25,
            loop: true,
            autoplay: {
                delay: 2800,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                1200: {
                    slidesPerView: 4
                },
                992: {
                    slidesPerView: 3
                },
                768: {
                    slidesPerView: 2
                },
                576: {
                    slidesPerView: 1
                },
            },
        });
    </script>

    <style>
        /* === PRODUCT CARD SWIPER === */
        .product-card.swiper {
            border-radius: 18px;
            position: relative;
            transition: all 0.35s ease;
            overflow: hidden;
        }

        /* IMAGE */
        .product-card.swiper .product-img {
            width: 100%;
            height: 280px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
            transition: transform 0.4s ease;
        }

        .product-card.swiper:hover .product-img {
            transform: scale(1.05);
        }

        /* Overlay hover chỉ trên ảnh */
        .product-card.swiper .product-actions {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0;
            transition: opacity 0.35s ease;
            background: rgba(0, 0, 0, 0.35);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            pointer-events: auto;
        }

        .product-card.swiper:hover .product-actions {
            opacity: 1;
        }

        /* Action button */
        .product-card.swiper .action-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fff;
            color: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .product-card.swiper .action-btn:hover {
            background: #111;
            color: #fff;
        }

        /* Badge giảm giá */
        .sale-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: #ff3b3b;
            color: #fff;
            font-weight: bold;
            font-size: 13px;
            padding: 5px 10px;
            border-radius: 20px;
            z-index: 2;
        }

        /* Navigation Swiper */
        .custom-swiper-nav {
            position: absolute;
            top: 50%;
            width: 100%;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .custom-nav-btn {
            width: 42px;
            height: 42px;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #111;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.35s ease;
            pointer-events: all;
        }

        .custom-nav-btn:hover {
            background: #111;
            color: #fff;
            transform: scale(1.1);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .custom-nav-btn {
                transform: scale(1);
            }
        }
    </style>


    <!-- PRODUCT AREA END -->

    <!-- BLOG AREA START -->
    <section class="blog-section py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="fw-bold" style="color: #198754;">Blog Thời Trang Mới Nhất</h1>
                <p class="text-muted">Cập nhật xu hướng và mẹo phối đồ mỗi ngày</p>
            </div>

            <div class="row g-4">
                <!-- Blog Item -->
                <div class="col-lg-4 col-md-6">
                    <div class="card blog-card border-0 shadow-sm h-100">
                        <div class="overflow-hidden">
                            <img src="https://tse3.mm.bing.net/th/id/OIP.s23OBfe9ZpnDK-kGVJAXrwHaFj?pid=Api&P=0&h=220"
                                class="card-img-top blog-img" alt="Blog 1">
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between small text-muted mb-2">
                                <span><i class="far fa-user me-1"></i>Admin</span>
                                <span><i class="far fa-calendar-alt me-1"></i>24/10/2025</span>
                            </div>
                            <h5 class="card-title fw-semibold">
                                <a href="#" class="text-dark text-decoration-none hover-green">
                                    5 Xu hướng thời trang thu đông 2025
                                </a>
                            </h5>
                            <p class="card-text text-muted small mb-3">
                                Khám phá những phong cách thời trang thu đông nổi bật giúp bạn luôn tự tin và nổi bật.
                            </p>
                            <a href="#" class="btn btn-sm text-white px-3" style="background-color: #198754;">
                                Đọc thêm
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Blog Item -->
                <div class="col-lg-4 col-md-6">
                    <div class="card blog-card border-0 shadow-sm h-100">
                        <div class="overflow-hidden">
                            <img src="https://cdn.vuahanghieu.com/unsafe/1200x0/left/top/smart/filters:quality(90)/https://admin.vuahanghieu.com/upload/news/2023/07/12-ca-ch-pho-i-do-vo-i-a-o-blazer-nu-tre-trung-phong-ca-ch-19072023142406.jpg"
                                class="card-img-top blog-img" alt="Blog 2">
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between small text-muted mb-2">
                                <span><i class="far fa-user me-1"></i>Admin</span>
                                <span><i class="far fa-calendar-alt me-1"></i>20/10/2025</span>
                            </div>
                            <h5 class="card-title fw-semibold">
                                <a href="#" class="text-dark text-decoration-none hover-green">
                                    Cách phối đồ với áo blazer nữ
                                </a>
                            </h5>
                            <p class="card-text text-muted small mb-3">
                                Áo blazer là item không thể thiếu trong tủ đồ của phái đẹp – xem cách phối chuẩn trend nhé!
                            </p>
                            <a href="#" class="btn btn-sm text-white px-3" style="background-color: #198754;">
                                Đọc thêm
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Blog Item -->
                <div class="col-lg-4 col-md-6">
                    <div class="card blog-card border-0 shadow-sm h-100">
                        <div class="overflow-hidden">
                            <img src="https://js0fpsb45jobj.vcdn.cloud/storage/upload/media/that-lung-1.jpg"
                                class="card-img-top blog-img" alt="Blog 3">
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between small text-muted mb-2">
                                <span><i class="far fa-user me-1"></i>Admin</span>
                                <span><i class="far fa-calendar-alt me-1"></i>15/10/2025</span>
                            </div>
                            <h5 class="card-title fw-semibold">
                                <a href="#" class="text-dark text-decoration-none hover-green">
                                    7 mẹo chọn phụ kiện nâng tầm outfit
                                </a>
                            </h5>
                            <p class="card-text text-muted small mb-3">
                                Những món phụ kiện nhỏ xinh nhưng lại tạo nên điểm nhấn hoàn hảo cho set đồ của bạn.
                            </p>
                            <a href="#" class="btn btn-sm text-white px-3" style="background-color: #198754;">
                                Đọc thêm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- BLOG AREA END -->

    <!-- CSS bổ sung -->
    <style>
        .blog-card {
            transition: all 0.3s ease;
            border-radius: 10px;
        }

        .blog-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .blog-img {
            height: 250px;
            width: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .blog-card:hover .blog-img {
            transform: scale(1.05);
        }

        .hover-green:hover {
            color: #198754 !important;
        }
    </style>


@endsection
<style>
    /* Giữ kích thước banner đồng đều */
    .ltn__banner-item {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
    }

    .ltn__banner-img {
        width: 100%;
        height: 300px;
        /* Chiều cao cố định để các ảnh bằng nhau */
        overflow: hidden;
    }

    .ltn__banner-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Cắt ảnh cho vừa khung mà không méo */
        border-radius: 10px;
        transition: transform 0.4s ease;
    }

    /* Hiệu ứng khi hover */
    .ltn__banner-item:hover img {
        transform: scale(1.05);
    }

    /* Responsive cho mobile */
    @media (max-width: 768px) {
        .ltn__banner-img {
            height: 200px;
        }
    }
</style>
@push('scripts')
    <script>
        // Popup: hiển thị ngay lập tức, tự ẩn sau duration milliseconds
        function showWishlistPopup(message = "Đã thêm vào Wishlist thành công", duration = 1400) {
            let popup = document.getElementById('wishlist-popup');
            if (!popup) {
                popup = document.createElement('div');
                popup.id = 'wishlist-popup';
                popup.style =
                    "position:fixed;top:25px;right:25px;background:#2ecc71;color:#fff;padding:12px 18px;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.12);opacity:0;visibility:hidden;transition:all .18s;z-index:99999;";
                document.body.appendChild(popup);
            }
            // set text exactly once
            popup.textContent = message;

            // show immediately
            popup.style.opacity = '1';
            popup.style.visibility = 'visible';

            // cancel previous hide timer if any
            if (popup._hideTimer) {
                clearTimeout(popup._hideTimer);
                popup._hideTimer = null;
            }

            // auto-hide after duration (if duration > 0)
            if (duration > 0) {
                popup._hideTimer = setTimeout(() => {
                    popup.style.opacity = '0';
                    popup.style.visibility = 'hidden';
                    popup._hideTimer = null;
                }, duration);
            }
        }

        $(function() {
            // Use one delegated handler and remove previous bindings to avoid duplication
            $(document).off('click', '#btn-add-to-wishlist, .add-to-wishlist')
                .on('click', '#btn-add-to-wishlist, .add-to-wishlist', function(e) {
                    e.preventDefault();

                    var $btn = $(this);
                    // prevent double-click
                    if ($btn.data('processing')) return;
                    $btn.data('processing', true);

                    // get product id from the button first; fallback to cart-qty-box
                    var productId = $btn.data('id') || $("#cart-qty-box").data("id");
                    var variantId = $btn.data('variant-id') || $("#cart-qty-box").data("variant-id") || 0;

                    if (!productId) {
                        console.warn("productId not found for wishlist add");
                        showWishlistPopup("Không tìm thấy sản phẩm", 1600);
                        $btn.data('processing', false);
                        return;
                    }

                    $.ajax({
                        url: "/wishlist/add",
                        type: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr("content"),
                            product_id: productId,
                            variant_id: variantId,
                        },
                        success: function(res) {
                            // ensure we only trigger once
                            if (res && res.status === true) {
                                // try show modal (if exists) but don't block popup
                                try {
                                    $("#liton_wishlist_modal-" + productId).modal('show');
                                } catch (e) {
                                    /* ignore */ }

                                // show immediate popup (correct text)
                                showWishlistPopup("Đã thêm vào wishlist thành công", 0);
                                console.log("Wishlist add success:", productId);
                            } else {
                                var msg = (res && res.message) ? res.message :
                                    "Đã thêm vào wishlist thành công";
                                showWishlistPopup(msg, 2000);
                                console.log("Wishlist add failed response:", res);
                            }
                        },
                        error: function(xhr) {
                            var msg = xhr.responseJSON?.message ||
                                "Có lỗi xảy ra khi thêm vào wishlist";
                            showWishlistPopup(msg, 2200);
                            console.error("Wishlist add error:", xhr);
                        },
                        complete: function() {
                            // re-enable button
                            $btn.data('processing', false);
                        }
                    });
                });
        });
    </script>
@endpush
