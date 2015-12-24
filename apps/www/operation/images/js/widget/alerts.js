/**
 * Created by Loin on 14/10/23.
 */
(function ($) {
    var render = function(type, message) {
        $('.page-container h3.page-title').eq(0)
            .after('<div class="alert alert-' + type + '">'
                + '<button class="close" data-dismiss="alert"></button>' + message
                + '</div>');
    };

    $.alerts = {
        info: function(message) {
            render('info', message);
        },
        error: function(message) {
            render('danger', message);
        },
        success: function(message) {
            render('success', message);
        }
    };
})(jQuery);