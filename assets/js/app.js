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

list.each(function (i) {
   $(this).sortable({
       connectWith: '.sortable',
       revert: 100,
       tolerance: "pointer",
       axis: "y",
       update: function( event, ui ) {
           $('input#form_day' + this.id).val($(this).sortable("toArray"));
       }
   });
    $('input#form_day' + this.id).val($(this).sortable("toArray"));
});