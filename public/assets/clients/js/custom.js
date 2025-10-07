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
});
