jQuery(function($) {
    $('.multiply_input .multiply_trigger').on('click', function(event) {
            fields = $('.multiply_fields').first().clone();
            fields.appendTo($('.multiply_fields').parent());
       });
    });
