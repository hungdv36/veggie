<!-- FOOTER AREA START -->
<footer class="footer-modern text-light pt-5">
    <div class="container pb-4 border-bottom border-secondary">
        <div class="row gy-5">
            <!-- Logo & About -->
            <div class="col-lg-3 col-md-6">
                <div class="mb-3">
                    <img src="{{ asset('assets/clients/img/logo1.png') }}" alt="Logo" class="img-fluid mb-3"
                        style="max-width: 180px;">
                </div>
                <p class="small text-secondary mb-3">
                    ClothStore – cửa hàng thời trang hàng đầu mang phong cách hiện đại và tinh tế.
                </p>
                <ul class="list-unstyled small">
                    <li class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i>Trịnh Văn Bô, Hà Nội</li>
                    <li class="mb-2"><i class="fas fa-phone text-primary me-2"></i><a href="tel:+0123-456789"
                            class="text-light text-decoration-none">+0123 456 789</a></li>
                    <li><i class="fas fa-envelope text-primary me-2"></i><a
                            href="mailto:clothstore.dev@gmail.com"
                            class="text-light text-decoration-none">clothstore.dev@gmail.com</a></li>
                </ul>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="text-light fs-5"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-light fs-5"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-light fs-5"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-light fs-5"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <!-- Công ty -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold text-uppercase text-white mb-3">Công ty</h6>
                <ul class="footer-links list-unstyled">
                    <li><a href="{{ url('/about') }}">Về chúng tôi</a></li>
                    <li><a href="{{ url('/shop') }}">Sản phẩm</a></li>
                    <li><a href="{{ url('/faq') }}">FAQ</a></li>
                    <li><a href="{{ url('/contact') }}">Liên hệ</a></li>
                </ul>
            </div>

            <!-- Dịch vụ -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold text-uppercase text-white mb-3">Dịch vụ</h6>
                <ul class="footer-links list-unstyled">
                    <li><a href="{{ url('/order-tracking') }}">Theo dõi đơn hàng</a></li>
                    <li><a href="{{ url('/wishlist') }}">Danh sách yêu thích</a></li>
                    <li><a href="{{ url('/login') }}">Đăng nhập</a></li>
                    <li><a href="{{ url('/account') }}">Tài khoản</a></li>
                    <li><a href="{{ url('/policy') }}">Điều khoản & Điều kiện</a></li>
                    <li><a href="{{ url('/promotions') }}">Khuyến mãi</a></li>
                </ul>
            </div>

            <!-- Hỗ trợ khách hàng -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold text-uppercase text-white mb-3">Hỗ trợ</h6>
                <ul class="footer-links list-unstyled">
                    <li><a href="{{ url('/login') }}">Đăng nhập</a></li>
                    <li><a href="{{ url('/account') }}">Tài khoản</a></li>
                    <li><a href="{{ url('/wishlist') }}">Yêu thích</a></li>
                    <li><a href="{{ url('/order-tracking') }}">Theo dõi đơn hàng</a></li>
                    <li><a href="{{ url('/faq') }}">Câu hỏi thường gặp</a></li>
                    <li><a href="{{ url('/contact') }}">Liên hệ</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold text-uppercase text-white mb-3">Bảng tin</h6>
                <p class="small text-secondary">Đăng ký nhận bản tin để không bỏ lỡ ưu đãi & sản phẩm mới nhất.</p>
                <form class="newsletter-form d-flex mt-3">
                    <input type="email" class="form-control me-2 rounded-pill" placeholder="Nhập email của bạn">
                    <button type="submit" class="btn btn-primary rounded-pill px-3">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
                <div class="mt-4">
                    <h6 class="text-uppercase small text-white mb-2">Chúng tôi chấp nhận</h6>
                    <img src="{{ asset('assets/clients/img/icons/payment.jfif') }}" alt="Payment"
                        class="img-fluid rounded shadow-sm" style="max-width: 180px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="container py-3 text-center small text-secondary">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
            <p class="mb-0">© <span class="current-year"></span> ClothStore. All rights reserved.</p>
            <div>
                <a href="#" class="text-secondary text-decoration-none me-3">Terms</a>
                <a href="#" class="text-secondary text-decoration-none me-3">Privacy</a>
                <a href="#" class="text-secondary text-decoration-none">Support</a>
            </div>
        </div>
    </div>
</footer>
<!-- FOOTER AREA END -->

<!-- Custom Styles -->
<style>
.footer-modern {
    background: #111;
    font-family: 'Poppins', sans-serif;
}
.footer-links li {
    margin-bottom: 0.5rem;
}
.footer-links a {
    color: #ccc;
    text-decoration: none;
    transition: all 0.3s ease;
}
.footer-links a:hover {
    color: #fff;
    padding-left: 5px;
}
.newsletter-form input {
    background: #222;
    border: none;
    color: #fff;
}
.newsletter-form input::placeholder {
    color: #999;
}
.newsletter-form button {
    transition: 0.3s;
}
.newsletter-form button:hover {
    transform: scale(1.05);
}
.btn-primary {
    background-color: #1A7F37 !important;
    border-color: #1A7F37 !important;
}

.btn-primary:hover {
    background-color: #14692D !important;
    border-color: #14692D !important;
}
.text-primary {
    color: #1A7F37 !important;
}
.footer-modern img {
    filter: brightness(1.25) contrast(1.2);
    transition: all 0.3s ease;
}

.footer-modern img:hover {
    filter: brightness(1.4) contrast(1.3);
}


</style>

<script>
document.querySelectorAll('.current-year').forEach(el => {
    el.textContent = new Date().getFullYear();
});
</script>
