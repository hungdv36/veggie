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
        // Tìm đúng form bên trong modal
        let form = button.closest(".modal").find("form")[0];

        if (!form) return; // phòng trường hợp không tìm thấy form

        let formData = new FormData(form);
        formData.append("category_id", categoryId);

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: "/admin/categories/update", // đúng url route
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
                    toastr.error("Có lỗi xảy ra, vui lòng thử lại");
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

    $("#product-images").change(function (e) {
        let files = e.target.files;
        console.log(files);

        let previewContainer = $("#image-preview-container");
        previewContainer.empty(); // Xóa ảnh cũ trước khi hiển thị ảnh mới

        if (files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                let file = files[i];
                if (file) {
                    let reader = new FileReader();

                    reader.onload = function (e) {
                        let img = $("<img>")
                            .attr("src", e.target.result)
                            .addClass("image-preview")
                            .css({
                                "max-width": "150px",
                                "max-height": "150px",
                                margin: "5px",
                                "border-radius": "5px",
                            });

                        previewContainer.append(img);
                    };

                    reader.readAsDataURL(file);
                }
            }
        } else {
            previewContainer.html("");
        }
    });
    // UPDATE VARIANT
    $(document).on("click", ".btn-update-variant", function (e) {
        e.preventDefault();
        let button = $(this);
        let variantId = button.data("id");
        let form = button.closest(".modal").find("form");

        if (!form.length) return;

        let formData = new FormData(form[0]);
        formData.append("variant_id", variantId);

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        button.prop("disabled", true).text("Đang cập nhật...");

        $.ajax({
            url: form.attr("action") || "/admin/variants/update", // fallback nếu Blade chưa render
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response); // debug xem JSON trả về
                if (response.status) {
                    toastr.success(response.message, "", {
                        timeOut: 5000,
                        extendedTimeOut: 1000,
                    });
                    $("#modalUpdate-" + variantId).modal("hide");
                    location.reload();
                } else {
                    toastr.error(response.message || "Cập nhật thất bại");
                }
            },
            error: function (xhr) {
                toastr.error("Có lỗi xảy ra, vui lòng thử lại");
            },
            complete: function () {
                button.prop("disabled", false).text("Cập nhật");
            },
        });
    });

    // DELETE VARIANT
    // Xóa mềm
    $(document).on("click", ".btn-delete-variant", function (e) {
        e.preventDefault();
        if (!confirm("Bạn có chắc muốn xóa biến thể này?")) return;

        let variantId = $(this).data("id");

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.post(
            "/admin/variants/delete",
            { variant_id: variantId },
            function (response) {
                if (response.status) {
                    toastr.success(response.message);
                    $("#variant-row-" + variantId).fadeOut();
                } else {
                    toastr.error(response.message);
                }
            }
        );
    });
    // Khôi phục
    $(document).on("click", ".btn-restore-variant", function (e) {
        e.preventDefault();
        let variantId = $(this).data("id");

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.post(
            "/admin/variants/restore",
            { variant_id: variantId },
            function (response) {
                if (response.status) {
                    toastr.success(response.message);
                    location.reload();
                } else {
                    toastr.error(response.message);
                }
            }
        );
    });

    // Xóa vĩnh viễn
    $(document).on("click", ".btn-force-delete-variant", function (e) {
        e.preventDefault();
        if (!confirm("Bạn có chắc muốn xóa vĩnh viễn biến thể này?")) return;

        let variantId = $(this).data("id");

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.post(
            "/admin/variants/force-delete",
            { variant_id: variantId },
            function (response) {
                if (response.status) {
                    toastr.success(response.message);
                    $("#variant-row-" + variantId).fadeOut();
                } else {
                    toastr.error(response.message);
                }
            }
        );
    });

    $('.product-images').change(function (e) {
        let files = e.target.files;
        let productId = $(this).data("id");
        let previewContainer = $("#image-preview-container-" + productId);
        previewContainer.empty();

        if (files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                let file = files[i];

                if (file) {
                    let reader = new FileReader();

                    reader.onload = function (e) {
                        let img = $("<img>")
                            .attr("src", e.target.result)
                            .addClass("image-preview");

                        img.css({
                            "max-width": "150px",
                            "max-height": "150px",
                            "margin": "5px",
                            "border-radius": "5px"
                        });

                        previewContainer.append(img);
                    };

                    reader.readAsDataURL(file);
                }
            }
        } else {
            previewContainer.html("");
        }
    });

    // ==================== XỬ LÝ CẬP NHẬT SẢN PHẨM ====================
    $(document).on("click", ".btn-update-submit-product", function (e) {
    e.preventDefault();

    let button = $(this);
    let productId = button.data("id");
    let form = button.closest(".modal").find("form");
    let formData = new FormData(form[0]);

    formData.append("product_id", productId);

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $.ajax({
        url: "/admin/product/update",
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
                let product = response.data;
                let productId = product.id;
                let imageSrc = product.image ? product.image : "storage/products/default-product.png";

                let newRow = `
<tr id="product-row-${productId}">
    <td><img src="${imageSrc}" alt="${product.name}" class="image-product" width="80"></td>
    <td>${product.name}</td>
    <td>${product.category.name}</td>
    <td>${product.slug}</td>
    <td>${product.description}</td>
    <td>${product.stock}</td>
    <td>${number_format(product.price, 0, '.', '.')} VND</td>
    <td>${product.unit}</td>
    <td>${product.status}</td>
    <td>
        <a class="btn btn-app btn-update-product" data-toggle="modal" 
           data-target="#modalUpdate-${productId}">
            <i class="fa fa-edit"></i> Chỉnh sửa
        </a>
    </td>
    <td>
        <a class="btn btn-app btn-delete-product" data-id="${productId}">
            <i class="fa fa-trash"></i> Xóa
        </a>
    </td>
</tr>
`;

                $("#product-row-" + productId).replaceWith(newRow);
                $("#modalUpdate-" + productId).modal("hide");
            } else {
                toastr.error(response.message);
            }
        },
        error: function (xhr, status, error) {
            alert("Lỗi: " + error);
        },
        complete: function () {
            button.prop("disabled", false);
            button.text("Chỉnh sửa");
        },
    });
});

});
