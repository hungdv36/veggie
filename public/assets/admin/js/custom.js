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

    /********************************
     * MANAGE CATEGORIES
     ******************************/
    $("#image").change(function () {
        let file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $("#image-preview").attr("src", e.target.result).show();
            };
            reader.readAsDataURL(file);
        } else {
            $("#image-preview").attr("src", "").hide();
        }
    });
    $(".image-input").change(function () {
        let file = this.files[0];
        let categoryId = $(this).data("id");
        let reader = new FileReader();

        reader.onload = function (e) {
            // Ẩn ảnh cũ
            $("#old-" + categoryId).hide();
            // Hiển thị preview mới
            $("#preview-" + categoryId)
                .attr("src", e.target.result)
                .show();
        };

        if (file) {
            reader.readAsDataURL(file);
        } else {
            // Nếu bỏ chọn file
            $("#preview-" + categoryId).hide();
            $("#old-" + categoryId).show();
        }
    });

    //UPDATE CATEGORY
    $(document).on("click", ".btn-update-submit-category", function (e) {
        e.preventDefault();
        let button = $(this);
        let categoryId = button.data("id");
        let form = button.closest(".modal").find("form");
        let formData = new FormData(form[0]);

        formData.append("category_id", categoryId);
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: "categories/update",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                button.prop("disabled", true);
                button.text("Đang cập nhật...");
            },
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message, "", {
                        timeOut: 10000,
                        extendedTimeOut: 2000,
                    });
                    $("#modalUpdate-" + categoryId).modal("hide");
                    location.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                alert("Có lỗi xảy ra, vui lòng thử lại");
            },
        });
    });

    // DELETE CATEGORY
    $(document).on("click", ".btn-delete-category", function (e) {
        e.preventDefault();
        let button = $(this);
        let categoryId = button.data("id");
        let row = button.closest("tr");

        if (confirm("Bạn có chắc muốn xóa danh mục này?")) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });

            $.ajax({
                url: "categories/delete",
                type: "POST",
                data: {
                    category_id: categoryId,
                },
                success: function (response) {
                    if (response.status) {
                        toastr.success(response.message);
                        row.fadeOut(500, function () {
                            $(this).remove();
                        });
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    alert("Có lỗi xảy ra, vui lòng thử lại");
                },
            });
        }
    });
    // Khôi phục
    $(document).on("click", ".btn-restore-category", function () {
        let id = $(this).data("id");
        $.post(
            "/admin/categories/restore",
            {
                category_id: id,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    location.reload();
                } else toastr.error(res.message);
            }
        );
    });

    // Xóa vĩnh viễn
    $(document).on("click", ".btn-force-delete-category", function () {
        let id = $(this).data("id");
        if (confirm("Bạn có chắc muốn xóa vĩnh viễn danh mục này?")) {
            $.post(
                "/admin/categories/force-delete",
                {
                    category_id: id,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                function (res) {
                    if (res.status) {
                        toastr.success(res.message);
                        location.reload();
                    } else toastr.error(res.message);
                }
            );
        }
    });
});
