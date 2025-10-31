@extends('layouts.client_home')

@section('title', 'Trang chủ')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/clients/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clients/css/slick-theme.css') }}">
    <script src="{{ asset('assets/clients/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/clients/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets/clients/js/main.js') }}"></script>

    <!-- ============ SLIDER START ============ -->
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
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 1s ease-in-out, visibility 1s ease-in-out;
        }

        .fashion-slide.active {
            opacity: 1;
            visibility: visible;
        }

        .fashion-slide::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom right, rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.2));
            z-index: 1;
        }

        .fashion-slide-content {
            position: relative;
            z-index: 2;
            color: #fff;
            text-align: center;
            padding: 20px;
            max-width: 800px;
            animation: fadeUp 1.2s ease;
        }

        @keyframes fadeUp {
            from {
                transform: translateY(40px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .fashion-slide h1 {
            font-size: 58px;
            font-weight: 800;
            letter-spacing: 1px;
            line-height: 1.1;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .fashion-slide p {
            font-size: 18px;
            color: #f5f5f5;
            margin-bottom: 35px;
            line-height: 1.6;
        }

        .fashion-btn {
            background: linear-gradient(90deg, #198754, #198754);
            color: #fff;
            padding: 14px 38px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .fashion-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(255, 90, 95, 0.4);
        }

        /* Arrows */
        .fashion-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 48px;
            color: #fff;
            cursor: pointer;
            z-index: 10;
            opacity: 0.7;
            transition: 0.3s;
            padding: 8px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.2);
        }

        .fashion-arrow:hover {
            opacity: 1;
            background: rgba(0, 0, 0, 0.4);
        }

        .fashion-prev {
            left: 40px;
        }

        .fashion-next {
            right: 40px;
        }

        /* Dots */
        .fashion-dots {
            position: absolute;
            bottom: 35px;
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
            transition: all 0.3s;
        }

        .fashion-dot.active {
            background-color: #198754;
            transform: scale(1.2);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .fashion-slide h1 {
                font-size: 38px;
            }

            .fashion-slide p {
                font-size: 16px;
            }

            .fashion-btn {
                padding: 12px 28px;
                font-size: 15px;
            }

            .fashion-arrow {
                font-size: 36px;
            }
        }
    </style>

    <div class="fashion-slider">
        <!-- Slide 1 -->
        <div class="fashion-slide active" style="background-image: url('{{ asset('assets/clients/img/slider/1.png') }}')">
            <div class="fashion-slide-content">
                <h1>Bộ Sưu Tập Mùa Thu</h1>
                <p>Khám phá phong cách thời trang mới nhất, giúp bạn tỏa sáng trong mọi khoảnh khắc.</p>
                <a href="{{ route('products.index') }}" class="fashion-btn">Mua Ngay</a>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="fashion-slide" style="background-image: url('{{ asset('assets/clients/img/slider/2.png') }}')">
            <div class="fashion-slide-content">
                <h1>Ưu Đãi Đặc Biệt 50%</h1>
                <p>Bộ sưu tập giới hạn - phong cách sang trọng, trẻ trung, năng động.</p>
                <a href="{{ route('products.index') }}" class="fashion-btn">Khám Phá</a>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="fashion-slide" style="background-image: url('{{ asset('assets/clients/img/slider/3.png') }}')">
            <div class="fashion-slide-content">
                <h1>Thời Trang Cá Tính</h1>
                <p>Sự kết hợp hoàn hảo giữa vẻ đẹp hiện đại và tinh tế, chỉ có tại FashionStore.</p>
                <a href="{{ route('products.index') }}" class="fashion-btn">Xem Ngay</a>
            </div>
        </div>

        <!-- Arrows -->
        <div class="fashion-arrow fashion-prev">&#10094;</div>
        <div class="fashion-arrow fashion-next">&#10095;</div>

        <!-- Dots -->
        <div class="fashion-dots"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.fashion-slide');
            const prev = document.querySelector('.fashion-prev');
            const next = document.querySelector('.fashion-next');
            const dotsContainer = document.querySelector('.fashion-dots');
            let index = 0;

            // Tạo dot
            slides.forEach((_, i) => {
                const dot = document.createElement('div');
                dot.classList.add('fashion-dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => showSlide(i));
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

            next.addEventListener('click', () => showSlide(index + 1));
            prev.addEventListener('click', () => showSlide(index - 1));

            // Auto slide
            setInterval(() => showSlide(index + 1), 6000);
        });
    </script>
    <!-- ============ SLIDER END ============ -->


    <!-- SLIDER AREA END -->

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
                            <a href="#">
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

            <!-- Navigation arrows -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>

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
            1200: { slidesPerView: 4 },
            992: { slidesPerView: 3 },
            768: { slidesPerView: 2 },
            576: { slidesPerView: 1 },
        },
    });
</script>
<!-- ============ CATEGORY SLIDER END ============ -->


    <!-- CATEGORY AREA END -->

    <!-- PRODUCT TAB AREA START (product-item-3) -->
    <div class="ltn__product-tab-area ltn__product-gutter pt-115 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h1 class="section-title">Sản phẩm</h1>
                    </div>
                    <div class="ltn__tab-menu ltn__tab-menu-2 ltn__tab-menu-top-right-- text-uppercase text-center">
                        <div class="nav">
                            @foreach ($categories as $index => $category)
                                <a class="{{ $index == 0 ? 'active show' : '' }}" data-bs-toggle="tab"
                                    href="#tab_{{ $category->id }}">{{ $category->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-content">
                        @foreach ($categories as $index => $category)
                            <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                                id="tab_{{ $category->id }}">
                                <div class="ltn__product-tab-content-inner">
                                    <div class="row ltn__tab-product-slider-one-active slick-arrow-1">
                                        @foreach ($category->products as $product)
                                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                                <div class="ltn__product-item ltn__product-item-3 text-center">
                                                    <div class="product-img">
                                                        <a href="{{ $product->id }}">
                                                            @if ($product->image)
                                                                <img src="{{ asset('assets/admin/img/product/' . $product->image) }}"
                                                                    alt="{{ $product->name }}"
                                                                    style="height:200px;width:200px; object-fit:cover;">
                                                            @else
                                                                <img src="{{ asset('assets/admin/img/product/default.png') }}"
                                                                    alt="Default" width="80">
                                                            @endif
                                                        </a>
                                                        <div class="product-badge">
                                                            <ul>
                                                                <li class="sale-badge">-19%</li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-hover-action">
                                                            <ul>
                                                                <li>
                                                                    <a href="javascript:void(0)" title="Xem nhanh"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#quick_view_modal-{{ $product->id }}">
                                                                        <i class="far fa-eye"></i>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:void(0)" title="Thêm vào giỏ hàng"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#add_to_cart_modal-{{ $product->id }}">
                                                                        <i class="fas fa-shopping-cart"></i>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:void(0)" title="Yêu thích"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#liton_wishlist_modal-{{ $product->id }}">
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
                                                            <span>{{ number_format($product->price, 0, ',', '.') }}
                                                                VNĐ</span>
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
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- PRODUCT TAB AREA END -->

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
    top: 0; left: 0;
    width: 100%; height: 100%;
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
    <div class="ltn__product-area ltn__product-gutter pt-115 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h1 class="section-title">Sản phẩm bán chạy</h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__tab-product-slider-one-active--- slick-arrow-1">
                <!-- ltn__product-item -->
                @foreach ($bestSellingProducts as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                        <div class="ltn__product-item ltn__product-item-3 text-left">
                            <div class="product-img">
                                <a href="{{ $product->id }}">
                                    @if ($product->image)
                                        <img src="{{ asset('assets/admin/img/product/' . $product->image) }}"
                                            alt="{{ $product->name }}"
                                            style="height:150px;width:150px; object-fit:cover;">
                                    @else
                                        <img src="{{ asset('assets/admin/img/product/default.png') }}" alt="Default"
                                            width="80">
                                    @endif
                                </a>
                                <div class="product-badge">
                                    <ul>
                                        <li class="sale-badge">-19%</li>
                                    </ul>
                                </div>
                                <div class="product-hover-action">
                                    <ul>
                                        <li>
                                            <a href="#" title="Xem nhanh" data-bs-toggle="modal"
                                                data-bs-target="#quick_view_modal-{{ $product->id }}">
                                                <i class="far fa-eye"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" title="Thêm vào giỏ hàng" data-bs-toggle="modal"
                                                data-bs-target="#add_to_cart_modal-{{ $product->id }}">
                                                <i class="fas fa-shopping-cart"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" title="Yêu thích" data-bs-toggle="modal"
                                                data-bs-target="#liton_wishlist_modal-{{ $product->id }}">
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
                                <h2 class="product-title"><a href="{{ route('products.detail', $product->slug) }}">{{ $product->name }}</a></h2>
                                <div class="product-price">
                                    <span>{{ number_format($product->price, 0, ',', '.') }}
                                        VNĐ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
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
            <img src="https://tse3.mm.bing.net/th/id/OIP.s23OBfe9ZpnDK-kGVJAXrwHaFj?pid=Api&P=0&h=220" class="card-img-top blog-img" alt="Blog 1">
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
            <img src="https://cdn.vuahanghieu.com/unsafe/1200x0/left/top/smart/filters:quality(90)/https://admin.vuahanghieu.com/upload/news/2023/07/12-ca-ch-pho-i-do-vo-i-a-o-blazer-nu-tre-trung-phong-ca-ch-19072023142406.jpg" class="card-img-top blog-img" alt="Blog 2">
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
            <img src="https://js0fpsb45jobj.vcdn.cloud/storage/upload/media/that-lung-1.jpg" class="card-img-top blog-img" alt="Blog 3">
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
