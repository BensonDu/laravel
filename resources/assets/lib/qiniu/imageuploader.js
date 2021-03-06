/*! jQuery v1.11.1 jquery.com | jquery.org/license */
!
    function() {
        var a, b = [].slice;
        a = function() {
            function a(a) {
                var b, c, d, e, f, g, h;
                if (this.opts = $.extend({}, this.opts, a), (f = this.constructor)._connectedClasses || (f._connectedClasses = []), d = function() {
                        var a, c, d, f;
                        for (d = this.constructor._connectedClasses, f = [], a = 0, c = d.length; c > a; a++) b = d[a], e = b.pluginName.charAt(0).toLowerCase() + b.pluginName.slice(1), b.prototype._connected && (b.prototype._module = this), f.push(this[e] = new b);
                        return f
                    }.call(this), this._connected) this.opts = $.extend({}, this.opts, this._module.opts);
                else for (this._init(), g = 0, h = d.length; h > g; g++) c = d[g], "function" == typeof c._init && c._init();
                this.trigger("initialized")
            }
            return a.extend = function(a) {
                var b, c, d;
                if (null != a && "object" == typeof a) {
                    for (b in a) c = a[b], "included" !== b && "extended" !== b && (this[b] = c);
                    return null != (d = a.extended) ? d.call(this) : void 0
                }
            }, a.include = function(a) {
                var b, c, d;
                if (null != a && "object" == typeof a) {
                    for (b in a) c = a[b], "included" !== b && "extended" !== b && (this.prototype[b] = c);
                    return null != (d = a.included) ? d.call(this) : void 0
                }
            }, a.connect = function(a) {
                if ("function" == typeof a) {
                    if (!a.pluginName) throw new Error("Module.connect: cannot connect plugin without pluginName");
                    return a.prototype._connected = !0, this._connectedClasses || (this._connectedClasses = []), this._connectedClasses.push(a), a.pluginName ? this[a.pluginName] = a : void 0
                }
            }, a.prototype.opts = {}, a.prototype._init = function() {}, a.prototype.on = function() {
                var a, c;
                return a = 1 <= arguments.length ? b.call(arguments, 0) : [], (c = $(this)).on.apply(c, a), this
            }, a.prototype.one = function() {
                var a, c;
                return a = 1 <= arguments.length ? b.call(arguments, 0) : [], (c = $(this)).one.apply(c, a), this
            }, a.prototype.off = function() {
                var a, c;
                return a = 1 <= arguments.length ? b.call(arguments, 0) : [], (c = $(this)).off.apply(c, a), this
            }, a.prototype.trigger = function() {
                var a, c;
                return a = 1 <= arguments.length ? b.call(arguments, 0) : [], (c = $(this)).trigger.apply(c, a), this
            }, a.prototype.triggerHandler = function() {
                var a, c;
                return a = 1 <= arguments.length ? b.call(arguments, 0) : [], (c = $(this)).triggerHandler.apply(c, a)
            }, a.prototype._t = function() {
                var a, c;
                return a = 1 <= arguments.length ? b.call(arguments, 0) : [], (c = this.constructor)._t.apply(c, a)
            }, a._t = function() {
                var a, c, d, e;
                return c = arguments[0], a = 2 <= arguments.length ? b.call(arguments, 1) : [], d = (null != (e = this.i18n[this.locale]) ? e[c] : void 0) || "", a.length > 0 ? (d = d.replace(/([^%]|^)%(?:(\d+)\$)?s/g, function(b, c, d) {
                    return d ? c + a[parseInt(d) - 1] : c + a.shift()
                }), d.replace(/%%s/g, "%s")) : d
            }, a.i18n = {
                "zh-CN": {}
            }, a.locale = "zh-CN", a
        }(), window.SimpleModule = a
    }.call(this);
!
    function() {
        var a, b, c = {}.hasOwnProperty,
            d = function(a, b) {
                function e() {
                    this.constructor = a
                }
                for (var d in b) c.call(b, d) && (a[d] = b[d]);
                return e.prototype = b.prototype, a.prototype = new e, a.__super__ = b.prototype, a
            };
        a = function(a) {
            function b() {
                return b.__super__.constructor.apply(this, arguments)
            }
            return d(b, a), b.count = 0, b.prototype.opts = {
                url: "",
                params: null,
                fileKey: "upload_file",
                connectionCount: 3
            }, b.prototype._init = function() {
                return this.files = [], this.queue = [], this.id = ++b.count, this.on("uploadcomplete", function(a) {
                    return function(b, c) {
                        return a.files.splice($.inArray(c, a.files), 1), a.queue.length > 0 && a.files.length < a.opts.connectionCount ? a.upload(a.queue.shift()) : a.uploading = !1
                    }
                }(this)), $(window).on("beforeunload.uploader-" + this.id, function(a) {
                    return function(b) {
                        return a.uploading ? (b.originalEvent.returnValue = a._t("leaveConfirm"), a._t("leaveConfirm")) : void 0
                    }
                }(this))
            }, b.prototype.generateId = function() {
                var a;
                return a = 0, function() {
                    return a += 1
                }
            }(), b.prototype.upload = function(a, b) {
                var c, d, e, f;
                if (null == b && (b = {}), null != a) {
                    if ($.isArray(a) || a instanceof window.FileList) for (e = 0, f = a.length; f > e; e++) c = a[e], this.upload(c, b);
                    else $(a).is("input:file") ? (d = $(a).attr("name"), d && (b.fileKey = d), this.upload($.makeArray($(a)[0].files), b)) : a.id && a.obj || (a = this.getFile(a));
                    if (a && a.obj) {
                        if ($.extend(a, b), this.files.length >= this.opts.connectionCount) return this.queue.push(a), void 0;
                        if (this.triggerHandler("beforeupload", [a]) !== !1) return this.files.push(a), this._xhrUpload(a), this.uploading = !0
                    }
                }
            }, b.prototype.getFile = function(a) {
                var b, c, d;
                return a instanceof window.File || a instanceof window.Blob ? (b = null != (c = a.fileName) ? c : a.name, {
                    id: this.generateId(),
                    params: this.opts.params,
                    fileKey: this.opts.fileKey,
                    name: b,
                    size: null != (d = a.fileSize) ? d : a.size,
                    ext: b ? b.split(".").pop().toLowerCase() : "",
                    obj: a
                }) : null
            }, b.prototype._xhrUpload = function(a) {
                var b;
                return b = this, $.ajax({
                    dataType: "json",
                    url: "/qiniu/upload/token",
                    async: true,
                    success: function(c) {
                        var d, e, f, g;
                        if (d = new FormData, d.append("file", a.obj), d.append("key", (function (n) {
                                var f = n.split('.'),l = f.length, c = f[l-1], t = new Date().getTime(), base64 = function(input){
                                    input = escape(input);
                                    if(window.hasOwnProperty('btoa'))return btoa(input);
                                    var output = "";
                                    var chr1, chr2, chr3 = "";
                                    var enc1, enc2, enc3, enc4 = "";
                                    var i = 0;
                                    var keyStr = "ABCDEFGHIJKLMNOP" +
                                        "QRSTUVWXYZabcdef" +
                                        "ghijklmnopqrstuv" +
                                        "wxyz0123456789+/" +
                                        "=";

                                    do {
                                        chr1 = input.charCodeAt(i++);
                                        chr2 = input.charCodeAt(i++);
                                        chr3 = input.charCodeAt(i++);

                                        enc1 = chr1 >> 2;
                                        enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                                        enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                                        enc4 = chr3 & 63;

                                        if (isNaN(chr2)) {
                                            enc3 = enc4 = 64;
                                        } else if (isNaN(chr3)) {
                                            enc4 = 64;
                                        }

                                        output = output +
                                            keyStr.charAt(enc1) +
                                            keyStr.charAt(enc2) +
                                            keyStr.charAt(enc3) +
                                            keyStr.charAt(enc4);
                                        chr1 = chr2 = chr3 = "";
                                        enc1 = enc2 = enc3 = enc4 = "";
                                    } while (i < input.length);
                                    return output;
                                },fc= function (n) {
                                    var l = n.length;
                                    return n = base64(n), n = n.substr(0,l-2),n = n.substr(Math.floor(l/2),16),n;
                                },h = fc(n);
                                return h+t+'.'+c;
                            })(a.name)), a.params) {
                            g = a.params;
                            for (e in g) f = g[e], d.append(e, f)
                        }
                        return d.append("token", c.token), a.xhr = $.ajax({
                            url: "http://up.qiniu.com",
                            data: d,
                            dataType: "json",
                            processData: !1,
                            contentType: !1,
                            type: "POST",
                            headers: {
                                "X-File-Name": encodeURIComponent(a.name)
                            },
                            xhr: function() {
                                var a;
                                return a = $.ajaxSettings.xhr(), a && (a.upload.onprogress = function(a) {
                                    return function(b) {
                                        return a.progress(b)
                                    }
                                }(this)), a
                            },
                            progress: function() {
                                return function(c) {
                                    return c.lengthComputable ? b.trigger("uploadprogress", [a, c.loaded, c.total]) : void 0
                                }
                            }(this),
                            error: function() {
                                return function(c, d) {
                                    return b.trigger("uploaderror", [a, c, d])
                                }
                            }(this),
                            success: function() {
                                return function(c) {
                                    return b.trigger("uploadprogress", [a, a.size, a.size]), b.trigger("uploadsuccess", [a, c])
                                }
                            }(this),
                            complete: function() {
                                return function(c) {
                                    return b.trigger("uploadcomplete", [a, c.responseText])
                                }
                            }(this)
                        })
                    }
                })
            }, b.prototype.cancel = function(a) {
                var b, c, d, e;
                if (!a.id) for (e = this.files, c = 0, d = e.length; d > c; c++) if (b = e[c], b.id === 1 * a) {
                    a = b;
                    break
                }
                return this.trigger("uploadcancel", [a]), a.xhr && a.xhr.abort(), a.xhr = null
            }, b.prototype.readImageFile = function(a, b) {
                var c, d;
                if ($.isFunction(b)) return d = new Image, d.onload = function() {
                    return b(d)
                }, d.onerror = function() {
                    return b()
                }, window.FileReader && FileReader.prototype.readAsDataURL && /^image/.test(a.type) ? (c = new FileReader, c.onload = function(a) {
                    return d.src = a.target.result
                }, c.readAsDataURL(a)) : b()
            }, b.prototype.destroy = function() {
                var a, b, c, d;
                for (this.queue.length = 0, d = this.files, b = 0, c = d.length; c > b; b++) a = d[b], this.cancel(a);
                return $(window).off(".uploader-" + this.id), $(document).off(".uploader-" + this.id)
            }, b.i18n = {
                "zh-CN": {
                    leaveConfirm: "文件正在上传,确认退出?"
                }
            }, b.locale = "zh-CN", b
        }(SimpleModule), b = function(b) {
            return new a(b)
        }, window.simple = window.simple || {}, simple.uploader = b, window.get_crop = function(a) {
            var b, c, d, e, f, g, h;
            return d = a, e = 0, g = 0, h = 0, d && (f = d.w, c = d.h, f > c ? (e = c, g = (f - c) / 2, h = 0) : (e = f, g = 0, h = (c - f) / 2)), b = "" + e + "x" + e + "a" + g + "a" + h
        }
    }.call(this);

(function () {

    this.imageUploader = function (start,progress,success,error) {
        var uploader = null,
            err = !!error ? error :alert,
            maxSize = 1024*1024*2,
            self = {},
            prefix = 'http://qiniu.cdn-chuang.com/';

        uploader = simple.uploader({});

        uploader.on("beforeupload", function (e, file, r) {
            start(e, file, r);
        });

        uploader.on("uploadprogress", function (e, file, loaded, total) {
            progress(parseFloat(((loaded / total) * 100).toFixed(0)))
        });

        uploader.on("uploadsuccess", function (e, file, r) {
            r.hasOwnProperty('key') ? success(prefix+r.key,r.key) : err('上传失败');
        });

        uploader.on('uploaderror', function (e, file, xhr, status) {
            err('服务器错误');
        });

        return self =  {
            upload : function (files) {
                var size = 0;
                if(!!files && files[0]  && files[0].size)size = files[0].size;
                if(size > maxSize)return err('上传文件过大');
                uploader.upload(files);
                return self;
            },
            cancel : function () {
                uploader.cancel();
                return self;
            },
            maxSize:function (size) {
                maxSize = size*1024*1024;
                return self;
            }
        };
    }
}).call(this);