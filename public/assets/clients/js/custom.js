$(document).ready(function () {
    console.log("Custom.js loaded ✅");

    /********************************
    PAGE LOGIN, REGISTER
    *******************************/

    // Validate register form
    $("#register-form").submit(function (e) {
        let name = $('input[name="name"]').val();
        let email = $('input[name="email"]').val();
        let password = $('input[name="password"]').val();
        let confirmPassword = $('input[name="confirmPassword"]').val();
        let checkbox1 = $('input[name="checkbox1"]').is(":checked");
        let checkbox2 = $('input[name="checkbox2"]').is(":checked");

        let errorMessage = "";

        if (name.length < 3) {
            errorMessage += "Họ tên phải có ít nhất 3 ký tự.<br>";
        }

        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errorMessage += "Email không hợp lý.<br>";
        }
        if (password.length < 6) {
            errorMessage += "Mật khẩu phải có ít nhất 6 ký tự.<br>";
        }

        if (password != confirmPassword) {
            errorMessage += "Mật khẩu nhập lại không khớp.<br>";
        }

        if (!checkbox1 || !checkbox2) {
            errorMessage +=
                "Bạn phải đồng ý với các điều khoản trước khi tạo tài khoản.<br>";
        }

        if (errorMessage != "") {
            toastr.error(errorMessage, "Lỗi");
            e.preventDefault();
        }
    });
    // Validate login form
    $("#login-form").submit(function (e) {
        toastr.clear();
        let email = $('input[name="email"]').val();
        let password = $('input[name="password"]').val();
        let errorMessage = "";

        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errorMessage += "Email không hợp lý.<br>";
        }
        if (password.length < 6) {
            errorMessage += "Mật khẩu phải có ít nhất 6 ký tự.<br>";
        }

        if (errorMessage != "") {
            toastr.error(errorMessage, "Lỗi");
            e.preventDefault();
        }
    });
    // Validate reset password form
    $("#reset-password-form").submit(function (e) {
        let email = $('input[name="email"]').val();
        let password = $('input[name="password"]').val();
        let confirmPassword = $('input[name="password_confirmation"]').val();
        let errorMessage = "";

        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errorMessage += "Email không hợp lý.<br>";
        }
        if (password.length < 6) {
            errorMessage += "Mật khẩu phải có ít nhất 6 ký tự.<br>";
        }
        if (password != confirmPassword) {
            errorMessage += "Mật khẩu nhập lại không khớp.<br>";
        }

        if (errorMessage != "") {
            toastr.error(errorMessage, "Lỗi");
            e.preventDefault();
        }
    });

    // Change password
    $(document).ready(function () {
        console.log("✅ Custom.js loaded");

        $("#change-password-form").submit(function (e) {
            e.preventDefault();

            let current_password = $('input[name="current_password"]')
                .val()
                .trim();
            let new_password = $('input[name="new_password"]').val().trim();
            let confirm_password = $('input[name="confirm_password"]')
                .val()
                .trim();

            let errorMessage = "";
            if (current_password.length < 6)
                errorMessage += "Mật khẩu cũ phải có ít nhất 6 ký tự.<br>";
            if (new_password.length < 6)
                errorMessage += "Mật khẩu mới phải có ít nhất 6 ký tự.<br>";
            if (new_password !== confirm_password)
                errorMessage += "Mật khẩu nhập lại không khớp.<br>";

            if (errorMessage !== "") {
                toastr.error(errorMessage, "Lỗi");
                return;
            }

            $.ajax({
                url: $(this).attr("action"),
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                beforeSend: function () {
                    $(".btn-wrapper button")
                        .text("Đang cập nhật...")
                        .attr("disabled", true);
                },
                success: function (response) {
                    console.log("Server response:", response);

                    if (response.success) {
                        toastr.success(response.message);
                        $("#change-password-form")[0].reset();
                    } else {
                        toastr.error(
                            response.message ||
                                "Đã có lỗi xảy ra, vui lòng thử lại."
                        );
                    }
                },
                error: function (xhr) {
                    console.log("Error response:", xhr);
                    let errors =
                        xhr.responseJSON?.error || xhr.responseJSON?.errors;
                    if (errors) {
                        $.each(errors, function (key, value) {
                            toastr.error(value[0] || value);
                        });
                    } else {
                        toastr.error("Đã có lỗi xảy ra, vui lòng thử lại.");
                    }
                },
                complete: function () {
                    $(".btn-wrapper button")
                        .text("Đổi mật khẩu")
                        .attr("disabled", false);
                },
            });
        });
    });

    // Validate form Address
    $("#addAddressForm").submit(function (e) {
        e.preventDefault();

        let isValid = true;

        $(".error-message").remove();

        let fullname = $("#full_name").val().trim();
        let phone = $("#phone").val().trim();

        if (fullname.length < 3) {
            isValid = false;
            $("#full_name").after(
                '<p class = "error-message text-danger">Họ tên phải có ít nhất 3 ký tự.</p>'
            );
        }

        let phoneRegex = /^[0-9]{10,11}$/;
        if (!phoneRegex.test(phone)) {
            isValid = false;
            $("#phone").after(
                '<p class = "error-message text-danger">Số điện thoại không hợp lệ.</p>'
            );
        }
        if (isValid) {
            this.submit();
        }
    });

    /*************************
     * PAGE PRODUCTS
     *************************/
    let currentPage = 1;
    $(document).on("click", ".pagination-link", function (e) {
        e.preventDefault();
        let pageUrl = $(this).attr("href");
        let page = pageUrl.split("page=")[1];
        currentPage = page;
        fetchProducts();
    });
    // Products load function (combining filter + pagination)
    function fetchProducts() {
        let category_id = $(".category-filter.active").data("id") || "";
        let minPrice = $(".slider-range").slider("values", 0);
        let maxPrice = $(".slider-range").slider("values", 1);
        let sort_by = $("#sort-by").val();

        $.ajax({
            url: "/products/filter?page=" + currentPage,
            type: "GET",
            data: {
                category_id: category_id,
                min_price: minPrice,
                max_price: maxPrice,
                sort_by: sort_by,
            },
            beforeSend: function () {
                $("#loading-spinner").show();
                $("#liton_product_grid").hide();
            },
            success: function (response) {
                $("#liton_product_grid").html(response.products);
                $(".ltn__pagination").html(response.pagination);
            },

            complete: function () {
                $("#loading-spinner").hide();
                $("#liton_product_grid").show();
            },
            error: function (xhr) {
                alert("Có lỗi xảy ra với ajax fetchProducts");
            },
        });
    }

    $(".category-filter").on("click", function () {
        $(".category-filter").removeClass("active");
        $(this).addClass("active");
        currentPage = 1; // Reset to first page on filter change
        fetchProducts();
    });
    $("#sort-by").change(function () {
        currentPage = 1; // Reset to first page on sort change
        fetchProducts();
    });

    $(".slider-range").slider({
        range: true,
        min: 0,
        max: 3000000,
        values: [0, 3000000],
        slide: function (event, ui) {
            $(".amount").val(ui.values[0] + " - " + ui.values[1] + " VNĐ");
        },
        change: function (event, ui) {
            fetchProducts();
        },
    });

    $(".amount").val(
        $(".slider-range").slider("values", 0) +
            " - " +
            $(".slider-range").slider("values", 1) +
            " VNĐ"
    );

    $(document).on("click", ".qtybutton", function () {
        let $input = $(this).siblings("input");
        let productId = $input.data("id");
        let variantId = $input.data("variant-id") ?? 0;
        let quantity = parseInt($input.val()) || 1;

        if ($(this).hasClass("inc")) quantity++;
        if ($(this).hasClass("dec") && quantity > 1) quantity--;

        updateCart(productId, variantId, quantity, $input);
    });

    // Hàm update cart (cả product + variant)
    function updateCart(productId, variantId, quantity, $input) {
        $.ajax({
            url: "/cart/update",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                product_id: productId,
                variant_id: variantId,
                quantity: quantity,
            },
            success: function (res) {
                $input.val(res.quantity);

                // Cập nhật subtotal từng dòng
                let price = parseInt(
                    $input
                        .closest("tr")
                        .find(".cart-product-subtotal")
                        .data("price")
                );
                $input
                    .closest("tr")
                    .find(".cart-product-subtotal")
                    .text((price * res.quantity).toLocaleString() + "đ");

                // Phí vận chuyển cố định
                const shippingFee = 25000;

                // Cập nhật tổng tiền và grand total
                let cartTotalNumber = Number(
                    res.cart_total.toString().replace(/\./g, "")
                ); // bỏ dấu chấm
                $("#cart-total").text(cartTotalNumber.toLocaleString() + "đ");

                // Cập nhật Tổng thanh toán = Tổng tiền hàng + phí vận chuyển
                let grandTotalNumber = cartTotalNumber + shippingFee;
                $("#cart-grand-total").text(
                    grandTotalNumber.toLocaleString() + "đ"
                );
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.error || "Lỗi cập nhật giỏ hàng");
            },
        });
    }

    $(document).ready(function () {
        // Tăng giảm số lượng chi tiết sản phẩm
        $(document).on("click", ".qtybutton-detail", function () {
            var $button = $(this);
            var $input = $button.siblings("input");
            var oldValue = parseInt($input.val()) || 1;
            var maxStock = parseInt($input.data("max")) || 100;
            var newValue = oldValue;

            if ($button.hasClass("inc"))
                newValue = Math.min(oldValue + 1, maxStock);
            if ($button.hasClass("dec")) newValue = Math.max(oldValue - 1, 1);

            $input.val(newValue);
            // cập nhật kho biến thể hiển thị
            $("#variant-stock").text(maxStock);
        });

        // Thêm sản phẩm vào giỏ hàng
        $("#btn-add-to-cart").click(function () {
            var $input = $("#cart-qty-box");
            var productId = $input.data("id");
            var variantId = $input.data("variant-id") || 0;
            var quantity = parseInt($input.val()) || 1;

            $.ajax({
                url: "/cart/add",
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                    product_id: productId,
                    variant_id: variantId,
                    quantity: quantity,
                },
                success: function (res) {
                    if (res.success) {
                        $("#cart-count").text(res.cart_count);
                        toastr.success(res.message); // hoặc alert
                    }
                },
                error: function (xhr) {
                    toastr.error(
                        xhr.responseJSON?.message || "Lỗi thêm giỏ hàng"
                    );
                },
            });
        });
    });

    // Remove from mini cart
    $(document).on("click", ".remove-from-cart", function (e) {
        e.preventDefault();
        var button = $(this);
        var productId = button.data("id");
        var variantId = button.data("variant-id");
        var row = button.closest("tr");

        $.ajax({
            url: "/cart/remove-cart",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                product_id: productId,
                variant_id: variantId,
            },
            success: function (response) {
                // Xóa dòng sản phẩm trên trang giỏ hàng
                row.remove();

                // Cập nhật tổng tiền trên trang giỏ hàng chi tiết
                if (response.cart_count > 0) {
                    $("#cart-total").text(
                        response.cart_total.toLocaleString() + "đ"
                    );
                    $("#cart-grand-total").text(
                        (response.cart_total + 25000).toLocaleString() + "đ"
                    );
                } else {
                    $(".shoping-cart-total").remove();
                    $(".shoping-cart-table tbody").html(
                        '<tr><td colspan="6" class="text-center">Giỏ hàng trống</td></tr>'
                    );
                }

                // Cập nhật mini cart icon header
                $("#cart-count").text(response.cart_count);
                // Nếu muốn, có thể cập nhật subtotal mini cart luôn
                $(".mini-cart-product-area").html(response.mini_cart_html);
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.error || "Có lỗi xảy ra");
            },
        });
    });

    // Load mini cart
    function loadMiniCart() {
        $.ajax({
            url: "/cart/mini",
            method: "GET",
            success: function (res) {
                if (res.status) {
                    $(".mini-cart-product-area").html(res.html);
                }
            },
        });
    }

    /**************************
     * PAGE CART
     **************************/
    // function updateCart(productId, newValue, $input) {
    //     // CSRF header chỉ cần setup 1 lần, không cần mỗi lần gọi function
    //     // nhưng nếu bạn muốn để đây cũng được
    //     $.ajaxSetup({
    //         headers: {
    //             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //         },
    //     });

    //     $.ajax({
    //         url: "/cart/update", // hoặc dùng route blade: window.cartUpdateUrl
    //         method: "POST",
    //         data: {
    //             product_id: productId,
    //             quantity: newValue,
    //         },
    //         success: function (response) {
    //             console.log("Cart updated successfully", response);
    //             $input.val(newValue); // đảm bảo UI luôn đúng
    //         },
    //         error: function (xhr) {
    //             if (xhr.status === 419) {
    //                 alert("Session hết hạn. Vui lòng tải lại trang!");
    //             } else {
    //                 alert(xhr.responseJSON?.error || "Lỗi cập nhật giỏ hàng");
    //             }
    //             console.log("Error updating cart", xhr);
    //         },
    //     });
    // }

    // ****************************
// HANDLE PAGE CONTACT
// ****************************
$("#contact-form").on("submit", function (e) {
    let name = $('input[name="name"]').val();
    let email = $('input[name="email"]').val();
    let phone = $('input[name="phone"]').val();
    let message = $('textarea[name="message"]').val();
    let errorMessage = "";

    if (name.length < 3) {
        errorMessage += "Họ và tên phải có ít nhất 3 ký tự.<br>";
    }

    if (phone.length < 10 || phone.length > 11) {
        errorMessage += "Số điện thoại phải từ 10-11 số.<br>";
    }

    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        errorMessage += "Email không hợp lệ.<br>";
    }

    if (errorMessage !== "") {
        toastr.error(errorMessage, "Lỗi");
        e.preventDefault();
    }
});


// ==============================
// HANDLE WISHLIST
// ==============================
$(document).on("click", ".add-to-wishlist", function (e) {
    e.preventDefault();

    let productId = $(this).data("id");

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $.ajax({
        url: "/wishlist/add",
        type: "POST",
        data: {
            product_id: productId,
        },
        success: function (response) {
            if (response.status) {
                $("#liiton_wishlist_modal_" + productId).modal("show");
            }
        },
        error: function (xhr) {
            alert("Có lỗi xảy ra với ajax addToWishList.");
        },
    });
});


});
