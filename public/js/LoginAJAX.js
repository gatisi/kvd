/*
*   AJAX login function
*   form must have  data-async
*                   data-target=[where to reload data on login fail]
*
*   response on success must be URL to redirect to (logged in first page)
*
*
*/



jQuery(function($) {
    $('form[data-async]').on('submit', function(event) {
        var form = $(this);
        var target = $(form.attr('data-target'));

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),

            success: function(resp) {
                window.location.href = resp;
            },
            error: function (xhr, ajaxOptions, thrownError) {
               target.load(form.attr('action'));
           }

       });

        event.preventDefault();
    });
});