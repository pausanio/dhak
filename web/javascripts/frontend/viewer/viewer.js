/*
 /*
 * dokument Viewer
 *
 * @author Ivo Bathke <ivo.bathke@gmail.com>
 */

(function ($) {

    State = Backbone.Model.extend({
        defaults: {
            sidebar: {
                open: false,
                tab: 'signature',
                collapsable: []
            },
            toolbar: {
                extendToolbar: false,
                collapseToolbar: false,
                hideMap: false,
                lock: false
            },
            manipulations: {
                contrast: 0,
                brightness: false,
                saturation: false,
                invert: false,
                grid: false,
                rotate: 0
            },
            bookmarks: {
                signature: null,
                image: null
            }
        },
        url: "/viewer/save",
        resetManipulations: function () {
            this.attributes.manipulations = this.defaults.manipulations;
        }
    });

    var state = new State;

    window.Viewer = Backbone.View.extend({
        prependNav: '',
        appendNav: '',
        orgImage: '',
        orgImageWidth: '',
        orgImageHeight: '',
        el: function () {
            return $("#viewer_container")
        },
        model: state,
        options: {'startScale': 0,
            'startX': 0,
            'startY': 0,
            'imgDir': '',
            'viewportWidth': '96%',
            'viewportHeight': '100%',
            'fitToViewportShortSide': false,
            'contentSizeOver100': true,
            'mainImgWidth': 6000,
            'mainImgHeight': 6000,
            'testMode': false,
            'intNavPos': 'BL',
            'intNavAutoHide': false,
            'intNavMoveDownBtt': false,
            'intNavMoveUpBtt': false,
            'intNavMoveRightBtt': false,
            'intNavMoveLeftBtt': false,
            'paging': null,
            'sidebar': false,
            'hideMap': false,
            'lock': false,
            'extendToolbar': false,
            'collapseToolbar': false,
            'manipulations': false,
            'prependNavCustom': {"grabber": '<li class="tiptool icon-grabber" title="Werkzeugleiste verschieben"></li>',
                "collapsable": '<li class="collapsable tiptool" title="Werkzeuge ein- oder ausklappen"><i class="icon-minus"></i></li>',
                "hidemap": '<li class="tiptool hidemap" title="MiniMap anzeigen oder verstecken"><i class="icon-minimap"/></li>'},
            'appendNavCustom': {"invert": '<li class="tiptool un extend invert" title="Invertieren"><i class="icon-invert"></i></li>',
                "contrast": '<li class="un extend contrast"> <input type="text" class="span2" value="0" data-slider-min="-100" data-slider-max="100" data-slider-step="1" data-slider-value="0" data-slider-orientation="vertical" data-slider-selection="before" data-slider-tooltip="show"><i class="icon-adjust tiptool" title="Kontrast"></i></li>',
                "brightness": '<li class="un extend brightness"> <input type="text" class="span2" value="0" data-slider-min="-100" data-slider-max="100" data-slider-step="1" data-slider-value="100" data-slider-orientation="vertical" data-slider-selection="after" data-slider-tooltip="show"><i class="icon-asterisk tiptool" title="Helligkeit"></i></li>',
                "saturation": '<li class="un extend saturation"> <input type="text" class="span2" value="0" data-slider-min="-100" data-slider-max="100" data-slider-step="1" data-slider-value="100" data-slider-orientation="vertical" data-slider-selection="after" data-slider-tooltip="show"><i class="icon-tint tiptool" title="Sättigung"></i></li>',
                "rotate": '<li class="un extend rotate"><div class="angle-container"><div class="log">0 &deg;</div><div id="anglepicker"></div><div>Drehen</div></div><i class="icon-repeat tiptool" title="Drehen"></i></li>',
                "grid": '<li class="un extend grid"><i class="icon-th tiptool" title="Hilfslinien"></i></li>',
                "lock": '<li class="tiptool un extend lock" title="Bei geschlossenem Schloß werden alle getätigten Einstellungen automatisch auf die gesamte Signatur angewendet."><i class="icon-unlock"/></li>',
                "reset": '<li class="tiptool un extend reset" title="Zurücksetzen"><i class="icon-retweet" /></li>',
                "openclose": '<li class="tiptool openclose" title="erweiterte Werkzeuge ein- oder ausklappen"><i class="icon-resize-full"></i></li>'}

        },
        initialize: function (options) {
            this.viewer = this.$('#viewer');
            this.viewerSidebar = $('#viewer-sidebar');
            this.viewerViewer = $('#viewer_viewer');

            //set data
            this.model.set({image: this.viewer.data('viewer-image'),
                saveMode: 've',
                sig: this.viewer.data('viewer-bestandsignatur'),
                cookiename: 'dfgViewer2_ui_' + this.viewer.data('viewer-signatur'),
                veid: this.viewer.data('viewer-veid'),
                dokumentid: this.viewer.data('viewer-dokumentid'),
                usergenerated: this.viewer.data('viewer-usergenerated'),
                user: this.viewer.data('viewer-user')});

            //set toolbar from options aka saved settings
            this.model.set({toolbar: {
                extendToolbar: (options.extendToolbar != undefined) ? options.extendToolbar : this.options.extendToolbar,
                collapseToolbar: (options.collapseToolbar != undefined) ? options.collapseToolbar : this.options.collapseToolbar,
                hideMap: (options.hideMap != undefined) ? options.hideMap : this.options.hideMap,
                lock: (options.lock != undefined) ? options.lock : this.options.lock
            }});
            //todo set sidebar from options

            this.model.set({bookmarks: {
                signature: (options.bookmarks.signature != undefined) ? options.bookmarks.signature : null,
                image: (options.bookmarks.image != undefined) ? options.bookmarks.image : null
            }});

            //actions
            this.model.on('change:manipulations', this.manipulate, this);
            this.model.on('change', this.store, this);

            //TODO flag if dokument settings or VE settings

            //apply session settings from cookie, cookie value will override usersettings because they are newer
            //cookie will only exist if lock is true
            $.cookie.json = true;
            var cookie = $.cookie(this.model.get('cookiename'));
            if (cookie != undefined) {
                this.model.set('sidebar', cookie.sidebar);
                if (cookie.toolbar) {
                    this.model.set('toolbar', cookie.toolbar);
                }
                if (cookie.manipulations) {
                    this.model.set('manipulations', cookie.manipulations);
                }
            }
            else {
                this.store();
            }

            this.orgImage = this.options.previewImgFilename;
            this.orgImageWidth = this.options.mainImgWidth;
            this.orgImageHeight = this.options.mainImgHeight;

            this._createPaging(options);

            //set lock, if lock is false: reset
            if (this.model.get('toolbar').lock === false) {
                manipulated = false;
                this.model.resetManipulations();
            }
            else {
                this.options.appendNavCustom.lock = '<li class="tiptool un extend unlock" title="Bei geschlossenem Schloß werden alle getätigten Einstellungen automatisch auf die gesamte Signatur angewendet."><i class="icon-lock"/></li>';
                //apply image manipulations from user settings
                if (options.manipulations) {
                    //check if there are manipulations to prevent ajax call, if yes call ajax & set image
                    var manipulated = true;
                    //images on serverside should be cached on user level
                    //s_sessid_name.jpg für guests
                    //u_md5(params)_name.jpg - if params hash not same: invalidate
                }
            }
            //if manipulations come from saved image settings they also need to be applied
            //todo unfuddle this!
            if (this.model.get('bookmarks').image != null) {
                manipulated = true;
            }

            if (manipulated) {
                manipulated = false;
                _.each(options.manipulations, function (val, key) {
                    if (_.isBoolean(val) && val == true) {
                        manipulated = true;
                    }
                    if (typeof val === 'number' && val != 0) {
                        manipulated = true;
                    }
                });
                if (manipulated) {
                    this.model.set({manipulations: options.manipulations});
                    this.manipulate();
                }
            }

            $.extend(this.options, options);
            $(window).on("resize", _.bind(this.resize, this));
        },
        render: function () {
            //start viewer
            var self = this;
            //init toolbar state
            var toolbar = this.model.get('toolbar');
            this.options.extendToolbar = toolbar.extendToolbar;
            this.options.hideMap = toolbar.hideMap;
            this.options.collapseToolbar = toolbar.collapseToolbar;
            this.viewer.lhpGigaImgViewer(this.options);
            this.resize();
            this.viewer.on("mouseover", '.lhp_giv_nav', function () {
                if (!$(this).data("init")) {
                    $(this).data("init", true);
                    $(this).draggable();
                    $('.lhp_giv_nav .tiptool').tooltip();
                }
            });
            this.SignatureCommentsView = new CommentsView({model: new Comments({"user": this.model.get('user'), "veid": this.model.get('veid')}),
                el: $("#signature_comments")
            });
            this.ImageCommentsView = new CommentsView({model: new Comments({"user": this.model.get('user'), "dokumentid": this.model.get('dokumentid')}),
                el: $("#image_comments")
            });

            if (this.model.get('sidebar').open === true) {
                this.openSidebar();
                $('#viewer-sidebar-tabs .tab_' + this.model.get('sidebar').tab + ' a').tab('show');
                var tab = this.model.get('sidebar').tab;
                $('#viewer-sidebar').removeClass(function (index, css) {
                    return (css.match(/\btab_\S+/g) || []).join(' ');
                }).addClass('tab_' + tab);
            }
            if (this.model.get('sidebar').collapsable.length > 0) {
                var ar = this.model.get('sidebar').collapsable;
                $(ar).each(function (index) {
                    $('#' + this).addClass('in'); //.collapse('show') doesnt work over multiple accordions ??
                });
            }

            $('#accordion_signature .collapse').on('show',function () {
                $(this).parent().find(".icon-chevron-right").removeClass("icon-chevron-right").addClass("icon-chevron-down");
            }).on('hide', function () {
                    $(this).parent().find(".icon-chevron-down").removeClass("icon-chevron-down").addClass("icon-chevron-right");
                });
            $('#accordion_image .collapse').on('show',function () {
                $(this).parent().find(".icon-chevron-right").removeClass("icon-chevron-right").addClass("icon-chevron-down");
            }).on('hide', function () {
                    $(this).parent().find(".icon-chevron-down").removeClass("icon-chevron-down").addClass("icon-chevron-right");
                });
        },
        events: {
            "click #viewer-sidebar .close-sidebar": "closeSidebar",
            "click #viewer_viewer .toggle_signature": "tabSignature",
            "click #viewer_viewer .toggle_image": "tabImage",
            "shown #viewer-sidebar .collapse": "setCollapseId",
            "hide #viewer-sidebar .collapse": "unsetCollapseId",
            "click #viewer-sidebar-tabs a": "toggleTab",
            "click .lhp_giv_nav ul li.hidemap": "hideMap",
            "click .lhp_giv_nav ul li.collapsable": "collapseToolbar",
            "click .lhp_giv_nav ul li.invert": "invert",
            "click .lhp_giv_nav ul li.grid": "grid",
            "click .lhp_giv_nav ul li.contrast": "contrastSlider",
            "click .lhp_giv_nav ul li.brightness": "brightnessSlider",
            "click .lhp_giv_nav ul li.saturation": "saturationSlider",
            "click .lhp_giv_nav ul li.brightness.active": "hideSlider",
            "click .lhp_giv_nav ul li.contrast.active": "hideSlider",
            "click .lhp_giv_nav ul li.saturation.active": "hideSlider",
            "click .lhp_giv_nav ul li.rotate": "rotate",
            "click .lhp_giv_nav ul li.reset": "reset",
            "click .lhp_giv_nav ul li.lock": "lock",
            "click .lhp_giv_nav ul li.unlock": "unlock",
            "click .lhp_giv_nav ul li.openclose": "extendToolbar",
            "keypress #viewer-paging-current": "page",
            "click #viewer-sidebar-tabs li.tab_signature .saveAsBookmark": "saveSignature",
            "click #viewer-sidebar-tabs li.tab_image .saveAsBookmark": "saveImage",
            "click #viewer-sidebar-tabs li.tab_signature .removeFromBookmark": "removeSignature",
            "click #viewer-sidebar-tabs li.tab_image .removeFromBookmark": "removeImage",
            "click #confirmModalVE button.btn-primary": "fireRemoveSignature",
            "click #confirmModalDokument button.btn-primary": "fireRemoveImage"
        },
        lock: function () {
            this._setToolbarState('lock', true);
            $('.lhp_giv_nav ul li.lock i').removeClass('icon-unlock').addClass('icon-lock');
            $('.lhp_giv_nav ul li.lock').removeClass('lock').addClass('unlock');
        },
        unlock: function () {
            $('.lhp_giv_nav ul li.unlock i').removeClass('icon-lock').addClass('icon-unlock');
            $('.lhp_giv_nav ul li.unlock').removeClass('unlock').addClass('lock');
            var ch = $.removeCookie(this.model.get('cookiename'), { path: '/' });
            this._setToolbarState('lock', false);
        },
        saveImage: function (e) {
            e.stopImmediatePropagation();//dont open sidebar
            if (this.model.get('user') != '') {
                this._setBookmark('image', this.model.get('dokumentid'));
                this.model.set('saveMode', 'dok');

                var self = this,
                    text = 'Einstellungen wurden gespeichert';
                var customText = $('#saveImageText').data('saved-text');//todo texte sollten auch direkt als object geladen werden
                if (customText) {
                    text = customText;
                }
                var successCb = function (model, response) {
                        self.$el.flash(text, {type: 'success'});
                        self._setBookmark('image', response.id);
                        $('.tab_image .saveAsBookmark').addClass('hide');
                        $('.tab_image .removeFromBookmark').removeClass('hide');
                    },
                    failCb = function (model, response) {
                        self.$el.flash('Es ist ein Fehler beim Speichern der Einstellungen aufgetreten', {type: 'error'});
                    };
                this.model.save(null, {
                    success: successCb,
                    error: failCb
                });
            }
            else {
                alert('Um die Einstellungen zu speichern, müssen Sie angemeldet sein.');
            }
        },
        saveSignature: function (e) {
            e.stopImmediatePropagation();//dont open sidebar
            if (this.model.get('user') != '') {
                this.model.set('saveMode', 've');
                var self = this,
                    text = 'Einstellungen wurden gespeichert';
                var customText = $('#saveSignatureText').data('saved-text');//todo texte sollten auch direkt als object geladen werden
                if (customText) {
                    text = customText;
                }
                var successCb = function (model, response) {
                        self._setBookmark('signature', response.id);
                        self.$el.flash(text, {type: 'success'});
                        $('.tab_signature .saveAsBookmark').addClass('hide');
                        $('.tab_signature .removeFromBookmark').removeClass('hide');
                    },
                    failCb = function (model, response) {
                        self.$el.flash('Es ist ein Fehler beim Speichern der Einstellungen aufgetreten', {type: 'error'});
                    };
                this.model.save(null, {
                    success: successCb,
                    error: failCb
                });
            }
            else {
                alert('Um die Einstellungen zu speichern, müssen Sie angemeldet sein.');
            }
        },
        removeSignature: function (e) {
            e.stopImmediatePropagation();//dont open sidebar
            if (this.model.get('user') != '') {
                $('#confirmModalVE').modal();
            }
            else {
                alert('Um die Einstellungen zu speichern, müssen Sie angemeldet sein.');
            }
        },
        fireRemoveSignature: function () {
            var text = $('#removeSignatureText').data('removed-text'),
                modalDialog = $('#confirmModalVE'),
                title = 'Signatur',
                self = this
            $.ajax({
                type: "DELETE",
                dataType: 'json',
                url: '/mein-archiv/delete',
                data: JSON.stringify({ id: self.model.get('bookmarks').signature, type: 've' })
            }).done(function () {
                    self._setBookmark('signature', null);
                    self.$el.flash(title + ' wurde aus "Mein Archiv" gelöscht', {type: 'success'});
                    $('.tab_signature .removeFromBookmark').addClass('hide');
                    $('.tab_signature .saveAsBookmark').removeClass('hide');
                }).fail(function () {
                    self.$el.flash('Es ist ein Fehler beim Löschen aufgetreten', {type: 'error'});
                }).always(function () {
                    modalDialog.modal('hide');
                })
        },
        removeImage: function (e) {
            e.stopImmediatePropagation();//dont open sidebar
            if (this.model.get('user') != '') {
                $('#confirmModalDokument').modal();
            }
            else {
                alert('Um die Einstellungen zu speichern, müssen Sie angemeldet sein.');
            }
        },
        fireRemoveImage: function () {
            var text = $('#removeImageText').data('removed-text'),
                modalDialog = $('#confirmModalDokument'),
                title = 'Bildansicht',
                self = this;
            $.ajax({
                type: "DELETE",
                dataType: 'json',
                url: '/mein-archiv/delete',
                data: JSON.stringify({ id: self.model.get('bookmarks').image, type: 'dok' })
            }).done(function () {
                    self.$el.flash(title + ' wurde aus "Mein Archiv" gelöscht', {type: 'success'});
                    $('.tab_image .removeFromBookmark').addClass('hide');
                    $('.tab_image .saveAsBookmark').removeClass('hide');
                }).fail(function () {
                    self.$el.flash('Es ist ein Fehler beim Löschen aufgetreten', {type: 'error'});
                }).always(function () {
                    modalDialog.modal('hide');
                });
        },
        store: function () {
            var data = {};
            data.sidebar = this.model.get('sidebar');
            $.cookie.json = true;
            //TODO if signature or image are saved then we need to update the db as well?
            if (this.model.get('toolbar').lock) {
                data.manipulations = this.model.get('manipulations');
                data.toolbar = this.model.get('toolbar');
            }
            //TODO cookie locale paths dynamic
            $.cookie(this.model.get('cookiename'), data, {expires: 7, path: '/'});
        },
        manipulate: function () {
            var self = this,
                params = this._makeParams();
            if (params) {
                $('#viewer_viewer').block({message: 'lade Bild...'});
                $.ajax({
                    dataType: "json",
                    url: "/viewer/image/" + this.model.get("sig") + "/" + this.model.get("image") + "/" + this.model.get("usergenerated") + "?" + params,
                    error: function () {
                        console.log('Ein Fehler ist aufgetreten');
                        self.options.previewImgFilename = self.orgImage;
                        self.render();
                        $('#viewer_viewer').unblock();
                    },
                    success: function (val, status) {
                        self.viewer.lhpGigaImgViewer('destroy');
                        if (status != 'notmodified') {
                            self.options.previewImgFilename = val.path + '?_=' + new Date().getTime();
                            if (val.width) {
                                self.options.mainImgWidth = val.width;
                                self.options.mainImgHeight = val.height;
                            }
                            else {
                                self.options.mainImgWidth = self.orgImageWidth;
                                self.options.mainImgHeight = self.orgImageHeight;
                            }
                        }
                        else {
                            self.options.previewImgFilename = self.orgImage;
                            self.options.mainImgWidth = self.orgImageWidth;
                            self.options.mainImgHeight = self.orgImageHeight;
                        }
                        self.render();
                        $('#viewer_viewer').unblock();
                    }
                });
            }
        },
        resize: function () {
            var viewportHeight = $(window).height(),
                heightHeader = $('#miniheader_container').height(),
                heightNavbar = $('#viewer_container .navbar').height(),
                remove = 23;//TODO this needs to be dynamic
            this.viewer.height(viewportHeight - heightHeader - heightNavbar - remove);
            this.viewerSidebar.height(viewportHeight - heightHeader - heightNavbar - remove);
        },
        toggleTab: function (e) {
            e.preventDefault();
            $(e.target).tab('show');
            var el = $(e.target);
            if (el.prop("tagName") == 'A') {
                var href = $(e.target).attr('href').substr(1);
            }
            else {
                var href = $(e.target).parent().attr('href').substr(1);
            }
//            $('a.toggle_sidebar').removeClass(function (index, css) {
//                return (css.match(/\btab_\S+/g) || []).join(' ');
//            }).addClass('tab_' + $(e.target).attr('href').substr(1));
            $('#viewer-sidebar').removeClass(function (index, css) {
                return (css.match(/\btab_\S+/g) || []).join(' ');
            }).addClass('tab_' + href);
        },
        closeSidebar: function () {
            var self = this;
            this.viewerSidebar.fadeOut(40, function () {
                self.viewerSidebar.removeClass('span4');
                self.viewerViewer.removeClass('span8').addClass('span12');
                self._setSidebarState('open', false);
            });
        },
        openSidebar: function () {
            var self = this;
            this.viewerSidebar.fadeIn(40, function () {
                self.viewerViewer.removeClass('span12').addClass('span8');
                self._setSidebarState('open', true);
            });
        },
        setCollapseId: function (e) {
            var a = this.model.get('sidebar').collapsable;
            if (_.indexOf(a, e.target.id) == -1) {
                var newdata = this.model.get('sidebar').collapsable.concat(e.target.id);
                this._setSidebarState('collapsable', newdata)
            }
        },
        unsetCollapseId: function (e) {
            var i = _.indexOf(this.model.get('sidebar').collapsable, e.target.id);
            if (i != -1) {
                var newdata = _.clone(this.model.get('sidebar').collapsable);
                newdata.splice(i, 1);
                this._setSidebarState('collapsable', newdata);
            }
        },
        tabSignature: function () {
            this._setSidebarState('tab', 'signature');
//            $('#viewer-sidebar-tabs a:first').tab('show');
            this.openSidebar();
        },
        tabImage: function () {
            this._setSidebarState('tab', 'image');
            this.openSidebar();
        },
        hideMap: function () {
            var self = this;
            $('.lhp_giv_nav ul li.hidemap i').toggleClass("icon-minimap icon-nominimap");
            //hack
            if ($('.lhp_giv_map').css('visibility') == 'hidden') {
                $('.lhp_giv_map').css('visibility', '');
            }
            else {
                $('.lhp_giv_map').toggle("slow", function () {
                    if ($('.lhp_giv_nav ul li.hidemap i').hasClass('icon-nominimap')) {
                        self._setToolbarState('hideMap', true);
                    }
                    else {
                        self._setToolbarState('hideMap', false);
                    }
                });
            }
        },
        collapseToolbar: function () {
            var self = this;
            $('.lhp_giv_nav ul li.collapsable i').toggleClass("icon-plus icon-minus", function () {
                if ($('.lhp_giv_nav ul li.collapsable i').hasClass('icon-plus')) {
                    self._setToolbarState('collapseToolbar', true);
                    var title = 'ausklappen';
                }
                else {
                    self._setToolbarState('collapseToolbar', false);
                    var title = 'einklappen';
                }
                $('.lhp_giv_nav ul li.collapsable').attr('title', 'Werkzeuge ' + title);
            });
            $('.lhp_giv_nav ul li:not(.collapsable, .save, .icon-grabber, .un.extend)').toggle("slow");
        },
        extendToolbar: function () {
            var self = this;
            $('.lhp_giv_nav ul li.openclose i').toggleClass("icon-resize-small icon-resize-full", function () {
                var title = 'ausklappen';
                if ($('.lhp_giv_nav ul li.openclose i').hasClass('icon-resize-small')) {
                    title = 'einklappen';
                    //we need both here because of viewer reload
                    self.options.extendToolbar = true;
                    self._setToolbarState('extendToolbar', true);
                }
                else {
                    self._setToolbarState('extendToolbar', false);
                    self.options.extendToolbar = false;
                }
                $('.lhp_giv_nav ul li.openclose').attr('title', 'erweiterte Werkzeuge ' + title);
            });

            $('.lhp_giv_nav ul li.extend').toggle("slow", function () {
                $(this).toggleClass('un');
            });
        },
        page: function (e) {
            if (e.keyCode != 13)
                return;
            var page = $('#viewer-paging-current').val(),
                maxPage = 0;
            if (this.options.paging.total) {
                maxPage = this.options.paging.total;
            }
            if (isNaN(page) || page > maxPage) {
                alert('Seitenzahl nicht bekannt');
                return;
            }
            else {
                //redirect
                var i = page - 1;
                if (this.options.paging.routes[i]) {
                    window.location.href = this.options.paging.routes[i];
                }
            }
        },
        grid: function () {
            var self = this,
                newGrid = true,
                grid = this.model.get('manipulations').grid;
            if (grid) {
                newGrid = false;
            }
            self._setManipulations('grid', newGrid);
        },
        invert: function () {
            var self = this,
                newInvert = true,
                invert = this.model.get('manipulations').invert;
            if (invert) {
                newInvert = false;
            }
            self._setManipulations('invert', newInvert);
        },
        hideSlider: function (e) {
            var input = $(e.currentTarget).find('input'),
                button = $(e.currentTarget),
                icon = $(e.currentTarget).find('i');
            $(e.currentTarget).find('.slider').remove();
            button.append(input).removeClass('active');
            icon.css({position: "relative", top: ""});
        },
        brightnessSlider: function () {
            this._hideSliders();
            var self = this,
                button = $("li.extend.brightness"),
                icon = $("li.extend.brightness i");
            var brightness = $('.lhp_giv_nav li.brightness input').slider({
                reversed: true
            }).on('slideStop',function () {
                    self._setManipulations('brightness', brightness.getValue());
                }).data('slider');
            brightness.setValue((this.model.get('manipulations').brightness === false ? 0 : this.model.get('manipulations').brightness));
            button.addClass('active');
            var slider = $("li.extend.brightness.active .slider");
            slider.css({position: "relative", left: "-2px", top: "-200px"});
            icon.css({position: "absolute", top: "5px"});
            slider.show();
        },
        contrastSlider: function () {
            this._hideSliders();
            var self = this,
                button = $("li.extend.contrast"),
                icon = $("li.extend.contrast i");
            var contrast = $('.lhp_giv_nav li.contrast input').slider({
                reversed: true
            }).on('slideStop',function () {
                    self._setManipulations('contrast', contrast.getValue());
                }).data('slider');
            contrast.setValue(this.model.get('manipulations').contrast);
            button.addClass('active');
            var slider = $("li.extend.contrast.active .slider");
            slider.css({position: "relative", left: "-2px", top: "-200px"});
            slider.show();
            icon.css({position: "absolute", top: "5px"});
        },
        saturationSlider: function () {
            this._hideSliders();
            var self = this,
                button = $("li.extend.saturation"),
                icon = $("li.extend.saturation i");
            var saturation = $('.lhp_giv_nav li.saturation input').slider({
                reversed: true
            }).on('slideStop',function () {
                    self._setManipulations('saturation', saturation.getValue());

                }).data('slider');
            saturation.setValue((this.model.get('manipulations').saturation === false ? 0 : this.model.get('manipulations').saturation));
            button.addClass('active');
            var slider = $("li.extend.saturation.active .slider");
            slider.css({position: "relative", left: "-2px", top: "-200px"});
            slider.show();
            icon.css({position: "absolute", top: "5px"});
        },
        rotate: function () {
            var self = this,
                degree = false;
            if ($("#anglepicker").hasClass('ui-anglepicker')) {
                $("#anglepicker").anglepicker('destroy');
                $(".angle-container").hide();
                $(".rotate i").show();
            }
            else {
                $(".angle-container").show();
                $(".angle-container").css({position: "relative", left: "6px", top: "-79px"});
                $(".rotate i").hide();
                self._printDegrees($(".log"), this.model.get('manipulations').rotate);
                $("#anglepicker").anglepicker({
                    value: this.model.get('manipulations').rotate,
                    clockwise: false,
                    change: function (e, ui) {
                        self._printDegrees($(".log"), ui.value);
                    },
                    start: function (e, ui) {
                    },
                    stop: function (e, ui) {
                        degree = ui.value;
                        self._printDegrees($(".log"), degree);
                        $(".angle-container").hide();
                        $(".rotate i").show();
                        self._setManipulations('rotate', degree);
                    }
                });
            }
        },
        reset: function () {
            this.model.resetManipulations();
            this.viewer.lhpGigaImgViewer('destroy');
            this.options.previewImgFilename = this.orgImage;
            this.render()
        },
        remove: function () {
            $(window).off("resize", this.resize);
            Backbone.View.prototype.remove.apply(this, arguments);
        },
        _hideSliders: function () {
            $('.slider').each(function (index) {
                var input = $(this).parent().find('input'),
                    button = $(this).parent(),
                    icon = $(this).parent().find('i');
                $(this).remove();
                button.append(input).removeClass('active');
                icon.css({position: "relative", top: ""});
            });
        },
        //todo abstract deep setters or use https://github.com/afeld/backbone-nested
        _setManipulations: function (key, val) {
            var man = _.omit(this.model.get('manipulations'));
            if (man[key] !== undefined) {
                man[key] = val;
                this.model.set({manipulations: man});
            }
        },
        _setBookmark: function (key, val) {
            var bookmarks = _.omit(this.model.get('bookmarks'));
            if (bookmarks[key] !== undefined) {
                bookmarks[key] = val;
                this.model.set({bookmarks: bookmarks});
            }
        },
        _setToolbarState: function (key, val) {
            var toolbar = _.omit(this.model.get('toolbar'));
            if (toolbar[key] !== undefined) {
                toolbar[key] = val;
                this.model.set({toolbar: toolbar});
            }
        },
        _setSidebarState: function (key, val) {
            var sidebar = _.omit(this.model.get('sidebar'));
            if (sidebar[key] !== undefined) {
                sidebar[key] = val;
                this.model.set({sidebar: sidebar});
            }
        },
        _printDegrees: function (log, deg) {
            log.html(deg + '&deg;');
        },
        _hideOverlay: function () {
            $('#viewer_container .overlay, #viewer_container .overlayimg').hide();
        },
        _makeParams: function () {
            var manipulations = this.model.get('manipulations');
            if (manipulations) {
                var params = ['rotate=' + manipulations.rotate,
                    'invert=' + manipulations.invert,
                    'grid=' + manipulations.grid,
                    'contrast=' + manipulations.contrast,
                    'brightness=' + manipulations.brightness,
                    'saturation=' + manipulations.saturation];
                return params.join('&');
            }
            return false;
        },
        _createPaging: function (options) {
            //apply paging
            if (options.paging) {
                var backward = '<li class="viewer-tostart">&nbsp;</li>',
                    forward = '';
                if (options.paging.current > 1) {
                    backward = '<li class="viewer-tostart"><a href="' + options.paging.routes[0] + '"><i class="icon-fast-backward tiptool" title="an den Anfang springen" /></a></li>';
                    backward += '<li class="viewer-paging-backward"><a href="' + options.paging.routes[options.paging.current - 2] + '"><i class="icon-step-backward tiptool" title="zurückblättern" /></a></li>';
                }
                if (options.paging.current < options.paging.total) {
                    forward = '<li class="viewer-paging-forward"><a href="' + options.paging.routes[options.paging.current] + '"><i class="icon-step-forward tiptool" title="weiterblättern" /></a></li>';
                    forward += '<li class="viewer-toend"><a href="' + options.paging.routes[options.paging.total - 1] + '"><i class="icon-fast-forward tiptool" title="zum Ende springen" /></a></li>';
                }
                var pagingHTML = backward +
                    '<li class="viewer-paging">&nbsp; <input type="text" id="viewer-paging-current" value="' + options.paging.current + '"><small> von ' + options.paging.total + '&nbsp;</small></li>' +
                    forward;
                this.options.appendNavCustom = $.extend({'paging': pagingHTML}, this.options.appendNavCustom);
            }
        }
    });

    var Comments = Backbone.Model.extend({
        comments: '',
        user: null,
        veid: null,
        dokumentid: null,
        url: "/viewer/savecomments"
    });
    var CommentsView = Backbone.View.extend({
        events: {
            "click button.submit": "save"
        },
        save: function () {
            var comments = this.$el.find('textarea.comments').val(),
                self = this;
            this.model.set({'comments': comments});
            if (this.model.get('user') != '') {
                this.model.save(null, {
                    success: function (model, response) {
                        self.$el.flash('Notizen wurden gespeichert', {type: 'success'});
                    },
                    error: function (model, response) {
                        self.$el.flash('Es ist ein Fehler beim Speichern aufgetreten', {type: 'error'});
                    }
                });
            }
            else {
                alert('Um zu speichern, müssen Sie angemeldet sein.');
            }
        }
    });

})(jQuery);
