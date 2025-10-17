@extends('layouts.client')

@section('title', 'FAQ')

@section('breadcrumb', 'FAQ')

@section('content')
<!-- KHU VỰC FAQ BẮT ĐẦU (faq-2) (ID > accordion_2) -->
<div class="ltn__faq-area mb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="ltn__faq-inner ltn__faq-inner-2">
                    <div id="accordion_2">
                        <!-- card -->
                        <div class="card">
                            <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-1" aria-expanded="false">
                                Làm thế nào để mua sản phẩm?
                            </h6>
                            <div id="faq-item-2-1" class="collapse" data-parent="#accordion_2">
                                <div class="card-body">
                                    <p>Bạn chỉ cần chọn sản phẩm mong muốn, thêm vào giỏ hàng và tiến hành thanh toán.
                                        Sau khi đặt hàng thành công, chúng tôi sẽ xác nhận và giao hàng đến địa chỉ của bạn
                                        trong thời gian sớm nhất.</p>
                                </div>
                            </div>
                        </div>
                        <!-- card -->
                        <div class="card">
                            <h6 class="ltn__card-title" data-bs-toggle="collapse" data-bs-target="#faq-item-2-2"
                                aria-expanded="true">
                                Tôi có thể hoàn tiền từ website của bạn bằng cách nào?
                            </h6>
                            <div id="faq-item-2-2" class="collapse show" data-parent="#accordion_2">
                                <div class="card-body">
                                    <div class="ltn__video-img alignleft">
                                        <img src="img/bg/17.jpg" alt="hình nền video">
                                        <a class="ltn__video-icon-2 ltn__video-icon-2-small ltn__video-icon-2-border----"
                                            href="https://www.youtube.com/embed/LjCzPp-MK48?autoplay=1&amp;showinfo=0"
                                            data-rel="lightcase:myCollection">
                                            <i class="fa fa-play"></i>
                                        </a>
                                    </div>
                                    <p>Bạn có thể yêu cầu hoàn tiền bằng cách liên hệ với bộ phận chăm sóc khách hàng
                                        hoặc gửi yêu cầu qua trang <strong>Liên hệ</strong>. Sau khi chúng tôi kiểm tra
                                        và xác nhận lý do, tiền sẽ được hoàn lại vào tài khoản/thẻ của bạn
                                        trong vòng 3–7 ngày làm việc.</p>
                                </div>
                            </div>
                        </div>
                        <!-- card -->
                        <div class="card">
                            <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-3" aria-expanded="false">
                                Tôi là người dùng mới. Tôi nên bắt đầu như thế nào?
                            </h6>
                            <div id="faq-item-2-3" class="collapse" data-parent="#accordion_2">
                                <div class="card-body">
                                    <p>Nếu bạn mới lần đầu sử dụng, hãy đăng ký một tài khoản miễn phí. 
                                        Sau đó bạn có thể đăng nhập, thêm sản phẩm yêu thích vào giỏ hàng 
                                        và tiến hành đặt hàng một cách dễ dàng.</p>
                                </div>
                            </div>
                        </div>
                        <!-- card -->
                        <div class="card">
                            <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-4" aria-expanded="false">
                                Chính sách đổi trả và hoàn tiền
                            </h6>
                            <div id="faq-item-2-4" class="collapse" data-parent="#accordion_2">
                                <div class="card-body">
                                    <p>Bạn có thể đổi trả sản phẩm trong vòng <strong>7 ngày</strong> 
                                        kể từ khi nhận hàng nếu sản phẩm bị lỗi, hư hỏng hoặc không đúng 
                                        như mô tả. Hoàn tiền sẽ được thực hiện sau khi chúng tôi nhận lại sản phẩm.</p>
                                </div>
                            </div>
                        </div>
                        <!-- card -->
                        <div class="card">
                            <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-5" aria-expanded="false">
                                Thông tin cá nhân của tôi có được bảo mật không?
                            </h6>
                            <div id="faq-item-2-5" class="collapse" data-parent="#accordion_2">
                                <div class="card-body">
                                    <p>Chúng tôi cam kết bảo mật thông tin cá nhân của khách hàng.
                                        Mọi dữ liệu đều được mã hóa và chỉ sử dụng cho mục đích xử lý đơn hàng.
                                        Thông tin sẽ không được chia sẻ với bên thứ ba nếu không có sự đồng ý của bạn.</p>
                                </div>
                            </div>
                        </div>
                        <!-- card -->
                        <div class="card">
                            <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-6" aria-expanded="false">
                                Mã giảm giá không hoạt động
                            </h6>
                            <div id="faq-item-2-6" class="collapse" data-parent="#accordion_2">
                                <div class="card-body">
                                    <p>Nếu mã giảm giá không hoạt động, hãy kiểm tra lại thời hạn sử dụng,
                                        điều kiện áp dụng hoặc xem mã đã được nhập chính xác chưa. 
                                        Nếu vẫn gặp sự cố, vui lòng liên hệ với bộ phận hỗ trợ để được giúp đỡ.</p>
                                </div>
                            </div>
                        </div>
                        <!-- card -->
                        <div class="card">
                            <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-7" aria-expanded="false">
                                Tôi thanh toán bằng thẻ tín dụng như thế nào?
                            </h6>
                            <div id="faq-item-2-7" class="collapse" data-parent="#accordion_2">
                                <div class="card-body">
                                    <p>Bạn có thể thanh toán bằng thẻ tín dụng một cách an toàn thông qua cổng thanh toán trực tuyến của chúng tôi.
                                        Chỉ cần chọn phương thức “Thẻ tín dụng” ở bước thanh toán và nhập thông tin thẻ. 
                                        Tất cả giao dịch đều được mã hóa để đảm bảo an toàn.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="need-support text-center mt-100">
                        <h2>Vẫn cần hỗ trợ? Liên hệ hỗ trợ 24/7:</h2>
                        <div class="btn-wrapper mb-30">
                            <a href="contact.html" class="theme-btn-1 btn">Liên hệ với chúng tôi</a>
                        </div>
                        <h3><i class="fas fa-phone"></i> +0123-456-789</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <aside class="sidebar-area ltn__right-sidebar">
                    <!-- Newsletter Widget -->
                    <div class="widget ltn__search-widget ltn__newsletter-widget">
                        <h6 class="ltn__widget-sub-title">// đăng ký</h6>
                        <h4 class="ltn__widget-title">Nhận bản tin</h4>
                        <form action="#">
                            <input type="text" name="search" placeholder="Tìm kiếm">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                        <div class="ltn__newsletter-bg-icon">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                    </div>
                    <!-- Banner Widget -->
                    <div class="widget ltn__banner-widget">
                        <a href="shop.html"><img src="img/banner/banner-3.jpg" alt="Hình banner"></a>
                    </div>

                </aside>
            </div>
        </div>
    </div>
</div>
<!-- KHU VỰC FAQ KẾT THÚC -->


        <!-- COUNTER UP AREA START -->
<div class="ltn__counterup-area bg-image bg-overlay-theme-black-80 pt-115 pb-70" data-bg="img/bg/5.jpg">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6 align-self-center">
                <div class="ltn__counterup-item-3 text-color-white text-center">
                    <div class="counter-icon"> <img src="img/icons/icon-img/2.png" alt="#"> </div>
                    <h1><span class="counter">733</span><span class="counterUp-icon">+</span> </h1>
                    <h6>Khách hàng đang hoạt động</h6>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 align-self-center">
                <div class="ltn__counterup-item-3 text-color-white text-center">
                    <div class="counter-icon"> <img src="img/icons/icon-img/3.png" alt="#"> </div>
                    <h1><span class="counter">33</span><span class="counterUp-letter">K</span><span
                            class="counterUp-icon">+</span> </h1>
                    <h6>Tách cà phê đã thưởng thức</h6>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 align-self-center">
                <div class="ltn__counterup-item-3 text-color-white text-center">
                    <div class="counter-icon"> <img src="img/icons/icon-img/4.png" alt="#"> </div>
                    <h1><span class="counter">100</span><span class="counterUp-icon">+</span> </h1>
                    <h6>Phần thưởng đạt được</h6>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 align-self-center">
                <div class="ltn__counterup-item-3 text-color-white text-center">
                    <div class="counter-icon"> <img src="img/icons/icon-img/5.png" alt="#"> </div>
                    <h1><span class="counter">21</span><span class="counterUp-icon">+</span> </h1>
                    <h6>Quốc gia phủ sóng</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- COUNTER UP AREA END -->

@endsection