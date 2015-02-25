/*
 * javascript for the viewer
 *
 * @author Ivo Bathke <ivo.bathke@gmail.com>
 */

function convertCanvasToImage(canvas) {
    var image = new Image();
    image.src = canvas.toDataURL("image/jpg");
    return $(image).addClass('lhp_giv_content');
}

/*
 * to make gigaViewer work again we have to convert canvas to img again
 */
var canvas2image = function(callback) {
    var canvas = $('canvas.lhp_giv_content');
    var image = convertCanvasToImage(canvas[0]);
    $('.lhp_giv_content_holder canvas').replaceWith(image);
    dfgViewer2.element.lhpGigaImgViewer('destroy');
    dfgViewer2.options.previewImgFilename = image.attr('src');
    dfgViewer2.element.lhpGigaImgViewer(dfgViewer2.options);
    //TODO fire event when canvas2 img is done so processbar can finish
    callback();
}

function printDegrees(log, deg) {
    log.html(deg + '&deg;');
}

$.fn.dfgViewer2 = function(options) {
    var viewerHolder = this;
    dfgViewer2.init(this, options);
    //events
    $(window).resize(function() {
        dfgViewer2.initViewer();
    });

    /*events general*/
    $(".pulldown").click(function() {
        $(this).hide();
        $('.pullup').show();
        $("#miniheader_container").hide();
        $("#header_container").show();
    });
    $(".pullup").click(function() {
        $(this).hide();
        $('.pulldown').show();
        $("#miniheader_container").show();
        $("#header_container").hide();
    });
    //signature sidebar einblenden
    $(".viewer_tabs .tab_signature a").click(function() {
        $("#viewer-sidebar").fadeIn(40, function() {
            $('#viewer-sidebar').addClass('span4');
            $('#viewer_viewer').removeClass('span12').addClass('span8');
            dfgViewer2.element.lhpGigaImgViewer('fitToViewport');
        });
        $('.viewer_tabs a[href="#signature"]').tab('show');
        $('.viewer_tabs .tab_signature').addClass('active');
    });
    //signature sidebar ausblenden , vollbild
    $("#viewer-sidebar .close, .viewer_tabs .tab_pure").click(function() {
        $("#viewer-sidebar").fadeOut(40, function() {
            $('#viewer-sidebar').removeClass('span4');
            $('#viewer_viewer').removeClass('span8').addClass('span12');
            dfgViewer2.element.lhpGigaImgViewer('adaptsToContainer');
        });
        $('.viewer_tabs .tab_pure').tab('show');
        $('.viewer_tabs .tab_pure').addClass('active');
    });

    /*events viewer toolbar*/
    //collapse toolbar
    $("#viewer_viewer").on("click", '.lhp_giv_nav ul li.collapsable', function() {
        $('.lhp_giv_nav ul li.collapsable i').toggleClass("icon-plus icon-minus");
        $('.lhp_giv_nav ul li:not(:first)').toggle("slow", function() {
            if ($('.lhp_giv_nav ul li.collapsable i').hasClass('icon-plus')) {
                var title = 'ausklappen';
            }
            else {
                var title = 'einklappen';
            }
            $('.lhp_giv_nav ul li.collapsable').attr('title', 'Werkzeuge ' + title);
        });
    });
    //invert
    $("#viewer_viewer").on("click", '.lhp_giv_nav ul li.invert', function() {
        Caman("img.lhp_giv_content, canvas.lhp_giv_content", function() {
            var img = this;
            $('#viewer_container .overlay, #viewer_container .overlayimg').show({complete: function() {
                    img.invert().render(function() {
                        canvas2image(setTimeout($('#viewer_container .overlay, #viewer_container .overlayimg').hide(), 4000));
                    });
                }
            });
        });
    });
    //rotate image
    $("#viewer_viewer").on("click", '.lhp_giv_nav ul li.rotate', function(e) {
        if ($("#anglepicker").hasClass('ui-anglepicker')) {
            $("#anglepicker").anglepicker('destroy');
            $(".angle-container").hide();
            $(".rotate i").show();
        }
        else {
            $(".angle-container").show();
            $(".angle-container").css({position: "relative", left: "6px", top: "-79px"});
            $(".rotate i").hide();
            $("#anglepicker").anglepicker({
                value: 0,
                clockwise: false,
                change: function(e, ui) {
                    printDegrees($(this).parent().find(".log"), ui.value);
                },
                start: function(e, ui) {
                    //TODO reset img to original angle

                },
                stop: function(e, ui) {
                    dfgViewer2.reset();
                    printDegrees($(this).parent().find(".log"), ui.value);
                    $('#viewer_container .overlay, #viewer_container .overlayimg').show({complete: function() {
                            //trigger caman rotate
                            var degree = ui.value;
                            Caman("img.lhp_giv_content", function() {
                                this.rotate(degree);
                                this.render(function() {
                                    canvas2image(setTimeout($('#viewer_container .overlay, #viewer_container .overlayimg').hide(), 1000));
                                });
                            });
                        }
                    });
                }
            });
        }
    });


    //collapse minimap
    $("#viewer_viewer").on("click", '.lhp_giv_nav ul li.minimap', function() {
        $('.lhp_giv_nav ul li.minimap span').toggleClass("ui-icon-squaresmall-minus ui-icon-squaresmall-plus");
        $('.lhp_giv_map').toggle("slow", function() {
            if ($('.lhp_giv_nav ul li.minimap span').hasClass('ui-icon-squaresmall-minus')) {
                var title = 'ausblenden';
            }
            else {
                var title = 'einblenden';
            }
            $('.lhp_giv_nav ul li.minimap').attr('title', 'MiniMap ' + title);
        });
    });

}

var dfgViewer2 = {
    element: null,
    orgImage: null,
    options: {'startScale': 0,
        'startX': 0,
        'startY': 0,
        'imgDir': '',
        'viewportWidth': '100%',
        'viewportHeight': '100%',
        'fitToViewportShortSide': true,
        'contentSizeOver100': true,
        'mainImgWidth': 6000,
        'mainImgHeight': 6000,
        'testMode': false,
        'intNavAutoHide': false,
        'intNavMoveDownBtt': false,
        'intNavMoveUpBtt': false,
        'intNavMoveRightBtt': false,
        'intNavMoveLeftBtt': false,
        'prependNavCustom': '<li class="collapsable" title="Werkzeuge einklappen"><i class="icon-minus"></i></li>' +
                '<li class="ui-state-default ui-corner-all minimap" title="MiniMap verstecken"><span class="ui-icon ui-icon-squaresmall-minus"></span></li>',
        'appendNavCustom': '<!--<li class="extend" title="erweiterte Werkzeuge einklappen"><i class="icon-plus"></i></li>-->' +
                '<li class="invert" title="Invertieren"><i class="icon-adjust"></i></li>' +
                '<li class="rotate" title="Drehen"><div class="angle-container"><div class="log">0 &deg;</div><div id="anglepicker"></div><div>Drehen</div></div><i class="icon-repeat"></i></li>'+
                '<li class="reset" title="Zurücksetzen"><i class="icon-retweet" /></li>'
    },
    init: function(element, options) {
        dfgViewer2.element = element;
        //apply options
        if (options['paging']) {
            var pagingHTML = '<li><small>&nbsp;' + options['paging'].current + ' von ' + options['paging'].total + '&nbsp;</small></li>';
            dfgViewer2.options['appendNavCustom'] = pagingHTML + dfgViewer2.options['appendNavCustom'];
        }
        this.orgImage = options.previewimgFilename;
        $.extend(dfgViewer2.options, options);
        dfgViewer2.element.lhpGigaImgViewer(dfgViewer2.options);


        Caman.Event.listen("processStart", function(job) {
            console.log("Start:", job.name);

        });
        Caman.Event.listen("processComplete", function(job) {
            console.log("Finished:", job.name);
        });
        Caman.Event.listen("blockFinished", function(job) {
            console.log("blockFinished:", job.name);
        });
        this.initViewer();
    },
    initViewer: function() {
        var viewportHeight = $(window).height(),
                heightHeader = $('#miniheader_container').height(),
                heightNavbar = $('#viewer_container .navbar').height(),
                remove = 47;//TODO this needs typeof(be) dynamic)
        $('#viewer').height(viewportHeight - heightHeader - heightNavbar - remove);
        $('#viewer-sidebar').height(viewportHeight - heightHeader - heightNavbar - remove);
        //iba: workaround for jquery.live, viewer has no callback
        $("#viewer").on("mousemove", '.lhp_giv_nav', function() {
            $(this).draggable();
        });
    },
    reset: function(){
        var resetOptions = dfgViewer2.options;
        resetOptions.previewImgFilename = this.orgImage;
        console.log(resetOptions.previewImgFilename);
        dfgViewer2.element.lhpGigaImgViewer(resetOptions);
        this.initViewer();
    }
}
