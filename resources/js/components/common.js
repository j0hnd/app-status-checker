$(function () {
    $(document).on("click", ".toggle-cancel-button", function (event) {
        event.preventDefault();
        window.location.href = $(this).data('url');
    });
});
