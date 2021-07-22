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

    $(document).on("click", "#toggle-group-filter", function (event) {
        event.preventDefault();
        $.ajax({
            url: "/dashboard/filter",
            data: {
                group: $("#filter-group").val()
            },
            dataType: "json",
            beforeSend: function () {
                $("#application-status-wrapper").html("<div class='text-center' id='loader'><i class='fa fa-spinner fa-spin fa-2x fa-fw'></i><span class='ml-2'>Please wait...</span></div>");
            },
            success: function (response) {
                if (response.success) {
                    $("#application-status-wrapper").html(response.data.html);
                }
            },
            error: function () {
                $("#loader").remove();
                toastr.error("Oops! Something went wrong...");
            },
        });
    });
});
