<!-- FEATURE AREA START (Fashion Shop Features) -->
<div class="ltn__feature-area py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <!-- 1 -->
            <div class="col-xl-3 col-md-6 col-12 mb-4">
                <div class="feature-card">
                    <div class="icon-box">
                        <i class="fas fa-tshirt"></i>
                    </div>
                    <h4>Chất lượng hàng đầu</h4>
                    <p>Cam kết sản phẩm chính hãng, chất vải bền đẹp và đường may tinh tế đến từng chi tiết.</p>
                </div>
            </div>
            <!-- 2 -->
            <div class="col-xl-3 col-md-6 col-12 mb-4">
                <div class="feature-card">
                    <div class="icon-box">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <h4>Đổi trả dễ dàng</h4>
                    <p>Hỗ trợ đổi trả trong 7 ngày nếu sản phẩm bị lỗi hoặc không vừa size.</p>
                </div>
            </div>
            <!-- 3 -->
            <div class="col-xl-3 col-md-6 col-12 mb-4">
                <div class="feature-card">
                    <div class="icon-box">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h4>Giao hàng toàn quốc</h4>
                    <p>Ship COD toàn quốc, nhận hàng kiểm tra thoải mái trước khi thanh toán.</p>
                </div>
            </div>
            <!-- 4 -->
            <div class="col-xl-3 col-md-6 col-12 mb-4">
                <div class="feature-card">
                    <div class="icon-box">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4>Hỗ trợ 24/7</h4>
                    <p>Đội ngũ tư vấn viên luôn sẵn sàng hỗ trợ bạn chọn lựa sản phẩm phù hợp nhất.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- FEATURE AREA END -->
<style>
/* Feature Section */
.feature-card {
    background: #fff;
    padding: 35px 25px;
    border-radius: 15px;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.35s ease;
    height: 100%;
}

.feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
}

/* Icon Box */
.icon-box {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #eaf7ef; /* nền xanh nhạt */
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    transition: all 0.3s ease;
}

.icon-box i {
    font-size: 34px;
    color: #198754; /* xanh lá Bootstrap */
    transition: all 0.3s ease;
}

/* Hover effects */
.feature-card:hover .icon-box {
    background: #198754;
}

.feature-card:hover .icon-box i {
    color: #fff;
    transform: scale(1.15);
}

/* Text */
.feature-card h4 {
    font-weight: 700;
    margin-bottom: 12px;
    font-size: 20px;
    color: #0f5132; /* xanh lá đậm */
}

.feature-card p {
    font-size: 15px;
    color: #555;
    margin-bottom: 0;
}
</style>
