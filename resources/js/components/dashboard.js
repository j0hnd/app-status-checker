$(function () {
    $(document).on("click", "#toggle-refresh-logs", function (event) {
        event.preventDefault();
        $.ajax({
            url: "/dashboard/refresh",
            dataType: "json",
            beforeSend: function() {
                toastr.info("Please wait while the manual ping is now being process...");
            },
            success: function (response) {
                toastr.success("Request done!");
            }
        });
    });

    $(document).on("click", ".toggle-app-refresh", function (event) {
        event.preventDefault();
        var application_code = $(this).data('code');
        $.ajax({
            url: "/dashboard/refresh/" + application_code,
            dataType: "json",
            beforeSend: function() {
                toastr.info("Please wait while the refresh is being processed...");
            },
            success: function (response) {
                toastr.success("Request done!");
            }
        });
    });
});
