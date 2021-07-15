$(function () {
    $.validator.setDefaults({
        submitHandler: function () {
            $.ajax({
                url: authenticateUrl,
                type: "post",
                data: $("#loginForm").serialize(),
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        window.location.href = response.redirect;
                    } else {
                        $('#login-error').removeClass('d-none');

                        setTimeout(function () {
                            $('#login-error').addClass('d-none');
                        }, 5000);
                    }
                },
                error: function (err) {
                    $('#login-error').removeClass('d-none');

                    setTimeout(function () {
                        $('#login-error').addClass('d-none');
                    }, 5000);
                }
            });
        }
    });

    $('#loginForm').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true
            }
        },
        messages: {
            email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
            },
            password: {
                required: "Please enter your password"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.input-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
