$(function () {
    $(document).on('click', '#toggle-forgot-password', function (event) {
        event.preventDefault();
        $('.login-card-body').addClass('d-none');
        $('.forgot-password-card-body').removeClass('d-none');
    });

    $(document).on('click', '#toggle-cancel-forgot-password', function (event) {
        event.preventDefault();
        $('.login-card-body').removeClass('d-none');
        $('.forgot-password-card-body').addClass('d-none');
    });

    $('#forgotPasswordForm').validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
                required: "Please enter your registered email address",
                email: "Please enter a valid email address",
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

    $(document).on('click', '#toggle-submit-forgot-password', function (event) {
        event.preventDefault();
        var validator = $( "#forgotPasswordForm" ).validate({
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                email: {
                    required: "Please enter your registered email address",
                    email: "Please enter a valid email address",
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

        if (validator.form()) {
            $.ajax({
                url: forgotPasswordUrl,
                type: "post",
                data: $("#forgotPasswordForm").serialize(),
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        validator.reset();

                        $('#forgot-password-success-message').html("A temporary password has been sent to your registered email.");
                        $('#forgot-password-success').removeClass('d-none')
                        setTimeout(function () {
                            $('#forgot-password-success').addClass('d-none');
                        }, 5000);
                    } else {
                        $('#forgot-password-error-message').html("Email address not existing.");
                        $('#forgot-password-error').removeClass('d-none')
                        setTimeout(function () {
                            $('#forgot-password-error').addClass('d-none');
                        }, 5000);
                    }
                },
                error: function (err) {
                    $('#forgot-password-error-message').html("Email address not existing.");
                    $('#forgot-password-error').removeClass('d-none')
                    setTimeout(function () {
                        $('#forgot-password-error').addClass('d-none');
                    }, 5000);
                }
            });
        }
    });
});
