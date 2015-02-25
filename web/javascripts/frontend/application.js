/**
 * Global scripts
 *
 * @author Maik Mettenheimer <mettenheimer@pausanio.de>
 */
$(document).ready(function () {

    /**
     * Trigger the bootstrap tooltip
     */
    $('a.tiptool').tooltip();
    $('.thumbnail img.icon').tooltip()

    /**
     * Lesesaal: Breadcrumb
     */
    if ($('#breadCrumb').length > 0) {
        $("#breadCrumb").jBreadCrumb({'maxFinalElementLength': 750});
    }

    $("a.save-bookmark").click(function (e) {
        var el = this,
            tektonikType = $(this).data('mein-archiv-type'),
            tektonikTitle = $(this).data('mein-archiv-title');
        if ($(this).find('i').hasClass('icon-star-empty')) {
            var url = "/mein-archiv/save",
                type = 'PUT',
                id = $(this).data('mein-archiv-id'),
                msg = ' wurde gemerkt.';
        }
        else {
            var url = "/mein-archiv/delete",
                type = 'DELETE',
                id = $(this).data('mein-archiv-bookmarkid'),
                msg = ' wurde aus "Mein Archiv" gelöscht.';
        }
        $.ajax({
            type: type,
            url: url,
            dataType: "json",
            data: JSON.stringify({ type: tektonikType, id: id })
        })
            .done(function (data) {
                var newid = '';
                if (data) {
                    newid = data.id;
                }
                $("a.save-bookmark").data('mein-archiv-bookmarkid', newid);
                var message = tektonikTitle + msg;
                $('.content').flash(message, {type: 'success'});

                $("a.save-bookmark").toggleClass('hide');
                $(el).toggleClass('icon-star icon-star-empty');
                $(el).attr('title', $(el).find('i').hasClass('icon-star') ? tektonikTitle + ' nicht mehr merken' : tektonikTitle + ' merken');

            })
            .fail(function () {
                var message = 'Es ist ein Fehler aufgetreten.';
                $('.content').flash(message, {type: 'error'});
            });
        e.preventDefault();
    });

    /**
     * Homepage: Slider
     *
     * @see http://bxslider.com/options
     */
    if ($('.bxslider').length > 0) {
        $('.bxslider').bxSlider({
            speed: 500,
            infiniteLoop: true,
            auto: true,
            pause: 7000,
            autoHover: true,
            mode: 'fade',
            adaptiveHeight: false
        });
    }

    /**
     * Supporter: Listnav plugin
     *
     * @example user > _supporter
     *
     * @see http://www.ihwy.com/Labs/jquery-listnav-plugin.aspx
     */
    if ($('#supporterList').length > 0) {
        $('#supporterList').listnav({
            includeAll: true,
            showCounts: true,
            noMatchText: 'Keine Einträge gefunden'
        });
    }

    /**
     * Lesesaal: scroll to active Menu item + Sticky content
     */
    if ($('.navArchive li.active').length > 0) {
        /*
         $('html, body').animate({
         scrollTop: $(".navArchive li.active").offset().top
         }, 0);
         */

        var height_total = $(window).height() - ($('#header').height() + $('#footer').height());

        if ($('.archiv-content .content').height() <= height_total) {
            var highestCol = Math.max($('.archiv-content .sidebar').height(), $('.archiv-content .content').height());
            var bottomSpacing = $('#footer').height() + 150; // 150 = footer img.media-object.max-height

            $('.archiv-content .sidebar').height(highestCol);
            $('.archiv-content .content').height(highestCol);

//            $('.sticky').css({'width': $('.sticky').width() + 'px'});
//            $(".sticky").sticky({
//                topSpacing: 0,
//                bottomSpacing: bottomSpacing
//            });
        }
    }

    /**
     * jQuery Expander Plugin
     *
     * The Expander Plugin hides (collapses) a portion of an element's content
     * and adds a "read more" link so that the text can be viewed by the user
     * if he or she wishes.
     *
     * @source https://github.com/kswedberg/jquery-expander
     */
    /*
     if ($('div.expander').length > 0) {
     $('div.expander').expander({
     slicePoint: 300,
     expandPrefix: ' ',
     expandText: '[mehr]',
     userCollapseText: '[weniger]',
     onCollapse: function() {
     setTimeout(1200, $("html, body").animate({scrollTop: 0}, 600));
     }
     });
     }
     */

    /**
     * Equal height for Lesesaal container
     */
//    if ($('div.archiv-content').length > 0) {
//        var highestCol = Math.max($('.archiv-content .sidebar').height(), $('.archiv-content .content').height());
//        $('.archiv-content .sidebar').height(highestCol);
//        $('.archiv-content .content').height(highestCol);
//    }

    if ($('input#ve_filter').length > 0) {
        // Attach the filter to our input and list
        //My.List.Filter('input#ve_filter', 'ul.ve_list>li>h3>a');
        My.List.Filter('input#ve_filter', 'ul.ve_list>li>a>h3');
    }

});

/**
 * Filter Verzeichnungseinheiten
 */
var My = {}
My.List = {
    Filter: function (inputSelector, listSelector) {

        // Sanity check
        var inp, rgx = new RegExp(), titles = $(listSelector), keys;
        if (titles.length === 0) {
            return false;
        }

        // The list with keys to skip (esc, arrows, return, etc)
        // 8 is backspace, you might want to remove that for better usability
        keys = [13, 27, 32, 37, 38, 39, 40];

        // binding keyup to the unordered list
        $(inputSelector).bind('keyup', function (e) {
            if (e.keyCode == 13) {
                $("#ve_filter").focus();
                return false;
            }
            if (jQuery.inArray(e.keyCode, keys) >= 0) {
                return false;
            }
            // Building the regex from our user input, 'inp' should be escaped
            inp = $(this).val();
            rgx.compile(inp, 'im');
            titles.each(function () {
                if (rgx.source !== '' && !rgx.test($(this).html())) {
                    $(this).closest('li').hide();
                } else {
                    $(this).closest('li').show();
                }
            });
        });
    }
};
