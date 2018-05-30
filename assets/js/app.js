import "bootstrap";

// Enable bootstrap pop over
$(function () {
    $('[data-toggle="popover"]').popover()
});

$('#flashMessageModal').modal('show');