@extends('layouts.client_home')

@section('title', 'Trang chủ')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/clients/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clients/css/slick-theme.css') }}">
    <script src="{{ asset('assets/clients/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/clients/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets/clients/js/main.js') }}"></script>

    <!-- SLIDER AREA START (slider-3) -->
    {{-- <div class="ltn__slider-area ltn__slider-3  section-bg-1">
        <div class="ltn__slide-one-active slick-slide-arrow-1 slick-slide-dots-1">
            <!-- ltn__slide-item -->
            <div class="ltn__slide-item ltn__slide-item-2 ltn__slide-item-3 ltn__slide-item-3-normal bg-image"
                data-bg="{{ asset('assets/clients/img/slider/1.jpg') }}">
                <div class="ltn__slide-item-inner">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 align-self-center">
                                <div class="slide-item-info">
                                    <div class="slide-item-info-inner ltn__slide-animation">
                                        <div class="slide-video mb-50 d-none">
                                            <a class="ltn__video-icon-2 ltn__video-icon-2-border"
                                                href="https://www.youtube.com/embed/ATI7vfCgwXE?autoplay=1&amp;showinfo=0"
                                                data-rel="lightcase:myCollection">
                                                <i class="fa fa-play"></i>
                                            </a>
                                        </div>
                                        <h6 class="slide-sub-title animated"><img src="img/icons/icon-img/1.png"
                                                alt="#"> 100% genuine Products</h6>
                                        <h1 class="slide-title animated ">Our Garden's Most <br> Favorite Food</h1>
                                        <div class="slide-brief animated">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                                                eiusmod tempor incididunt ut labore.</p>
                                        </div>
                                        <div class="btn-wrapper animated">
                                            <a href="shop.html" class="theme-btn-1 btn btn-effect-1 text-uppercase">Explore
                                                Products</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ltn__slide-item -->
            <div class="ltn__slide-item ltn__slide-item-2 ltn__slide-item-3 ltn__slide-item-3-normal bg-image"
                data-bg="{{ asset('assets/clients/img/slider/2.jpg') }}">
                <div class="ltn__slide-item-inner  text-right text-end">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 align-self-center">
                                <div class="slide-item-info">
                                    <div class="slide-item-info-inner ltn__slide-animation">
                                        <h6 class="slide-sub-title ltn__secondary-color animated">// TALENTED
                                            ENGINEER & MECHANICS</h6>
                                        <h1 class="slide-title animated ">Tasty & Healthy <br> Organic Food</h1>
                                        <div class="slide-brief animated">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                                                eiusmod tempor incididunt ut labore.</p>
                                        </div>
                                        <div class="btn-wrapper animated">
                                            <a href="shop.html" class="theme-btn-1 btn btn-effect-1 text-uppercase">Explore
                                                Products</a>
                                            <a href="about.html" class="btn btn-transparent btn-effect-3">LEARN
                                                MORE</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="slide-item-img slide-img-left">
                                                                                        <img src="img/slider/22.png" alt="#">
                                                                                    </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
        </div>
    </div> --}}
    <!-- ============ SLIDER START ============ -->
    <style>
        .fashion-slider {
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        .fashion-slide {
            position: relative;
            width: 100%;
            height: 650px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: none;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-align: center;
            transition: opacity 1s ease-in-out;
        }

        .fashion-slide.active {
            display: flex;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .fashion-slide::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .fashion-slide-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
        }

        .fashion-slide h1 {
            font-size: 56px;
            font-weight: 700;
            line-height: 1.2;
            text-transform: uppercase;
            margin-bottom: 20px;
            color: #fff;
        }

        .fashion-slide p {
            font-size: 18px;
            margin-bottom: 30px;
            color: #f1f1f1;
        }

        .fashion-btn {
            background-color: #ff5a5f;
            color: #fff;
            padding: 14px 32px;
            border: none;
            border-radius: 30px;
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .fashion-btn:hover {
            background-color: #e0484c;
        }

        /* Arrows */
        .fashion-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 40px;
            color: #fff;
            cursor: pointer;
            z-index: 10;
            opacity: 0.7;
            transition: 0.3s;
        }

        .fashion-arrow:hover {
            opacity: 1;
        }

        .fashion-prev {
            left: 30px;
        }

        .fashion-next {
            right: 30px;
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
            background-color: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            transition: 0.3s;
        }

        .fashion-dot.active {
            background-color: #ff5a5f;
        }
    </style>

    <div class="fashion-slider">
        <!-- Slide 1 -->
        <div class="fashion-slide active" style="background-image: url('{{ asset('assets/clients/img/slider/1.jpg') }}')">
            <div class="fashion-slide-content">
                <h1>Bộ Sưu Tập Mùa Thu</h1>
                <p>Khám phá phong cách thời trang mới nhất, giúp bạn tỏa sáng trong mọi khoảnh khắc.</p>
                <a href="{{ route('products.index') }}" class="fashion-btn">Mua Ngay</a>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="fashion-slide" style="background-image: url('{{ asset('assets/clients/img/slider/2.jpg') }}')">
            <div class="fashion-slide-content">
                <h1>Ưu Đãi Lên Đến 50%</h1>
                <p>Bộ sưu tập giới hạn - phong cách sang trọng, trẻ trung, năng động.</p>
                <a href="{{ route('products.index') }}" class="fashion-btn">Khám Phá</a>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="fashion-slide" style="background-image: url('{{ asset('assets/clients/img/slider/3.jpg') }}')">
            <div class="fashion-slide-content">
                <h1>Thời Trang Nữ Tính & Cá Tính</h1>
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

            // tạo dot cho từng slide
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

            // Tự động chạy
            setInterval(() => {
                showSlide(index + 1);
            }, 5000);
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

    <!-- FEATURE AREA START ( Feature - 3) -->
    <div class="ltn__feature-area mt-100 mt--65 d-none">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__feature-item-box-wrap ltn__feature-item-box-wrap-2 ltn__border section-bg-6">
                        <div class="ltn__feature-item ltn__feature-item-8">
                            <div class="ltn__feature-icon">
                                <img src="img/icons/svg/8-trolley.svg" alt="#">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>Free shipping</h4>
                                <p>On all orders over $49.00</p>
                            </div>
                        </div>
                        <div class="ltn__feature-item ltn__feature-item-8">
                            <div class="ltn__feature-icon">
                                <img src="img/icons/svg/9-money.svg" alt="#">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>15 days returns</h4>
                                <p>Moneyback guarantee</p>
                            </div>
                        </div>
                        <div class="ltn__feature-item ltn__feature-item-8">
                            <div class="ltn__feature-icon">
                                <img src="img/icons/svg/10-credit-card.svg" alt="#">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>Secure checkout</h4>
                                <p>Protected by Paypal</p>
                            </div>
                        </div>
                        <div class="ltn__feature-item ltn__feature-item-8">
                            <div class="ltn__feature-icon">
                                <img src="img/icons/svg/11-gift-card.svg" alt="#">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>Offer & gift here</h4>
                                <p>On all orders over</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FEATURE AREA END -->

    <!-- ABOUT US AREA START -->
    <div class="ltn__about-us-area pt-120 pb-120 d-none">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 align-self-center">
                    <div class="about-us-img-wrap about-img-left">
                        <img src="img/others/6.png" alt="About Us Image">
                    </div>
                </div>
                <div class="col-lg-6 align-self-center">
                    <div class="about-us-info-wrap">
                        <div class="section-title-area ltn__section-title-2">
                            <h6 class="section-subtitle ltn__secondary-color">Know More About Shop</h6>
                            <h1 class="section-title">Trusted Organic <br class="d-none d-md-block"> Food Store</h1>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                incididunt ut labore</p>
                        </div>
                        <p>sellers who aspire to be good, do good, and spread goodness. We
                            democratic, self-sustaining, two-sided marketplace which thrives
                            on trust and is built on community and quality content.</p>
                        <div class="about-author-info d-flex">
                            <div class="author-name-designation  align-self-center mr-30">
                                <h4 class="mb-0">Jerry Henson</h4>
                                <small>/ Shop Director</small>
                            </div>
                            <div class="author-sign  align-self-center">
                                <img src="img/icons/icon-img/author-sign.png" alt="#">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ABOUT US AREA END -->

    <!-- CATEGORY AREA START -->
    <div class="ltn__category-area section-bg-1-- ltn__primary-bg before-bg-1 bg-image bg-overlay-theme-black-5--0 pt-115 pb-90"
        data-bg="img/bg/5.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h1 class="section-title white-color">Danh mục</h1>
                    </div>
                </div>
            </div>
            <div class="category-container">
                @foreach ($categories as $category)
                    <div class="category-item">
                        <div class="category-item-img">
                            <a href="#">
                                @if (isset($category) && $category->image)
                                    <img src="{{ asset('assets/admin/img/category/' . $category->image) }}"
                                        alt="{{ $category->name }}" style="height:100px;width:100px;">
                                @else
                                    <img src="{{ asset('uploads/categories/default.png') }}" alt="Default"
                                        width="80">
                                @endif
                            </a>
                        </div>
                        <div class="category-item-name">
                            <h5><a href="shop.html">{{ $category->name }}</a></h5>
                            <h6>({{ $category->products->count() }} sản phẩm)</h6>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
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
                                                                <img src="{{ asset('assets/img/product/' . $product->image) }}"
                                                                    alt="{{ $product->name }}"
                                                                    style="height:100px;width:100px; object-fit:cover;">
                                                            @else
                                                                <img src="{{ asset('assets/img/product/default.png') }}"
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
                                                                        class="add-to-wishlist"
                                                                        data-id="{{ $product->id }}">
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
                                                                <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                                <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                                <li><a href="#"><i
                                                                            class="fas fa-star-half-alt"></i></a></li>
                                                                <li><a href="#"><i class="far fa-star"></i></a></li>
                                                                <li class="review-total"><a href="#">(24)</a></li>
                                                            </ul>
                                                        </div>
                                                        <h2 class="product-title"><a
                                                                href="product-details.html">{{ $product->name }}</a></h2>
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
    <div class="ltn__counterup-area bg-image bg-overlay-theme-black-80 pt-115 pb-70" data-bg="img/bg/5.jpg">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 align-self-center">
                    <div class="ltn__counterup-item-3 text-color-white text-center">
                        <div class="counter-icon"> <img src="img/icons/icon-img/2.png" alt="#"> </div>
                        <h1><span class="counter">733</span><span class="counterUp-icon">+</span> </h1>
                        <h6>Active Clients</h6>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 align-self-center">
                    <div class="ltn__counterup-item-3 text-color-white text-center">
                        <div class="counter-icon"> <img src="img/icons/icon-img/3.png" alt="#"> </div>
                        <h1><span class="counter">33</span><span class="counterUp-letter">K</span><span
                                class="counterUp-icon">+</span> </h1>
                        <h6>Cup Of Coffee</h6>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 align-self-center">
                    <div class="ltn__counterup-item-3 text-color-white text-center">
                        <div class="counter-icon"> <img src="img/icons/icon-img/4.png" alt="#"> </div>
                        <h1><span class="counter">100</span><span class="counterUp-icon">+</span> </h1>
                        <h6>Get Rewards</h6>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 align-self-center">
                    <div class="ltn__counterup-item-3 text-color-white text-center">
                        <div class="counter-icon"> <img src="img/icons/icon-img/5.png" alt="#"> </div>
                        <h1><span class="counter">21</span><span class="counterUp-icon">+</span> </h1>
                        <h6>Country Cover</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                                        <img src="{{ asset('assets/img/product/' . $product->image) }}"
                                            alt="{{ $product->name }}"
                                            style="height:100px;width:100px; object-fit:cover;">
                                    @else
                                        <img src="{{ asset('assets/img/product/default.png') }}" alt="Default"
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
                                    <ul>
                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                        <li class="review-total"><a href="#">(24)</a></li>
                                    </ul>
                                </div>
                                <h2 class="product-title"><a href="product-details.html">{{ $product->name }}</a></h2>
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

    <!-- CALL TO ACTION START (call-to-action-4) -->
    <div class="ltn__call-to-action-area ltn__call-to-action-4 bg-image pt-115 pb-120" data-bg="img/bg/6.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="call-to-action-inner call-to-action-inner-4 text-center">
                        <div class="section-title-area ltn__section-title-2">
                            <h6 class="section-subtitle ltn__secondary-color">// any question you have //</h6>
                            <h1 class="section-title white-color">897-876-987-90</h1>
                        </div>
                        <div class="btn-wrapper">
                            <a href="tel:+123456789" class="theme-btn-1 btn btn-effect-1">MAKE A CALL</a>
                            <a href="contact.html" class="btn btn-transparent btn-effect-4 white-color">CONTACT
                                US</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ltn__call-to-4-img-1">
            <img src="img/bg/12.png" alt="#">
        </div>
        <div class="ltn__call-to-4-img-2">
            <img src="img/bg/11.png" alt="#">
        </div>
    </div>
    <!-- CALL TO ACTION END -->

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
