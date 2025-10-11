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
                                            @foreach ($product->images as $image)
                                                <a href="{{ asset('storage/' . $image->image) }}"
                                                    data-rel="lightcase:myCollection">
                                                    <img src="{{ asset('storage/' . $image->image) }}"
                                                        alt="{{ $product->name }}">
                                                </a>
                                            @endforeach
                                        </div>

                                    </div>
                                    <div class="ltn__shop-details-small-img slick-arrow-2">
                                        @foreach ($product->images as $image)
                                            <div class="single-small-img">
                                                <img src="{{ asset('storage/' . $image->image) }}"
                                                    alt="{{ $product->name }}">
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="modal-product-info shop-details-info pl-0">
                                    <div class="product-ratting">
                                        <ul>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li class="review-total"> <a href="#"> ( 95 Reviews )</a></li>
                                        </ul>
                                    </div>
                                    <h3>{{ $product->name }}</h3>
                                    <div class="product-price">
                                        <span>{{ number_format($product->price, 0, ',', '.') }} VNĐ</span>
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
                                                            <label for="variant-color"
                                                                class="form-label fw-semibold d-block">Màu sắc:</label>
                                                            @if ($colors->count() > 0)
                                                                <select id="variant-color" class="form-select variant-color"
                                                                    name="color">
                                                                    <option value="">-- Chọn màu --</option>
                                                                    @foreach ($colors as $color)
                                                                        <option value="{{ $color }}">
                                                                            {{ ucfirst($color) }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                                <p class="text-muted mb-0">Không có</p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Kích thước -->
                                                    <div class="col-md-6 col-12">
                                                        <div class="mb-2">
                                                            <label for="variant-size"
                                                                class="form-label fw-semibold d-block">Kích thước:</label>
                                                            @if ($sizes->count() > 0)
                                                                <select id="variant-size" class="form-select variant-size"
                                                                    name="size">
                                                                    <option value="">-- Chọn size --</option>
                                                                    @foreach ($sizes as $size)
                                                                        <option value="{{ $size }}">
                                                                            {{ strtoupper($size) }}</option>
                                                                    @endforeach
                                                                </select>
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
                                                <div class="cart-plus-minus">
                                                    <div class="dec qtybutton">-</div>
                                                    <input id="cart-qty-box" type="text" value="1" name="qtybutton"
                                                        class="cart-plus-minus-box" readonly
                                                        data-max="{{ $product->stock }}">
                                                    <div class="inc qtybutton">+</div>
                                                </div>
                                                <p class="text-muted small mt-1">Kho: <span
                                                        id="variant-stock">{{ $product->stock ?? '--' }}</span></p>
                                            </li>
                                            <li>
                                                <a href="#" class="theme-btn-1 btn btn-effect-1 add-to-cart-btn"
                                                    title="Thêm vào giỏ hàng" data-id="{{ $product->id }}">
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
                                    <hr>
                                    <div class="ltn__social-media">
                                        <ul>
                                            <li>Chia sẻ:</li>
                                            <li><a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                            </li>
                                            <li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                            <li><a href="#" title="Linkedin"><i class="fab fa-linkedin"></i></a>
                                            </li>
                                            <li><a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                                            </li>

                                        </ul>
                                    </div>
                                    <hr>
                                    <div class="ltn__safe-checkout">
                                        <h5>Đảm bảo thanh toán an toàn</h5>
                                        <img src="{{ asset('assets/clients/img/icons/payment-2.png') }}"
                                            alt="Payment Image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Shop Tab Start -->
                    <div class="ltn__shop-details-tab-inner ltn__shop-details-tab-inner-2">
                        <div class="ltn__shop-details-tab-menu">
                            <div class="nav">
                                <a class="active show" data-bs-toggle="tab" href="#liton_tab_details_description">Mô tả</a>
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
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li><a href="#"><i class="fas fa-star"></i></a></li>
                                            <li class="review-total"> <a href="#"> ( 95 Reviews )</a></li>
                                        </ul>
                                    </div>
                                    <hr>
                                    <!-- comment-area -->
                                    <div class="ltn__comment-area mb-30">
                                        <div class="ltn__comment-inner">
                                            <ul>
                                                <li>
                                                    <div class="ltn__comment-item clearfix">
                                                        <div class="ltn__commenter-img">
                                                            <img src="img/testimonial/1.jpg" alt="Image">
                                                        </div>
                                                        <div class="ltn__commenter-comment">
                                                            <h6><a href="#">Adam Smit</a></h6>
                                                            <div class="product-ratting">
                                                                <ul>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a>
                                                                    </li>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a>
                                                                    </li>
                                                                    <li><a href="#"><i class="fas fa-star"></i></a>
                                                                    </li>
                                                                    <li><a href="#"><i
                                                                                class="fas fa-star-half-alt"></i></a>
                                                                    </li>
                                                                    <li><a href="#"><i class="far fa-star"></i></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing
                                                                elit. Doloribus, omnis fugit corporis iste magnam
                                                                ratione.</p>
                                                            <span class="ltn__comment-reply-btn">September 3,
                                                                2020</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- comment-reply -->
                                    <div class="ltn__comment-reply-area ltn__form-box mb-30">
                                        <form action="#">
                                            <h4 class="title-2">Thêm đánh giá</h4>
                                            <div class="mb-30">
                                                <div class="add-a-review">
                                                    <h6>Số sao:</h6>
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
                                                </div>
                                            </div>
                                            <div class="input-item input-item-textarea ltn__custom-icon">
                                                <textarea placeholder="Type your comments...."></textarea>
                                            </div>
                                            <div class="input-item input-item-name ltn__custom-icon">
                                                <input type="text" placeholder="Type your name....">
                                            </div>
                                            <div class="input-item input-item-email ltn__custom-icon">
                                                <input type="email" placeholder="Type your email....">
                                            </div>
                                            <div class="input-item input-item-website ltn__custom-icon">
                                                <input type="text" name="website" placeholder="Type your website....">
                                            </div>
                                            <label class="mb-0"><input type="checkbox" name="agree"> Save my name,
                                                email, and website in this browser for the next time I
                                                comment.</label>
                                            <div class="btn-wrapper">
                                                <button class="btn theme-btn-1 btn-effect-1 text-uppercase"
                                                    type="submit">Submit</button>
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
                                <a href="{{ route('products.detail', $product->slug) }}"><img
                                        src="{{ $product->image_url }}" alt="{{ $product->name }}"></a>
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
                                <h2 class="product-title"><a
                                        href="{{ route('products.detail', $product->slug) }}">{{ $product->name }}</a>
                                </h2>
                                <div class="product-price">
                                    <span>{{ number_format($product->price, 0, ',', '.') }} VNĐ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- PRODUCT SLIDER AREA END -->
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const variants = @json($jsVariants ?? []);
        console.log('variants', variants);

        function norm(v) {
            return (v === null || v === undefined) ? '' : String(v).toLowerCase().trim();
        }

        function findVariant(color, size) {
            const nc = norm(color),
                ns = norm(size);
            return variants.find(v => {
                if (nc && norm(v.color) !== nc) return false;
                if (ns && norm(v.size) !== ns) return false;
                return true;
            }) || null;
        }

        function formatPrice(vnd) {
            return new Intl.NumberFormat('vi-VN').format(vnd) + ' VNĐ';
        }

        function updateVariantUI() {
            const color = document.getElementById('variant-color') ? document.getElementById('variant-color')
                .value : '';
            const size = document.getElementById('variant-size') ? document.getElementById('variant-size')
                .value : '';
            const variant = findVariant(color, size);

            const priceEl = document.querySelector('.modal-product-info .product-price span');
            const stockEl = document.getElementById('variant-stock');
            const qtyBox = document.getElementById('cart-qty-box');

            if (variant) {
                if (priceEl) priceEl.textContent = formatPrice(variant.price);
                if (stockEl) stockEl.textContent = variant.stock;
                if (qtyBox) {
                    qtyBox.dataset.max = variant.stock;
                    let val = parseInt(qtyBox.value) || 1;
                    if (val > variant.stock) qtyBox.value = Math.max(1, variant.stock);
                }
            } else {
                if (priceEl) priceEl.textContent = formatPrice({{ $product->price ?? 0 }});
                if (stockEl) stockEl.textContent = '{{ $product->stock ?? '--' }}';
                if (qtyBox) qtyBox.dataset.max = '{{ $product->stock ?? 0 }}';
            }
        }

        const selColor = document.getElementById('variant-color');
        const selSize = document.getElementById('variant-size');
        if (selColor) selColor.addEventListener('change', updateVariantUI);
        if (selSize) selSize.addEventListener('change', updateVariantUI);

        // quantity controls
        document.querySelectorAll('.cart-plus-minus').forEach(wrapper => {
            const decBtn = wrapper.querySelector('.dec');
            const incBtn = wrapper.querySelector('.inc');
            const input = wrapper.querySelector('.cart-plus-minus-box');
            if (!decBtn || !incBtn || !input) return;

            decBtn.addEventListener('click', () => {
                let val = parseInt(input.value) || 1;
                if (val > 1) input.value = val - 1;
            });
            incBtn.addEventListener('click', () => {
                let val = parseInt(input.value) || 1;
                const max = parseInt(input.dataset.max) || 9999;
                if (val < max) input.value = val + 1;
            });
        });

        // add to cart
        document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (btn.disabled) return;
                btn.disabled = true;

                const productId = this.dataset.id;
                const qtyInput = document.getElementById('cart-qty-box');
                const quantity = qtyInput ? parseInt(qtyInput.value) || 1 : 1;
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content');

                const hasVariants = variants.length > 0;
                const selectedColor = selColor ? selColor.value : '';
                const selectedSize = selSize ? selSize.value : '';
                const chosenVariant = hasVariants ? findVariant(selectedColor, selectedSize) :
                    null;

                if (hasVariants && !chosenVariant) {
                    alert('Vui lòng chọn thuộc tính sản phẩm (màu / size) hợp lệ.');
                    btn.disabled = false;
                    return;
                }

                if (chosenVariant && chosenVariant.stock < quantity) {
                    alert('Số lượng vượt quá tồn kho của biến thể.');
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

        // init
        updateVariantUI();
    });
</script>
