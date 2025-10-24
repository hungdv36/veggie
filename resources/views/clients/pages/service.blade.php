@extends('layouts.client')

@section('title', 'Dịch vụ')
@section('breadcrumb', 'Dịch vụ')

@section('content')
<!-- SERVICE INTRO AREA START -->
<section class="py-5" style="background:#f8f9fa;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 text-center mb-4 mb-lg-0">
                <div class="position-relative">
                    <i class="fas fa-shirt fa-8x text-success" style="opacity:.15; position:absolute; top:0; left:0; right:0; margin:auto;"></i>
                    <i class="fas fa-truck-fast fa-5x text-success position-relative" style="z-index:2;"></i>
                </div>
            </div>
            <div class="col-lg-7">
                <div>
                    <h6 class="text-uppercase fw-bold" style="color:#1EB980;">// Dịch vụ đáng tin cậy</h6>
                    <h2 class="fw-bold mb-3">Phong cách & Chuyên nghiệp<span style="color:#1EB980;">.</span></h2>
                    <p class="text-muted">Chúng tôi cam kết mang đến trải nghiệm mua sắm hoàn hảo cho tín đồ thời trang: sản phẩm chất lượng, dịch vụ tận tâm và giao hàng nhanh chóng.</p>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2"><i class="fas fa-truck text-success me-2"></i> Giao hàng nhanh toàn quốc</li>
                        <li class="mb-2"><i class="fas fa-sync text-success me-2"></i> Đổi trả miễn phí trong 7 ngày</li>
                        <li class="mb-2"><i class="fas fa-headset text-success me-2"></i> Hỗ trợ khách hàng 24/7</li>
                        <li class="mb-2"><i class="fas fa-shield-alt text-success me-2"></i> Thanh toán an toàn, bảo mật</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- SERVICE INTRO AREA END -->

<!-- SERVICE LIST AREA START -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Dịch vụ của chúng tôi</h2>
            <p class="text-muted">Chăm sóc khách hàng từ trái tim – mang phong cách đến từng chi tiết</p>
        </div>

        <div class="row g-4">
            @php
                $services = [
                    ['icon'=>'fa-shirt','title'=>'Thiết kế độc quyền','desc'=>'Những bộ sưu tập thời trang được thiết kế riêng, giúp bạn thể hiện phong cách cá nhân.'],
                    ['icon'=>'fa-truck-fast','title'=>'Giao hàng nhanh 24/7','desc'=>'Hỗ trợ giao hàng toàn quốc, nhanh chóng, đảm bảo an toàn sản phẩm.'],
                    ['icon'=>'fa-rotate-left','title'=>'Đổi trả dễ dàng','desc'=>'Đổi trả trong 7 ngày nếu sản phẩm bị lỗi hoặc không vừa.'],
                    ['icon'=>'fa-gem','title'=>'Sản phẩm cao cấp','desc'=>'Chất liệu được chọn lọc kỹ lưỡng, mang lại trải nghiệm cao cấp.'],
                    ['icon'=>'fa-headset','title'=>'Tư vấn tận tâm','desc'=>'Đội ngũ hỗ trợ luôn sẵn sàng tư vấn để bạn chọn sản phẩm phù hợp.'],
                    ['icon'=>'fa-credit-card','title'=>'Thanh toán tiện lợi','desc'=>'Chấp nhận nhiều hình thức thanh toán, an toàn & nhanh chóng.'],
                ];
            @endphp

            @foreach ($services as $s)
            <div class="col-md-6 col-lg-4">
                <div class="card text-center border-0 shadow-sm h-100 rounded-4 p-4 hover-up">
                    <div class="mb-3">
                        <i class="fas {{ $s['icon'] }} fa-3x text-success mb-3"></i>
                    </div>
                    <h5 class="fw-bold">{{ $s['title'] }}</h5>
                    <p class="text-muted small">{{ $s['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- SERVICE LIST AREA END -->

<!-- OUR JOURNEY AREA START -->
<section class="py-5" style="background: linear-gradient(120deg,#1EB980,#149a71); color:#fff;">
    <div class="container text-center">
        <h2 class="fw-bold mb-4">Hành trình phát triển</h2>
        <div class="row justify-content-center text-start text-white">
            <div class="col-lg-10">
                <div class="timeline position-relative ps-4">
                    <div class="timeline-item mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-seedling fa-lg me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">2015 – Khởi đầu hành trình</h6>
                                <p class="mb-0 small text-light">Ra mắt thương hiệu thời trang đầu tiên, tập trung vào phong cách trẻ trung & năng động.</p>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-award fa-lg me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">2018 – Đạt giải thưởng uy tín</h6>
                                <p class="mb-0 small text-light">Nhận giải “Thương hiệu thời trang được yêu thích nhất”.</p>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-store fa-lg me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">2020 – Mở rộng hệ thống</h6>
                                <p class="mb-0 small text-light">Khai trương 15 chi nhánh mới trên toàn quốc, khẳng định uy tín thương hiệu.</p>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-globe fa-lg me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">2024 – Vươn ra quốc tế</h6>
                                <p class="mb-0 small text-light">Mở rộng thị trường Đông Nam Á, đưa thương hiệu thời trang Việt ra thế giới.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- OUR JOURNEY AREA END -->

<!-- Custom CSS -->
<style>
.hover-up { transition: all 0.3s ease; }
.hover-up:hover { transform: translateY(-6px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
.timeline::before {
    content: "";
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: rgba(255,255,255,0.3);
}
.timeline-item { position: relative; }
.timeline-item i { color:#fff; background:rgba(255,255,255,0.15); padding:10px; border-radius:50%; }
</style>
@endsection
