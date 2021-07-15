$(function () {
    $("#applicationDescription").summernote();

    $(document).on('change', '#monitored', function (event) {
        event.preventDefault();

        if ($(this).is(':checked')) {
            $('#frequency').removeAttr('disabled');
        } else {
            $('#frequency').attr('disabled', 'disabled');
        }
    });

    $(document).on('change', '#applicationType', function (event) {
        event.preventDefault();
        if ($(this).val() == 'api') {
            $('#endpoint-wrapper').removeClass('d-none');
        } else {
            $('#endpoint-wrapper').addClass('d-none');
        }
    });

    $(document).on('click', '#toggle-add-endpoint-parameter', function (event) {
        event.preventDefault();
        $.ajax({
            url: "/application/create-endpoint-param-row",
            dataType: 'json',
            success: function (response) {
                $('#endpoint-parameter-wrapper').removeClass('d-none');
                $('#endpoint-parameter-wrapper').append(response.html);
            }
        });
    });

    $(document).on('click', '.toggle-remove-endpoint-parameter', function (event) {
        event.preventDefault();
        var selected = $(this);

        Swal.fire({
            icon: 'warning',
            title: 'Delete this selected key/value pair?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((response) => {
            if (response.value) {
                selected.parent().parent().remove();
            }
        });
    });

    $(document).on('change', '#authorization_type', function  (event) {
        event.preventDefault();

        if ($(this).val() == 'basic_auth') {
            $('.basic-auth-wrapper').removeClass('d-none');
            $('.api-key-auth-wrapper').addClass('d-none');
            $('.bearer-token-wrapper').addClass('d-none');
        }

        if ($(this).val() == 'api_key_auth') {
            $('.api-key-auth-wrapper').removeClass('d-none');
            $('.basic-auth-wrapper').addClass('d-none');
            $('.bearer-token-wrapper').addClass('d-none');
        }

        if ($(this).val() == 'bearer_token') {
            $('.bearer-token-wrapper').removeClass('d-none');
            $('.api-key-auth-wrapper').addClass('d-none');
            $('.basic-auth-wrapper').addClass('d-none');
        }
    });
});
