const $ = require('jquery');
import "bootstrap";
import "./jquery-ui";
import "./jquery-ui-touch-punch"

// Enable bootstrap pop over
$(function () {
    $('[data-toggle="popover"]').popover()
});

$('#flashMessageModal').modal('show');

// Jquery UI
let list = $('.sortable');

list.each(function (i, el) {
    $(this).sortable({
        connectWith: '.sortable',
        revert: 100,
        axis: "y",
        placeholder: "list-group-item list-group-item-placeholder",
        update: function () {
            // The substring is use to get rid of the "sortable_" in the id
            $('input#form_day_' + el.id.substr(9)).val($(this).sortable("toArray"));
        }
    });
    // The substring is use to get rid of the "sortable_" in the id
    $('input#form_day_' + el.id.substr(9)).val($(this).sortable("toArray"));
});

$(".custom-file-input").on("change",function(){
    //get the file name
    const fileName = $(this).val().replace(/C:\\fakepath\\/i, '');
    //replace the "Choose a file" label
    $(this).next('.custom-file-label').html(fileName);
});