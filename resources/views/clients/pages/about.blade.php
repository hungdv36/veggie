@extends('layouts.client')

@section('title', 'Về chúng tôi')
@section('breadcrumb', 'Về chúng tôi')

@section('content')
<!-- ABOUT US AREA START -->
        <div class="ltn__about-us-area pt-120--- pb-120">
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
                                <h6 class="section-subtitle ltn__secondary-color">Tìm hiểu thêm về cửa hàng</h6>
                                <h1 class="section-title">Cửa hàng thực phẩm<br class="d-none d-md-block"> Hữu cơ uy tín</h1>
                                <p>Chúng tôi cam kết mang đến những sản phẩm chất lượng, an toàn và tốt cho sức khỏe</p>
                            </div>
                            <p>Những người bán hàng luôn hướng tới điều tốt đẹp, lan tỏa giá trị tích cực.
                                Chúng tôi là một thị trường dân chủ, tự duy trì, hoạt động dựa trên niềm tin, cộng đồng và chất lượng.
                            </p>
                            <div class="about-author-info d-flex">
                                <div class="author-name-designation  align-self-center">
                                    <h4 class="mb-0">Đào Hùng</h4>
                                    <small>/ Giám đốc cửa hàng</small>
                                </div>
                                <div class="author-sign">
                                    <img src="{{ asset('assets/clients/icons/icon-img/author-sign.png') }}" alt="#">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ABOUT US AREA END -->

        <!-- FEATURE AREA START ( Feature - 6) -->
        <div class="ltn__feature-area section-bg-1 pt-115 pb-90">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title-area ltn__section-title-2 text-center">
                            <h6 class="section-subtitle ltn__secondary-color">// Đặc điểm //</h6>
                            <h1 class="section-title">Tại sao chọn chúng tôi<span>.</span></h1>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="ltn__feature-item ltn__feature-item-7">
                            <div class="ltn__feature-icon-title">
                                <div class="ltn__feature-icon">
                                    <span><img src="img/icons/icon-img/21.png" alt="#"></span>
                                </div>
                                <h3><a href="service-details.html">Đa dạng thương hiệu</a></h3>
                            </div>
                            <div class="ltn__feature-info">
                                <p>Chúng tôi cung cấp nhiều thương hiệu uy tín, đảm bảo chất lượng và nguồn gốc rõ ràng.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="ltn__feature-item ltn__feature-item-7">
                            <div class="ltn__feature-icon-title">
                                <div class="ltn__feature-icon">
                                    <span><img src="img/icons/icon-img/22.png" alt="#"></span>
                                </div>
                                <h3><a href="service-details.html">Sản phẩm tuyển chọn</a></h3>
                            </div>
                            <div class="ltn__feature-info">
                                <p>Mỗi sản phẩm đều được chọn lọc kỹ lưỡng, mang đến sự an tâm và hài lòng cho khách hàng</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="ltn__feature-item ltn__feature-item-7">
                            <div class="ltn__feature-icon-title">
                                <div class="ltn__feature-icon">
                                    <span><img src="img/icons/icon-img/23.png" alt="#"></span>
                                </div>
                                <h3><a href="service-details.html">Không chứa thuốc trừ sâu</a></h3>
                            </div>
                            <div class="ltn__feature-info">
                                <p>Chúng tôi cam kết cung cấp sản phẩm hoàn toàn tự nhiên, không chứa hóa chất độc hại.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FEATURE AREA END -->

        
@endsection