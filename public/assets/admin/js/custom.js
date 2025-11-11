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
    $("#category-image").change(function () {
        let file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $("#image-preview").attr("src", e.target.result);
            };
            reader.readAsDataURL(file);
        } else {
            $("#image-preview").attr("src", "");
        }
    });

    $(document).on("change", ".image-input", function () {
        let file = this.files[0];
        let categoryId = $(this).data("id");
        let preview = $("#preview-" + categoryId);
        let oldImage = $("#old-" + categoryId);

        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                preview.attr("src", e.target.result).show();
                oldImage.hide(); // ẩn ảnh cũ khi chọn ảnh mới
            };
            reader.readAsDataURL(file);
        } else {
            preview.attr("src", "").hide();
            oldImage.show(); // không chọn file thì hiện lại ảnh cũ
        }
    });

    // UPDATE CATEGORY
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
            url: "/categories/update", // đúng url route
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                button.prop("disabled", true).text("Đang cập nhật...");
            },
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message);
                    $("#modalUpdate-" + categoryId).modal("hide");
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message || "Cập nhật thất bại");
                }
            },
            error: function (xhr) {
                toastr.error("Có lỗi xảy ra, vui lòng thử lại");
                console.error(xhr.responseText);
            },
            complete: function () {
                button.prop("disabled", false).text("Chỉnh sửa");
            },
        });
    });

    // DELETE CATEGORY
    $(document).on("click", ".btn-delete-category", function (e) {
        e.preventDefault();
        let button = $(this);
        let categoryId = button.data("id");
        let row = button.closest("tr");

        if (confirm("Bạn có chắc chắn muốn xóa danh mục này?")) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });

            $.ajax({
                url: "/admin/categories/delete",
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
                        toastr.error(response.message || "Xóa thất bại");
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 400 && xhr.responseJSON?.message) {
                        // Lỗi logic có thể dự đoán được
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        // Các lỗi khác (500, lỗi mạng, ...)
                        toastr.error("Có lỗi xảy ra, vui lòng thử lại");
                    }
                    console.error(xhr.responseText);
                },
            });
        }
    });

    // Khôi phục category
    $(document).on("click", ".btn-restore-category", function () {
        let button = $(this);
        let categoryId = button.data("id");

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: "/admin/categories/restore",
            type: "POST",
            data: { category_id: categoryId },
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message);
                    $("#row-" + categoryId).remove(); // xóa row khỏi bảng nếu muốn
                } else {
                    toastr.error(response.message);
                }
            },
            error: function () {
                toastr.error("Có lỗi xảy ra, vui lòng thử lại");
            },
        });
    });

    // Xóa vĩnh viễn category
    $(document).on("click", ".btn-force-delete-category", function () {
        if (!confirm("Bạn có chắc muốn xóa vĩnh viễn?")) return;

        let button = $(this);
        let categoryId = button.data("id");

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: "/admin/categories/force-delete",
            type: "POST",
            data: { category_id: categoryId },
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message);
                    $("#row-" + categoryId).remove();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function () {
                toastr.error("Có lỗi xảy ra, vui lòng thử lại");
            },
        });
    });

    $(document).ready(function () {
        console.log("✅ Custom JS loaded");

        // ================= Ảnh đại diện =================
        $(document).on("change", "input[name='image']", function () {
            const input = $(this)[0];
            const id = $(this).attr("id").split("-")[1];
            const oldImg = $(this).siblings("label").find(".old-image");
            const preview = $("#imagePreview-" + id);

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    oldImg.hide();
                    preview.attr("src", e.target.result).show();
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.hide();
                oldImg.show();
            }
        });

        // ================= Album ảnh =================
        $(document).on("change", "input[id^='imagesInput-']", function () {
            const id = $(this).attr("id").replace("imagesInput-", "");
            const previewContainer = $("#imagesPreview-" + id);
            previewContainer.empty();

            if (this.files && this.files.length > 0) {
                Array.from(this.files).forEach((file) => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = $("<img>", {
                            src: e.target.result,
                            class: "rounded border",
                            css: {
                                height: "80px",
                                width: "80px",
                                objectFit: "cover",
                            },
                        });
                        previewContainer.append(img);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });

        // ================= AJAX cập nhật sản phẩm =================
        $(document).on("click", ".btn-update-submit-product", function (e) {
            e.preventDefault();
            let button = $(this);
            let productId = button.data("id");
            let form = button.closest(".modal").find("form");
            let formData = new FormData(form[0]);

            formData.append("id", productId);
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });

            $.ajax({
                url: "/products/update",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    button.prop("disabled", true);
                    button.text("Đang cập nhật...");
                },
                success: function (response) {
                    if (response.status) {
                        toastr.success(response.message);
                        let product = response.data; // <-- dùng đây
                        let productId = product.id;
                        let imageSrc = product.image
                            ? `/assets/admin/img/product/${product.image}`
                            : "#";

                        //Regenerate new HTML for update row
                        let newRow = `
                        <tr id="product-row-${productId}">
                            <td>
                                <img src = "${imageSrc}" alt="${product.name}" class="image-product" width="80">
                            </td>
                            <td>${product.name}</td>
                            <td>${product.category.name}</td>
                            <td>number_format(${product.price}, 0, ',', '.') }}VNĐ</td>
                            <td>${product.unit}</td>
                            <td>${product.stock}</td>
                            <td>${product.description}</td>
                            <td>${product.status}</td>
                            <td>
                                <a class="btn btn-app btn-update-product" data-toggle="modal"
                                    data-target="#modalUpdate-${productId}">
                                    <i class="fa fa-edit"></i>Chỉnh sửa
                                </a>
</td>
                            <td>
                                <a class="btn btn-app btn-delete-product" data-id="${productId}">
                                    <i class="fa fa-trash"></i>Xóa
                                </a>
                            </td>
                        </tr>`;
                        //Replace old row with new row
                        $("#product-row-" + productId).replaceWith(newRow);
                        toastr.success(response.message);
                        $("#modalUpdate-" + productId).modal("hide");
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    alert("An error occurred: " + error);
                },
                complete: function () {
                    button.prop("disabled", false);
                    button.text("Chỉnh sửa");
                },
            });
        });
    });
    // Xóa mềm
    $(document).on("click", ".btn-delete-product", function () {
        let id = $(this).data("id");
        if (confirm("Bạn có chắc muốn xóa sản phẩm?")) {
            // ẩn modal nếu mở
            $("#modalUpdate-" + id).modal("hide");
            $("#variantsModal-" + id).modal("hide");

            $.post(
                "/admin/products/delete",
                {
                    product_id: id,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                function (res) {
                    if (res.status) {
                        toastr.success(res.message);
                        // xóa row trong table
                        $("#product-row-" + id).fadeOut(300, function () {
                            $(this).remove();
                        });
                    } else {
                        toastr.error(res.message);
                    }
                }
            );
        }
    });

    // Khôi phục
    $(document).on("click", ".btn-restore-product", function () {
        let id = $(this).data("id");
        if (confirm("Bạn có chắc muốn khôi phục sản phẩm này?")) {
            $.post(
                "/admin/products/restore",
                {
                    product_id: id,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                function (res) {
                    if (res.status) {
                        toastr.success(res.message);
                        $("#product-row-" + id).remove(); // nếu muốn ẩn row ngay
                        // hoặc dùng location.reload(); nếu muốn load lại trang
                    } else {
                        toastr.error(res.message);
                    }
                }
            );
        }
    });

    // Xóa vĩnh viễn
    $(document).on("click", ".btn-force-delete-product", function () {
        let id = $(this).data("id");
        if (confirm("Bạn có chắc muốn xóa vĩnh viễn sản phẩm này?")) {
            $.post(
                "/admin/products/force-delete",
                {
                    product_id: id,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                function (res) {
                    if (res.status) {
                        toastr.success(res.message);
                        $("#product-row-" + id).remove(); // ẩn row sau khi xóa
                    } else {
                        toastr.error(res.message);
                    }
                }
            );
        }
    });

    // JS number_format cho JS
    function number_format(number) {
        return new Intl.NumberFormat("vi-VN").format(number);
    }

    // DELETE COLOR
    $(document).on("click", ".btn-delete-color", function (e) {
        e.preventDefault();

        const btn = $(this);
        const colorId = btn.data("id");

        if (!confirm("Bạn có chắc chắn muốn xóa màu này?")) return;

        btn.prop("disabled", true).html(
            '<i class="fa fa-spinner fa-spin"></i>'
        );

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.post("/admin/colors/delete", { color_id: colorId }, function (res) {
            btn.prop("disabled", false).html('<i class="fa fa-trash"></i>');

            if (res.status) {
                toastr.success(res.message);
                $("#color-row-" + colorId).fadeOut();
            } else {
                toastr.error(res.message);
            }
        }).fail(function () {
            btn.prop("disabled", false).html('<i class="fa fa-trash"></i>');
            toastr.error("Có lỗi xảy ra!");
        });
    });
    // DELETE SIZE
    $(document).on("click", ".btn-delete-size", function (e) {
        e.preventDefault();

        const btn = $(this);
        const sizeId = btn.data("id");

        if (!confirm("Bạn có chắc chắn muốn xóa size này?")) return;

        btn.prop("disabled", true).html(
            '<i class="fa fa-spinner fa-spin"></i>'
        );

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.post("/admin/sizes/delete", { size_id: sizeId }, function (res) {
            btn.prop("disabled", false).html('<i class="fa fa-trash"></i>');

            if (res.status) {
                toastr.success(res.message);
                $("#size-row-" + sizeId).fadeOut();
            } else {
                toastr.error(res.message);
            }
        }).fail(function () {
            btn.prop("disabled", false).html('<i class="fa fa-trash"></i>');
            toastr.error("Có lỗi xảy ra!");
        });
    });

    //     if ($("#editor-contact").length) {
    //         CKEDITOR.replace("editor-contact");

    //     }
    //    $(document).on("click", ".contact-item", function (e) {
    //     $(".mail_view").show();
    // });
    

    /*
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * MANAGEMENT PROFILE
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    */

    $(".form-change-pass").on("click", function (e) {
        e.preventDefault();
        $("#change-password").toggle();
        if ($("#change-password").is(":visible")) {
            $(this).text("Đóng");
        } else {
            $(this).text("Đổi mật khẩu");
        }
    });


    $(".update-avatar").on("click", function (e) {
        e.preventDefault();
        $("#avatar").trigger("click");
    });


    $("#avatar").on("change", function (e) {
        let file = e.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $("#avatar-preview").attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        } else {
            $("#avatar-preview").attr('src', '');
        }
    });



});
