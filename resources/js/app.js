require('./bootstrap');

Echo.channel('apps')
    .listen('AppStatusUpdated', (data) => {
        $.ajax({
            url: "/application/update/row",
            data: {
                application_code: data.application_code
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#app-" + data.application_code).html(response.data.row);
                }
            }
        });
    })
