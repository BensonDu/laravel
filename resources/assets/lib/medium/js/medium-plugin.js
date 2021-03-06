/**
 * Created by Benson on 16/7/12.
 */
this["MediumInsert"] = this["MediumInsert"] || {};
this["MediumInsert"]["Templates"] = this["MediumInsert"]["Templates"] || {};
this["MediumInsert"]["Templates"]["src/js/templates/core-buttons.hbs"] = Handlebars.template({
    "1": function(container, depth0, helpers, partials, data) {
        var stack1, helper, alias1 = depth0 != null ? depth0 : {},
            alias2 = helpers.helperMissing,
            alias3 = "function";
        return "            <li><a data-addon=\"" + container.escapeExpression(((helper = (helper = helpers.key || (data && data.key)) != null ? helper : alias2), (typeof helper === alias3 ? helper.call(alias1, {
                "name": "key",
                "hash": {},
                "data": data
            }) : helper))) + "\" data-action=\"add\" class=\"medium-insert-action\">" + ((stack1 = ((helper = (helper = helpers.label || (depth0 != null ? depth0.label : depth0)) != null ? helper : alias2), (typeof helper === alias3 ? helper.call(alias1, {
                "name": "label",
                "hash": {},
                "data": data
            }) : helper))) != null ? stack1 : "") + "</a></li>\n"
    },
    "compiler": [7, ">= 4.0.0"],
    "main": function(container, depth0, helpers, partials, data) {
        var stack1;
        return "<div class=\"medium-insert-buttons\" contenteditable=\"false\" style=\"display: none\">\n    <a class=\"medium-insert-buttons-show\">+</a>\n    <ul class=\"medium-insert-buttons-addons\" style=\"display: none\">\n" + ((stack1 = helpers.each.call(depth0 != null ? depth0 : {}, (depth0 != null ? depth0.addons : depth0), {
                "name": "each",
                "hash": {},
                "fn": container.program(1, data, 0),
                "inverse": container.noop,
                "data": data
            })) != null ? stack1 : "") + "    </ul>\n</div>\n"
    },
    "useData": true
});
this["MediumInsert"]["Templates"]["src/js/templates/core-caption.hbs"] = Handlebars.template({
    "compiler": [7, ">= 4.0.0"],
    "main": function(container, depth0, helpers, partials, data) {
        var helper;
        return "<figcaption contenteditable=\"true\" class=\"medium-insert-caption-placeholder\" data-placeholder=\"" + container.escapeExpression(((helper = (helper = helpers.placeholder || (depth0 != null ? depth0.placeholder : depth0)) != null ? helper : helpers.helperMissing), (typeof helper === "function" ? helper.call(depth0 != null ? depth0 : {}, {
                "name": "placeholder",
                "hash": {},
                "data": data
            }) : helper))) + "\"></figcaption>"
    },
    "useData": true
});
this["MediumInsert"]["Templates"]["src/js/templates/core-empty-line.hbs"] = Handlebars.template({
    "compiler": [7, ">= 4.0.0"],
    "main": function(container, depth0, helpers, partials, data) {
        return "<p><br></p>\n"
    },
    "useData": true
});
this["MediumInsert"]["Templates"]["src/js/templates/embeds-toolbar.hbs"] = Handlebars.template({
    "1": function(container, depth0, helpers, partials, data) {
        var stack1;
        return "    <div class=\"medium-insert-embeds-toolbar medium-editor-toolbar medium-toolbar-arrow-under medium-editor-toolbar-active\">\n        <ul class=\"medium-editor-toolbar-actions clearfix\">\n" + ((stack1 = helpers.each.call(depth0 != null ? depth0 : {}, (depth0 != null ? depth0.styles : depth0), {
                "name": "each",
                "hash": {},
                "fn": container.program(2, data, 0),
                "inverse": container.noop,
                "data": data
            })) != null ? stack1 : "") + "        </ul>\n    </div>\n"
    },
    "2": function(container, depth0, helpers, partials, data) {
        var stack1;
        return ((stack1 = helpers["if"].call(depth0 != null ? depth0 : {}, (depth0 != null ? depth0.label : depth0), {
            "name": "if",
            "hash": {},
            "fn": container.program(3, data, 0),
            "inverse": container.noop,
            "data": data
        })) != null ? stack1 : "")
    },
    "3": function(container, depth0, helpers, partials, data) {
        var stack1, helper, alias1 = depth0 != null ? depth0 : {},
            alias2 = helpers.helperMissing,
            alias3 = "function";
        return "                    <li>\n                        <button class=\"medium-editor-action\" data-action=\"" + container.escapeExpression(((helper = (helper = helpers.key || (data && data.key)) != null ? helper : alias2), (typeof helper === alias3 ? helper.call(alias1, {
                "name": "key",
                "hash": {},
                "data": data
            }) : helper))) + "\">" + ((stack1 = ((helper = (helper = helpers.label || (depth0 != null ? depth0.label : depth0)) != null ? helper : alias2), (typeof helper === alias3 ? helper.call(alias1, {
                "name": "label",
                "hash": {},
                "data": data
            }) : helper))) != null ? stack1 : "") + "</button>\n                    </li>\n"
    },
    "5": function(container, depth0, helpers, partials, data) {
        var stack1;
        return "    <div class=\"medium-insert-embeds-toolbar2 medium-editor-toolbar medium-editor-toolbar-active\">\n        <ul class=\"medium-editor-toolbar-actions clearfix\">\n" + ((stack1 = helpers.each.call(depth0 != null ? depth0 : {}, (depth0 != null ? depth0.actions : depth0), {
                "name": "each",
                "hash": {},
                "fn": container.program(2, data, 0),
                "inverse": container.noop,
                "data": data
            })) != null ? stack1 : "") + "        </ul>\n    </div>\n"
    },
    "compiler": [7, ">= 4.0.0"],
    "main": function(container, depth0, helpers, partials, data) {
        var stack1, alias1 = depth0 != null ? depth0 : {};
        return ((stack1 = helpers["if"].call(alias1, (depth0 != null ? depth0.styles : depth0), {
                "name": "if",
                "hash": {},
                "fn": container.program(1, data, 0),
                "inverse": container.noop,
                "data": data
            })) != null ? stack1 : "") + "\n" + ((stack1 = helpers["if"].call(alias1, (depth0 != null ? depth0.actions : depth0), {
                "name": "if",
                "hash": {},
                "fn": container.program(5, data, 0),
                "inverse": container.noop,
                "data": data
            })) != null ? stack1 : "")
    },
    "useData": true
});
this["MediumInsert"]["Templates"]["src/js/templates/embeds-wrapper.hbs"] = Handlebars.template({
    "compiler": [7, ">= 4.0.0"],
    "main": function(container, depth0, helpers, partials, data) {
        var stack1, helper;
        return "<div class=\"medium-insert-embeds\" contenteditable=\"false\">\n	<figure>\n		<div class=\"medium-insert-embed\">\n			" + ((stack1 = ((helper = (helper = helpers.html || (depth0 != null ? depth0.html : depth0)) != null ? helper : helpers.helperMissing), (typeof helper === "function" ? helper.call(depth0 != null ? depth0 : {}, {
                "name": "html",
                "hash": {},
                "data": data
            }) : helper))) != null ? stack1 : "") + "\n		</div>\n	</figure>\n	<div class=\"medium-insert-embeds-overlay\"></div>\n</div>"
    },
    "useData": true
});
this["MediumInsert"]["Templates"]["src/js/templates/images-fileupload.hbs"] = Handlebars.template({
    "compiler": [7, ">= 4.0.0"],
    "main": function(container, depth0, helpers, partials, data) {
        return "<input type=\"file\" multiple>"
    },
    "useData": true
});
this["MediumInsert"]["Templates"]["src/js/templates/images-image.hbs"] = Handlebars.template({
    "1": function(container, depth0, helpers, partials, data) {
        return "        <div class=\"medium-insert-images-progress\"></div>\n"
    },
    "compiler": [7, ">= 4.0.0"],
    "main": function(container, depth0, helpers, partials, data) {
        var stack1, helper, alias1 = depth0 != null ? depth0 : {};
        return "<figure contenteditable=\"false\">\n    <img src=\"" + container.escapeExpression(((helper = (helper = helpers.img || (depth0 != null ? depth0.img : depth0)) != null ? helper : helpers.helperMissing), (typeof helper === "function" ? helper.call(alias1, {
                "name": "img",
                "hash": {},
                "data": data
            }) : helper))) + "\" alt=\"\">\n" + ((stack1 = helpers["if"].call(alias1, (depth0 != null ? depth0.progress : depth0), {
                "name": "if",
                "hash": {},
                "fn": container.program(1, data, 0),
                "inverse": container.noop,
                "data": data
            })) != null ? stack1 : "") + "</figure>"
    },
    "useData": true
});
this["MediumInsert"]["Templates"]["src/js/templates/images-progressbar.hbs"] = Handlebars.template({
    "compiler": [7, ">= 4.0.0"],
    "main": function(container, depth0, helpers, partials, data) {
        return "<progress min=\"0\" max=\"100\" value=\"0\">0</progress>"
    },
    "useData": true
});
this["MediumInsert"]["Templates"]["src/js/templates/images-toolbar.hbs"] = Handlebars.template({
    "1": function(container, depth0, helpers, partials, data) {
        var stack1;
        return ((stack1 = helpers["if"].call(depth0 != null ? depth0 : {}, (depth0 != null ? depth0.label : depth0), {
            "name": "if",
            "hash": {},
            "fn": container.program(2, data, 0),
            "inverse": container.noop,
            "data": data
        })) != null ? stack1 : "")
    },
    "2": function(container, depth0, helpers, partials, data) {
        var stack1, helper, alias1 = depth0 != null ? depth0 : {},
            alias2 = helpers.helperMissing,
            alias3 = "function";
        return "                <li>\n                    <button class=\"medium-editor-action\" data-action=\"" + container.escapeExpression(((helper = (helper = helpers.key || (data && data.key)) != null ? helper : alias2), (typeof helper === alias3 ? helper.call(alias1, {
                "name": "key",
                "hash": {},
                "data": data
            }) : helper))) + "\">" + ((stack1 = ((helper = (helper = helpers.label || (depth0 != null ? depth0.label : depth0)) != null ? helper : alias2), (typeof helper === alias3 ? helper.call(alias1, {
                "name": "label",
                "hash": {},
                "data": data
            }) : helper))) != null ? stack1 : "") + "</button>\n                </li>\n"
    },
    "4": function(container, depth0, helpers, partials, data) {
        var stack1;
        return "	<div class=\"medium-insert-images-toolbar2 medium-editor-toolbar medium-editor-toolbar-active\">\n		<ul class=\"medium-editor-toolbar-actions clearfix\">\n" + ((stack1 = helpers.each.call(depth0 != null ? depth0 : {}, (depth0 != null ? depth0.actions : depth0), {
                "name": "each",
                "hash": {},
                "fn": container.program(5, data, 0),
                "inverse": container.noop,
                "data": data
            })) != null ? stack1 : "") + "    	</ul>\n    </div>\n"
    },
    "5": function(container, depth0, helpers, partials, data) {
        var stack1;
        return ((stack1 = helpers["if"].call(depth0 != null ? depth0 : {}, (depth0 != null ? depth0.label : depth0), {
            "name": "if",
            "hash": {},
            "fn": container.program(6, data, 0),
            "inverse": container.noop,
            "data": data
        })) != null ? stack1 : "")
    },
    "6": function(container, depth0, helpers, partials, data) {
        var stack1, helper, alias1 = depth0 != null ? depth0 : {},
            alias2 = helpers.helperMissing,
            alias3 = "function";
        return "        	        <li>\n        	            <button class=\"medium-editor-action\" data-action=\"" + container.escapeExpression(((helper = (helper = helpers.key || (data && data.key)) != null ? helper : alias2), (typeof helper === alias3 ? helper.call(alias1, {
                "name": "key",
                "hash": {},
                "data": data
            }) : helper))) + "\">" + ((stack1 = ((helper = (helper = helpers.label || (depth0 != null ? depth0.label : depth0)) != null ? helper : alias2), (typeof helper === alias3 ? helper.call(alias1, {
                "name": "label",
                "hash": {},
                "data": data
            }) : helper))) != null ? stack1 : "") + "</button>\n        	        </li>\n"
    },
    "compiler": [7, ">= 4.0.0"],
    "main": function(container, depth0, helpers, partials, data) {
        var stack1, alias1 = depth0 != null ? depth0 : {};
        return "<div class=\"medium-insert-images-toolbar medium-editor-toolbar medium-toolbar-arrow-under medium-editor-toolbar-active\">\n    <ul class=\"medium-editor-toolbar-actions clearfix\">\n" + ((stack1 = helpers.each.call(alias1, (depth0 != null ? depth0.styles : depth0), {
                "name": "each",
                "hash": {},
                "fn": container.program(1, data, 0),
                "inverse": container.noop,
                "data": data
            })) != null ? stack1 : "") + "    </ul>\n</div>\n\n" + ((stack1 = helpers["if"].call(alias1, (depth0 != null ? depth0.actions : depth0), {
                "name": "if",
                "hash": {},
                "fn": container.program(4, data, 0),
                "inverse": container.noop,
                "data": data
            })) != null ? stack1 : "")
    },
    "useData": true
});
(function($, window, document, undefined) {
    'use strict';
    var pluginName = 'mediumInsert',
        defaults = {
            editor: null,
            enabled: true,
            addons: {
                images: true,
                embeds: true
            },
            insertBtnPositionFix: {
                left: 0,
                top: 0
            }
        };

    function ucfirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1)
    }
    function Core(el, options) {
        var editor;
        this.el = el;
        this.$el = $(el);
        this.templates = window.MediumInsert.Templates;
        if (options) {
            editor = options.editor;
            options.editor = null
        }
        this.options = $.extend(true, {}, defaults, options);
        this.options.editor = editor;
        this._defaults = defaults;
        this._name = pluginName;
        if (this.options && this.options.editor) {
            this.options.editor._serialize = this.options.editor.serialize;
            this.options.editor._destroy = this.options.editor.destroy;
            this.options.editor._setup = this.options.editor.setup;
            this.options.editor._hideInsertButtons = this.hideButtons;
            this.options.editor.serialize = this.editorSerialize;
            this.options.editor.destroy = this.editorDestroy;
            this.options.editor.setup = this.editorSetup;
            this.options.editor.getExtensionByName('placeholder').updatePlaceholder = this.editorUpdatePlaceholder
        }
    }
    Core.prototype.init = function() {
        this.$el.addClass('medium-editor-insert-plugin');
        if (typeof this.options.addons !== 'object' || Object.keys(this.options.addons).length === 0) {
            this.disable()
        }
        this.initAddons();
        this.clean();
        this.events()
    };
    Core.prototype.events = function() {
        var that = this;
        this.$el.on('dragover drop', function(e) {
            e.preventDefault()
        }).on('keyup click', $.proxy(this, 'toggleButtons')).on('selectstart mousedown', '.medium-insert, .medium-insert-buttons', $.proxy(this, 'disableSelection')).on('click', '.medium-insert-buttons-show', $.proxy(this, 'toggleAddons')).on('click', '.medium-insert-action', $.proxy(this, 'addonAction')).on('paste', '.medium-insert-caption-placeholder', function(e) {
            $.proxy(that, 'removeCaptionPlaceholder')($(e.target))
        });
        $(window).on('resize', $.proxy(this, 'positionButtons', null))
    };
    Core.prototype.getEditor = function() {
        return this.options.editor
    };
    Core.prototype.editorSerialize = function() {
        var data = this._serialize();
        $.each(data, function(key) {
            var $data = $('<div />').html(data[key].value);
            $data.find('.medium-insert-buttons').remove();
            data[key].value = $data.html()
        });
        return data
    };
    Core.prototype.editorDestroy = function() {
        $.each(this.elements, function(key, el) {
            $(el).data('plugin_' + pluginName).disable()
        });
        this._destroy()
    };
    Core.prototype.editorSetup = function() {
        this._setup();
        $.each(this.elements, function(key, el) {
            $(el).data('plugin_' + pluginName).enable()
        })
    };
    Core.prototype.editorUpdatePlaceholder = function(el) {
        var $clone = $(el).clone(),
            cloneHtml;
        $clone.find('.medium-insert-buttons').remove();
        cloneHtml = $clone.html().replace(/^\s+|\s+$/g, '').replace(/^<p( class="medium-insert-active")?><br><\/p>$/, '');
        if (!(el.querySelector('img, blockquote')) && cloneHtml === '') {
            this.showPlaceholder(el);
            this.base._hideInsertButtons($(el))
        } else {
            this.hidePlaceholder(el)
        }
    };
    Core.prototype.triggerInput = function() {
        if (this.getEditor()) {
            this.getEditor().trigger('editableInput', null, this.el)
        }
    };
    Core.prototype.deselect = function() {
        document.getSelection().removeAllRanges()
    };
    Core.prototype.disable = function() {
        this.options.enabled = false;
        this.$el.find('.medium-insert-buttons').addClass('hide')
    };
    Core.prototype.enable = function() {
        this.options.enabled = true;
        this.$el.find('.medium-insert-buttons').removeClass('hide')
    };
    Core.prototype.disableSelection = function(e) {
        var $el = $(e.target);
        if ($el.is('img') === false || $el.hasClass('medium-insert-buttons-show')) {
            e.preventDefault()
        }
    };
    Core.prototype.initAddons = function() {
        var that = this;
        if (!this.options.addons || this.options.addons.length === 0) {
            return
        }
        $.each(this.options.addons, function(addon, options) {
            var addonName = pluginName + ucfirst(addon);
            if (options === false) {
                delete that.options.addons[addon];
                return
            }
            that.$el[addonName](options);
            that.options.addons[addon] = that.$el.data('plugin_' + addonName).options
        })
    };
    Core.prototype.clean = function() {
        var that = this,
            $buttons, $lastEl, $text;
        if (this.options.enabled === false) {
            return
        }
        if (this.$el.html().trim() === '' || this.$el.html().trim() === '<br>') {
            this.$el.html(this.templates['src/js/templates/core-empty-line.hbs']().trim())
        }
        $text = this.$el.contents().filter(function() {
            return this.nodeName === '#text' && $.trim($(this).text()) !== ''
        });
        $text.each(function() {
            $(this).wrap('<p />');
            that.moveCaret($(this).parent(), $(this).text().length)
        });
        this.addButtons();
        $buttons = this.$el.find('.medium-insert-buttons');
        $lastEl = $buttons.prev();
        if ($lastEl.attr('class') && $lastEl.attr('class').match(/medium\-insert(?!\-active)/)) {
            $buttons.before(this.templates['src/js/templates/core-empty-line.hbs']().trim())
        }
    };
    Core.prototype.getButtons = function() {
        if (this.options.enabled === false) {
            return
        }
        return this.templates['src/js/templates/core-buttons.hbs']({
            addons: this.options.addons
        }).trim()
    };
    Core.prototype.addButtons = function() {
        if (this.$el.find('.medium-insert-buttons').length === 0) {
            this.$el.append(this.getButtons())
        }
    };
    Core.prototype.toggleButtons = function(e) {
        var $el = $(e.target),
            selection = window.getSelection(),
            that = this,
            range, $current, $p, activeAddon;
        if (this.options.enabled === false) {
            return
        }
        if (!selection || selection.rangeCount === 0) {
            $current = $el
        } else {
            range = selection.getRangeAt(0);
            $current = $(range.commonAncestorContainer)
        }
        if ($current.hasClass('medium-editor-insert-plugin')) {
            $current = $current.find('p:first')
        }
        $p = $current.is('p') ? $current : $current.closest('p');
        this.clean();
        if ($el.hasClass('medium-editor-placeholder') === false && $el.closest('.medium-insert-buttons').length === 0 && $current.closest('.medium-insert-buttons').length === 0) {
            this.$el.find('.medium-insert-active').removeClass('medium-insert-active');
            $.each(this.options.addons, function(addon) {
                if ($el.closest('.medium-insert-' + addon).length) {
                    $current = $el
                }
                if ($current.closest('.medium-insert-' + addon).length) {
                    $p = $current.closest('.medium-insert-' + addon);
                    activeAddon = addon;
                    return
                }
            });
            if ($p.length && (($p.text().trim() === '' && !activeAddon) || activeAddon === 'images')) {
                $p.addClass('medium-insert-active');
                setTimeout(function() {
                    that.positionButtons(activeAddon);
                    that.showButtons(activeAddon)
                }, activeAddon ? 100 : 0)
            } else {
                this.hideButtons()
            }
        }
    };
    Core.prototype.showButtons = function(activeAddon) {
        var $buttons = this.$el.find('.medium-insert-buttons');
        $buttons.show();
        $buttons.find('li').show();
        if (activeAddon) {
            $buttons.find('li').hide();
            $buttons.find('a[data-addon="' + activeAddon + '"]').parent().show()
        }
    };
    Core.prototype.hideButtons = function($el) {
        $el = $el || this.$el;
        $el.find('.medium-insert-buttons').hide();
        $el.find('.medium-insert-buttons-addons').hide();
        $el.find('.medium-insert-buttons-show').removeClass('medium-insert-buttons-rotate')
    };
    Core.prototype.positionButtons = function(activeAddon) {
        var $buttons = this.$el.find('.medium-insert-buttons'),
            $p = this.$el.find('.medium-insert-active'),
            $first = $p.find('figure:first').length ? $p.find('figure:first') : $p,
            left, top;
        if ($p.length) {
            left = $p.position().left - parseInt($buttons.find('.medium-insert-buttons-addons').css('left'), 10) - parseInt($buttons.find('.medium-insert-buttons-addons a:first').css('margin-left'), 10);
            left = left < 0 ? $p.position().left : left;
            top = $p.position().top + parseInt($p.css('margin-top'), 10);
            if (activeAddon) {
                if ($p.position().left !== $first.position().left) {
                    left = $first.position().left
                }
                top += $p.height() + 15
            }
            $buttons.css({
                left: left + this.options.insertBtnPositionFix.left,
                top: top + this.options.insertBtnPositionFix.top
            })
        }
    };
    Core.prototype.toggleAddons = function() {
        this.$el.find('.medium-insert-buttons-addons').fadeToggle();
        this.$el.find('.medium-insert-buttons-show').toggleClass('medium-insert-buttons-rotate')
    };
    Core.prototype.hideAddons = function() {
        this.$el.find('.medium-insert-buttons-addons').hide();
        this.$el.find('.medium-insert-buttons-show').removeClass('medium-insert-buttons-rotate')
    };
    Core.prototype.addonAction = function(e) {
        var $a = $(e.target).is('a') ? $(e.target) : $(e.target).closest('a'),
            addon = $a.data('addon'),
            action = $a.data('action');
        this.$el.data('plugin_' + pluginName + ucfirst(addon))[action]()
    };
    Core.prototype.moveCaret = function($el, position) {
        var range, sel, el;
        position = position || 0;
        range = document.createRange();
        sel = window.getSelection();
        el = $el.get(0);
        if (!el.childNodes.length) {
            var textEl = document.createTextNode(' ');
            el.appendChild(textEl)
        }
        range.setStart(el.childNodes[0], position);
        range.collapse(true);
        sel.removeAllRanges();
        sel.addRange(range)
    };
    Core.prototype.addCaption = function($el, placeholder) {
        var $caption = $el.find('figcaption');
        if ($caption.length === 0) {
            $el.append(this.templates['src/js/templates/core-caption.hbs']({
                placeholder: placeholder
            }))
        }
    };
    Core.prototype.removeCaptions = function($ignore) {
        var $captions = this.$el.find('figcaption');
        if ($ignore) {
            $captions = $captions.not($ignore)
        }
        $captions.each(function() {
            if ($(this).hasClass('medium-insert-caption-placeholder') || $(this).text().trim() === '') {
                $(this).remove()
            }
        })
    };
    Core.prototype.removeCaptionPlaceholder = function($el) {
        var $caption = $el.is('figcaption') ? $el : $el.find('figcaption');
        if ($caption.length) {
            $caption.removeClass('medium-insert-caption-placeholder').removeAttr('data-placeholder')
        }
    };
    $.fn[pluginName] = function(options) {
        return this.each(function() {
            var that = this,
                textareaId;
            if ($(that).is('textarea')) {
                textareaId = $(that).attr('medium-editor-textarea-id');
                that = $(that).siblings('[medium-editor-textarea-id="' + textareaId + '"]').get(0)
            }
            if (!$.data(that, 'plugin_' + pluginName)) {
                $.data(that, 'plugin_' + pluginName, new Core(that, options));
                $.data(that, 'plugin_' + pluginName).init()
            } else if (typeof options === 'string' && $.data(that, 'plugin_' + pluginName)[options]) {
                $.data(that, 'plugin_' + pluginName)[options]()
            }
        })
    }
})(jQuery, window, document);
(function($, window, document, undefined) {
    'use strict';
    var pluginName = 'mediumInsert',
        addonName = 'Embeds',
        defaults = {
            label: '<span class="fa fa-youtube-play"></span>',
            placeholder: '插入一个优酷视频的链接地址,然后按回车键',
            oembedProxy: false,
            captions: true,
            captionPlaceholder: '添加描述 (可选)',
            styles: {
                wide: {
                    label: '<span class="fa fa-align-justify"></span>',
                },
                left: {
                    label: '<span class="fa fa-align-left"></span>',
                },
                right: {
                    label: '<span class="fa fa-align-right"></span>',
                }
            },
            actions: {
                remove: {
                    label: '<span class="fa fa-times"></span>',
                    clicked: function() {
                        var $event = $.Event('keydown');
                        $event.which = 8;
                        $(document).trigger($event)
                    }
                }
            }
        };

    function Embeds(el, options) {
        this.el = el;
        this.$el = $(el);
        this.templates = window.MediumInsert.Templates;
        this.core = this.$el.data('plugin_' + pluginName);
        this.options = $.extend(true, {}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        if (this.core.getEditor()) {
            this.core.getEditor()._serializePreEmbeds = this.core.getEditor().serialize;
            this.core.getEditor().serialize = this.editorSerialize
        }
        this.init()
    }
    Embeds.prototype.init = function() {
        var $embeds = this.$el.find('.medium-insert-embeds');
        $embeds.attr('contenteditable', false);
        $embeds.each(function() {
            if ($(this).find('.medium-insert-embeds-overlay').length === 0) {
                $(this).append($('<div />').addClass('medium-insert-embeds-overlay'))
            }
        });
        this.events();
        this.backwardsCompatibility()
    };
    Embeds.prototype.events = function() {
        $(document).on('click', $.proxy(this, 'unselectEmbed')).on('keydown', $.proxy(this, 'removeEmbed')).on('click', '.medium-insert-embeds-toolbar .medium-editor-action', $.proxy(this, 'toolbarAction')).on('click', '.medium-insert-embeds-toolbar2 .medium-editor-action', $.proxy(this, 'toolbar2Action'));
        this.$el.on('keyup click paste', $.proxy(this, 'togglePlaceholder')).on('keydown', $.proxy(this, 'processLink')).on('click', '.medium-insert-embeds-overlay', $.proxy(this, 'selectEmbed')).on('contextmenu', '.medium-insert-embeds-placeholder', $.proxy(this, 'fixRightClickOnPlaceholder'))
    };
    Embeds.prototype.backwardsCompatibility = function() {
        var that = this;
        this.$el.find('.mediumInsert-embeds').removeClass('mediumInsert-embeds').addClass('medium-insert-embeds');
        this.$el.find('.medium-insert-embeds').each(function() {
            if ($(this).find('.medium-insert-embed').length === 0) {
                $(this).after(that.templates['src/js/templates/embeds-wrapper.hbs']({
                    html: $(this).html()
                }));
                $(this).remove()
            }
        })
    };
    Embeds.prototype.editorSerialize = function() {
        var data = this._serializePreEmbeds();
        $.each(data, function(key) {
            var $data = $('<div />').html(data[key].value);
            $data.find('.medium-insert-embeds').removeAttr('contenteditable');
            $data.find('.medium-insert-embeds-overlay').remove();
            data[key].value = $data.html()
        });
        return data
    };
    Embeds.prototype.add = function() {
        var $place = this.$el.find('.medium-insert-active');
        $place.html(this.templates['src/js/templates/core-empty-line.hbs']().trim());
        if ($place.is('p')) {
            $place.replaceWith('<div class="medium-insert-active">' + $place.html() + '</div>');
            $place = this.$el.find('.medium-insert-active');
            this.core.moveCaret($place)
        }
        $place.addClass('medium-insert-embeds medium-insert-embeds-input medium-insert-embeds-active');
        this.togglePlaceholder({
            target: $place.get(0)
        });
        $place.click();
        this.core.hideButtons()
    };
    Embeds.prototype.togglePlaceholder = function(e) {
        var $place = $(e.target),
            selection = window.getSelection(),
            range, $current, text;
        if (!selection || selection.rangeCount === 0) {
            return
        }
        range = selection.getRangeAt(0);
        $current = $(range.commonAncestorContainer);
        if ($current.hasClass('medium-insert-embeds-active')) {
            $place = $current
        } else if ($current.closest('.medium-insert-embeds-active').length) {
            $place = $current.closest('.medium-insert-embeds-active')
        }
        if ($place.hasClass('medium-insert-embeds-active')) {
            text = $place.text().trim();
            if (text === '' && $place.hasClass('medium-insert-embeds-placeholder') === false) {
                $place.addClass('medium-insert-embeds-placeholder').attr('data-placeholder', this.options.placeholder)
            } else if (text !== '' && $place.hasClass('medium-insert-embeds-placeholder')) {
                $place.removeClass('medium-insert-embeds-placeholder').removeAttr('data-placeholder')
            }
        } else {
            this.$el.find('.medium-insert-embeds-active').remove()
        }
    };
    Embeds.prototype.fixRightClickOnPlaceholder = function(e) {
        this.core.moveCaret($(e.target))
    };
    Embeds.prototype.processLink = function(e) {
        var $place = this.$el.find('.medium-insert-embeds-active'),
            url;
        if (!$place.length) {
            return
        }
        url = $place.text().trim();
        if (url === '' && [8, 46, 13].indexOf(e.which) !== -1) {
            $place.remove();
            return
        }
        if (e.which === 13) {
            e.preventDefault();
            e.stopPropagation();
            if (this.options.oembedProxy) {
                this.oembed(url)
            } else {
                this.parseUrl(url)
            }
        }
    };
    Embeds.prototype.oembed = function(url) {
        var that = this;
        $.support.cors = true;
        $.ajax({
            crossDomain: true,
            cache: false,
            url: this.options.oembedProxy,
            dataType: 'json',
            data: {
                url: url
            },
            success: function(data) {
                console.log(data);
                var html = data && data.html;
                if (data && !data.html && data.type === 'photo' && data.url) {
                    html = '<img src="' + data.url + '" alt="">'
                }
                $.proxy(that, 'embed', html)()
            },
            error: function(jqXHR, textStatus, errorThrown) {
                var responseJSON = (function() {
                    try {
                        return JSON.parse(jqXHR.responseText)
                    } catch (e) {}
                })();
                if (typeof window.console !== 'undefined') {
                    window.console.log((responseJSON && responseJSON.error) || jqXHR.status || errorThrown.message)
                } else {
                    window.alert('Error requesting media from ' + that.options.oembedProxy + ' to insert: ' + errorThrown + ' (response status: ' + jqXHR.status + ')')
                }
                $.proxy(that, 'convertBadEmbed', url)()
            }
        })
    };
    Embeds.prototype.parseUrl = function(url) {
        var html, match;
        if (!(new RegExp(['youku', 'v.qq'].join('|')).test(url))) {
            $.proxy(this, 'convertBadEmbed', url)();
            return false
        }
        html = url.replace(/\n?/g, '').replace(/^((http(s)?:\/\/)(v\.|\w\\.))(youku\.com\/v_show\/id_)(\w+==|\w+)(.*?$)/, '<div style="left: 0px; width: 100%; height: 0px; position: relative; padding-bottom: 62.5%;"> <iframe src="http://player.youku.com/embed/$6"  frameborder="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" style="top: 0px; left: 0px; width: 100%; height: 100%; position: absolute;"></iframe> </div>').replace(/^((http(s)?:\/\/)(v\.|\w\\.))(qq\.com\/cover\/(\w+\/|\/))(\w+)(\.html(\?vid=)(\w+).*?$)/, '<div style="left: 0px; width: 100%; height: 0px; position: relative; padding-bottom: 62.5%;"> <iframe src="http://v.qq.com/iframe/player.html?vid=$10&auto=1"  frameborder="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" style="top: 0px; left: 0px; width: 100%; height: 100%; position: absolute;"></iframe> </div>');
        this.embed((/<("[^"]*"|'[^']*'|[^'">])*>/).test(html) ? html : false)
    };
    Embeds.prototype.embed = function(html) {
        var $place = this.$el.find('.medium-insert-embeds-active');
        if (!html) {
            alert('Incorrect URL format specified');
            return false
        } else {
            $place.after(this.templates['src/js/templates/embeds-wrapper.hbs']({
                html: html
            }));
            $place.remove();
            this.core.triggerInput();
            if (html.indexOf('facebook') !== -1) {
                if (typeof(FB) !== 'undefined') {
                    setTimeout(function() {
                        FB.XFBML.parse()
                    }, 2000)
                }
            }
        }
    };
    Embeds.prototype.convertBadEmbed = function(content) {
        var $place, $empty, $content, emptyTemplate = this.templates['src/js/templates/core-empty-line.hbs']().trim();
        $place = this.$el.find('.medium-insert-embeds-active');
        $content = $(emptyTemplate);
        $place.before($content);
        $place.remove();
        $content.html(content);
        $empty = $(emptyTemplate);
        $content.after($empty);
        this.core.triggerInput();
        this.core.moveCaret($place)
    };
    Embeds.prototype.selectEmbed = function(e) {
        if (this.core.options.enabled) {
            var $embed = $(e.target).hasClass('medium-insert-embeds') ? $(e.target) : $(e.target).closest('.medium-insert-embeds'),
                that = this;
            $embed.addClass('medium-insert-embeds-selected');
            setTimeout(function() {
                that.addToolbar();
                if (that.options.captions) {
                    that.core.addCaption($embed.find('figure'), that.options.captionPlaceholder)
                }
            }, 50)
        }
    };
    Embeds.prototype.unselectEmbed = function(e) {
        var $el = $(e.target).hasClass('medium-insert-embeds') ? $(e.target) : $(e.target).closest('.medium-insert-embeds'),
            $embed = this.$el.find('.medium-insert-embeds-selected');
        if ($el.hasClass('medium-insert-embeds-selected')) {
            $embed.not($el).removeClass('medium-insert-embeds-selected');
            $('.medium-insert-embeds-toolbar, .medium-insert-embeds-toolbar2').remove();
            this.core.removeCaptions($el.find('figcaption'));
            if ($(e.target).is('.medium-insert-caption-placeholder') || $(e.target).is('figcaption')) {
                $el.removeClass('medium-insert-embeds-selected');
                this.core.removeCaptionPlaceholder($el.find('figure'))
            }
            return
        }
        $embed.removeClass('medium-insert-embeds-selected');
        $('.medium-insert-embeds-toolbar, .medium-insert-embeds-toolbar2').remove();
        if ($(e.target).is('.medium-insert-caption-placeholder')) {
            this.core.removeCaptionPlaceholder($el.find('figure'))
        } else if ($(e.target).is('figcaption') === false) {
            this.core.removeCaptions()
        }
    };
    Embeds.prototype.removeEmbed = function(e) {
        var $embed, $empty;
        if (e.which === 8 || e.which === 46) {
            $embed = this.$el.find('.medium-insert-embeds-selected');
            if ($embed.length) {
                e.preventDefault();
                $('.medium-insert-embeds-toolbar, .medium-insert-embeds-toolbar2').remove();
                $empty = $(this.templates['src/js/templates/core-empty-line.hbs']().trim());
                $embed.before($empty);
                $embed.remove();
                this.core.hideAddons();
                this.core.moveCaret($empty);
                this.core.triggerInput()
            }
        }
    };
    Embeds.prototype.addToolbar = function() {
        var $embed = this.$el.find('.medium-insert-embeds-selected'),
            active = false,
            $toolbar, $toolbar2, top;
        if ($embed.length === 0) {
            return
        }
        var mediumEditor = this.core.getEditor();
        var toolbarContainer = mediumEditor.options.elementsContainer || 'body';
        $(toolbarContainer).append(this.templates['src/js/templates/embeds-toolbar.hbs']({
            styles: this.options.styles,
            actions: this.options.actions
        }).trim());
        $toolbar = $('.medium-insert-embeds-toolbar');
        $toolbar2 = $('.medium-insert-embeds-toolbar2');
        top = $embed.offset().top - $toolbar.height() - 8 - 2 - 5;
        if (top < 0) {
            top = 0
        }
        $toolbar.css({
            top: top,
            left: $embed.offset().left + $embed.width() / 2 - $toolbar.width() / 2
        }).show();
        $toolbar2.css({
            top: $embed.offset().top + 2,
            left: $embed.offset().left + $embed.width() - $toolbar2.width() - 4
        }).show();
        $toolbar.find('button').each(function() {
            if ($embed.hasClass('medium-insert-embeds-' + $(this).data('action'))) {
                $(this).addClass('medium-editor-button-active');
                active = true
            }
        });
        if (active === false) {
            $toolbar.find('button').first().addClass('medium-editor-button-active')
        }
    };
    Embeds.prototype.toolbarAction = function(e) {
        var $button = $(e.target).is('button') ? $(e.target) : $(e.target).closest('button'),
            $li = $button.closest('li'),
            $ul = $li.closest('ul'),
            $lis = $ul.find('li'),
            $embed = this.$el.find('.medium-insert-embeds-selected'),
            that = this;
        $button.addClass('medium-editor-button-active');
        $li.siblings().find('.medium-editor-button-active').removeClass('medium-editor-button-active');
        $lis.find('button').each(function() {
            var className = 'medium-insert-embeds-' + $(this).data('action');
            if ($(this).hasClass('medium-editor-button-active')) {
                $embed.addClass(className);
                if (that.options.styles[$(this).data('action')].added) {
                    that.options.styles[$(this).data('action')].added($embed)
                }
            } else {
                $embed.removeClass(className);
                if (that.options.styles[$(this).data('action')].removed) {
                    that.options.styles[$(this).data('action')].removed($embed)
                }
            }
        });
        this.core.triggerInput()
    };
    Embeds.prototype.toolbar2Action = function(e) {
        var $button = $(e.target).is('button') ? $(e.target) : $(e.target).closest('button'),
            callback = this.options.actions[$button.data('action')].clicked;
        if (callback) {
            callback(this.$el.find('.medium-insert-embeds-selected'))
        }
        this.core.triggerInput()
    };
    $.fn[pluginName + addonName] = function(options) {
        return this.each(function() {
            if (!$.data(this, 'plugin_' + pluginName + addonName)) {
                $.data(this, 'plugin_' + pluginName + addonName, new Embeds(this, options))
            }
        })
    }
})(jQuery, window, document);
(function($, window, document, Util, undefined) {
    'use strict';
    var pluginName = 'mediumInsert',
        addonName = 'Images',
        defaults = {
            label: '<span class="fa fa-camera"></span>',
            preview: true,
            captions: true,
            captionPlaceholder: '添加图片描述 (可选)',
            autoGrid: 3,
            fileUploadOptions: {
                url: 'upload.php',
                acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
            },
            fileDeleteOptions: {},
            styles: {
                wide: {
                    label: '<span class="fa fa-align-justify"></span>',
                },
                left: {
                    label: '<span class="fa fa-align-left"></span>',
                },
                right: {
                    label: '<span class="fa fa-align-right"></span>',
                },
                grid: {
                    label: '<span class="fa fa-th"></span>',
                }
            },
            actions: {
                remove: {
                    label: '<span class="fa fa-times"></span>',
                    clicked: function() {
                        var $event = $.Event('keydown');
                        $event.which = 8;
                        $(document).trigger($event)
                    }
                }
            },
            sorting: function() {
                var that = this;
                $('.medium-insert-images').sortable({
                    group: 'medium-insert-images',
                    containerSelector: '.medium-insert-images',
                    itemSelector: 'figure',
                    placeholder: '<figure class="placeholder">',
                    handle: 'img',
                    nested: false,
                    vertical: false,
                    afterMove: function() {
                        that.core.triggerInput()
                    }
                })
            },
            messages: {
                acceptFileTypesError: 'This file is not in a supported format: ',
                maxFileSizeError: 'This file is too big: '
            }
        },
        imageWidth = 0;

    function Images(el, options) {
        this.el = el;
        this.$el = $(el);
        this.templates = window.MediumInsert.Templates;
        this.core = this.$el.data('plugin_' + pluginName);
        this.options = $.extend(true, {}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        if (this.options.preview && !window.FileReader) {
            this.options.preview = false
        }
        if (this.core.getEditor()) {
            this.core.getEditor()._serializePreImages = this.core.getEditor().serialize;
            this.core.getEditor().serialize = this.editorSerialize
        }
        this.init()
    }
    Images.prototype.init = function() {
        var $images = this.$el.find('.medium-insert-images');
        $images.find('figcaption').attr('contenteditable', true);
        $images.find('figure').attr('contenteditable', false);
        this.events();
        this.backwardsCompatibility();
        this.sorting()
    };
    Images.prototype.events = function() {
        $(document).on('click', $.proxy(this, 'unselectImage')).on('keydown', $.proxy(this, 'removeImage')).on('click', '.medium-insert-images-toolbar .medium-editor-action', $.proxy(this, 'toolbarAction')).on('click', '.medium-insert-images-toolbar2 .medium-editor-action', $.proxy(this, 'toolbar2Action'));
        this.$el.on('click', '.medium-insert-images img', $.proxy(this, 'selectImage'))
    };
    Images.prototype.backwardsCompatibility = function() {
        this.$el.find('.mediumInsert').removeClass('mediumInsert').addClass('medium-insert-images');
        this.$el.find('.medium-insert-images.small').removeClass('small').addClass('medium-insert-images-left')
    };
    Images.prototype.editorSerialize = function() {
        var data = this._serializePreImages();
        $.each(data, function(key) {
            var $data = $('<div />').html(data[key].value);
            $data.find('.medium-insert-images').find('figcaption, figure').removeAttr('contenteditable');
            data[key].value = $data.html()
        });
        return data
    };
    Images.prototype.add = function() {
        var that = this,
            $file = $(this.templates['src/js/templates/images-fileupload.hbs']()),
            uploader = simple.uploader({}),
            get_img_url = function(id, option) {
                return 'http://qiniu.cdn-chuang.com/' + id + (imageWidth > 850 ? '?imageView2/2/w/850' : '')
            },
            submit_data = {};
        $file.prop("accept", "image/*").removeAttr('multiple');
        uploader.on("beforeupload", function(e, file, r) {});
        uploader.on("uploadprogress", function(e, file, loaded, total) {
            submit_data.loaded = loaded;
            submit_data.total = total;
            $.proxy(that, 'uploadProgress', e, submit_data)()
        });
        uploader.on("uploadsuccess", function(e, file, r) {
            submit_data.url = get_img_url(r.key, r);
            $.proxy(that, 'uploadDone', e, submit_data)()
        });
        if (new XMLHttpRequest().upload && 0) {
            fileUploadOptions.progress = function(e, data) {
                $.proxy(that, 'uploadProgress', e, data)()
            };
            fileUploadOptions.progressall = function(e, data) {
                $.proxy(that, 'uploadProgressall', e, data)()
            }
        }
        $file.change(function(e) {
            var val = $(this).val(),
                _this = this,
                data = {
                    files: this,
                    submit: function(after_submit_data) {
                        submit_data = after_submit_data;
                        uploader.upload(_this.files)
                    }
                };
            $.proxy(that, 'uploadAdd', e, data)()
        });
        $file.click()
    };
    Images.prototype.uploadAdd = function(e, data) {
        var $place = this.$el.find('.medium-insert-active'),
            that = this,
            uploadErrors = [],
            file = data.files.files[0],
            acceptFileTypes = this.options.fileUploadOptions.acceptFileTypes,
            maxFileSize = this.options.fileUploadOptions.maxFileSize,
            reader;
        if (acceptFileTypes && !acceptFileTypes.test(file['type'])) {
            uploadErrors.push(this.options.messages.acceptFileTypesError + file['name'])
        } else if (maxFileSize && file['size'] > maxFileSize) {
            uploadErrors.push(this.options.messages.maxFileSizeError + file['name'])
        }
        if (uploadErrors.length > 0) {
            alert(uploadErrors.join("\n"));
            return
        }
        this.core.hideButtons();
        if ($place.is('p')) {
            $place.replaceWith('<div class="medium-insert-active">' + $place.html() + '</div>');
            $place = this.$el.find('.medium-insert-active');
            this.core.moveCaret($place)
        }
        $place.addClass('medium-insert-images');
        if (this.options.preview === false && $place.find('progress').length === 0 && (new XMLHttpRequest().upload)) {
            $place.append(this.templates['src/js/templates/images-progressbar.hbs']())
        }
        reader = new FileReader();
        reader.onload = function(e) {
            var img = new Image();
            img.src = e.target.result;
            img.onload = function() {
                imageWidth = img.width
            };
            $.proxy(that, 'showImage', e.target.result, data)()
        };
        reader.readAsDataURL(file)
    };
    Images.prototype.uploadProgressall = function(e, data) {
        var progress, $progressbar;
        if (this.options.preview === false) {
            progress = parseInt(data.loaded / data.total * 100, 10);
            $progressbar = this.$el.find('.medium-insert-active').find('progress');
            $progressbar.attr('value', progress).text(progress);
            if (progress === 100) {
                $progressbar.remove()
            }
        }
    };
    Images.prototype.uploadProgress = function(e, data) {
        var progress, $progressbar;
        if (this.options.preview) {
            progress = 100 - parseInt(data.loaded / data.total * 100, 10);
            $progressbar = data.context.find('.medium-insert-images-progress');
            $progressbar.css('width', progress + '%');
            if (progress === 0) {
                $progressbar.remove()
            }
        }
    };
    Images.prototype.uploadDone = function(e, data) {
        var $el = $.proxy(this, 'showImage', data.url, data)();
        this.core.clean();
        this.sorting();
        if (this.options.uploadCompleted) {
            this.options.uploadCompleted($el, data)
        }
    };
    Images.prototype.showImage = function(img, data) {
        var $place = this.$el.find('.medium-insert-active'),
            domImage, that;
        $place.click();
        that = this;
        if (this.options.preview && data.context) {
            domImage = this.getDOMImage();
            domImage.onload = function() {
                data.context.find('img').attr('src', domImage.src);
                that.core.triggerInput()
            };
            domImage.src = img
        } else {
            data.context = $(this.templates['src/js/templates/images-image.hbs']({
                img: img,
                progress: this.options.preview
            })).appendTo($place);
            $place.find('br').remove();
            if (this.options.autoGrid && $place.find('figure').length >= this.options.autoGrid) {
                $.each(this.options.styles, function(style, options) {
                    var className = 'medium-insert-images-' + style;
                    $place.removeClass(className);
                    if (options.removed) {
                        options.removed($place)
                    }
                });
                $place.addClass('medium-insert-images-grid');
                if (this.options.styles.grid.added) {
                    this.options.styles.grid.added($place)
                }
            }
            if (this.options.preview) {
                data.submit(data)
            }
        }
        this.core.triggerInput();
        return data.context
    };
    Images.prototype.getDOMImage = function() {
        return new window.Image()
    };
    Images.prototype.selectImage = function(e) {
        if (this.core.options.enabled) {
            var $image = $(e.target),
                that = this;
            this.$el.blur();
            $image.addClass('medium-insert-image-active');
            $image.closest('.medium-insert-images').addClass('medium-insert-active');
            setTimeout(function() {
                that.addToolbar();
                if (that.options.captions) {
                    that.core.addCaption($image.closest('figure'), that.options.captionPlaceholder)
                }
            }, 50)
        }
    };
    Images.prototype.unselectImage = function(e) {
        var $el = $(e.target),
            $image = this.$el.find('.medium-insert-image-active');
        if ($el.is('img') && $el.hasClass('medium-insert-image-active')) {
            $image.not($el).removeClass('medium-insert-image-active');
            $('.medium-insert-images-toolbar, .medium-insert-images-toolbar2').remove();
            this.core.removeCaptions($el);
            return
        }
        $image.removeClass('medium-insert-image-active');
        $('.medium-insert-images-toolbar, .medium-insert-images-toolbar2').remove();
        if ($el.is('.medium-insert-caption-placeholder')) {
            this.core.removeCaptionPlaceholder($image.closest('figure'))
        } else if ($el.is('figcaption') === false) {
            this.core.removeCaptions()
        }
    };
    Images.prototype.removeImage = function(e) {
        var $image, $parent, $empty;
        if (e.which === 8 || e.which === 46) {
            $image = this.$el.find('.medium-insert-image-active');
            if ($image.length) {
                e.preventDefault();
                $parent = $image.closest('.medium-insert-images');
                $image.closest('figure').remove();
                $('.medium-insert-images-toolbar, .medium-insert-images-toolbar2').remove();
                if ($parent.find('figure').length === 0) {
                    $empty = $parent.next();
                    if ($empty.is('p') === false || $empty.text() !== '') {
                        $empty = $(this.templates['src/js/templates/core-empty-line.hbs']().trim());
                        $parent.before($empty)
                    }
                    $parent.remove();
                    this.core.hideAddons();
                    this.core.moveCaret($empty)
                }
                this.core.triggerInput()
            }
        }
    };
    Images.prototype.addToolbar = function() {
        var $image = this.$el.find('.medium-insert-image-active'),
            $p = $image.closest('.medium-insert-images'),
            active = false,
            $toolbar, $toolbar2, top;
        var mediumEditor = this.core.getEditor();
        var toolbarContainer = mediumEditor.options.elementsContainer || 'body';
        $(toolbarContainer).append(this.templates['src/js/templates/images-toolbar.hbs']({
            styles: this.options.styles,
            actions: this.options.actions
        }).trim());
        $toolbar = $('.medium-insert-images-toolbar');
        $toolbar2 = $('.medium-insert-images-toolbar2');
        top = $image.offset().top - $toolbar.height() - 8 - 2 - 5;
        if (top < 0) {
            top = 0
        }
        $toolbar.css({
            top: top,
            left: $image.offset().left + $image.width() / 2 - $toolbar.width() / 2
        }).show();
        $toolbar2.css({
            top: $image.offset().top + 2,
            left: $image.offset().left + $image.width() - $toolbar2.width() - 4
        }).show();
        $toolbar.find('button').each(function() {
            if ($p.hasClass('medium-insert-images-' + $(this).data('action'))) {
                $(this).addClass('medium-editor-button-active');
                active = true
            }
        });
        if (active === false) {
            $toolbar.find('button').first().addClass('medium-editor-button-active')
        }
    };
    Images.prototype.toolbarAction = function(e) {
        var $button = $(e.target).is('button') ? $(e.target) : $(e.target).closest('button'),
            $li = $button.closest('li'),
            $ul = $li.closest('ul'),
            $lis = $ul.find('li'),
            $p = this.$el.find('.medium-insert-active'),
            that = this;
        $button.addClass('medium-editor-button-active');
        $li.siblings().find('.medium-editor-button-active').removeClass('medium-editor-button-active');
        $lis.find('button').each(function() {
            var className = 'medium-insert-images-' + $(this).data('action');
            if ($(this).hasClass('medium-editor-button-active')) {
                $p.addClass(className);
                if (that.options.styles[$(this).data('action')].added) {
                    that.options.styles[$(this).data('action')].added($p)
                }
            } else {
                $p.removeClass(className);
                if (that.options.styles[$(this).data('action')].removed) {
                    that.options.styles[$(this).data('action')].removed($p)
                }
            }
        });
        this.core.hideButtons();
        this.core.triggerInput()
    };
    Images.prototype.toolbar2Action = function(e) {
        var $button = $(e.target).is('button') ? $(e.target) : $(e.target).closest('button'),
            callback = this.options.actions[$button.data('action')].clicked;
        if (callback) {
            callback(this.$el.find('.medium-insert-image-active'))
        }
        this.core.hideButtons();
        this.core.triggerInput()
    };
    Images.prototype.sorting = function() {
        $.proxy(this.options.sorting, this)()
    };
    $.fn[pluginName + addonName] = function(options) {
        return this.each(function() {
            if (!$.data(this, 'plugin_' + pluginName + addonName)) {
                $.data(this, 'plugin_' + pluginName + addonName, new Images(this, options))
            }
        })
    }
})(jQuery, window, document, MediumEditor.util);