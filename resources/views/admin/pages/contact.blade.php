@extends('layouts.admin')
@section('title', 'Quản lý Liên hệ')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Liên hệ</h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Inbox Design <small>User Mail</small></h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <div class="row">
                                <!-- MAIL LIST -->
                                <div class="col-sm-3 mail_list_column" style="overflow-y: scroll; max-height: 500px;">

                                    <label for="" class="badge bg-green"
                                        style="width: 100%; line-height: 2; font-size: 16px;">
                                        Liên hệ khách hàng
                                    </label>

                                    @foreach ($contacts as $contact)
                                        <a href="javascript:void(0)" class="contact-item"
                                            data-name="{{ $contact->full_name }}" data-email="{{ $contact->email }}"
                                            data-message="{{ $contact->message }}" data-id="{{ $contact->id }}"
                                            data-replied="{{ $contact->is_replied }}"> {{-- ✅ thêm dòng này --}}
                                            <div class="mail_list">
                                                <div class="left">
                                                    <i class="fa fa-circle"
                                                        style="color: {{ $contact->is_replied ? 'green' : 'red' }}"></i>
                                                </div>
                                                <div class="right">
                                                    <h3>
                                                        {{ $contact->full_name }}
                                                        <small>{{ $contact->created_at->format('H:i A d-m-Y') }}</small>
                                                    </h3>
                                                    <p>{{ Str::limit($contact->message, 50) }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                <!-- /MAIL LIST -->

                                <!-- CONTENT MAIL -->
                                <div class="col-sm-9 mail_view" style="display: none">
                                    <div class="inbox-body">
                                        <div class="sender-info" style="border-bottom:1px solid #ddd;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <strong>Jon Doe</strong> <span></span> to <strong>me</strong>
                                                    <a class="sender-dropdown"><i class="fa fa-chevron-down"></i></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="view_mail">
                                            <p></p>
                                            <div class="btn-group">
                                                <button id="compose" class="btn btn-sm btn-primary" type="button">
                                                    <i class="fa fa-reply"></i> Trả lời
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /CONTENT MAIL -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- compose -->
        <div class="compose col-md-6">
            <div class="compose-header">
                Phản hồi liên hệ
                <button type="button" class="close compose-close"><span>×</span></button>
            </div>

            <div class="compose-body">
                <textarea id="description" name="description"></textarea>
            </div>

            <div class="compose-footer">
                <button class="send-reply-contact btn btn-sm btn-success" type="button">Gửi</button>
            </div>
        </div>
        <!-- /compose -->
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

    <script>
        $(document).ready(function () {

            // --- Khi bấm vào từng liên hệ ---
            $(document).off("click", ".contact-item").on("click", ".contact-item", function (e) {
                e.preventDefault();

                $(".mail_view").show();

                let contactName = $(this).data("name");
                let contactEmail = $(this).data("email");
                let contactMessage = $(this).data("message");
                let contactId = $(this).data("id");
                let isReplied = $(this).data("replied");

                // Đưa dữ liệu ra giao diện
                $(".mail_view .inbox-body .sender-info strong").text(contactName);
                $(".mail_view .inbox-body .sender-info span").text(contactEmail);
                $(".mail_view .view_mail p").text(contactMessage);

                // Gán thông tin cho nút gửi
                $(".send-reply-contact").attr("data-email", contactEmail);
                $(".send-reply-contact").attr("data-id", contactId);

                // 🔥 Kiểm tra trạng thái phản hồi
                if (isReplied == 1) {
                    $("#compose").hide(); // Ẩn nút “Trả lời” nếu đã phản hồi
                } else {
                    $("#compose").show(); // Hiện nút nếu chưa phản hồi
                }
            });

            // --- Khi bấm nút “Trả lời” ---
            $(document).on("click", "#compose", function () {
                $(".compose").show();

                // 🔥 Chỉ khởi tạo CKEditor nếu chưa tồn tại
                if (!CKEDITOR.instances['description']) {
                    CKEDITOR.replace("description");
                }
            });

            // --- Khi bấm nút đóng compose ---
            $(document).on("click", ".compose-close", function () {
                $(".compose").hide();
            });

            // --- Khi bấm nút Gửi ---
            $(document).on("click", ".send-reply-contact", function () {
                let email = $(this).attr("data-email");
                let id = $(this).attr("data-id");
                let message = CKEDITOR.instances['description'].getData();

                if (message.trim() === "") {
                    alert("Vui lòng nhập nội dung phản hồi.");
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.contacts.reply') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        email: email,
                        message: message
                    },
                    success: function (res) {
                        alert("Đã gửi phản hồi thành công!");
                        $(".compose").hide();
                        $("#compose").hide(); // ẩn nút “Trả lời” sau khi gửi
                    },
                    error: function (xhr) {
                        alert("Gửi phản hồi thất bại!");
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush

