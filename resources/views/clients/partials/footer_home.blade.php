<!-- FOOTER AREA START -->
<footer class="footer-modern bg-dark text-light pt-5">
    <div class="container">
        <div class="row gy-4">
            <!-- Logo & Info -->
            <div class="col-lg-3 col-md-6">
                <div class="mb-3">
                    <img src="{{ asset('assets/clients/img/logo1.png') }}" alt="Logo"
                        class="img-fluid mb-3" style="max-width: 180px;">
                </div>
                <p class="small text-secondary">
                    Cửa hàng thời trang cao cấp – mang phong cách hiện đại, trẻ trung và tinh tế.
                </p>
                <ul class="list-unstyled small">
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Trịnh Văn Bô, Hà Nội</li>
                    <li class="mb-2"><i class="fas fa-phone me-2 text-primary"></i><a href="tel:+0123456789" class="text-light text-decoration-none">+0123 456 789</a></li>
                    <li><i class="fas fa-envelope me-2 text-primary"></i><a href="mailto:clothstore.dev@gmail.com" class="text-light text-decoration-none">clothstore.dev@gmail.com</a></li>
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
                <h6 class="text-uppercase mb-3 fw-bold text-white">Công ty</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="{{ url('/about') }}">Về chúng tôi</a></li>
                    <li><a href="{{ url('/shop') }}">Sản phẩm</a></li>
                    <li><a href="{{ url('/faq') }}">FAQ</a></li>
                    <li><a href="{{ url('/contact') }}">Liên hệ</a></li>
                </ul>
            </div>

            <!-- Dịch vụ -->
            <div class="col-lg-2 col-md-6">
                <h6 class="text-uppercase mb-3 fw-bold text-white">Dịch vụ</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="{{ url('/order-tracking') }}">Theo dõi đơn hàng</a></li>
                    <li><a href="{{ url('/wishlist') }}">Yêu thích</a></li>
                    <li><a href="{{ url('/account') }}">Tài khoản</a></li>
                    <li><a href="{{ url('/promotions') }}">Khuyến mãi</a></li>
                </ul>
            </div>

            <!-- Hỗ trợ -->
            <div class="col-lg-2 col-md-6">
                <h6 class="text-uppercase mb-3 fw-bold text-white">Hỗ trợ</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="{{ url('/faq') }}">Câu hỏi thường gặp</a></li>
                    <li><a href="{{ url('/policy') }}">Chính sách bảo mật</a></li>
                    <li><a href="{{ url('/terms') }}">Điều khoản dịch vụ</a></li>
                    <li><a href="{{ url('/contact') }}">Liên hệ hỗ trợ</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-uppercase mb-3 fw-bold text-white">Đăng ký nhận tin</h6>
                <p class="small text-secondary mb-3">
                    Nhận thông tin về khuyến mãi, sản phẩm mới và xu hướng thời trang.
                </p>
                <form action="#" method="post" class="d-flex">
                    <input type="email" class="form-control me-2 rounded-pill" placeholder="Nhập email của bạn">
                    <button class="btn btn-primary rounded-pill px-3"><i class="fas fa-paper-plane"></i></button>
                </form>
                <div class="mt-4">
                    <h6 class="text-uppercase small text-white mb-2">Chấp nhận thanh toán</h6>
                    <img src="{{ asset('assets/clients/img/icons/payment.jfif') }}" alt="Payment"
                        class="img-fluid rounded shadow-sm" style="max-width: 180px;">
                </div>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <!-- Copyright -->
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start small text-secondary">
                © <span class="current-year"></span> <strong>ClothStore</strong>. All rights reserved.
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="#" class="text-secondary small me-3">Terms</a>
                <a href="#" class="text-secondary small me-3">Privacy</a>
                <a href="#" class="text-secondary small">Support</a>
            </div>
        </div>
    </div>
</footer>
<!-- FOOTER AREA END -->

<!-- Thêm CSS cho footer -->
<style>
.footer-links li {
    margin-bottom: 0.5rem;
}
.footer-links a {
    color: #ccc;
    text-decoration: none;
    transition: 0.3s;
}
.footer-links a:hover {
    color: #fff;
    padding-left: 5px;
}
.footer-modern {
    background-color: #111;
    color: #ddd;
}
.footer-modern h6 {
    color: #fff;
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
