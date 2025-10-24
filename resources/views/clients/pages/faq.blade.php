@extends('layouts.client')

@section('title', 'FAQ')
@section('breadcrumb', 'FAQ')

@section('content')

    <!-- FAQ AREA START -->
    <section class="faq-area py-5" style="background:#f7f9f8;">
        <div class="container">
            <div class="row gy-4">
                <!-- LEFT: FAQ CONTENT -->
                <div class="col-lg-8">
                    <div class="text-center mb-5">
                        <h6 class="text-uppercase fw-bold" style="color:#1EB980; letter-spacing:1px;">Câu hỏi thường gặp</h6>
                        <h1 class="display-6 fw-bold mb-2">Giải đáp mọi thắc mắc của bạn</h1>
                        <p class="text-muted">Nếu bạn cần giúp đỡ thêm, vui lòng liên hệ với đội ngũ hỗ trợ 24/7 của chúng
                            tôi.</p>
                    </div>

                    <div class="accordion shadow-sm rounded-4 overflow-hidden" id="faqAccordion">
                        @php
                            $faqs = [
                                [
                                    'id' => 'faq1',
                                    'title' => 'Làm thế nào để mua sản phẩm?',
                                    'body' =>
                                        'Bạn chỉ cần chọn sản phẩm mong muốn, thêm vào giỏ hàng và tiến hành thanh toán. Sau khi đặt hàng thành công, chúng tôi sẽ xác nhận và giao hàng đến địa chỉ của bạn trong thời gian sớm nhất.',
                                ],
                                [
                                    'id' => 'faq2',
                                    'title' => 'Tôi có thể hoàn tiền bằng cách nào?',
                                    'body' =>
                                        'Bạn có thể yêu cầu hoàn tiền bằng cách liên hệ bộ phận chăm sóc khách hàng hoặc gửi yêu cầu qua trang Liên hệ. Sau khi kiểm tra và xác nhận, tiền sẽ được hoàn lại vào tài khoản của bạn trong vòng 3–7 ngày làm việc.',
                                    'show' => true,
                                ],
                                [
                                    'id' => 'faq3',
                                    'title' => 'Tôi là người dùng mới. Bắt đầu như thế nào?',
                                    'body' =>
                                        'Nếu bạn là người dùng mới, hãy đăng ký tài khoản miễn phí, sau đó đăng nhập để thêm sản phẩm yêu thích và đặt hàng.',
                                ],
                                [
                                    'id' => 'faq4',
                                    'title' => 'Chính sách đổi trả và hoàn tiền',
                                    'body' =>
                                        'Bạn có thể đổi trả trong vòng 7 ngày kể từ khi nhận hàng nếu sản phẩm bị lỗi hoặc không đúng mô tả. Hoàn tiền sẽ được thực hiện sau khi chúng tôi nhận lại sản phẩm.',
                                ],
                                [
                                    'id' => 'faq5',
                                    'title' => 'Thông tin cá nhân có được bảo mật không?',
                                    'body' =>
                                        'Chúng tôi cam kết bảo mật thông tin khách hàng; dữ liệu được mã hóa và chỉ dùng cho mục đích xử lý đơn hàng.',
                                ],
                                [
                                    'id' => 'faq6',
                                    'title' => 'Mã giảm giá không hoạt động',
                                    'body' =>
                                        'Kiểm tra thời hạn, điều kiện áp dụng và đảm bảo mã nhập chính xác. Nếu vẫn lỗi, liên hệ bộ phận hỗ trợ.',
                                ],
                                [
                                    'id' => 'faq7',
                                    'title' => 'Thanh toán bằng thẻ tín dụng',
                                    'body' =>
                                        'Bạn có thể thanh toán bằng thẻ tín dụng qua cổng thanh toán an toàn; tất cả giao dịch được mã hóa.',
                                ],
                            ];
                        @endphp

                        @foreach ($faqs as $f)
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header" id="heading-{{ $f['id'] }}">
                                    <button
                                        class="accordion-button d-flex align-items-center @if (empty($f['show'])) collapsed @endif"
                                        type="button" data-bs-toggle="collapse" data-bs-target="#{{ $f['id'] }}"
                                        aria-expanded="{{ !empty($f['show']) ? 'true' : 'false' }}"
                                        aria-controls="{{ $f['id'] }}">
                                        <span
                                            class="me-3 faq-icon d-flex align-items-center justify-content-center rounded-circle"
                                            style="width:44px;height:44px;background:rgba(30,185,128,0.08);color:#1EB980;">
                                            <i class="fas fa-question" aria-hidden="true"></i>
                                        </span>
                                        <span class="flex-grow-1 text-start fw-semibold">{{ $f['title'] }}</span>
                                        <span class="ms-3 toggle-icon text-muted" aria-hidden="true">
                                            
                                        </span>
                                    </button>
                                </h2>
                                <div id="{{ $f['id'] }}"
                                    class="accordion-collapse collapse @if (!empty($f['show'])) show @endif"
                                    data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted" style="background:#fff;">
                                        {!! nl2br(e($f['body'])) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-center mt-5">
                        <h5 class="fw-bold mb-3">Vẫn cần hỗ trợ?</h5>
                        <a href="#" class="btn btn-pill text-white px-4 py-2" style="background:#1EB980;">Liên hệ
                            ngay</a>
                        <p class="mt-3 mb-0 text-muted"><i class="fas fa-phone text-success me-2"></i> Hotline: <strong>0123
                                456 789</strong></p>
                    </div>
                </div>

                <!-- RIGHT: SIDEBAR -->
                <div class="col-lg-4">
                    <aside class="p-4 bg-white shadow-sm rounded-4 sticky-top" style="top:140px; z-index: 10;">
                        <h5 class="fw-bold mb-2">Đăng ký nhận ưu đãi</h5>
                        <p class="text-muted small mb-3">Nhận mã giảm giá & cập nhật bộ sưu tập mới.</p>

                        <form action="#" method="POST" class="mb-3">
                            @csrf
                            <div class="input-group">
                                <input type="email" name="email" class="form-control rounded-start-pill"
                                    placeholder="Nhập email của bạn" aria-label="Email" required>
                                <button class="btn text-white rounded-end-pill" style="background:#1EB980;"
                                    type="submit">Gửi</button>
                            </div>
                        </form>

                        <div class="divider my-3"
                            style="height:1px;background:linear-gradient(90deg, rgba(30,185,128,0.15), rgba(30,185,128,0));">
                        </div>

                        <h6 class="fw-bold mb-2">Mua sắm an tâm</h6>
                        <ul class="list-unstyled text-muted small mb-3">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Miễn phí đổi trả 7 ngày
                            </li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Giao hàng nhanh toàn
                                quốc</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Thanh toán an toàn</li>
                        </ul>

                        <div class="text-center mt-3">
                            <div class="p-4 rounded-3" style="background: rgba(30,185,128,0.08); display: inline-block;">
                                <i class="fas fa-gift fa-3x text-success"></i>
                            </div>
                            <p class="mt-2 mb-0 fw-semibold text-success">Nhận ưu đãi hấp dẫn mỗi tuần!</p>
                        </div>

                    </aside>
                </div>
            </div>
        </div>
    </section>
    <!-- FAQ AREA END -->


    <!-- COUNTER AREA START -->
    <section class="counterup-area py-5" style="background: linear-gradient(120deg,#1EB980,#17a67a);">
        <div class="container">
            <div class="row text-center text-white g-4">
                <div class="col-6 col-md-3">
                    <div>
                        <i class="fas fa-users fa-2x mb-2" aria-hidden="true"></i>
                        <h2 class="fw-bold mb-0 counter" data-target="733">0</h2>
                        <p class="mb-0 small">Khách hàng hoạt động</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div>
                        <i class="fas fa-star fa-2x mb-2" aria-hidden="true"></i>
                        <h2 class="fw-bold mb-0 counter" data-target="33000">0</h2>
                        <p class="mb-0 small">Tách cà phê</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div>
                        <i class="fas fa-award fa-2x mb-2" aria-hidden="true"></i>
                        <h2 class="fw-bold mb-0 counter" data-target="100">0</h2>
                        <p class="mb-0 small">Phần thưởng</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div>
                        <i class="fas fa-globe fa-2x mb-2" aria-hidden="true"></i>
                        <h2 class="fw-bold mb-0 counter" data-target="21">0</h2>
                        <p class="mb-0 small">Quốc gia phủ sóng</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- COUNTER AREA END -->

    <!-- Styles -->
    <style>
        .accordion-button {
            background: transparent;
            color: #222;
            padding: 1rem 1.25rem;
            border-radius: 0;
            transition: color .2s ease, background .2s ease;
        }

        .accordion-button:focus {
            box-shadow: none;
        }

        .accordion-button .faq-icon {
            font-size: 18px;
        }

        .accordion-button .toggle-icon {
            transition: transform .25s ease;
        }

        .accordion-button:not(.collapsed) .toggle-icon {
            transform: rotate(180deg);
            color: #1EB980;
        }

        .accordion-body {
            background: #ffffff;
            padding: 1rem 1.25rem 1.5rem;
        }

        .btn-pill {
            border-radius: 50px;
        }

        .sticky-top {
            position: sticky;
        }

        @media (max-width: 991.98px) {
            .sticky-top {
                position: static;
                top: auto;
            }
        }
    </style>

    <!-- Scripts: simple counter and ensure accordion chevron rotates on show/hide -->
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Counter animation (simple)
                const counters = document.querySelectorAll('.counter');
                const speed = 1200; // duration ms

                const runCounter = (el) => {
                    const target = +el.getAttribute('data-target');
                    const start = 0;
                    const step = Math.max(1, Math.floor(target / (speed / 16)));
                    let current = start;
                    const updater = () => {
                        current += step;
                        if (current >= target) {
                            el.textContent = target.toLocaleString();
                        } else {
                            el.textContent = current.toLocaleString();
                            requestAnimationFrame(updater);
                        }
                    };
                    requestAnimationFrame(updater);
                };

                // Animate counters when visible
                const obs = new IntersectionObserver((entries, o) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            runCounter(entry.target);
                            o.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.4
                });

                counters.forEach(c => obs.observe(c));

                // Rotate chevron handled by CSS using .collapsed class; nothing else needed.
            });
        </script>
    @endpush

@endsection
