<!-- MODAL AREA START (Quick View Modal) -->
<div class="ltn__modal-area ltn__quick-view-modal-area">
    <div class="modal fade" id="quick_view_modal-{{ $product->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="ltn__quick-view-modal-inner">
                        <div class="modal-product-item">
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <div class="modal-product-img">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="modal-product-info">
                                        <div class="product-ratting">
                                             @include('clients.components.includes.rating', [
                                            'product' => $product,
                                           ])
                                        </div>
                                        <h3>{{ $product->name }}</h3>
                                        <div class="product-price">
                                            <span>{{ number_format($product->price, 0, ',', '.') }} VNĐ</span>
                                        </div>
                                        <div class="modal-product-meta ltn__product-details-menu-1">
                                            <ul>
                                                <li>
                                                    <strong>Danh mục:</strong>
                                                    <span>
                                                        <a href="javascript:void(0)">{{ $product->category->name }}</a>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="ltn__product-details-menu-2">
                                            <ul>
                                                <li>
                                                    <div class="cart-plus-minus">
                                                        <div class="dec qtybutton">-</div>
                                                        <input id="cart-qty-box" type="text" value="1"
                                                            name="qtybutton" class="cart-plus-minus-box" readonly
                                                            data-max="{{ $product->stock }}">
                                                        <div class="inc qtybutton">+</div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)"
                                                        class="theme-btn-1 btn btn-effect-1 add-to-cart-btn"
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
                                                    <a href="#" class="" title="Wishlist"
                                                        data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
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
                                                <li><a href="#" title="Facebook"><i
                                                            class="fab fa-facebook-f"></i></a></li>
                                                <li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                                                </li>
                                                <li><a href="#" title="Linkedin"><i
                                                            class="fab fa-linkedin"></i></a></li>
                                                <li><a href="#" title="Instagram"><i
                                                            class="fab fa-instagram"></i></a></li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MODAL AREA END -->
