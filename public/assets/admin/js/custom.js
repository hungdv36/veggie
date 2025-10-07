$(document).ready(function () {
    $(document).on("click", ".upgradeStaff", function (e) {
        e.preventDefault();
        let button = $(this);
        let userId = button.data("userid");

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            type: "POST",
            url: upgradeUrl, // Biến JS từ Blade
            data: { user_id: userId },
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message);

                    // Lấy container profile tương ứng
                    let profileView = button.closest(".profile_view");

                    // Cập nhật role hiển thị
                    profileView.find(".brief i").text("staff");

                    // Ẩn toàn bộ nút trong phần col-sm-8 emphasis
                    profileView.find(".col-sm-8.emphasis").hide();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                toastr.error("Có lỗi xảy ra, vui lòng thử lại");
                console.error("AJAX error:", error);
            },
        });
    });
    // Chặn / Bỏ chặn
    $(document).on("click", ".toggleBlock", function () {
        let button = $(this);
        let userId = button.data("userid");

        $.ajax({
            url: "/admin/user/updateStatus",
            type: "POST",
            data: {
                user_id: userId,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                if (!res.status) {
                    alert(res.message);
                    return;
                }

                if (res.new_status === "banned") {
                    button
                        .html('<i class="fa fa-times"></i> Bỏ chặn')
                        .removeClass("btn-success")
                        .addClass("btn-warning");
                } else {
                    button
                        .html('<i class="fa fa-check"></i> Chặn')
                        .removeClass("btn-warning")
                        .addClass("btn-success");
                }
            },
        });
    });

    // Xóa / Khôi phục
    $(document).on("click", ".toggleDelete", function (e) {
        e.preventDefault();
        let button = $(this);
        let userId = button.data("userid");

        $.ajax({
            url: "/admin/user/toggleDelete",
            type: "POST",
            data: {
                user_id: userId,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                if (!res.status) {
                    toastr.error(res.message);
                    return;
                }

                if (res.new_status === "deleted") {
                    // Chuyển nút Xóa thành Khôi phục
                    button
                        .html('<i class="fa fa-check"></i> Khôi phục')
                        .removeClass("btn-danger")
                        .addClass("btn-secondary");

                    // Ẩn nút Chặn/Bỏ chặn ngay lập tức
                    $('.toggleBlock[data-userid="' + userId + '"]').hide();
                } else if (res.new_status === "active") {
                    // Chuyển nút Khôi phục thành Xóa
                    button
                        .html('<i class="fa fa-times"></i> Xóa')
                        .removeClass("btn-secondary")
                        .addClass("btn-danger");

                    // Hiển thị lại nút Chặn/Bỏ chặn
                    $('.toggleBlock[data-userid="' + userId + '"]').show();
                }

                toastr.success(res.message);
            },
            error: function (xhr, status, error) {
                toastr.error("Có lỗi xảy ra, vui lòng thử lại");
                console.error(error);
            },
        });
    });
    $('#category-image').change(function () {
    let file = this.files[0];
    if (file) {
        let reader = new FileReader();
        reader.onload = function (e) {
            $('#image-preview').attr('src', e.target.result);
        };
        reader.readAsDataURL(file);
    } else {
        $('#image-preview').attr('src', '');
    }
});

});
