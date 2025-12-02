@extends('layouts.admin')
@section('title', 'Quản lý thông tin admin')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">


            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Tài khoản</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>

                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="col-md-3 col-sm-3  profile_left">
                                <div class="profile_img">
                                    <div id="crop-avatar">
                                        @if ($user)
                                            <img id="avatar-preview" class="img-responsive avatar-view img-account"
                                                src="{{ asset('path_to_storage/' . $user->avatar) }}" alt="avatar"
                                                title="avatar">
                                        @else
                                            <img id="avatar-preview" class="img-responsive avatar-view img-account"
                                                src="{{ asset('images/default-avatar.png') }}" alt="default avatar"
                                                title="User Guest">
                                        @endif
                                    </div>
                                    <div class="div">
                                        <form enctype="multipart/form-data" onsubmit="return false;">
                                            <input type="file" id="avatar" name="avatar" accept="image/*"
                                                style="display: none">

                                            <!-- thêm type="button" để không bị submit form -->
                                            <button type="button" class="btn btn-success update-avatar"
                                                style="margin: 10px 5px">
                                                <i class="fa fa-edit m-right-xs"></i> Chọn ảnh
                                            </button>
                                        </form>

                                    </div>
                                </div>
                                <h3 id="user-name">{{ $user->name }}</h3>
                                <ul class="list-unstyled user_data">
                                    <li>
                                        <i class="fa fa-map-marker user-profile-icon"></i> <span
                                            id="user-address">{{ $user->address }}</span>
                                    </li>
                                    <li>
                                        <i class="fa fa-briefcase user-profile-icon"></i> <span
                                            id="user-email">{{ $user->email }}</span>
                                    </li>
                                    <li class="m-top-xs">
                                        <i class="fa fa-phone user-profile-icon"></i>
                                        <span id="user-phone">{{ $user->phone_number }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-9 col-sm-9 ">
                                <form id="update-profile" enctype="multipart/form-data" method="POST">
                                    @csrf

                                    <div class="item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="name">Họ và tên
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" id="name" name="name" required
                                                class="form-control" value="{{ $user->name }}">

                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="email">Email
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" id="email" name="email" required
                                                class="form-control" value="{{ $user->email }}">

                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="phone">Số điện
                                            thoại <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" id="phone" name="phone" required
                                                class="form-control" value="{{ $user->phone_number }}">

                                        </div>
                                    </div>

                                    <div class="item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="address">Địa chỉ
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" id="address" name="address" required
                                                class="form-control" value="{{ $user->address }}">

                                        </div>
                                    </div>

                                    <div class="ln_solid"></div>
                                    <div class="item form-group">
                                        <div class="col-md-6 col-sm-6 offset-md-3">

                                            <button class="btn btn-primary" type="reset">Reset</button>
                                            <button type="submit" class="btn btn-success"> Cập nhập</button>
                                            <button id="trigger-change-pass" type="button" class="btn btn-warning">
                                                Đổi mật khẩu
                                            </button>
                                        </div>
                                    </div>

                                </form>
                                <div id="change-password-form-wrapper" class="space-y-4" style="display: none;">

                                    <form id="password-form" onsubmit="event.preventDefault(); alert('Form submitted!');">
                                        <div class="item form-group">
                                            <!-- Trường Mật khẩu hiện tại -->
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"
                                                for="current_password">
                                                Mật khẩu hiện tại
                                                <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="password" id="current_password" required
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <!-- Trường Mật khẩu mới -->
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"
                                                for="new_password">
                                                Mật khẩu mới
                                                <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="password" id="new_password" name="new_password" required
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <!-- Trường Xác nhận mật khẩu mới/Số điện thoại -->
                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="phone">
                                                Xác nhận mật khẩu mới
                                                <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6">
                                                <!-- Chú ý: Ở đây bạn đang sử dụng trường "phone" và có vẻ có vấn đề về logic đặt tên:
                                                                         Tên trường là 'phone' nhưng label là 'Xác nhận mật khẩu mới'.
                                                                         Giá trị mặc định lại là số điện thoại. -->
                                                <input type="password" id="confirm_password" name="confirm_password"
                                                    required class="form-control">

                                            </div>
                                        </div>


                                        <div class="ln_solid"></div>
                                        <div class="item form-group">
                                            <div class="col-md-6 col-sm-6 offset-md-3">

                                                <button class="btn btn-primary" type="reset">Reset</button>
                                                <button type="submit" class="btn btn-success"> Cập nhập</button>
                                                <button id="cancel-change-pass" type="button"
                                                    class="btn btn-secondary">Hủy</button>

                                            </div>
                                        </div>

                                    </form>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        #avatar-preview {
            width: 150px;
            height: 150px;
            object-fit: cover;
            /* giữ đúng tỉ lệ, cắt phần thừa */
            border-radius: 50%;
            /* bo tròn */
            border: 2px solid #ddd;
        }
    </style>
    <!-- /page content -->
@endsection

@push('scripts')
<script>

document.addEventListener('DOMContentLoaded', () => {

    /* ==========================
       1. Preview Avatar
    ===========================*/
    $(".update-avatar").off("click").on("click", function(e){
        e.preventDefault();
        $("#avatar").trigger("click");
    });

    $("#avatar").off("change").on("change", function(e) {
        let file = e.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(event) {
                $("#avatar-preview").attr("src", event.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    /* ==========================
       2. Cập nhật thông tin Admin (Ajax)
    ===========================*/
    $("#update-profile").submit(function(e){
        e.preventDefault();

        let formData = new FormData(this);
        formData.append("avatar", $("#avatar")[0].files[0]);

        $.ajax({
            url: "{{ route('admin.profile.update') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                alert("Cập nhật thành công!");
                location.reload();
            },
            error: function(xhr){
                alert("Lỗi cập nhật thông tin!");
            }
        });
    });
    /* ==========================
       ✅ Hiển thị form đổi mật khẩu
    ===========================*/
    $("#trigger-change-pass").off("click").on("click", function () {
        $("#change-password-form-wrapper").slideDown();
    });

    /* ==========================
       ✅ Ẩn form đổi mật khẩu
    ===========================*/
    $("#cancel-change-pass").off("click").on("click", function () {
        $("#change-password-form-wrapper").slideUp();
    });

    /* ==========================
       3. Đổi mật khẩu (Ajax)
    ===========================*/
    $("#password-form").submit(function(e){
        e.preventDefault();

        $.ajax({
            url: "{{ route('admin.profile.change-password') }}",
            type: "POST",
            data: {
                current_password: $("#current_password").val(),
                new_password: $("#new_password").val(),
                confirm_password: $("#confirm_password").val(),
                _token: "{{ csrf_token() }}"
            },
            success: function(res){
                alert("Đổi mật khẩu thành công!");
                $("#cancel-change-pass").trigger("click");
            },
            error: function(xhr){
                alert("Mật khẩu hiện tại không đúng!");
            }
        });
    });

});
</script>
@endpush

