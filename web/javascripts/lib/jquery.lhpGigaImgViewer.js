// Giga Image Viewer v1.4 - jQuery image viewer plugin - converting <div> element to an animated image viewer
// (c) 2012 lhp - http://codecanyon.net/user/lhp

/*
 * ----------------------------------------------------------------------------
 * settings:
 * viewportWidth 				string (default: '100%'; accepted: string; Defines width of the area in which image will be displayed inside the outer div (myDiv). Size can be given in pixels, ems, percentages.)
 * viewportHeight 				string (default: '100%'; accepted: string; Defines height of the area in which image will be displayed inside the outer div (myDiv). Size can be given in pixels, ems, percentages.)
 * fitToViewportShortSide		boolean (default: false; accepted: true, false; Shorter side of the displayed object will fit the viewport. )
 * contentSizeOver100 			boolean (default: false; accepted: true, false; If the viewport size (width and height) is greater than the size of the displayed object, allow the object scaled over 100% to fit the viewport (zoom is disabled). )
 * startScale 					number (default: 1; accepted: 0...1; Defines start scale.)
 * startX 						number (default: 0; accepted: integer; Defines start coordinate x in px, in the display object frame of reference, which will be moved to the center of the viewport, if it is possible.)
 * startY 						number (default: 0; accepted: integer; Defines start coordinate y in px, in the display object frame of reference, which will be moved to the center of the viewport, if it is possible.)
 * animTime						number (default: 500; accepted: integer; Defines duration in ms of the scale and position animations.)
 * draggInertia					number (default: 10; accepted: integer; Defines inertia after dragging.)
 * imgDir	 					string (default: ''; accepted: string; Defines a path for images source (tiles, preview image, map thumb image - prepared by imageSlicer.exe).)
 * mainImgWidth					number (default: 256; accepted: integer; Defines width of the main image (after execute imageSlicer.exe displays this value).)
 * mainImgHeight                number (default: 256; accepted: integer; Defines height of the main image (after execute imageSlicer.exe displays this value).)
 * intNavEnable					boolean (default: true; accepted: true, false; Defines the navigation bar enabled/disabled. )
 * intNavPos 					string (default: 'T'; accepted: 'TL', 'T', 'TR', 'L', 'R', 'BL', 'B', 'BR'; Defines the navigation bar position. )
 * intNavAutoHide 				boolean (default: false; accepted: true, false; Defines the navigation bar autohide. )
 * intNavMoveDownBtt			boolean (default: true; accepted: true, false; Defines button visibility 'move down' on the navigation bar. )
 * intNavMoveUpBtt				boolean (default: true; accepted: true, false; Defines button visibility 'move up' on the navigation bar. )
 * intNavMoveRightBtt			boolean (default: true; accepted: true, false; Defines button visibility 'move right' on the navigation bar. )
 * intNavMoveLeftBtt			boolean (default: true; accepted: true, false; Defines button visibility 'move left' on the navigation bar. )
 * intNavZoomBtt				boolean (default: true; accepted: true, false; Defines button visibility 'zoom' on the navigation bar. )
 * intNavUnzoomBtt				boolean (default: true; accepted: true, false; Defines button visibility 'unzoom' on the navigation bar. )
 * intNavFitToViewportBtt		boolean (default: true; accepted: true, false; Defines button visibility 'fit to viewport' on the navigation bar. )
 * intNavFullSizeBtt			boolean (default: true; accepted: true, false; Defines button visibility 'full size' on the navigation bar. )
 * intNavBttSizeRation			number (default: 1; accepted: integer; Defines button size. )
 * mapEnable					boolean (default: true; accepted: true, false; Displays the map palette to quickly change the view using a thumbnails. )
 * mapPos						string (default: 'BL'; accepted: 'TL', 'T', 'TR', 'L', 'R', 'BL', 'B', 'BR'; Defines the map palette position. )
 * popupShowAction				string (default: 'rollover'; accepted: 'click', 'rollover'; Defines 'popup' window opening action. )
 * testMode						boolean (default: false; accepted: true, false; Displays coordinates of the cursor. )
 * ----------------------------------------------------------------------------
 */

(function($) {

    var pubMet, constSett, defaultSett;

    constSett = {
        'dragSmooth': 8,
        'mainImgExt': 'jpg'
    };

    defaultSett = {
        'viewportWidth': '100%',
        'viewportHeight': '100%',
        'fitToViewportShortSide': false,
        'contentSizeOver100': false,
        'startScale': 1,
        'startX': 0,
        'startY': 0,
        'animTime': 500,
        'draggInertia': 0,
        'imgDir': '',
        'mainImgWidth': 256,
        'mainImgHeight': 256,
        'intNavEnable': true,
        'intNavPos': 'B',
        'intNavAutoHide': false,
        'intNavMoveDownBtt': true,
        'intNavMoveUpBtt': true,
        'intNavMoveRightBtt': true,
        'intNavMoveLeftBtt': true,
        'intNavZoomBtt': true,
        'intNavUnzoomBtt': true,
        'intNavFitToViewportBtt': true,
        'intNavFullSizeBtt': true,
        'intNavBttSizeRation': 1,
        'mapEnable': true,
        'mapPos': 'TL',
        'popupShowAction': 'click',
        'testMode': false,
        'previewImgFilename': 'preview.jpg',
        'mapThumbFilename': 'thumb.jpg'
    };

    pubMet = {
        init: function(options, markersContainer) {

            return this.each(function() {
                var $t = $(this), data = $t.data('lhpGIV'), sett = {};
                $.extend(sett, defaultSett, options);
                $.extend(sett, constSett);

                if (!data) {

                    if (sett.draggInertia < 0) {
                        sett.draggInertia = 0;
                    }

                    sett.animTime = parseInt(sett.animTime);
                    if (sett.animTime < 0) {
                        sett.animTime = 0;
                    }

                    $t.data('lhpGIV', {});
                    $t.data('lhpGIV').lc = new LocationChanger(sett, $t, markersContainer);
                }
            });
        },
        /*
         * Sets the position and size of the displayed object. The second parameter is optional - if empty, the size remains unchanged.
         * @param {integer} x Coordinate x in px, in the display object frame of reference, which will be moved to the center of the viewport (if it is possible).
         * @param {integer} y Coordinate y in px, in the display object frame of reference, which will be moved to the center of the viewport (if it is possible).
         * @param {number} scale The size to which the display object will be scaled (if it is possible); optional.
         * @param {boolean} noAnim Animations disabled; optional.
         * @return {Object} Returns jQuery object.
         */
        setPosition: function(x, y, scale, noAnim) {
            return this.each(function() {
                var $t = $(this), data = $t.data('lhpGIV');
                if (data) {
                    $t.data('lhpGIV').lc.setProperties(x, y, scale, noAnim);
                }
            });
        },
        /*
         * Initializes the movement of the display object to the top, to the boundary of the viewport or untill the moveStop method is called.
         * @return {Object} Returns jQuery object.
         */
        moveUp: function() {
            return this.each(function() {
                var $t = $(this), data = $t.data('lhpGIV');
                if (data) {
                    $t.data('lhpGIV').lc.beginDirectMove('U');
                }
            });
        },
        /*
         * Initializes the movement of the display object to the bottom, to the boundary of the viewport or untill the moveStop method is called.
         * @return {Object} Returns jQuery object.
         */
        moveDown: function() {
            return this.each(function() {
                var $t = $(this), data = $t.data('lhpGIV');
                if (data) {
                    $t.data('lhpGIV').lc.beginDirectMove('D');
                }
            });
        },
        /*
         * Initializes the movement of the display object to the left, to the boundary of the viewport or untill the moveStop method is called.
         * @return {Object} Returns jQuery object.
         */
        moveLeft: function() {
            return this.each(function() {
                var $t = $(this), data = $t.data('lhpGIV');
                if (data) {
                    $t.data('lhpGIV').lc.beginDirectMove('L');
                }
            });
        },
        /*
         * Initializes the movement of the display object to the right, to the boundary of the viewport or untill the moveStop method is called.
         * @return {Object} Returns jQuery object.
         */
        moveRight: function() {
            return this.each(function() {
                var $t = $(this), data = $t.data('lhpGIV');
                if (data) {
                    $t.data('lhpGIV').lc.beginDirectMove('R');
                }
            });
        },
        /*
         * Stops the movement of the display object.
         * @return {Object} Returns jQuery object.
         */
        moveStop: function() {
            return this.each(function() {
                var $t = $(this), data = $t.data('lhpGIV');
                if (data) {
                    $t.data('lhpGIV').lc.stopDirectMoving();
                }
            });
        },
        /*
         * Initializes the zooming of the display object up to 100% or untill the zoomStop method is called.
         * @return {Object} Returns jQuery object.
         */
        zoom: function() {
            return this.each(function() {
                var $t = $(this), data = $t.data('lhpGIV');
                if (data) {
                    $t.data('lhpGIV').lc.beginZooming('Z');
                }
            });
        },
        /*
         * Initializes the unzooming of the display object up to the viewport's size or untill the zoomStop method is called.
         * @return {Object} Returns jQuery object.
         */
        unzoom: function() {
            return this.each(function() {
                var $t = $(this), data = $t.data('lhpGIV');
                if (data) {
                    $t.data('lhpGIV').lc.beginZooming('U');
                }
            });
        },
        /*
         * Stops the zooming/unzooming of the display object.
         * @return {Object} Returns jQuery object.
         */
        zoomStop: function() {
            return this.each(function() {
                var $t = $(this), data = $t.data('lhpGIV');
                if (data) {
                    $t.data('lhpGIV').lc.stopZooming();
                }
            });
        },
        /*
         * Fits the display obejct's size to the viewport size and moves the object to the center of the viewport.
         * @return {Object} Returns jQuery object.
         */
        fitToViewport: function() {
            return this.each(function() {
                var $t = $(this), data = $t.data('lhpGIV');
                if (data) {
                    $t.data('lhpGIV').lc.setProperties(null, null, 0);
                }
            });
        },
        /*
         * Sets the initial size of the display object and moves the object to the center of the viewport.
         * @return {Object} Returns jQuery object.
         */
        fullSize: function() {
            return this.each(function() {
                var $t = $(this), data = $t.data('lhpGIV');
                if (data) {
                    $t.data('lhpGIV').lc.setProperties(null, null, 1);
                }
            });
        },
        /*
         * Control the correct position and size of the object displayed inside the viewport.
         * @return {Object} Returns jQuery object.
         */
        adaptsToContainer: function() {
            return this.each(function() {
                var $t = $(this), data = $t.data('lhpGIV');
                if (data) {
                    $t.data('lhpGIV').lc.adaptsToContainer();
                }
            });
        },
        /*
         * @return {Object} Returns object with control values. The object is identical as the object returned by the event 'givChange'.
         */
        getCurrentState: function() {
            var $t = $(this), data = $t.data('lhpGIV'), res = {};
            if (data) {
                res = $t.data('lhpGIV').lc.getCurrentState();
            }
            return res;
        },
        /*
         * Destructor. Removes the viewer from the page. Restores the original appearance and functionality of the outer <div> element. Allows to efficiently clean the memory.
         * @return {Object} Returns jQuery object.
         */
        destroy: function() {
            return this.each(function() {
                var $t = $(this), data = $t.data('lhpGIV');
                if (data) {
                    $t.data('lhpGIV').lc.destroy();
                    $t.removeData('lhpGIV');
                }
            });
        }
    };

    $.fn.lhpGigaImgViewer = function(method) {
        if (pubMet[method]) {
            return pubMet[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return pubMet.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.lhpGigaImgViewer');
            return null;
        }
    };

    /*location changer*/
    var LocationChanger = function(sett, $mainHolder, markersContainer) {
        this.isTouchDev = this.isTouchDevice();
        this.sett = sett;
        this.$mainHolder = $mainHolder;
        this.lastMousePageCoor = {};
        this.lastDrag = {};
        this.contentFullSize = {};
        this.$givHol = null;
        this.$contentHol = null;
        this.$content = null;
        this.$preloadHol = null;
        this.$blackScreen = null;
        this.$infoBox = null;
        this.movingIntreval = null;
        this.movingDirectIntreval = null;
        this.navAutohideInterval = null;
        this.speedX = this.speedY = null;
        this.targetX = this.targetY = null;
        this.allow = {allowDown: false, allowUp: false, allowLeft: false, allowRight: false, allowZoom: false, allowUnzoom: false};
        this.isScaled = false;
        this.sm = new ScaleManager();

        /*nav*/
        this.nav = null;

        /*map*/
        this.map = null;

        /*markers*/
        this.markersContainer = markersContainer;
        this.markers = null;

        /*tile layers*/
        this.tiles = {};
        this.tileSize = 256;

        this.contentFullSize = {'w': this.sett.mainImgWidth, 'h': this.sett.mainImgHeight};
        this.createHolders();
        /*
         /*load content*/
        this.contentLoader = new LoaderImgContent(this.sett.imgDir + this.sett.previewImgFilename, this.$contentHol, function(that) {
            return function($content) {
                that.imgContentStart($content);
            }
        }(this));
        this.contentLoader.loadStart();
        /**/
    };
    //initialization
    LocationChanger.prototype.isTouchDevice = function() {
        if (navigator.userAgent.toLowerCase().indexOf('chrome') > -1) {
            if (navigator.userAgent.match(/(iPad)|(iPhone)|(iPod)|(android)|(webOS)/i) == null) {
                return false;
            }
        }
        return (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch);
    };
    LocationChanger.prototype.createHolders = function() {
        this.$givHol = $('<div />')
                .addClass('lhp_giv_holder')
                .css({position: 'relative', overflow: 'hidden', width: this.sett.viewportWidth, height: this.sett.viewportHeight});
        // 2013-07-16, mm - Do not display the preoload holder!
        //this.$preloadHol = $('<div />').addClass('lhp_giv_preload_holder');

        this.$contentHol = $('<div />')
                .addClass('lhp_giv_content_holder')
                .css({position: 'absolute', width: this.contentFullSize.w, height: this.contentFullSize.h});

        this.$blackScreen = $('<div />')
                .addClass('lhp_giv_blackScreen')
                .css({position: 'absolute', 'z-index': '9999', width: '100%', height: '100%', background: '#ffffff'});

        this.$givHol.append(this.$preloadHol);
        this.$givHol.append(this.$blackScreen);
        this.$givHol.append(this.$contentHol);
        this.$mainHolder.append(this.$givHol);

        if (this.sett.testMode) {
            this.$infoBox = $('<div />').addClass('lhp_giv_infoBox_holder');
            this.$givHol.append(this.$infoBox);
        }
    }
    LocationChanger.prototype.imgContentStart = function($content) {
        this.$content = $content;
        this.start();
        this.$blackScreen.animate({opacity: 0}, {duration: 500, complete: function() {
                $(this).remove();
            }});
    }
    LocationChanger.prototype.start = function() {

        if (this.sett.intNavEnable) {
            this.nav = new Navigation(this.sett, this.$mainHolder, this.isTouchDev);
            this.$givHol.prepend(this.nav.ini());
        }

        if (this.sett.mapEnable) {
            this.map = new Map(this.sett, this.$mainHolder, this.$content, this.isTouchDev);
            this.map.ini(this.$givHol);
            //iba: if (i hide here icant toggle again)
            if (this.sett.hideMap) {
                $('.lhp_giv_map').css('visibility', 'hidden');
            }
        }

        this.$content.addClass('lhp_giv_content').css({'float': 'left', width: this.contentFullSize.w, height: this.contentFullSize.h});

        this.markers = new Markers(this.$mainHolder, this.$contentHol, this.markersContainer, this.isTouchDev, this.sett.popupShowAction, this.sett.startScale);
        this.markers.ini();

        if (this.isTouchDev) {
            this.$contentHol.bind('touchstart.lhpGIV', {'_this': this}, this.mousedownHandler);
        } else {
            this.$contentHol.bind('mouseenter.lhpGIV', {'_this': this}, this.mouseenterHandler);
            this.$contentHol.bind('mousedown.lhpGIV', {'_this': this}, this.mousedownHandler);
            this.$contentHol.bind('mouseup.lhpGIV', {'_this': this}, this.mouseupHandler);
            this.$contentHol.bind('mouseleave.lhpGIV', {'_this': this}, this.mouseupHandler);
            this.$contentHol.bind('mousewheel.lhpGIV', {'_this': this}, this.mousewheelHandler);

            if (this.sett.testMode) {
                this.$contentHol.bind('mousemove.lhpGIV', {'_this': this}, this.showCurrentCoor);
            }
        }

        this.setProperties(this.sett.startX, this.sett.startY, this.sett.startScale, true);
        this.addTiles();
    }
    LocationChanger.prototype.destroy = function() {
        /*clear content*/
        this.contentLoader.dispose();

        /*clear callback*/
        this.animStop();
        this.stopMoving();
        this.stopDirectMoving();

        /*destroy markers*/
        if (this.markers) {
            this.markers.destroy();
        }

        /*destroy nav*/
        if (this.nav) {
            this.nav.destroy();
        }

        /*destroy map*/
        if (this.map) {
            this.map.destroy();
        }

        /*clear handler*/
        this.$mainHolder.unbind('.lhpGIV');
        this.$contentHol.unbind();

        /*clear holders*/
        this.$givHol.remove();

        /*clear properties*/
        $.each(this, function(k, v) {
            if (!$.isFunction(v)) {
                k = null;
            }
        });
    }
    LocationChanger.prototype.preloaderDisplay = function() {
        //this.$preloadHol.stop(true, true).fadeIn(200);
    }
    LocationChanger.prototype.preloaderHide = function() {
        //this.$preloadHol.fadeOut(300);
    }
    //mouse handlers
    LocationChanger.prototype.mousePageCoor = function(e) {
        var r = {x: e.pageX, y: e.pageY};
        e = e.originalEvent;

        if (this.isTouchDev && e) {
            r.x = e.changedTouches[0].pageX;
            r.y = e.changedTouches[0].pageY;
        }
        return r;
    }
    LocationChanger.prototype.mouseenterHandler = function(e) {
        if (!e.data._this.sett.testMode)
            e.data._this.$contentHol.removeClass('lhp_cursor_drag').addClass('lhp_cursor_hand');
    }
    LocationChanger.prototype.mousedownHandler = function(e) {
        var _this = e.data._this;

        _this.animStop(true);
        _this.stopMoving();
        _this.stopDirectMoving();

        if (_this.isTouchDev) {
            _this.$contentHol.unbind('touchmove.lhpGIV', _this.mousemoveHandler).bind('touchmove.lhpGIV', {'_this': _this}, _this.mousemoveHandler);
            _this.$contentHol.unbind({'touchend.lhpGIV': _this.positioning}).bind('touchend.lhpGIV', {'_this': _this}, _this.positioning);
        } else {
            _this.$contentHol.removeClass('lhp_cursor_hand').addClass('lhp_cursor_drag');
            _this.$contentHol.unbind('mousemove.lhpGIV', _this.mousemoveHandler).bind('mousemove.lhpGIV', {'_this': _this}, _this.mousemoveHandler);
            _this.$contentHol.unbind({'mouseup.lhpGIV': _this.positioning}).bind('mouseup.lhpGIV', {'_this': _this}, _this.positioning);
        }

        _this.lastMousePageCoor = _this.mousePageCoor(e);
        e.preventDefault();
    }
    LocationChanger.prototype.mousemoveHandler = function(e) {
        var _this = e.data._this;

        if (_this.isTouchDev) {
            _this.$contentHol.unbind({'touchend.lhpGIV': _this.positioning});
            _this.$contentHol.unbind({'touchend.lhpGIV': _this.stopDraggingHandler}).bind('touchend.lhpGIV', {'_this': _this}, _this.stopDraggingHandler);
        } else {
            _this.$contentHol.unbind('mouseup.lhpGIV', _this.positioning);
            _this.$contentHol.unbind({'mouseup.lhpGIV': _this.stopDraggingHandler}).bind('mouseup.lhpGIV', {'_this': _this}, _this.stopDraggingHandler);
            _this.$contentHol.unbind({'mouseleave.lhpGIV': _this.stopDraggingHandler}).bind('mouseleave.lhpGIV', {'_this': _this}, _this.stopDraggingHandler);
        }

        _this.dragging(e, 'hard');
        e.preventDefault();
    }
    LocationChanger.prototype.mouseupHandler = function(e) {
        var _this = e.data._this;

        _this.$contentHol.unbind('mousemove.lhpGIV', _this.mousemoveHandler);
        _this.$contentHol.unbind('mouseup.lhpGIV', _this.positioning);
        if (!_this.sett.testMode) {
            _this.$contentHol.removeClass('lhp_cursor_drag').addClass('lhp_cursor_hand');
        } else {
            _this.$contentHol.css('cursor', 'default');
        }
    }
    LocationChanger.prototype.stopDraggingHandler = function(e) {
        var _this = e.data._this;
        _this.$contentHol.unbind({'mouseup.lhpGIV': _this.stopDraggingHandler});
        _this.$contentHol.unbind({'mouseleave.lhpGIV': _this.stopDraggingHandler});
        _this.dragging(e, 'inertia');
    }
    LocationChanger.prototype.mousewheelHandler = function(e, delta) {
        var _this = e.data._this,
                newScale = (delta > 0) ? _this.sm.nextScale() : _this.sm.prevScale(),
                newProp = _this.calculateScale(e, newScale);

        _this.animStop();
        _this.stopMoving();
        _this.stopDirectMoving();

        if ((delta > 0 && !_this.allow.allowZoom) || (delta < 0 && !_this.allow.allowUnzoom))
            return false;

        _this.animSizeAndPos(newProp.x, newProp.y, newProp.w, newProp.h);
        e.preventDefault();
        e.stopPropagation();
        return false;
    }
    LocationChanger.prototype.showCurrentCoor = function(e) {
        var _this = e.data._this,
                mousePageCoor = _this.mousePageCoor(e),
                contentHolPos = _this.$contentHol.position(),
                givHolOff = _this.$givHol.offset(),
                iterimScale = _this.$content.width() / _this.contentFullSize.w;

        mousePageCoor.x = Math.round((mousePageCoor.x - contentHolPos.left - givHolOff.left) / iterimScale);
        mousePageCoor.y = Math.round((mousePageCoor.y - contentHolPos.top - givHolOff.top) / iterimScale);

        _this.$infoBox.css('display', 'block');
        _this.$infoBox.html('x:' + mousePageCoor.x + ' y:' + mousePageCoor.y);
    }
    //changers
    LocationChanger.prototype.adaptsToContainer = function() {
        if (this.$content) {
            var iterimScale = this.$content.width() / this.contentFullSize.w;
            iterimScale = (iterimScale > 1) ? 1 : iterimScale;

            this.animStop();
            this.stopMoving();
            this.stopDirectMoving();
            this.setProperties(null, null, iterimScale, true);
        }
    }
    LocationChanger.prototype.beginZooming = function(t) {

        if (this.$content) {

            var delta = (t == 'Z') ? 1 : -1,
                    data = {_this: this},
            givCenter = {'x': (this.$givHol.width() / 2), 'y': (this.$givHol.height() / 2)},
            givHolOff = this.$givHol.offset(),
                    mouseGivCenter = {'x': (givCenter.x + givHolOff.left), 'y': (givCenter.y + givHolOff.top)},
            e = {data: data, pageX: mouseGivCenter.x, pageY: mouseGivCenter.y}; //pseudo event object

            this.animStop(true);
            this.stopMoving();
            this.stopDirectMoving();

            if (!this.movingIntreval) {
                this.movingIntreval = setInterval(function(_this, e, delta) {
                    return function() {
                        _this.zooming(e, delta);
                    };
                }(this, e, delta), this.sett.animTime / 5);
            }

            this.zooming(e, delta);
        }
    }
    LocationChanger.prototype.zooming = function(e, delta) {
        var newScale = (delta > 0) ? this.sm.nextScale() : this.sm.prevScale(),
                newProp = this.calculateScale(e, newScale);

        this.animStop();
        this.animSizeAndPos(newProp.x, newProp.y, newProp.w, newProp.h);

        if (this.sett.fitToViewportShortSide) {
            if (newScale >= 1 || newProp.w <= this.$givHol.width() || newProp.h <= this.$givHol.height()) {
                this.stopZooming();
            }
        } else {
            if (newScale >= 1 || (newProp.w <= this.$givHol.width() && newProp.h <= this.$givHol.height())) {
                this.stopZooming();
            }
        }
    }
    LocationChanger.prototype.stopZooming = function() {
        this.stopMoving();
    }
    LocationChanger.prototype.beginDirectMove = function(direct) {

        if (this.$content) {

            this.animStop(true);
            this.stopMoving();
            this.sm.setScale(this.$content.width() / this.contentFullSize.w);
            this.speedX = this.speedY = 0;

            switch (direct) {
                case 'U':
                    this.speedY = -50000 / this.sett.animTime;
                    break;
                case 'D':
                    this.speedY = 50000 / this.sett.animTime;
                    break;
                case 'L':
                    this.speedX = -50000 / this.sett.animTime;
                    break;
                case 'R':
                    this.speedX = 50000 / this.sett.animTime;
                    break;
            }

            if (!this.movingDirectIntreval && (this.speedX || this.speedY)) {
                this.movingDirectIntreval = setInterval(function(_this) {
                    return function() {
                        _this.directMoveWithInertia();
                    };
                }(this), 10);
            }

        }
    }
    LocationChanger.prototype.directMoveWithInertia = function() {
        var holLeft = this.$contentHol.position().left,
                holTop = this.$contentHol.position().top,
                targetX = Math.ceil(holLeft + this.speedX),
                targetY = Math.ceil(holTop + this.speedY),
                safeTarget;

        if (!this.movingIntreval) {
            this.movingIntreval = setInterval(function(_this) {
                return function() {
                    _this.moveWithInertia();
                };
            }(this), 10);
        }

        safeTarget = this.getSafeTarget(targetX, targetY, this.speedX, this.speedY);

        this.targetX = Math.round(safeTarget.x);
        this.targetY = Math.round(safeTarget.y);
    }
    LocationChanger.prototype.stopDirectMoving = function() {
        clearInterval(this.movingDirectIntreval);
        this.movingDirectIntreval = null;
    }
    LocationChanger.prototype.dragging = function(e, type) {
        var draggIner = this.sett.draggInertia,
                mousePageCoor = this.mousePageCoor(e),
                draggShftX = mousePageCoor.x - this.lastMousePageCoor.x,
                draggShftY = mousePageCoor.y - this.lastMousePageCoor.y;

        if (type == 'inertia' && this.lastDragg) {
            this.draggingWithInertia(this.lastDragg.x * draggIner, this.lastDragg.y * draggIner);
        } else {
            this.draggingHard(draggShftX, draggShftY);
        }

        this.lastDragg = {x: (Math.abs(draggShftX) < 5) ? 0 : draggShftX,
            y: (Math.abs(draggShftY) < 5) ? 0 : draggShftY};

        this.lastMousePageCoor = mousePageCoor;
    }
    LocationChanger.prototype.draggingHard = function(draggShftX, draggShftY) {
        var contentHolPos = this.$contentHol.position(),
                targetX = contentHolPos.left + draggShftX,
                targetY = contentHolPos.top + draggShftY,
                safeTarget = this.getSafeTarget(targetX, targetY, draggShftX, draggShftY);

        this.animStop();
        this.addTiles();

        this.$contentHol.css({'left': safeTarget.x, 'top': safeTarget.y});
        this.dispatchEventChange();
    }
    LocationChanger.prototype.draggingWithInertia = function(draggShftX, draggShftY) {
        var targetX = this.targetX + draggShftX,
                targetY = this.targetY + draggShftY,
                safeTarget;

        if (!this.movingIntreval) {
            this.movingIntreval = setInterval(function(_this) {
                return function() {
                    _this.moveWithInertia();
                };
            }(this), 10);
            targetX = this.$contentHol.position().left + draggShftX;
            targetY = this.$contentHol.position().top + draggShftY;
        }

        safeTarget = this.getSafeTarget(targetX, targetY, draggShftX, draggShftY);

        this.targetX = Math.round(safeTarget.x);
        this.targetY = Math.round(safeTarget.y);
    }
    LocationChanger.prototype.getSafeTarget = function(targetX, targetY, moveDirectX, moveDirectY) {
        var limits = this.getLimit(this.sm.getScale()),
                xMin = limits.xMin,
                xMax = limits.xMax,
                yMin = limits.yMin,
                yMax = limits.yMax,
                givHolW = this.$givHol.width(),
                givHolH = this.$givHol.height(),
                givHolCentX = givHolW / 2,
                givHolCentY = givHolH / 2,
                newContentW = this.contentFullSize.w * this.sm.getScale(),
                newContentH = this.contentFullSize.h * this.sm.getScale();

        /*Y*/
        if ((moveDirectY < 0) && (targetY < yMin)) { //move up limit
            targetY = yMin;
        } else if ((moveDirectY > 0) && (targetY > yMax)) { // move down limit
            targetY = yMax;
        }

        if (newContentH < givHolH) {
            targetY = givHolCentY - newContentH / 2;
        }

        /*X*/
        if ((moveDirectX < 0) && (targetX < xMin)) { //move left limit
            targetX = xMin;
        } else if ((moveDirectX > 0) && (targetX > xMax)) { //move right limit
            targetX = xMax;
        }

        if (newContentW < givHolW) {
            targetX = givHolCentX - newContentW / 2;
        }

        return {x: targetX, y: targetY};
    }
    LocationChanger.prototype.moveWithInertia = function() {
        var contentHolPos = this.$contentHol.position(),
                damping = this.sett.dragSmooth,
                distX, distY;

        contentHolPos.left = Math.ceil(contentHolPos.left);
        contentHolPos.top = Math.ceil(contentHolPos.top);
        distX = (this.targetX - contentHolPos.left) / damping;
        distY = (this.targetY - contentHolPos.top) / damping;

        if (Math.abs(distX) < 1) {
            distX = (distX > 0) ? 1 : -1
        }

        if (Math.abs(distY) < 1) {
            distY = (distY > 0) ? 1 : -1
        }

        if (contentHolPos.left == this.targetX) {
            distX = 0;
        }

        if (contentHolPos.top == this.targetY) {
            distY = 0;
        }

        this.$contentHol.css({'left': contentHolPos.left + distX, 'top': contentHolPos.top + distY});

        if (contentHolPos.left == this.targetX && contentHolPos.top == this.targetY) {
            this.stopDirectMoving();
            this.stopMoving();
            this.addTiles();
        }

        this.dispatchEventChange();
    }
    LocationChanger.prototype.stopMoving = function() {
        clearInterval(this.movingIntreval);
        this.movingIntreval = null;
    }
    LocationChanger.prototype.positioning = function(e) {
        var _this = e.data._this,
                newProp = _this.calculatePosInCenter(e);

        _this.animStop();
        _this.stopMoving();
        _this.stopDirectMoving();
        _this.animSizeAndPos(newProp.x, newProp.y);
    }
    LocationChanger.prototype.setProperties = function(x, y, scale, noAnim) {
        if (this.$content) {
            var data = {_this: this},
            givCenter = {'x': (this.$givHol.width() / 2), 'y': (this.$givHol.height() / 2)},
            givHolOff = this.$givHol.offset(),
                    mouseGivCenter = {'x': (givCenter.x + givHolOff.left), 'y': (givCenter.y + givHolOff.top)},
            e = {data: data, pageX: mouseGivCenter.x, pageY: mouseGivCenter.y}, //pseudo event object
            contentHolPos = this.$contentHol.position(),
                    newProp, iterimScale,
                    newX = contentHolPos.left, newY = contentHolPos.top, newW = this.$content.width(), newH = this.$content.height();

            x = parseFloat(x);
            y = parseFloat(y);
            scale = parseFloat(scale);
            if (!isNaN(scale)) {
                if (scale > 1) {
                    scale = 1;
                }
                newProp = this.calculateScale(e, scale);
                newX = newProp.x;
                newY = newProp.y;
                newW = newProp.w;
                newH = newProp.h;
            }

            iterimScale = newW / this.contentFullSize.w;

            if (!isNaN(x)) {
                newX = -(x * iterimScale) + givCenter.x;
            }

            if (!isNaN(y)) {
                newY = -(y * iterimScale) + givCenter.y;
            }

            this.animStop();
            this.stopMoving();
            this.stopDirectMoving();
            this.animSizeAndPos(newX, newY, newW, newH, noAnim);
        }
    }
    LocationChanger.prototype.calculatePosInCenter = function(e) {
        var contentHolPos = this.$contentHol.position(),
                givHolOff = this.$givHol.offset(),
                givCenter = {'x': (this.$givHol.width() / 2), 'y': (this.$givHol.height() / 2)},
        mousePageCoor = this.mousePageCoor(e),
                mouseHolCoor = {'x': (mousePageCoor.x - givHolOff.left), 'y': (mousePageCoor.y - givHolOff.top)},
        shftX, shftY,
                newX, newY;

        shftX = givCenter.x - mouseHolCoor.x;
        shftY = givCenter.y - mouseHolCoor.y;
        newX = contentHolPos.left + shftX;
        newY = contentHolPos.top + shftY;

        return {x: newX, y: newY, 'shftX': shftX, 'shftY': shftY};
    }
    LocationChanger.prototype.calculateScale = function(e, newScale) {
        var givHolOff = this.$givHol.offset(),
                contentOff = this.$content.offset(),
                mousePageCoor = this.mousePageCoor(e),
                iterimScale,
                mouseContentCoor,
                newX, newY, newW, newH;

        newScale = this.getSafeScale(newScale);
        this.sm.setScale(newScale);
        iterimScale = this.$content.width() / this.contentFullSize.w;

        mouseContentCoor = {'x': (mousePageCoor.x - contentOff.left) / iterimScale,
            'y': (mousePageCoor.y - contentOff.top) / iterimScale};

        newW = Math.round(this.contentFullSize.w * newScale);
        newH = Math.round(this.contentFullSize.h * newScale);
        newX = Math.round(contentOff.left - givHolOff.left + mouseContentCoor.x * (iterimScale - newScale));
        newY = Math.round(contentOff.top - givHolOff.top + mouseContentCoor.y * (iterimScale - newScale));

        return {x: newX, y: newY, w: newW, h: newH};
    }
    LocationChanger.prototype.getSafeScale = function(newScale) {
        var safeScale = (newScale <= 0) ? 0.00001 : newScale,
                givHolW = this.$givHol.width(),
                givHolH = this.$givHol.height(),
                defContentW = this.contentFullSize.w,
                defContentH = this.contentFullSize.h,
                newContentW = defContentW * safeScale,
                newContentH = defContentH * safeScale,
                horScale = givHolW / defContentW,
                verScale = givHolH / defContentH,
                givHolProp = givHolW / givHolH, //viewport proportion; p < 1 -  vertical; p > 1 - horizontal
                contentProp = newContentW / newContentH; //content proportion


        if (this.sett.fitToViewportShortSide) {
            if (newContentW < givHolW || newContentH < givHolH) {
                horScale = givHolW / this.contentFullSize.w;
                verScale = givHolH / this.contentFullSize.h;
                safeScale = Math.max(horScale, verScale);

                if (!this.sett.contentSizeOver100 && (defContentW <= givHolW || defContentH <= givHolH)) {
                    safeScale = 1;
                }
            }
        } else {
            if (newContentW < givHolW && newContentH < givHolH) {
                if (contentProp <= givHolProp) {
                    safeScale = verScale;
                } else {
                    safeScale = horScale;
                }
            }

            if (!this.sett.contentSizeOver100 && defContentW <= givHolW && defContentH <= givHolH) {
                safeScale = 1;
            }
        }

        return safeScale;
    }
    LocationChanger.prototype.getLimit = function(inScale) {
        var xMin = -(Math.round(this.contentFullSize.w * inScale) - this.$givHol.width()),
                yMin = -(Math.round(this.contentFullSize.h * inScale) - this.$givHol.height());
        return {'xMin': xMin, 'xMax': 0, 'yMin': yMin, 'yMax': 0};
    }
    LocationChanger.prototype.getSafeXY = function(x, y, inScale) {
        var limits = this.getLimit(inScale),
                givHolW = this.$givHol.width(),
                givHolH = this.$givHol.height(),
                givHolCentX = givHolW / 2,
                givHolCentY = givHolH / 2,
                defContentW = this.contentFullSize.w,
                defContentH = this.contentFullSize.h,
                newContentW = defContentW * inScale,
                newContentH = defContentH * inScale,
                safeX = x, safeY = y;


        /*X*/
        if (newContentW < givHolW) {
            if (x < limits.xMin || x > limits.xMax) {
                safeX = givHolCentX - newContentW / 2;
            }
        }
        else
        {
            if (x < limits.xMin) {
                safeX = limits.xMin;
            } else if (x > limits.xMax) {
                safeX = limits.xMax;
            }
        }

        /*Y*/
        if (newContentH < givHolH)
        {
            if (y < limits.yMin || y > limits.yMax) {
                safeY = givHolCentY - newContentH / 2;
            }
        }
        else
        {
            if (y < limits.yMin) {
                safeY = limits.yMin;
            } else if (y > limits.yMax) {
                safeY = limits.yMax;
            }
        }

        return {'x': Math.round(safeX), 'y': Math.round(safeY)};
    }
    LocationChanger.prototype.animSizeAndPos = function(x, y, w, h, noAnim) {
        var safeXY, iterimScale,
                stepHandlerAnimPos = function(_this) {
            return function() {
                _this.dispatchEventChange();
            }
        }(this),
                completeHandlerAnimPos = function(_this) {
            return function() {
                _this.addTiles();
                _this.dispatchEventChange();
            }
        }(this),
                stepHandlerAnimSize = function(_this) {
            return function() {
                _this.setSizeAndPosTiles();
                _this.dispatchEventChange();
            }
        }(this),
                completeHandlerAnimSize = function(_this) {
            return function() {
                var resultInIn = true;
                _this.isScaled = false;
                _this.setSizeAndPosTiles(resultInIn);
                _this.dispatchEventChange();
            }
        }(this);

        if (w != undefined) {
            iterimScale = w / this.contentFullSize.w;
        } else {
            iterimScale = this.$content.width() / this.contentFullSize.w;
        }

        if (x != undefined && y != undefined) {
            safeXY = this.getSafeXY(x, y, iterimScale);
            if (noAnim) {
                this.$contentHol.css({left: safeXY.x, top: safeXY.y});
                completeHandlerAnimPos();
            } else {
                this.$contentHol.animate({left: safeXY.x, top: safeXY.y},
                {duration: this.sett.animTime, easing: 'easeOutCubic',
                    step: stepHandlerAnimPos,
                    complete: completeHandlerAnimPos});
            }
        }

        if (w != undefined && h != undefined && (w != this.$content.width() || h != this.$content.height())) {
            this.isScaled = true;
            if (noAnim) {
                this.$content.css({width: w, height: h});
                stepHandlerAnimSize();
                completeHandlerAnimSize();
            } else {
                this.removeAllTiles();
                this.$content.animate({width: w, height: h},
                {duration: this.sett.animTime, easing: 'easeOutCubic',
                    step: stepHandlerAnimSize,
                    complete: completeHandlerAnimSize});
            }
        }
    }
    LocationChanger.prototype.animStop = function(saveScale) {
        if (this.$contentHol && this.$content) {
            this.$contentHol.stop().clearQueue();
            this.$content.stop().clearQueue();

            if (saveScale) {
                this.sm.setScale(this.$content.width() / this.contentFullSize.w);
            }

            this.dispatchEventChange();
        }
    }
    LocationChanger.prototype.dispatchEventChange = function() {
        var a = this.getCurrentState(),
                e = $.Event("givChange", a);

        this.allow = a;
        this.$mainHolder.trigger(e);
    }
    LocationChanger.prototype.getCurrentState = function() {
        var a = {};

        if (this.$content) {

            var contentHolPos = this.$contentHol.position(),
                    limits = this.getLimit(this.sm.getScale()),
                    contentW = this.$content.width(),
                    contentH = this.$content.height(),
                    givCenter = {'x': (this.$givHol.width() / 2), 'y': (this.$givHol.height() / 2)},
            iterimScale = contentW / this.contentFullSize.w;

            /*position*/
            a.allowDown = (Math.ceil(contentHolPos.top) < Math.ceil(limits.yMax));
            a.allowUp = (Math.ceil(contentHolPos.top) > Math.ceil(limits.yMin));
            a.allowRight = (Math.ceil(contentHolPos.left) < Math.ceil(limits.xMax));
            a.allowLeft = (Math.ceil(contentHolPos.left) > Math.ceil(limits.xMin));

            /*scale*/
            a.allowZoom = (contentW / this.contentFullSize.w < 1);
            if (this.sett.fitToViewportShortSide) {
                a.allowUnzoom = (contentW > this.$givHol.width() && contentH > this.$givHol.height());
            } else {
                a.allowUnzoom = (contentW > this.$givHol.width() || contentH > this.$givHol.height());
            }

            /*prop width, height viewport-content*/
            a.wPropViewpContent = this.$givHol.width() / contentW;
            a.hPropViewpContent = this.$givHol.height() / contentH;

            /*content position in viewport center*/
            a.xPosInCenter = Math.round((-contentHolPos.left + givCenter.x) / iterimScale);
            a.yPosInCenter = Math.round((-contentHolPos.top + givCenter.y) / iterimScale);

            /*scale*/
            a.scale = iterimScale;

            /*is scaled*/
            a.isScaled = this.isScaled;

        }

        return a;
    }
    LocationChanger.prototype.addTiles = function() {
// 2013-07-16, mm: Do not load the tiles!
//        var tileToChange = this.getTileToChange(), toLoadNum = tileToChange.L.length, tileId, i, tileData, prevTile;
//
//        for (i = 0; i < toLoadNum; i++) {
//            tileId = tileToChange.L[i];
//
//            tileData = {};
//            tileData.$img = $('<img/>');
//            tileData.url = this.sett.imgDir + tileId + '.' + this.sett.mainImgExt;
//            tileData.state = 'loadWait';
//            if (prevTile)
//                tileData.prev = prevTile;
//            if (tileToChange.L[i + 1])
//                tileData.next = tileToChange.L[i + 1];
//            this.tiles[tileId] = tileData;
//
//            prevTile = tileId;
//        }
//
//        if (toLoadNum > 0) {
//            this.preloaderDisplay();
//            this.loadTile(tileToChange.L[0]);
//        }
    }
    LocationChanger.prototype.loadTile = function(id) {
        var $img = this.tiles[id].$img,
                url = this.tiles[id].url,
                m = id.split('_'),
                sPref = m[0],
                convScale = this.sm.getConversionScale(sPref);

        this.tiles[id].state = 'loadStarted';

        if (!this.isVisible(id)) { //if current tile not visible, unload current [and load next]
            this.unloadTile(id);
            return;
        }

        $img.addClass('tile').addClass(id)
                .data('convScale', convScale)
                .load(function(_this, id) {
            return function() {
                _this.tileLoadCompleteHandler(id);
            }
        }(this, id))
                .error(function(_this, id) {
            return function() {
                _this.unloadTile(id);
            }
        }(this, id))
                .attr('src', url);
    }
    LocationChanger.prototype.tileLoadCompleteHandler = function(id) {
        var nextTileId = this.tiles[id].next,
                $img = this.tiles[id].$img,
                _this = this,
                $imgCopy,
                realSize,
                iterimScale = (this.$content.width() / this.contentFullSize.w) / $img.data('convScale');

        /*get real image size*/
        $imgCopy = $img.clone();
        $imgCopy.removeAttr('style');
        $imgCopy.removeAttr('class');
        $imgCopy.removeAttr('width');
        $imgCopy.removeAttr('height');
        $imgCopy.css({"visibility": "hidden", 'position': 'absolute', 'left': '-9999px'});
        this.$contentHol.prepend($imgCopy);
        realSize = {'w': $imgCopy.width(), 'h': $imgCopy.height()};
        $imgCopy.remove();
        /**/


        this.tiles[id].state = 'loadComplete';
        $img.css({'z-index': '1', 'position': 'absolute'})
                .css(this.getCorrectSizeAndPosTiles(id, realSize, iterimScale, true))
                .data('realSize', realSize);
        this.$contentHol.prepend($img);

        if (nextTileId) {
            setTimeout(function() {
                return function() {
                    _this.loadTile(nextTileId);
                }
            }(), 10);
        } else {
            this.tilesQueueComplete();
        }
    }
    LocationChanger.prototype.setSizeAndPosTiles = function(resultInInt) {
        var iterimScale = this.$content.width() / this.contentFullSize.w,
                currScale, $tileImg, i;

        for (i in this.tiles) {
            $tileImg = this.tiles[i].$img;
            currScale = iterimScale / $tileImg.data('convScale');
            $tileImg.css(this.getCorrectSizeAndPosTiles(i, $tileImg.data('realSize'), currScale, resultInInt));
        }
    }
    LocationChanger.prototype.getCorrectSizeAndPosTiles = function(id, realSize, isScale, resultInInt) {
        var index = id.split('_'),
                verIndex = index[1],
                horIndex = index[2],
                tileCurrSize = (resultInInt) ? Math.ceil(this.tileSize * isScale) : (this.tileSize * isScale),
                currL = horIndex * tileCurrSize,
                currR = verIndex * tileCurrSize;

        if (realSize) {
            var w = (resultInInt) ? Math.ceil(realSize.w * isScale) : (realSize.w * isScale);
            var h = (resultInInt) ? Math.ceil(realSize.h * isScale) : (realSize.h * isScale);
            return {width: w, height: h, left: currL, top: currR};
        } else {
            // for not loaded tiles (the real size is not yet known)
            return {width: tileCurrSize, height: tileCurrSize, left: currL, top: currR};
        }
    }
    LocationChanger.prototype.tilesQueueComplete = function() {
        var allLoadComplete = true, i;

        for (i in this.tiles) {
            if (this.tiles[i].state != 'loadComplete') {
                allLoadComplete = false;
                break;
            }
        }

        if (allLoadComplete) {
            this.removeInvisibleTiles();
            this.preloaderHide();
        }
    }
    LocationChanger.prototype.removeInvisibleTiles = function() {
        var _this = this,
                tileVisible = this.getTileToChange().V,
                tileId;

        this.$contentHol.find('.tile').each(function() {
            tileId = $(this).attr('class').split(' ')[1];
            if (tileVisible.indexOf(tileId) == -1) {
                _this.unloadTile(tileId);
            }
        });
    }
    LocationChanger.prototype.removeAllTiles = function() {
        var _this = this, tileId;

        this.$contentHol.find('.tile').each(function() {
            tileId = $(this).attr('class').split(' ')[1];
            _this.unloadTile(tileId);
        });
    }
    LocationChanger.prototype.unloadTile = function(id) {
        var tile = this.tiles[id],
                nextTileId = tile.next,
                prevTileId = tile.prev;

        tile.$img.removeData('convScale');
        tile.$img.removeData('realSize');
        tile.$img.remove();
        delete this.tiles[id];

        if (tile.state == 'loadWait') {
            if (prevTileId)
                this.tiles[prevTileId].next = nextTileId;
            if (nextTileId) {
                this.tiles[nextTileId].prev = prevTileId;
            } else { //if last
                this.tilesQueueComplete();
            }
        } else if (tile.state == 'loadStarted') {
            if (nextTileId) {
                this.loadTile(nextTileId);
            } else { //if last
                this.tilesQueueComplete();
            }
        }
    }
    LocationChanger.prototype.isVisible = function(id) {
        var visibleTiles = this.getTileToChange().V;
        return (visibleTiles.indexOf(id) != -1);
    }
    LocationChanger.prototype.getTileToChange = function() {
        var h, v,
                iterimScale = this.sm.getScale(),
                scalePrefix = this.sm.getScalePrefix(iterimScale),
                convScale = this.sm.getConversionScale(scalePrefix),
                tileSize = this.tileSize,
                tileCurrSize = tileSize * iterimScale / convScale,
                contentHolPos = this.$contentHol.position(),
                contentHolPosX = Math.abs(contentHolPos.left) - ((contentHolPos.left > 0) ? contentHolPos.left : 0),
                contentHolPosY = Math.abs(contentHolPos.top) - ((contentHolPos.top > 0) ? contentHolPos.top : 0),
                horTileNum = Math.ceil(this.contentFullSize.w * convScale / tileSize),
                verTileNum = Math.ceil(this.contentFullSize.h * convScale / tileSize),
                tileRect, tileId, tileName,
                tileToLoad = '',
                tileVisible = '',
                viewportRect = {'l': contentHolPosX,
            'r': contentHolPosX + this.$givHol.width(),
            't': contentHolPosY,
            'b': contentHolPosY + this.$givHol.height()};

        for (v = 0; v < verTileNum; v++) {

            for (h = 0; h < horTileNum; h++) {
                tileId = scalePrefix + '_' + (v) + '_' + (h);
                tileName = ',' + tileId;
                tileRect = {'l': h * tileCurrSize,
                    'r': h * tileCurrSize + tileCurrSize,
                    't': v * tileCurrSize,
                    'b': v * tileCurrSize + tileCurrSize};

                if (((tileRect.l >= viewportRect.l && tileRect.l <= viewportRect.r) || (tileRect.r >= viewportRect.l && tileRect.r <= viewportRect.r) || (tileRect.l <= viewportRect.l && tileRect.r >= viewportRect.r)) &&
                        ((tileRect.t >= viewportRect.t && tileRect.t <= viewportRect.b) || (tileRect.b >= viewportRect.t && tileRect.b <= viewportRect.b) || (tileRect.t <= viewportRect.t && tileRect.b >= viewportRect.b))) {

                    if (!this.tiles[tileId]) {
                        tileToLoad += tileName;
                    }

                    tileVisible += tileName;
                }
            }
        }

        if (tileToLoad.length > 0) {
            tileToLoad = tileToLoad.slice(1).split(',');
        }

        return {'L': tileToLoad, 'V': tileVisible.slice(1)};
    }

    /*scale manager*/
    var ScaleManager = function() {
        this.step = .1;
        this.curr = 1;
    };
    ScaleManager.prototype.getScale = function() {
        return this.curr;
    }
    ScaleManager.prototype.setScale = function(v) {
        this.curr = v;
    }
    ScaleManager.prototype.nextScale = function() {
        var scale = this.curr + this.step;
        if (scale > 1) {
            this.curr = 1;
        } else {
            this.curr = scale;
        }
        return this.getScale();
    }
    ScaleManager.prototype.prevScale = function() {
        var scale = this.curr - this.step;
        if (scale < this.step) {
            this.curr = 0;
        } else {
            this.curr = scale;
        }
        return this.getScale();
    }
    ScaleManager.prototype.getScalePrefix = function(s) {
        /*
         * '0' = 1.00
         * '1' = 0.75
         * '2' = 0.50
         * '3' = 0.25
         * '4' = 0.125
         */

        if (s > .75) {
            return 0;
        } else if (s > .5 && s <= .75) {
            return 1;
        } else if (s > .25 && s <= .5) {
            return 2;
        } else if (s > .125 && s <= .25) {
            return 3;
        } else {
            return 4;
        }
    }
    ScaleManager.prototype.getConversionScale = function(sPref) {
        var conv = [1, .75, .5, .25, .125];
        return conv[sPref];
    }
    /**/

    /*content loaders*/
    var LoaderImgContent = function(url, $imgHolder, callback) {
        this.url = url;
        this.$imgHolder = $imgHolder;
        this.callback = callback;
    }
    LoaderImgContent.prototype.loadStart = function() {
        var $img = $('<img/>');

        $img.one('load', function(that) {
            return function(e) {
                that.loadComplete(e);
            }
        }(this));

        this.$imgHolder.prepend($img);
        $img.attr('src', this.url); //load
    }
    LoaderImgContent.prototype.loadComplete = function(e) {
        if (this.callback) {
            this.callback($(e.currentTarget));
        }
    }
    LoaderImgContent.prototype.dispose = function() {
        this.callback = null;
    }
    /**/

    /*navigation*/
    var Navigation = function(sett, $mainHolder, isTouchDev) {
        this.sett = sett;
        this.$mainHolder = $mainHolder;
        this.isTouchDev = isTouchDev;
        this.$navHol = null;
    }
    Navigation.prototype.navBttCalcSize = function() {
        var width = 27, paddingHoriziontal = 0, paddingVertical = 4, ratio = this.sett.intNavBttSizeRation;

        if (ratio > 1) {
            paddingVertical = Math.ceil(paddingVertical * ratio);
            paddingHoriziontal = paddingVertical - 4;
            width += 2 * paddingHoriziontal;
        }

        return {'width': width, 'paddingHoriziontal': paddingHoriziontal, 'paddingVertical': paddingVertical};
    }
    Navigation.prototype.ini = function() {
        var $ul = $('<ul />').addClass('ui-widget ui-helper-clearfix'),
                $mainHolder = this.$mainHolder,
                $navHol,
                $li, $span,
                _this = this,
                navBttW = this.navBttCalcSize().width,
                paddingVertical = this.navBttCalcSize().paddingVertical,
                paddingHoriziontal = this.navBttCalcSize().paddingHoriziontal,
                navHolW = 0,
                btt = [['moveDown', 'moveStop', 'ui-icon-carat-1-n', 'intNavMoveDownBtt', 'Runter'],
            ['moveUp', 'moveStop', 'ui-icon-carat-1-s', 'intNavMoveUpBtt', 'Hoch'],
            ['moveRight', 'moveStop', 'ui-icon-carat-1-w', 'intNavMoveRightBtt', 'Rechts'],
            ['moveLeft', 'moveStop', 'ui-icon-carat-1-e', 'intNavMoveLeftBtt', 'Links'],
            ['zoom', 'zoomStop', 'icon-zoom-in', 'intNavZoomBtt', 'vergrößern'],
            ['unzoom', 'zoomStop', 'icon-zoom-out', 'intNavUnzoomBtt', 'verkleinern'],
            ['fitToViewport', null, 'icon-screenshot', 'intNavFitToViewportBtt', 'Größe anpassen'],
            ['fullSize', null, 'icon-fullscreen', 'intNavFullSizeBtt', 'Originalgröße'],
        ];
        $navHol = $('<div class="lhp_giv_nav"/>').addClass('lhp_giv_nav_pos_' + this.sett.intNavPos);
        this.$navHol = $navHol;

        $.each(btt, function(i) {
            var mousedownFunc = btt[i][0],
                    mouseupFunc = btt[i][1],
                    settName = btt[i][3],
                    title = btt[i][4],
                    $li, $span;

            if (_this.sett[settName]) {
                navHolW += navBttW;

                $li = $('<li />').addClass(mousedownFunc).addClass('tiptool').attr('title', title),
                        $span = $('<span />').addClass('ui-icon ' + btt[i][2]),
                        $iTag = $('<i/>').addClass(btt[i][2])
                $li.append($iTag);

                $ul.append($li);

                $li.css('padding', paddingVertical + 'px ' + paddingHoriziontal + 'px');

                $li.bind('mouseenter.lhpGIV touchstart.lhpGIV', function() {
                    if (!$(this).hasClass('lhp_giv_nav_btt_disab')) {
//                        $(this).addClass('ui-state-hover'); // iba: wenn nav items komplett dynamisch dann wieder anschalten
                    }
                });

                $li.bind('mouseleave.lhpGIV touchend.lhpGIV', function() {
                    $(this).removeClass('ui-state-hover');
                });

                $li.bind(((_this.isTouchDev) ? 'touchstart.lhpGIV' : 'mousedown.lhpGIV'), function(func) {
                    return function(e) {
                        if (!$(this).hasClass('lhp_giv_nav_btt_disab')) {
                            $mainHolder.lhpGigaImgViewer(func);
                        }
                        e.preventDefault();
                    }
                }(mousedownFunc));

                if (mouseupFunc) {
                    $li.bind(((_this.isTouchDev) ? 'touchend.lhpGIV' : 'mouseup.lhpGIV'), function(func) {
                        return function(e) {
                            if (!$(this).hasClass('lhp_giv_nav_btt_disab')) {
                                $mainHolder.lhpGigaImgViewer(func);
                            }
                            e.preventDefault();
                        }
                    }(mouseupFunc));
                }

            }
        });

        //key events
        $(document.documentElement).keypress(function(e) {
            e = e || window.event;
            var charCode = e.which || e.keyCode,
                    charStr = String.fromCharCode(charCode);
            if (charStr == "-") {
                $mainHolder.lhpGigaImgViewer('unzoom');
            }
            if (charStr == "+") {
                $mainHolder.lhpGigaImgViewer('zoom');
            }
            if (charStr == "#") {
                $mainHolder.lhpGigaImgViewer('fullSize');
            }
            if (charStr == ".") {
                $mainHolder.lhpGigaImgViewer('fitToViewport');
            }
        });
        $(document.documentElement).keydown(function(e) {
            if (e.keyCode == 38) {
                $mainHolder.lhpGigaImgViewer('moveDown');
            }
            if (e.keyCode == 40) {
                $mainHolder.lhpGigaImgViewer('moveUp');
            }
            if (e.keyCode == 39) {
                $mainHolder.lhpGigaImgViewer('moveLeft');
            }
            if (e.keyCode == 37) {
                $mainHolder.lhpGigaImgViewer('moveRight');
            }
        });
        $(document.documentElement).keyup(function(ev) {
            $mainHolder.lhpGigaImgViewer('moveStop');
            $mainHolder.lhpGigaImgViewer('zoomStop');
        });

        /**
         * Add Custom nav items (html)
         * @author Maik Mettenheimer <mettenheimer@pausanio.de>
         */
        if (_this.sett['appendNavCustom']) {
            var $custli = ''
            $.each( _this.sett['appendNavCustom'], function( key, value ) {
                $custli += value;
            });
            $ul.append($custli);
        }
        if (_this.sett['prependNavCustom']) {
            var $custli = '';
            $.each( _this.sett['prependNavCustom'], function( key, value ) {
                $custli += value;
            });
            $ul.prepend($custli);
        }

        /*position*/
        if (this.$navHol.hasClass('lhp_giv_nav_pos_L') || this.$navHol.hasClass('lhp_giv_nav_pos_R')) {
            this.$navHol.css('width', navBttW);
            this.$navHol.css('margin-top', -navHolW / 2);
        }
        if (this.$navHol.hasClass('lhp_giv_nav_pos_T') || this.$navHol.hasClass('lhp_giv_nav_pos_B')) {
            this.$navHol.css('margin-left', -navHolW / 2);
        }

        $mainHolder.bind('givChange.lhpGIV', function(e) {

            var c1 = 'lhp_giv_nav_btt_disab', c2 = 'ui-state-hover';

            if (e.allowDown) {
                $navHol.find('.moveDown').removeClass(c1);
            } else {
                $navHol.find('.moveDown').removeClass(c2).addClass(c1);
            }

            if (e.allowUp) {
                $navHol.find('.moveUp').removeClass(c1);
            } else {
                $navHol.find('.moveUp').removeClass(c2).addClass(c1);
            }

            if (e.allowLeft) {
                $navHol.find('.moveLeft').removeClass(c1);
            } else {
                $navHol.find('.moveLeft').removeClass(c2).addClass(c1);
            }

            if (e.allowRight) {
                $navHol.find('.moveRight').removeClass(c1);
            } else {
                $navHol.find('.moveRight').removeClass(c2).addClass(c1);
            }

            if (e.allowZoom) {
                $navHol.find('.zoom').removeClass(c1);
                $navHol.find('.fullSize').removeClass(c1);
            } else {
                $navHol.find('.zoom').removeClass(c2).addClass(c1);
                $navHol.find('.fullSize').removeClass(c2).addClass(c1);
            }

            if (e.allowUnzoom) {
                $navHol.find('.unzoom').removeClass(c1);
                $navHol.find('.fitToViewport').removeClass(c1);
            } else {
                $navHol.find('.unzoom').removeClass(c2).addClass(c1);
                $navHol.find('.fitToViewport').removeClass(c2).addClass(c1);
            }

        });

        if (this.sett.intNavAutoHide) {
            $navHol.css('display', 'none');
            $mainHolder.bind('mouseenter.lhpGIV touchstart.lhpGIV', function() {
                clearInterval(_this.navAutohideInterval);
                $navHol.fadeIn('fast');
            });
            $mainHolder.bind('mouseleave.lhpGIV touchend.lhpGIV', function() {
                clearInterval(_this.navAutohideInterval);
                _this.navAutohideInterval = setInterval(function($navHol) {
                    return function() {
                        $navHol.stop().clearQueue().fadeOut('fast');
                    };
                }($navHol), 1000);
            });
        }
        if (this.sett.extendToolbar) {
            $ul.find('.extend').removeClass('un').show();
            $ul.find('li.openclose').attr('title', 'erweiterte Werkzeuge einklappen');
            $ul.find('li.openclose i').removeClass('icon-resize-full').addClass('icon-resize-small');
        }
        if (this.sett.collapseToolbar) {
            $ul.find('li:not(.collapsable, .icon-grabber)').hide();
            $ul.find('li.collapsable').attr('title', 'Werkzeuge ausklappen');
            $ul.find('li.collapsable i').removeClass('icon-minus').addClass('icon-plus');
        }
        if (this.sett.hideMap) {
            $ul.find('li.minimap').attr('title', 'MiniMap einblenden');
            $ul.find('li.minimap i').removeClass('icon-minimap').addClass('icon-minimap');
        }
        $navHol.append($ul);
        return $navHol;
    }
    Navigation.prototype.destroy = function() {
        if (this.$navHol) {
            this.$navHol.find('li').each(function() {
                $(this).unbind('.lhpGIV');
            });
        }
    }
    /**/

    /*map*/
    var Map = function(sett, $mainHolder, $previewImg, isTouchDev) {
        this.isTouchDev = isTouchDev;
        this.sett = sett;
        this.$mainHolder = $mainHolder;
        this.$previewImg = $previewImg;
        this.$img = null;
        this.$mapHol = null;
        this.$mapWrappHol = null;
        this.$vr = null;
        this.lastMousePageCoor = {};
    }
    Map.prototype.ini = function($givHol) {
        this.$mapHol = $('<div class="lhp_giv_map"/>');
        this.$mapWrappHol = $('<div class="lhp_giv_map_wrapp_hol"/>');
        this.$mapHol.append(this.$mapWrappHol);

        $givHol.prepend(this.$mapHol);

        /*load content*/
        this.contentLoader = new LoaderImgContent(this.sett.imgDir + this.sett.mapThumbFilename, this.$mapWrappHol, function(that) {
            return function($content) {
                that.start($content);
            }
        }(this));

        var _this = this;
        this.contentLoadStartTimeout = setTimeout(function() {
            return function() {
                _this.contentLoader.loadStart();
            }
        }(), 10);
    }
    Map.prototype.start = function($content) {
        var w = $content.width(),
                h = $content.height(),
                e;

        this.$img = $content;
        this.$img.css({'cursor': 'pointer'});

        this.$mapHol.addClass('lhp_giv_map_pos_' + this.sett.mapPos)
                .css({'width': w, 'height': h});

        this.$mapWrappHol.addClass('lhp_giv_map_wrapp_hol_' + this.sett.mapPos)
                .css({'width': w, 'height': h});

        switch (this.sett.mapPos) {
            case 'T':
            case 'B':
                this.$mapHol.css('margin-left', -w / 2)
                break;
            case 'L':
            case 'R':
                this.$mapHol.css('margin-top', -h / 2)
                break;
        }

        this.$mapWrappHol.append(this.$img);

        this.$vr = $('<div class="lhp_giv_map_vr"/>')
                .css({'position': 'absolute', 'z-index': 2})
                .appendTo(this.$mapWrappHol);

        this.vrAddInteractions();
        this.$mainHolder.bind('givChange.lhpGIV', {'_this': this}, this.givChangeHandler);

        e = this.$mainHolder.lhpGigaImgViewer('getCurrentState');
        e.data = {};
        e.data._this = this;
        this.givChangeHandler(e);
    }
    Map.prototype.destroy = function() {
        clearTimeout(this.contentLoadStartTimeout);

        /*clear handler*/
        if(this.$vr){
            this.$vr.unbind('.lhpGIV');
            this.$mapHol.unbind('.lhpGIV');
            this.$img.unbind('.lhpGIV');
        }
        this.contentLoader.dispose();
        this.contentLoader = null;

    }
    Map.prototype.vrAddInteractions = function() {
        if (this.isTouchDev) {
            this.$vr.bind('touchstart.lhpGIV', {'_this': this}, this.mousedownHandler);
            this.$vr.bind('touchend.lhpGIV', {'_this': this}, this.mouseupHandler);
            this.$img.bind('touchstart.lhpGIV', {'_this': this}, this.mouseclickHandler);
        } else {
            this.$vr.bind('mouseenter.lhpGIV', {'_this': this}, this.mouseenterHandler);
            this.$vr.bind('mousedown.lhpGIV', {'_this': this}, this.mousedownHandler);
            this.$mapHol.bind('mouseup.lhpGIV', {'_this': this}, this.mouseupHandler);
            this.$mapHol.bind('mouseleave.lhpGIV', {'_this': this}, this.mouseupHandler);
            this.$img.bind('click.lhpGIV', {'_this': this}, this.mouseclickHandler);
        }
    }
    //mouse handlers
    Map.prototype.mouseenterHandler = function(e) {
        e.data._this.$vr.removeClass('lhp_cursor_drag').addClass('lhp_cursor_hand');
    }
    Map.prototype.mousedownHandler = function(e) {
        var _this = e.data._this;

        _this.$mainHolder.unbind('givChange.lhpGIV', _this.givChangeHandler);

        if (_this.isTouchDev) {
            _this.$mapHol.unbind('touchmove.lhpGIV', _this.mousemoveHandler).bind('touchmove.lhpGIV', {'_this': _this}, _this.mousemoveHandler);
        } else {
            _this.$vr.removeClass('lhp_cursor_hand').addClass('lhp_cursor_drag');
            _this.$mapHol.unbind('mousemove.lhpGIV', _this.mousemoveHandler).bind('mousemove.lhpGIV', {'_this': _this}, _this.mousemoveHandler);
        }

        _this.$vr.unbind('mouseenter.lhpGIV', _this.mouseenterHandler);
        _this.lastMousePageCoor = _this.mousePageCoor(e);
        _this.$vr.addClass('lhp_giv_map_vr_over');
        e.preventDefault();
    }
    Map.prototype.mousemoveHandler = function(e) {
        var _this = e.data._this;

        if (_this.isTouchDev) {
            _this.$mapHol.unbind({'touchend.lhpGIV': _this.stopDraggingHandler}).bind('touchend.lhpGIV', {'_this': _this}, _this.stopDraggingHandler);
        } else {
            _this.$mapHol.unbind({'mouseup.lhpGIV': _this.stopDraggingHandler}).bind('mouseup.lhpGIV', {'_this': _this}, _this.stopDraggingHandler);
            _this.$mapHol.unbind({'mouseleave.lhpGIV': _this.stopDraggingHandler}).bind('mouseleave.lhpGIV', {'_this': _this}, _this.stopDraggingHandler);
        }

        _this.dragging(e);
        e.preventDefault();
    }
    Map.prototype.mouseupHandler = function(e) {
        var _this = e.data._this;

        _this.$mapHol.unbind('touchmove.lhpGIV', _this.mousemoveHandler);
        _this.$mapHol.unbind('mousemove.lhpGIV', _this.mousemoveHandler);
        _this.$mainHolder.unbind('givChange.lhpGIV', _this.givChangeHandler).bind('givChange.lhpGIV', {'_this': _this}, _this.givChangeHandler);

        if (!_this.isTouchDev) {
            _this.$vr.unbind('mouseenter.lhpGIV', _this.mouseenterHandler).bind('mouseenter.lhpGIV', {'_this': _this}, _this.mouseenterHandler);
            _this.$vr.removeClass('lhp_cursor_drag').addClass('lhp_cursor_hand');
        }

        _this.$vr.removeClass('lhp_giv_map_vr_over');
    }
    Map.prototype.mouseclickHandler = function(e) {
        var _this = e.data._this,
                mousePageCoor = _this.mousePageCoor(e),
                mapHolOffset = _this.$mapHol.offset(),
                x = (mousePageCoor.x - mapHolOffset.left) * _this.sett.mainImgWidth / _this.$mapWrappHol.width(),
                y = (mousePageCoor.y - mapHolOffset.top) * _this.sett.mainImgHeight / _this.$mapWrappHol.height();

        _this.$mainHolder.lhpGigaImgViewer('setPosition', x, y)
    }
    //changers
    Map.prototype.dragging = function(e) {
        var mousePageCoor = this.mousePageCoor(e),
                draggShftX = mousePageCoor.x - this.lastMousePageCoor.x,
                draggShftY = mousePageCoor.y - this.lastMousePageCoor.y,
                contentHolPos = this.$vr.position(),
                targetX = contentHolPos.left + draggShftX,
                targetY = contentHolPos.top + draggShftY,
                safeTarget = this.getSafeTarget(targetX, targetY, draggShftX, draggShftY);

        this.$vr.css({'left': safeTarget.x, 'top': safeTarget.y});
        this.lastMousePageCoor = mousePageCoor;
        this.mainHolderSetPosition(safeTarget.x, safeTarget.y);
    }
    Map.prototype.stopDraggingHandler = function(e) {
        var _this = e.data._this;
        _this.$mapHol.unbind({'touchend.lhpGIV': _this.stopDraggingHandler});
        _this.$mapHol.unbind({'mouseup.lhpGIV': _this.stopDraggingHandler});
        _this.$mapHol.unbind({'mouseleave.lhpGIV': _this.stopDraggingHandler});
    }
    Map.prototype.mousePageCoor = function(e) {
        var r = {x: e.pageX, y: e.pageY};
        e = e.originalEvent;
        if (this.isTouchDev && e) {
            r.x = e.changedTouches[0].pageX;
            r.y = e.changedTouches[0].pageY;
        }
        return r;
    }
    Map.prototype.getSafeTarget = function(targetX, targetY, moveDirectX, moveDirectY) {
        var xMin = 0, yMin = 0,
                xMax = this.$mapWrappHol.width() - this.$vr.width(),
                yMax = this.$mapWrappHol.height() - this.$vr.height();

        /*Y*/
        if ((moveDirectY < 0) && (targetY < yMin)) { //move up limit
            targetY = yMin;
        } else if ((moveDirectY > 0) && (targetY > yMax)) { // move down limit
            targetY = yMax;
        }

        /*X*/
        if ((moveDirectX < 0) && (targetX < xMin)) { //move left limit
            targetX = xMin;
        } else if ((moveDirectX > 0) && (targetX > xMax)) { //move right limit
            targetX = xMax;
        }

        return {x: targetX, y: targetY};
    }
    Map.prototype.mainHolderSetPosition = function(vrX, vrY) {
        var x = (vrX + this.$vr.width() / 2) * this.sett.mainImgWidth / this.$mapWrappHol.width(),
                y = (vrY + this.$vr.height() / 2) * this.sett.mainImgHeight / this.$mapWrappHol.height();

        this.$mainHolder.lhpGigaImgViewer('setPosition', x, y, null, true);
    }
    Map.prototype.givChangeHandler = function(e) {
        var _this = e.data._this,
                mapW = _this.$mapWrappHol.width(),
                mapH = _this.$mapWrappHol.height(),
                vrW = Math.round(mapW * ((e.wPropViewpContent > 1) ? 1 : e.wPropViewpContent)),
                vrH = Math.round(mapH * ((e.hPropViewpContent > 1) ? 1 : e.hPropViewpContent)),
                vrX = Math.round((mapW / _this.sett.mainImgWidth) * e.xPosInCenter - (vrW / 2)),
                vrY = Math.round((mapH / _this.sett.mainImgHeight) * e.yPosInCenter - (vrH / 2));

        _this.$vr.css({'width': vrW, 'height': vrH, 'left': vrX, 'top': vrY});
    }
    /**/

    /*markers*/
    var Markers = function($mainHolder, $contentHol, containerId, isTouchDev, popupShowAction, startScale) {
        this.$mainHolder = $mainHolder;
        this.$contentHol = $contentHol;
        this.containerId = containerId;
        this.mClass = 'lhp_giv_hotspot';
        this.mInnClass = 'lhp_giv_marker';
        this.pClass = 'lhp_giv_popup';
        this.isTouchDev = isTouchDev;
        this.markers = [];
        this.popups = [];
        this.currShowPopup = null;
        this.popupShowAction = popupShowAction;
        this.startScale = startScale;

    }
    Markers.prototype.ini = function() {
        var _this = this;

        $('#' + this.containerId).find('.' + this.mClass).each(function() {
            _this.addMarker($(this).clone(true, true));
        });

        this.$mainHolder.bind('givChange.lhpGIV', {'_this': this}, this.givChangeHandler);

        if (this.startScale == 1) {
            this.positionsMarkers(1);
        }
    }
    Markers.prototype.destroy = function() {
        var i;

        for (i in this.markers) {
            this.markers[i].destroy();
        }

        for (i in this.popups) {
            this.popups[i].destroy();
        }

        this.$mainHolder = null;
        this.$contentHol = null;
        this.markers = null;
        this.popups = null;
    }
    Markers.prototype.addMarker = function($m) {
        var id = 0,
                x = 0,
                y = 0,
                visibleScale = 0,
                url,
                marker,
                popup,
                p;

        if ($m.attr('data-id')) {
            id = $m.attr('data-id');
        }

        if ($m.attr('data-x')) {
            x = parseInt($m.attr('data-x'));
        }

        if ($m.attr('data-y')) {
            y = parseInt($m.attr('data-y'));
        }

        if ($m.attr('data-visible-scale')) {
            visibleScale = parseFloat($m.attr('data-visible-scale'));
        }

        if ($m.attr('data-url')) {
            url = $m.attr('data-url');
        }

        p = $m.find('.' + this.pClass).remove()[0];

        /*marker*/
        this.$contentHol.append($m);
        marker = new Marker(this, id, x, y, visibleScale, url, $m);
        this.markers.push(marker);
        /**/

        /*popup window*/
        if (p) {
            this.$contentHol.append(p);
            popup = new Popup(id, $(p), marker);
            popup.ini();
            this.popups.push(popup);
            marker.popup = popup;
        }
        /**/

        marker.ini();
    }
    Markers.prototype.givChangeHandler = function(e) {
        var _this = e.data._this;

        if (e.isScaled) {
            _this.positionsMarkers(e.scale);
            _this.positionsPopup();
        } else {
            _this.positionsPopup();
        }
    }
    Markers.prototype.positionsMarkers = function(inScale) {
        var i, marker;

        for (i in this.markers) {
            marker = this.markers[i];
            marker.positions(inScale);
            marker.visibility(inScale);
        }
    }
    Markers.prototype.positionsPopup = function() {
        if (this.currShowPopup) {
            this.currShowPopup.positions();
        }
    }
    Markers.prototype.getLimit = function() {
        var contentHolPos = this.$contentHol.position(),
                xMin = -contentHolPos.left,
                xMax = xMin + this.$mainHolder.width(),
                yMin = -contentHolPos.top,
                yMax = yMin + this.$mainHolder.height();

        return {'xMin': xMin, 'xMax': xMax, 'yMin': yMin, 'yMax': yMax};
    }
    Markers.prototype.showPopup = function(popup) {

        if (!this.currShowPopup) {
            this.currShowPopup = popup;
            this.currShowPopup.show();
            this.currShowPopup.positions();
            return;
        }

        if (this.currShowPopup && this.currShowPopup != popup) {
            this.hidePopup(this.currShowPopup);
            this.currShowPopup = popup;
            this.currShowPopup.show();
            this.currShowPopup.positions();
        }
    }
    Markers.prototype.hidePopup = function(popup) {

        if (this.currShowPopup && this.currShowPopup == popup) {
            this.currShowPopup.hide();
            this.currShowPopup = null;
        }
    }
    /**/

    /*marker*/
    var Marker = function(markers, id, x, y, visibleScale, url, $m) {
        this.markers = markers;
        this.id = id;
        this.x = x;
        this.y = y;
        this.visibleScale = visibleScale;
        this.url = url;
        this.$m = $m;
        this.visible = false;
        this.popup = null;
        this.popupClose = null;
    }
    Marker.prototype.ini = function() {
        this.style();
        this.positions(1);

        if (this.url) {
            this.addInteractivityUrl();
        }

        if (this.popup) {
            this.popupClose = this.popup.addClose();
            this.addPopupAction();
        } else {
            if (this.markers.popupShowAction == 'rollover')
                this.addPopupActionNull();
        }
    }
    Marker.prototype.destroy = function() {
        this.getInn().unbind('.lhpGIV');

        if (this.popup) {
            this.popupClose.unbind('.lhpGIV');
            this.popupClose = null;
            this.popup = null;
        }

        this.$m = null;
        this.markers = null;
    }
    Marker.prototype.getInn = function() {
        return this.$m.find('.' + this.markers.mInnClass);
    }
    Marker.prototype.getSize = function() {
        return {'w': this.getInn().width(), 'h': this.getInn().height()};
    }
    Marker.prototype.getEdges = function() {
        return this.findEdges();
    }
    Marker.prototype.findEdges = function() {
        var mInnOff = this.getInn().offset(),
                mainHolOff = this.markers.$mainHolder.offset(),
                contentHolPos = this.markers.$contentHol.position(),
                contentL = contentHolPos.left,
                contentT = contentHolPos.top,
                mSize = this.getSize(),
                l = mInnOff.left - contentL - mainHolOff.left,
                r = l + mSize.w,
                t = mInnOff.top - contentT - mainHolOff.top,
                b = t + mSize.h;

        return({'L': l, 'R': r, 'T': t, 'B': b});
    }
    Marker.prototype.getLimit = function() {
        return this.markers.getLimit();
    }
    Marker.prototype.style = function() {
        var css = {'position': 'absolute',
            'z-index': '2',
            'display': 'none'};

        this.$m.css(css);
        this.$m.css('height', this.$m.height());
    }
    Marker.prototype.positions = function(inScale) {
        var x = Math.round(this.x * inScale),
                y = Math.round(this.y * inScale);

        this.$m.css({'left': x, 'top': y});
    }
    Marker.prototype.visibility = function(inScale) {
        if (inScale >= this.visibleScale) {
            if (!this.visible)
                this.$m.stop(true, true).fadeIn(200);
            this.visible = true;
        } else {
            if (this.visible)
                this.$m.fadeOut(300);
            this.visible = false;
            this.markers.hidePopup(this.popup);
        }
    }
    Marker.prototype.addInteractivityUrl = function() {
        this.getInn().css('cursor', 'pointer');
        this.getInn().bind(((this.markers.isTouchDev) ? 'touchend.lhpGIV' : 'mousedown.lhpGIV'), {'_this': this}, this.clickHandlerUrl);
    }
    Marker.prototype.clickHandlerUrl = function(e) {
        var _this = e.data._this

        if (_this.url) {
            window.location = _this.url;
        }

        e.stopPropagation();
    }
    Marker.prototype.addPopupAction = function() {

        if (this.markers.popupShowAction == 'click') {
            this.getInn().bind(((this.markers.isTouchDev) ? 'touchend.lhpGIV' : 'mousedown.lhpGIV'), {'_this': this}, this.showPopup);
            this.getInn().css('cursor', 'pointer');
        } else {
            this.getInn().bind(((this.markers.isTouchDev) ? 'touchend.lhpGIV' : 'mouseenter.lhpGIV'), {'_this': this}, this.showPopup);
        }

        this.popupClose.bind(((this.markers.isTouchDev) ? 'touchend.lhpGIV' : 'mousedown.lhpGIV'), {'_this': this}, this.hidePopup);

    }
    Marker.prototype.addPopupActionNull = function() {
        this.getInn().bind(((this.markers.isTouchDev) ? 'touchend.lhpGIV' : 'mouseenter.lhpGIV'), {'_this': this}, this.showPopup);
    }
    Marker.prototype.showPopup = function(e) {
        var _this = e.data._this;

        _this.markers.showPopup(_this.popup);
        e.stopPropagation();
    }
    Marker.prototype.hidePopup = function(e) {
        var _this = e.data._this;

        _this.markers.hidePopup(_this.popup);
        e.stopPropagation();
    }
    /**/

    /*popup*/
    var Popup = function(id, $p, marker) {
        this.id = id;
        this.$p = $p;
        this.marker = marker;
        this.posHor = this.posHC;
        this.posVer = this.posVT;
        this.$closeHolder = null;
    }
    Popup.prototype.ini = function() {

        /*positioning type*/
        if (this.$p.hasClass('pos-TL')) {
            this.posHor = this.posHL;
            this.posVer = this.posVT;
        } else if (this.$p.hasClass('pos-T')) {
            this.posHor = this.posHC;
            this.posVer = this.posVT;
        } else if (this.$p.hasClass('pos-TR')) {
            this.posHor = this.posHR;
            this.posVer = this.posVT;
        } else if (this.$p.hasClass('pos-L')) {
            this.posHor = this.posHL;
            this.posVer = this.posVC;
        } else if (this.$p.hasClass('pos-R')) {
            this.posHor = this.posHR;
            this.posVer = this.posVC;
        } else if (this.$p.hasClass('pos-BL')) {
            this.posHor = this.posHL;
            this.posVer = this.posVB;
        } else if (this.$p.hasClass('pos-B')) {
            this.posHor = this.posHC;
            this.posVer = this.posVB;
        } else if (this.$p.hasClass('pos-BR')) {
            this.posHor = this.posHR;
            this.posVer = this.posVB;
        }
        /**/

        this.$p.bind('mousedown.lhpGIV touchmove.lhpMIV touchend.lhpMIV mouseenter.lhpMIV mouseleave.lhpMIV mousewheel.lhpMIV', function(e) {
            e.stopPropagation();
            return false;
        });

        this.style();
        this.positions(1);
    }
    Popup.prototype.destroy = function() {
        this.$p = null;
        this.marker = null;
    }
    Popup.prototype.style = function() {
        var css = {'display': 'none',
            'position': 'absolute',
            'z-index': '3'};

        this.$p.css(css);
        this.$p.css('height', this.$p.height());
    }
    Popup.prototype.addClose = function() {
        this.$closeHolder = $('<div class="lhp_giv_popup_close"></div>');

        this.$closeHolder.hover(
                function() {
                    $(this).css('opacity', .7);
                },
                function() {
                    $(this).css('opacity', 1);
                }
        );

        this.$p.append(this.$closeHolder);
        return this.$closeHolder;
    }
    Popup.prototype.getSize = function() {
        return {'w': this.$p.width(), 'h': this.$p.height()};
    }
    Popup.prototype.positions = function() {
        var mEdges = this.marker.getEdges(),
                x = this.posHor(mEdges),
                y = this.posVer(mEdges),
                limit = this.marker.getLimit(),
                w = this.$p.width(),
                h = this.$p.height();

        /*X*/
        if (x < limit.xMin) {
            x = limit.xMin;
        } else if (x + w > limit.xMax) {
            x = limit.xMax - w;
        }

        /*Y*/
        if (y < limit.yMin) {
            y = limit.yMin;
        } else if (y + h > limit.yMax) {
            y = limit.yMax - h;
        }

        this.$p.css({'left': x, 'top': y});
    }
    Popup.prototype.posVT = function(mEdges) {
        return Math.round(mEdges.T) - this.$p.height();
    }
    Popup.prototype.posVC = function(mEdges) {
        return Math.round(mEdges.T + (mEdges.B - mEdges.T) / 2) - this.$p.height() / 2;
    }
    Popup.prototype.posVB = function(mEdges) {
        return Math.round(mEdges.B);
    }
    Popup.prototype.posHL = function(mEdges) {
        return Math.round(mEdges.L) - this.$p.width();
    }
    Popup.prototype.posHC = function(mEdges) {
        return Math.round(mEdges.L + (mEdges.R - mEdges.L) / 2) - this.$p.width() / 2;
    }
    Popup.prototype.posHR = function(mEdges) {
        return Math.round(mEdges.R);
    }
    Popup.prototype.show = function() {
        this.$p.fadeIn(300);
    }
    Popup.prototype.hide = function() {
        this.$p.stop().clearQueue().fadeOut(100);
    }
    /**/

})(jQuery);