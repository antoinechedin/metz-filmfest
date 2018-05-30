import "bootstrap";

// Enable bootstrap pop over
$(function () {
    $('[data-toggle="popover"]').popover()
});

$('#registrationModal').modal('show');