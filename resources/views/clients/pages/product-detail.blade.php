@extends('layouts.client')

@section('title', 'Chi tiết sản phẩm')

@section('breadcrumb', 'Chi tiết sản phẩm')

@section('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SHOP DETAILS AREA START -->
    <div class="ltn__shop-details-area pb-85">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="ltn__shop-details-inner mb-60">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="ltn__shop-details-img-gallery">
                                    <div class="ltn__shop-details-large-img">
                                        <div class="single-large-img">
                                            @if ($product->image)
                                                <img src="{{ asset('assets/admin/img/product/' . $product->image) }}"
                                                    alt="{{ $product->name }}"
                                                    style="height:850px;width:850px; object-fit:cover;">
                                            @else
                                                <img src="{{ asset('assets/img/product/default.png') }}" alt="Default"
                                                    width="80">
                                            @endif
                                        </div>

                                    </div>
                                    <div class="ltn__shop-details-small-img slick-arrow-2">
                                        @foreach ($product->images as $img)
                                            <div class="col-md-3 mb-2">
                                                <img src="{{ asset($img->image_path) }}" class="img-fluid rounded shadow-sm"
                                                    style="height:100px; object-fit:cover;">
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="modal-product-info shop-details-info pl-0">
                                    <div class="product-ratting">
                                        @include('clients.components.includes.rating', [
                                            'product' => $product,
                                        ])
                                    </div>
                                    <h3>{{ $product->name }}</h3>
                                    @php
                                        $minPrice = $product->variants->min('price');
                                        $maxPrice = $product->variants->max('price');
                                    @endphp

                                    <div class="product-price">
                                        @if ($product->is_flash_sale ?? false)
                                            {{-- ✅ Nếu đang Flash Sale --}}
                                            <div class="flash-sale-price-box p-3 rounded"
                                                style="background:#fff5f5; border:1px solid #ffc6c6;">
                                                <h5 class="text-danger mb-2">
                                                    🔥 Flash Sale đang diễn ra! Giảm {{ $product->discount_price }}%
                                                </h5>

                                                {{-- Hiển thị giá gốc bị gạch và giá Flash Sale nổi bật --}}
                                                <p class="mb-1">
                                                    <span class="text-muted text-decoration-line-through"
                                                        style="font-size:16px;">
                                                        {{ number_format($product->price ?? $maxPrice, 0, ',', '.') }} VNĐ
                                                    </span>
                                                    <span class="fw-bold text-danger ms-2" id="product-price"
                                                        style="font-size:22px;">
                                                        {{ number_format($product->flash_sale_price ?? $minPrice, 0, ',', '.') }}
                                                        VNĐ
                                                    </span>
                                                </p>

                                                {{-- Countdown --}}
                                                <div id="countdown" class="fw-semibold text-danger mt-2"></div>

                                                {{-- Progress bar (hiển thị đã bán nếu muốn) --}}
                                                @if (!empty($flashItem))
                                                    @php
                                                        $sold = $flashItem->sold ?? 0;
                                                        $total = $flashItem->quantity ?? 1;
                                                        $percent = $total > 0 ? round(($sold / $total) * 100, 0) : 0;
                                                    @endphp
                                                    <div class="mt-2">
                                                        <div class="progress" style="height: 10px;">
                                                            <div class="progress-bar bg-danger" role="progressbar"
                                                                style="width: {{ $percent }}%;"
                                                                aria-valuenow="{{ $percent }}" aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>
                                                        <small class="text-muted d-block mt-1">
                                                            Đã bán {{ $sold }}/{{ $total }} sản phẩm
                                                            ({{ $percent }}%)
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- JS đếm ngược thời gian --}}
                                            <script>
                                                const endTime = new Date("{{ $product->flash_end_time }}").getTime();
                                                const countdownEl = document.getElementById("countdown");

                                                const timer = setInterval(() => {
                                                    const now = new Date().getTime();
                                                    const distance = endTime - now;

                                                    if (distance <= 0) {
                                                        clearInterval(timer);
                                                        countdownEl.innerHTML = "⏰ Flash Sale đã kết thúc!";
                                                        return;
                                                    }

                                                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                                    countdownEl.innerHTML = `Kết thúc sau: ${hours}h ${minutes}m ${seconds}s`;
                                                }, 1000);
                                            </script>
                                        @else
                                            {{-- ❌ Không có Flash Sale --}}
                                            @if ($minPrice == $maxPrice)
                                                <span id="product-price">{{ number_format($minPrice, 0, ',', '.') }}
                                                    VNĐ</span>
                                            @else
                                                <span id="product-price">{{ number_format($minPrice, 0, ',', '.') }} -
                                                    {{ number_format($maxPrice, 0, ',', '.') }} VNĐ</span>
                                            @endif
                                        @endif
                                    </div>



                                    <div class="modal-product-meta ltn__product-details-menu-1">
                                        <ul class="list-unstyled mb-0">
                                            <!-- Danh mục -->
                                            <li class="mb-3">
                                                <strong>Danh mục:</strong>
                                                <span>
                                                    <a href="javascript:void(0)" class="text-decoration-none text-primary">
                                                        {{ $product->category->name }}
                                                    </a>
                                                </span>
                                            </li>

                                            @php
                                                $colors = $product->variants->pluck('color')->unique()->filter();
                                                $sizes = $product->variants->pluck('size')->unique()->filter();
                                            @endphp

                                            <!-- Biến thể -->
                                            <li>
                                                <div class="row g-3">
                                                    <!-- Màu sắc -->
                                                    <div class="col-md-6 col-12">
                                                        <div class="mb-2">
                                                            <label class="form-label fw-semibold d-block">Màu sắc:</label>
                                                            @if ($colors->count() > 0)
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    @foreach ($colors as $color)
                                                                        <button type="button"
                                                                            class="btn color-btn d-flex align-items-center"
                                                                            data-value="{{ $color->id }}"
                                                                            style="padding:6px 12px; border:1px solid #ccc; border-radius:4px; cursor:pointer;">
                                                                            <span class="rounded-circle me-2"
                                                                                style="width:14px; height:14px; background-color: {{ $color->hex_code }};"></span>
                                                                            {{ $color->name }}
                                                                        </button>
                                                                    @endforeach
                                                                </div>
                                                                <input type="hidden" name="color" id="color-value">
                                                            @else
                                                                <p class="text-muted mb-0">Không có</p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Kích thước -->
                                                    <div class="col-md-6 col-12">
                                                        <div class="mb-2">
                                                            <label class="form-label fw-semibold d-block">Kích
                                                                thước:</label>
                                                            @if ($sizes->count() > 0)
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    @foreach ($sizes as $size)
                                                                        <button type="button" class="btn size-btn"
                                                                            data-value="{{ $size->id }}"
                                                                            style="padding:6px 12px; border:1px solid #ccc; border-radius:4px; cursor:pointer;">
                                                                            {{ strtoupper($size->name) }}
                                                                        </button>
                                                                    @endforeach
                                                                </div>
                                                                <input type="hidden" name="size" id="size-value">
                                                            @else
                                                                <p class="text-muted mb-0">Không có</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="ltn__product-details-menu-2">
                                        <ul>
                                            <li>
                                                <div class="cart-quantity-wrapper"
                                                    style="display: flex; align-items: center; width: fit-content; border: 1px solid #ddd; border-radius: 6px; overflow: hidden;">
                                                    <button type="button" class="qtybutton-detail dec"
                                                        style="background: #f5f5f5; border: none; padding: 6px 12px; cursor: pointer; font-weight: bold;">-</button>
                                                    <input id="cart-qty-box" type="text" value="1" readonly
                                                        class="cart-plus-minus-box"
                                                        style="width: 50px; text-align: center; border: none; outline: none;"
                                                        data-max="{{ $product->stock }}">
                                                    <button type="button" class="qtybutton-detail inc"
                                                        style="background: #f5f5f5; border: none; padding: 6px 12px; cursor: pointer; font-weight: bold;">+</button>
                                                </div>
                                                <p class="text-muted small mt-1">
                                                    Tổng kho: <span
                                                        id="product-stock">{{ $product->variants->sum('quantity') }}</span>
                                                </p>
                                                <p class="text-muted small mt-1">
                                                    Kho biến thể: <span id="variant-stock">0</span>
                                                </p>

                                            </li>
                                            <li>
                                                <a href="javascript:void(0)"
                                                    class="theme-btn-1 btn btn-effect-1 add-to-cart-btn"
                                                    title="Thêm vào giỏ hàng" data-id="{{ $product->id }}"
                                                    data-price="{{ $product->is_flash_sale ? $product->flash_sale_price : $product->price }}">
                                                    <i class="fas fa-shopping-cart"></i>
                                                    <span>THÊM VÀO GIỎ HÀNG</span>
                                                </a>

                                            </li>
                                        </ul>
                                    </div>
                                    <div class="ltn__product-details-menu-3">
                                        <ul>
                                            <li>
                                                <a href="#" class="" title="Wishlist" data-bs-toggle="modal"
                                                    data-bs-target="#liton_wishlist_modal">
                                                    <i class="far fa-heart"></i>
                                                    <span>Yêu thích</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Shop Tab Start -->
                    <div class="ltn__shop-details-tab-inner ltn__shop-details-tab-inner-2">
                        <div class="ltn__shop-details-tab-menu">
                            <div class="nav">
                                <a class="active show" data-bs-toggle="tab" href="#liton_tab_details_description">Mô
                                    tả</a>
                                <a data-bs-toggle="tab" href="#liton_tab_details_reviews" class="">Đánh giá</a>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="liton_tab_details_description">
                                <div class="ltn__shop-details-tab-content-inner">
                                    <h4 class="title-2">Mô tả sản phẩm</h4>
                                    <p>{{ $product->description }}</p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="liton_tab_details_reviews">
                                <div class="ltn__shop-details-tab-content-inner">
                                    <h4 class="title-2">Đánh giá của khách hàng</h4>
                                    <div class="product-ratting">
                                        <ul>
                                            @php
                                                $avg = $product->average_rating ?? 0;
                                            @endphp

                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($avg))
                                                    <li><a href="javascript:void(0)"><i
                                                                class="fas fa-star text-warning"></i></a></li>
                                                @elseif ($i - $avg < 1 && $avg - floor($avg) >= 0.5)
                                                    <li><a href="javascript:void(0)"><i
                                                                class="fas fa-star-half-alt text-warning"></i></a></li>
                                                @else
                                                    <li><a href="javascript:void(0)"><i
                                                                class="far fa-star text-warning"></i></a></li>
                                                @endif
                                            @endfor

                                            <li class="review-total"> <a href="javascript:void(0)"> (
                                                    {{ $product->reviews->count() }} Đánh giá )</a></li>
                                        </ul>
                                    </div>
                                    <hr>
                                    <!-- comment-area -->
                                    <div class="ltn__comment-area mb-30">
                                        <div class="ltn__comment-inner">
                                            @include('clients.components.includes.review-list', [
                                                'product' => $product,
                                            ])
                                        </div>
                                    </div>
                                    <!-- comment-reply -->
                                    <div class="ltn__comment-reply-area ltn__form-box mb-30">
                                        <form id="review-form" data-product-id={{ $product->id }}>
                                            <h4 class="title-2">Thêm đánh giá</h4>
                                            <div class="mb-30">
                                                <div class="add-a-review">
                                                    <h6>Số sao:</h6>
                                                    <div class="product-ratting">
                                                        <ul>
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <li>
                                                                    <a href="javascript:void(0)" class="rating-star"
                                                                        data-value="{{ $i }}">
                                                                        <i class="far fa-star"></i>
                                                                    </a>
                                                                </li>
                                                            @endfor

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="rating" id="rating-value" value="0">
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <textarea placeholder="Nhập đánh giá của bạn ..." id="review-content"></textarea>
                                            </div>
                                            <div class="btn-wrapper">
                                                <button class="btn theme-btn-1 btn-effect-1 text-uppercase"
                                                    type="submit">Gửi</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Shop Tab End -->
                </div>
            </div>
        </div>
    </div>
    <!-- SHOP DETAILS AREA END -->

    <!-- PRODUCT SLIDER AREA START -->
    <div class="ltn__product-slider-area ltn__product-gutter pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2">
                        <h1 class="section-title">Sản phẩm tương tự<span>.</span></h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__related-product-slider-one-active slick-arrow-1">
                @foreach ($relatedProducts as $product)
                    <div class="col-lg-12">
                        <div class="ltn__product-item ltn__product-item-3 text-center">
                            <div class="product-img">
                                <a href="{{ route('products.detail', $product->slug) }}"> <img
                                        src="{{ asset('assets/admin/img/product/' . $product->image) }}"
                                        alt="{{ $product->name }}"
                                        style="height:100px;width:100px; object-fit:cover;"></a>
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
                                    @include('clients.components.includes.rating', [
                                        'product' => $product,
                                    ])
                                </div>
                                <h2 class="product-title"><a
                                        href="{{ route('products.detail', $product->slug) }}">{{ $product->name }}</a>
                                </h2>
                                @php
                                    $prices = $product->variants->pluck('price')->sort()->values();
                                @endphp

                                <div class="product-price">
                                    @if ($prices->count() > 1)
                                        <span>{{ number_format($prices->first(), 0, ',', '.') }} –
                                            {{ number_format($prices->last(), 0, ',', '.') }} VNĐ</span>
                                    @elseif($prices->count() == 1)
                                        <span>{{ number_format($prices->first(), 0, ',', '.') }} VNĐ</span>
                                    @else
                                        <span>{{ number_format($product->price, 0, ',', '.') }} VNĐ</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @foreach ($relatedProducts as $product)
                @include('clients.components.includes.include-modals')
            @endforeach
        </div>
    </div>
    <!-- PRODUCT SLIDER AREA END -->
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const variants = @json($jsVariants);

        const colorInput = document.getElementById('color-value');
        const sizeInput = document.getElementById('size-value');
        const stockEl = document.getElementById('variant-stock');
        const qtyBox = document.getElementById('cart-qty-box');
        const priceEl = document.querySelector('.product-price span');

        // Lấy dao động giá ban đầu
        const allPrices = variants.map(v => v.sale_price ?? v.price).sort((a, b) => a - b);
        if (allPrices.length > 1) {
            priceEl.textContent = new Intl.NumberFormat('vi-VN').format(allPrices[0]) + ' – ' +
                new Intl.NumberFormat('vi-VN').format(allPrices[allPrices.length - 1]) + ' VNĐ';
        } else if (allPrices.length === 1) {
            priceEl.textContent = new Intl.NumberFormat('vi-VN').format(allPrices[0]) + ' VNĐ';
        } else {
            priceEl.textContent = new Intl.NumberFormat('vi-VN').format({{ $product->price ?? 0 }}) + ' VNĐ';
        }

        function findVariant(color, size) {
            return variants.find(v => v.color_id == color && v.size_id == size) || null;
        }

        function updateVariantUI() {
            const color = colorInput.value;
            const size = sizeInput.value;
            const variant = findVariant(color, size);

            if (variant) {
                // Stock biến thể
                stockEl.textContent = variant.quantity;
                qtyBox.dataset.max = variant.quantity;
                if (parseInt(qtyBox.value) > variant.quantity) qtyBox.value = variant.quantity;

                // Giá biến thể
                const displayPrice = variant.sale_price ?? variant.price;
                priceEl.textContent = new Intl.NumberFormat('vi-VN').format(displayPrice) + ' VNĐ';
            } else {
                // Không có biến thể hợp lệ
                stockEl.textContent = '0';
                qtyBox.dataset.max = 0;
                qtyBox.value = 1;

                // Hiển thị dao động giá ban đầu
                if (allPrices.length > 1) {
                    priceEl.textContent = new Intl.NumberFormat('vi-VN').format(allPrices[0]) + ' – ' +
                        new Intl.NumberFormat('vi-VN').format(allPrices[allPrices.length - 1]) + ' VNĐ';
                } else if (allPrices.length === 1) {
                    priceEl.textContent = new Intl.NumberFormat('vi-VN').format(allPrices[0]) + ' VNĐ';
                } else {
                    priceEl.textContent = new Intl.NumberFormat('vi-VN').format({{ $product->price ?? 0 }}) +
                        ' VNĐ';
                }
            }
        }

        // Chọn màu
        document.querySelectorAll('.color-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.color-btn').forEach(b => b.classList.remove(
                    'btn-primary', 'text-white'));
                this.classList.add('btn-primary', 'text-white');
                colorInput.value = this.dataset.value;
                updateVariantUI();
            });
        });

        // Chọn size
        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.size-btn').forEach(b => b.classList.remove(
                    'btn-primary', 'text-white'));
                this.classList.add('btn-primary', 'text-white');
                sizeInput.value = this.dataset.value;
                updateVariantUI();
            });
        });

        // Quantity controls
        document.querySelectorAll('.cart-plus-minus').forEach(wrapper => {
            const dec = wrapper.querySelector('.dec');
            const inc = wrapper.querySelector('.inc');
            const input = wrapper.querySelector('.cart-plus-minus-box');
            if (!dec || !inc || !input) return;

            dec.addEventListener('click', () => {
                let val = parseInt(input.value) || 1;
                if (val > 1) input.value = val - 1;
            });
            inc.addEventListener('click', () => {
                let val = parseInt(input.value) || 1;
                const max = parseInt(input.dataset.max) || 0;
                if (val < max) input.value = val + 1;
            });
        });

        // Add to cart
        document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (btn.disabled) return;
                btn.disabled = true;

                const productId = this.dataset.id;
                const quantity = parseInt(qtyBox.value) || 1;
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content');
                const chosenVariant = findVariant(colorInput.value, sizeInput.value);

                if (variants.length > 0 && !chosenVariant) {
                    alert('Vui lòng chọn biến thể hợp lệ!');
                    btn.disabled = false;
                    return;
                }

                if (chosenVariant && chosenVariant.quantity < quantity) {
                    alert('Số lượng vượt quá tồn kho!');
                    btn.disabled = false;
                    return;
                }

                fetch('/cart/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            variant_id: chosenVariant ? chosenVariant.id : null,
                            quantity: quantity
                        })
                    })
                    .then(res => {
                        if (res.status === 401) window.location.href = '/login';
                        return res.json();
                    })
                    .then(data => {
                        if (data.success || data.message === true) {
                            const cartCountElem = document.querySelector('#cart-count');
                            if (cartCountElem && data.cart_count !== undefined)
                                cartCountElem.innerText = data.cart_count;
                            alert('Đã thêm vào giỏ hàng! Số lượng: ' + quantity);
                        } else {
                            alert(data.message || 'Có lỗi xảy ra');
                        }
                    })
                    .catch(() => alert('Lỗi mạng, thử lại sau.'))
                    .finally(() => btn.disabled = false);
            });
        });

        // Init
        updateVariantUI();
    });
</script>
