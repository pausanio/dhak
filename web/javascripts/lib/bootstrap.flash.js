/*
 Bootstrap Flash
 Version: 1.0
 A small lib to display notifications by @vsergeyev
 https://github.com/vsergeyev/bootstrap-flash

 Twitter Bootstrap (http://twitter.github.com/bootstrap).
 */

(function () {
    $.fn.flash = function (msg, options) {
        var defaults = {
            type: 'info',
            fadeOut: {
                enabled: true,
                delay: 3000
            }};
        var settings  = $.extend(true, {}, defaults, options);

        $(this).prepend('<div class="bootstrap-flash alert hide"><a class="close" href="#" onclick="$(this).parent().hide();return false;">&times;</a><p></p></div>');

        var box = $(".bootstrap-flash");
        box.addClass("alert-" + settings.type).find("p").html(msg);
        box.show();

        if (settings.fadeOut.enabled) {
            setInterval(function () {
                box.fadeOut("slow", function () {
                    box.remove();
                });
            }, settings.fadeOut.delay);
        }
    }
})(jQuery);
