@extends('layouts.client')

@section('title', 'Về chúng tôi')
@section('breadcrumb', 'Về chúng tôi')

@section('content')

<!-- ABOUT US AREA START -->
<section class="about-area py-100 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <!-- Ảnh minh hoạ -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="position-relative">
                    <img src="{{ asset('client-assets/img/about-fashion.jpg') }}" 
                         alt="Về chúng tôi" 
                         class="img-fluid rounded-4 shadow-sm">
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-10 rounded-4"></div>
                </div>
            </div>

            <!-- Nội dung giới thiệu -->
            <div class="col-lg-6">
                <div class="ps-lg-4">
                    <h6 class="text-uppercase fw-bold mb-2" style="color: #1EB980;">Về cửa hàng</h6>
                    <h1 class="display-6 fw-bold mb-3">
                        Cửa hàng thời trang <span style="color: #1EB980;">ClotheStore</span>
                    </h1>
                    <p class="lead text-muted">
                        Chúng tôi mang đến cho bạn những bộ trang phục tinh tế, đẳng cấp và phù hợp với mọi phong cách. 
                        Mỗi sản phẩm đều được lựa chọn kỹ lưỡng từ các thương hiệu hàng đầu, giúp bạn tự tin thể hiện cá tính.
                    </p>
                    <p class="text-secondary">
                        Với sứ mệnh nâng tầm phong cách người Việt, ClotheStore luôn hướng đến sự hài lòng tuyệt đối 
                        của khách hàng qua từng chi tiết nhỏ nhất – từ chất liệu, kiểu dáng đến dịch vụ chăm sóc tận tâm.
                    </p>                        
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ABOUT US AREA END -->

<!-- WHY CHOOSE US AREA START -->
<section class="why-choose-area py-100">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="text-uppercase fw-bold" style="color: #1EB980;">// Lý do chọn chúng tôi //</h6>
            <h1 class="display-6 fw-bold">
                Tại sao khách hàng yêu thích <span style="color: #1EB980;">ClotheStore</span>
            </h1>
        </div>

        <div class="row g-4">
            <!-- 1 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4 rounded-4 feature-card">
                    <div class="icon-box mb-3">
                        <i class="fas fa-tshirt" style="font-size: 40px; color: #1EB980;"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Bộ sưu tập đa dạng</h4>
                    <p class="text-muted mb-0">
                        Hàng trăm mẫu thiết kế mới được cập nhật mỗi tuần, từ phong cách thanh lịch đến năng động.
                    </p>
                </div>
            </div>
            <!-- 2 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4 rounded-4 feature-card">
                    <div class="icon-box mb-3">
                        <i class="fas fa-crown" style="font-size: 40px; color: #1EB980;"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Chất lượng hàng đầu</h4>
                    <p class="text-muted mb-0">
                        Từng sản phẩm đều được kiểm duyệt kỹ lưỡng về chất liệu và đường may để đảm bảo sự hoàn hảo tuyệt đối.
                    </p>
                </div>
            </div>
            <!-- 3 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4 rounded-4 feature-card">
                    <div class="icon-box mb-3">
                        <i class="fas fa-shipping-fast" style="font-size: 40px; color: #1EB980;"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Giao hàng nhanh chóng</h4>
                    <p class="text-muted mb-0">
                        Dịch vụ giao hàng toàn quốc siêu tốc, hỗ trợ đổi trả miễn phí trong 7 ngày.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- WHY CHOOSE US AREA END -->

<!-- Custom CSS -->
<style>
    .feature-card {
        transition: all 0.3s ease;
        background: #fff;
    }
    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 24px rgba(30, 185, 128, 0.25);
    }
    .icon-box {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        background: rgba(30, 185, 128, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
</style>

@endsection
