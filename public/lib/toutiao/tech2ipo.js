!
    function(e) {
        function t(i) {
            if (n[i]) return n[i].exports;
            var o = n[i] = {
                exports: {},
                id: i,
                loaded: !1
            };
            return e[i].call(o.exports, o, o.exports, t), o.loaded = !0, o.exports
        }
        var n = {};
        return t.m = e, t.c = n, t.p = "//s0.pstatp.com/site/reads-sdk/", t.h = "9af982803992f72d503a", t.s = {
            id: "5000095",
            style: ""
        }, t(0)
    }([function(e, t, n) {
        e.exports = n(13)
    }, function(e, t, n) {
        "use strict";
        Object.defineProperty(t, "__esModule", {
            value: !0
        });
        var i, o = n(2),
            r = "//partner.toutiao.com/user_mark/";
        try {
            var a = window.localStorage.getItem("tt_uid");
            i = a ? o.Promise.resolve(a) : (0, o.jsonp)(r).then(function(e) {
                if (e.user_id) return window.localStorage.setItem("tt_uid", e.user_id), e.user_id;
                throw "Login::ServerError"
            })["catch"](function() {
                return ""
            })
        } catch (l) {
            i = o.Promise.resolve("")
        }
        t["default"] = i
    }, function(e, t, n) {
        "use strict";

        function i(e, t) {
            if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
        }
        Object.defineProperty(t, "__esModule", {
            value: !0
        });
        var o = function() {
                function e(e, t) {
                    for (var n = 0; n < t.length; n++) {
                        var i = t[n];
                        i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
                    }
                }
                return function(t, n, i) {
                    return n && e(t.prototype, n), i && e(t, i), t
                }
            }(),
            r = t.os = {},
            a = t.path = {},
            l = t.browser = {},
            s = t.dom = {},
            c = t.event = {},
            d = t.Promise = window.Promise,
            u = t.Tapable = void 0,
            _ = t.noop = void 0,
            p = t.param = void 0,
            f = t.defer = void 0,
            h = t.jsonp = void 0,
            m = t.ajax = void 0,
            g = t.loadScript = void 0,
            v = t.formatDate = void 0,
            y = t.callApp = void 0,
            b = Array.prototype.slice,
            w = Array.isArray ||
                function(e) {
                    return "[object Array]" === Object.prototype.toString.call(e)
                };
        t.Tapable = u = function() {
            function e() {
                i(this, e), this._plugins = {}
            }
            return o(e, [{
                key: "applyPlugins",
                value: function(e) {
                    var t = this,
                        n = arguments,
                        i = this._plugins[e];
                    i && i.forEach(function(e) {
                        return e.apply(t, b.call(n, 1))
                    })
                }
            }, {
                key: "applyPluginsBailResult",
                value: function(e) {
                    var t, n = this,
                        i = arguments,
                        o = this._plugins[e];
                    return o && o.forEach(function(e) {
                        return t = e.apply(n, b.call(i, 1))
                    }), t
                }
            }, {
                key: "applyPluginsWaterfall",
                value: function(e, t) {
                    var n = this,
                        i = arguments,
                        o = this._plugins[e],
                        r = t;
                    return o && o.forEach(function(e) {
                        r = e.apply(n, [r].concat(b.call(i, 1)))
                    }), r
                }
            }, {
                key: "plugin",
                value: function(e, t) {
                    var n = this._plugins[e] || (this._plugins[e] = []);
                    this._plugins[e] = n.concat(t)
                }
            }]), e
        }();
        var x = function() {
                function e(t) {
                    function n(t) {
                        if (s) {
                            var n = s;
                            l = a === t ? new j("resolved by self") : t instanceof e || t instanceof k || t instanceof j ? t : new k(t), s = void 0, setTimeout(function() {
                                if (!a._thenish && l instanceof j) throw "(in promise): " + l.toString();
                                for (; n.length;) n.shift()(l)
                            }, 0)
                        }
                    }
                    function o(e) {
                        n(new j(e))
                    }
                    function r() {
                        for (var e = arguments.length, t = Array(e), n = 0; e > n; n++) t[n] = arguments[n];
                        var i = function(e) {
                            e._when.apply(e, t)
                        };
                        s ? s.push(i) : setTimeout(function() {
                            return i(l)
                        }, 0)
                    }
                    i(this, e);
                    var a = this,
                        l = null,
                        s = [];
                    this._when = r, t(n, o)
                }
                return o(e, [{
                    key: "then",
                    value: function(t, n) {
                        var i = this;
                        return this._thenish = !0, new e(function(e, o) {
                            return i._when(e, o, t, n)
                        })
                    }
                }, {
                    key: "catch",
                    value: function(e) {
                        return this.then(void 0, e)
                    }
                }], [{
                    key: "reject",
                    value: function(t) {
                        return new e(function(e, n) {
                            return n(t)
                        })
                    }
                }, {
                    key: "resolve",
                    value: function(t) {
                        return new e(function(e) {
                            return e(t)
                        })
                    }
                }]), e
            }(),
            k = function F(e) {
                i(this, F), this._when = function(t, n, i) {
                    try {
                        t("function" == typeof i ? i(e) : e)
                    } catch (o) {
                        n(o)
                    }
                }
            },
            j = function() {
                function e(t) {
                    i(this, e), this._when = function(e, n, i, o) {
                        try {
                            if (!o || "function" != typeof o) throw t;
                            e(o(t))
                        } catch (r) {
                            n(r)
                        }
                    }, this._value = t
                }
                return o(e, [{
                    key: "toString",
                    value: function() {
                        return this._value.toString()
                    }
                }]), e
            }();
        d && d.resolve || (t.Promise = d = x);
        var P = navigator.userAgent,
            A = P.match(/(Android);?[\s\/]+([\d.]+)?/),
            E = P.match(/(iPad).*OS\s([\d_]+)/),
            q = !E && P.match(/(iPhone\sOS)\s([\d_]+)/),
            C = P.match(/Chrome\/([\d.]+)/) || P.match(/CriOS\/([\d.]+)/),
            S = !C && P.match(/(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/),
            T = S || P.match(/Version\/([\d.]+)([^S](Safari)|[^M]*(Mobile)[^S]*(Safari))/);
        A && (r.android = !0, r.version = A[2]), q && (r.ios = r.iphone = !0, r.version = q[2].replace(/_/g, ".")), E && (r.ios = r.ipad = !0, r.version = E[2].replace(/_/g, ".")), r.version = parseFloat(r.version), l.ucbrowser = !! P.match(/ucbrowser/gi), l.toutiao = "http://nativeapp.toutiao.com" == document.referrer || /(News|NewsSocial|Explore|NewsArticle)( |\/)(\d.\d.\d)/i.test(P), l.toutiaoSDK = /(ArticleStreamSdk)( |\/)(\d+)/i.test(P), l.qqbrowser = !! P.match(/qqbrowser/gi), l.weixin = !! P.match(/MicroMessenger/i), l.safari = T;
        var O = function() {
            return b.call(arguments).join("/").replace(/\/{1,}/g, "/")
        };
        a.join = function() {
            var e = arguments[0],
                t = /^.*?:?\/\//,
                n = t.exec(e),
                i = n ? n[0] : "",
                o = [e.replace(t, "")].concat(b.call(arguments, 1));
            return i + O.apply(null, o)
        };
        var D = document.createElement("div"),
            M = function K(e, t) {
                var K = e.matches || e.webkitMatches || e.msMatches || e.webkitMatchesSelector || e.matchesSelector;
                if (K) return K.call(e, t);
                var n, i = e.parentNode,
                    o = !i;
                return o && (i = D).appendChild(e), n = i.querySelectorAll(t).indexOf(e), o && D.removeChild(e), n
            };
        s.closest = function(e, t, n) {
            for (var i = e; i && !M(i, t);) i = i !== n && i.nodeType !== i.DOCUMENT_NODE && i.parentNode;
            return i
        };
        var z = function() {
                return !0
            },
            R = function() {
                return !1
            },
            Z = /^([A-Z]|returnValue$|layer[XY]$)/,
            B = {
                preventDefault: "isDefaultPrevented",
                stopImmediatePropagation: "isImmediatePropagationStopped",
                stopPropagation: "isPropagationStopped"
            },
            X = function(e, t) {
                var n = arguments;
                return !t && e.isDefaultPrevented || (t = t || e, Object.keys(B).map(function(i) {
                    var o = t[i];
                    e[i] = function() {
                        return e[B[i]] = z, o && o.apply(t, n)
                    }, e[B[i]] = R
                }), (void 0 !== t.defaultPrevented ? t.defaultPrevented : "returnValue" in t ? t.returnValue === !1 : t.getPreventDefault && t.getPreventDefault()) && (e.isDefaultPrevented = z)), e
            },
            I = function(e) {
                var t = {
                    originalEvent: e
                };
                return Object.keys(e).filter(function(e) {
                    return !Z.test(e)
                }).forEach(function(n) {
                    t[n] = e[n]
                }), X(t, e)
            },
            H = 1,
            G = function(e) {
                return e.__eid || (e.__eid = H++)
            },
            L = {};
        c.add = function(e, t, n, i, o, r) {
            var a, l, d = this,
                u = arguments;
            return e = w(e) ? e : [e], t = t.split(/\s/), "function" == typeof n && (o = n, r = !! i, n = "", i = void 0), "function" == typeof i && (o = i, i = void 0), e.forEach(function(e) {
                var _ = G(e),
                    p = L[_] || (L[_] = []);
                r && (a = function(t) {
                    return c.remove(e, t.type, o), o.apply(d, u)
                }), n && (l = function(t) {
                    var i = s.closest(t.target, n, e);
                    if (i) {
                        var r = I(t),
                            l = b.call(u, 1);
                        return r.currentTarget = i, r.liveFired = e, (a || o).apply(i, [r].concat(l))
                    }
                }), t.forEach(function(t) {
                    var r = {
                        e: t,
                        i: p.length,
                        slct: n,
                        fn: o
                    };
                    r.proxy = function(t) {
                        if (t = X(t), !t.isImmediatePropagationStopped()) {
                            t.data = i;
                            var n = (l || a || o).apply(e, [t]);
                            return n === !1 && (t.preventDefault(), t.stopPropagation()), n
                        }
                    }, p.push(r), e.addEventListener(t, r.proxy, l || a)
                })
            }), function() {
                c.remove(e, t.join(" "), n, o)
            }
        }, c.remove = function(e, t, n, i) {
            e = w(e) ? e : [e], t = t.split(/\s/), "function" == typeof n && (i = n, n = ""), e.forEach(function(e) {
                var o = G(e),
                    r = L[o] || [];
                t.forEach(function(t) {
                    var o = function(e) {
                        return e && t === e.e && (i ? G(i) === G(e.fn) : !0) && (n && e.slct ? n === e.slct : !0)
                    };
                    r.filter(function(e) {
                        return o(e)
                    }).forEach(function(n) {
                        delete r[n.i], e.removeEventListener(t, n.proxy)
                    })
                })
            })
        }, t.noop = _ = function() {}, t.param = p = function(e) {
            return Object.keys(e).map(function(t) {
                return t + "=" + encodeURIComponent(e[t])
            }).join("&")
        }, t.defer = f = function() {
            var e = {},
                t = !1;
            return e.promise = new d(function(n, i) {
                e.resolve = function(i) {
                    return t ? d.resolve(i) : (t = !0, n(i), e.promise)
                }, e.reject = function(n) {
                    return t ? d.reject(n) : (t = !0, i(n), e.promise)
                }
            }), e.promise._defered = e, e
        };
        var W = "callback",
            J = "__jsonp__",
            N = 3e4;
        t.jsonp = h = function(e) {
            var t = arguments.length <= 1 || void 0 === arguments[1] ? {} : arguments[1],
                n = document.createElement("script"),
                i = J + Math.random().toString(36).substring(2, 12),
                o = f(),
                r = function() {
                    delete window[i], n.onerror = "", n.parentElement.removeChild(n)
                },
                a = setTimeout(function() {
                    o.reject("jsonp timeout")
                }, N);
            return n.onerror = function() {
                clearTimeout(a), o.reject("net error")
            }, window[i] = function(e) {
                clearTimeout(a), o.resolve(e)
            }, t[W] = i, document.head.appendChild(n), n.src = e + "?" + p(t), n.charset = "utf-8", n.async = !0, o.promise.then(r, r), o.promise
        }, t.ajax = m = function(e, t) {
            var n = f();
            try {
                var i;
                !
                    function() {
                        var o = new XMLHttpRequest;
                        o.open("GET", e + (t ? "?" + p(t) : ""), !0), o.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8"), o.send(), i = null;
                        var r = function() {
                                if (4 === o.readyState) if (i && clearTimeout(i), o.status >= 200 && o.status < 300 || 304 === o.status) try {
                                    n.resolve(JSON.parse(o.responseText))
                                } catch (e) {
                                    n.reject("data error")
                                } else n.reject(o.status)
                            },
                            a = function() {
                                o.onreadystatechange = _, 4 !== o.readyState && o.abort()
                            };
                        4 === o.readyState ? r() : (o.onreadystatechange = r, i = setTimeout(function() {
                            n.reject("ajax timeout")
                        }, N), n.promise.then(a, a))
                    }()
            } catch (o) {
                n.reject(o)
            }
            return n.promise
        }, t.loadScript = g = function(e) {
            var t = f(),
                n = document.createElement("script");
            return n.onload = function() {
                t.resolve(), n.onload = n.onerror = ""
            }, n.onerror = function() {
                t.reject("( load script ) net error")
            }, n.src = e, n.charset = "utf-8", n.async = !0, n.crossOrigin = "anonymous", document.head.appendChild(n), t.promise
        }, t.formatDate = v = function(e) {
            var t = arguments.length <= 1 || void 0 === arguments[1] ? "yyyy-MM-dd hh:mm:ss" : arguments[1],
                n = (new Date).getTime(),
                i = n - e,
                o = new Date(e),
                r = {};
            return r.h = o.getHours(), r.m = o.getMinutes(), r.s = o.getSeconds(), r.Y = o.getFullYear(), r.M = o.getMonth() + 1, r.D = o.getDate(), r.hh = (r.h < 10 ? "0" : "") + r.h, r.mm = (r.m < 10 ? "0" : "") + r.m, r.ss = (r.s < 10 ? "0" : "") + r.s, r.yyyy = r.Y, r.MM = (r.M < 10 ? "0" : "") + r.M, r.dd = (r.D < 10 ? "0" : "") + r.D, i > 31536e6 ? r.b = Math.floor(i / 365 / 24 / 60 / 60 / 1e3) + "年前" : i > 2592e6 ? r.b = Math.floor(i / 30 / 24 / 60 / 60 / 1e3) + "个月前" : i > 864e5 ? r.b = Math.floor(i / 24 / 60 / 60 / 1e3) + "天前" : r.b = r.hh + ":" + r.mm, i > 864e5 ? r.bb = r.yyyy + "-" + r.MM + "-" + r.dd : i > 36e5 ? r.bb = Math.floor(i / 60 / 60 / 1e3) + "小时前" : r.bb = "刚刚", Object.keys(r).forEach(function(e) {
                t.match(new RegExp("(" + e + ")\\1")) || (t = t.replace(new RegExp(e, "g"), r[e]))
            }), t
        }, t.callApp = y = function(e) {
            var t, i, o, a, s = arguments.length <= 1 || void 0 === arguments[1] ? _ : arguments[1];
            if ((l.weixin || l.qqbrowser) && r.ios && r.version >= 9) return void s(1);
            if (o = "snssdk14" + (r.ios ? "1" : "3") + "://detail?groupid=" + e + "&gd_label=click_wap_rc_jssdk_" + n.s.id, r.ios && r.version >= 9 && !l.ucbrowser) a = location.href, location.href = "//toutiao.com/m/detail/?group_id=" + e + "&item_id=&scheme=" + encodeURIComponent(o);
            else {
                var c = document.createElement("iframe");
                c.style.display = "none", c.src = o, document.body.appendChild(c)
            }
            t = +new Date, i = l.safari ? 4e3 : 2e3, setTimeout(function() {
                var e = document.visibilityState || document.webkitVisibilityState || "unknown";
                "hidden" === e || new Date - t > i + 200 ? (a && location.href.replace(a), s(0)) : s(1)
            }, i)
        }
    }, function(e, t) {
        e.exports = function() {
            var e = [];
            return e.toString = function() {
                for (var e = [], t = 0; t < this.length; t++) {
                    var n = this[t];
                    n[2] ? e.push("@media " + n[2] + "{" + n[1] + "}") : e.push(n[1])
                }
                return e.join("")
            }, e.i = function(t, n) {
                "string" == typeof t && (t = [
                    [null, t, ""]
                ]);
                for (var i = {}, o = 0; o < this.length; o++) {
                    var r = this[o][0];
                    "number" == typeof r && (i[r] = !0)
                }
                for (o = 0; o < t.length; o++) {
                    var a = t[o];
                    "number" == typeof a[0] && i[a[0]] || (n && !a[2] ? a[2] = n : n && (a[2] = "(" + a[2] + ") and (" + n + ")"), e.push(a))
                }
            }, e
        }
    }, function(e, t, n) {
        "use strict";

        function i(e) {
            return e && e.__esModule ? e : {
                "default": e
            }
        }
        function o(e) {
            if (Array.isArray(e)) {
                for (var t = 0, n = Array(e.length); t < e.length; t++) n[t] = e[t];
                return n
            }
            return Array.from(e)
        }
        function r() {
            for (var e = arguments.length, t = Array(e), n = 0; e > n; n++) t[n] = arguments[n];
            c || (c = u["default"].then(function(e) {
                return e && e.substr(e.length - 2) % 100 < 2 ? !0 : void 0
            })), c.then(function(e) {
                return e && a.apply(void 0, t)
            })
        }
        function a() {
            for (var e = arguments.length, t = Array(e), n = 0; e > n; n++) t[n] = arguments[n];
            t.unshift("send"), s || (s = document.createElement("iframe"), s.src = "javascript:;", s.style.display = "none", s.onload = function() {
                for (s.contentDocument.write(g), m.push = function(e) {
                    var t;
                    return (t = s.contentWindow).ga.apply(t, o(e))
                }; m.length;) m.push(m.shift())
            }, document.body.appendChild(s)), m.push(t)
        }
        function l(e) {
            e.filename && e.filename === l.__src__ && a("exception", {
                exDescription: "[" + e.lineno + ":" + e.colno + "]" + e.error,
                exFatal: !1
            })
        }
        Object.defineProperty(t, "__esModule", {
            value: !0
        }), t["default"] = r;
        var s, c, d = n(1),
            u = i(d),
            _ = JSON.stringify("UA-28423340-33"),
            p = JSON.stringify(n.s.id),
            f = JSON.stringify(window.innerWidth + "x" + window.innerHeight),
            h = JSON.stringify(document.referrer),
            m = [],
            g = "<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js?v=2','ga');ga('create'," + _ + ",'auto');ga('set','dimension1'," + p + ");ga('set','viewportSize'," + f + ");ga('set','referrer'," + h + ");</script>";
        window.__err_toutiao__ && window.removeEventListener("error", window.__err_toutiao__), window.__err_toutiao__ = l, l.__src__ = (document.currentScript || {}).src, window.addEventListener("error", l)
    }, function(e, t) {
        e.exports = function(e) {
            var t = "";
            if (__lcls__ = e.locals || {}, e && e.length) {
                styles = e.styles || {}, t += '<div id="' + (__lcls__.reset ? __lcls__.reset : "reset") + '">', styles.noHeader || (t += '<div class="' + (__lcls__.header ? __lcls__.header : "header") + '" style="', styles.mainColor && (t += "border-color:" + styles.mainColor + ";"), t += '">相关推荐</div>'), t += '<div class="' + (__lcls__.section ? __lcls__.section : "section") + '"> ';
                var n = e;
                if (n) for (var i, o = -1, r = n.length - 1; r > o;) {
                    if (i = n[o += 1], t += ' <a href="' + i.url + '" class="' + (__lcls__.item ? __lcls__.item : "item") + '" data-id="' + i.id + '"> ', 3 == i.imgs.length) {
                        t += ' <div class="' + (__lcls__.title ? __lcls__.title : "title") + '">' + i.title + '</div> <div class="' + (__lcls__.pics ? __lcls__.pics : "pics") + '"> <ul class="' + (__lcls__.gallery ? __lcls__.gallery : "gallery") + '"> ';
                        var a = i.imgs;
                        if (a) for (var l, s = -1, c = a.length - 1; c > s;) l = a[s += 1], t += ' <li class="' + (__lcls__.list ? __lcls__.list : "list") + '"> <div class="' + (__lcls__.dumb ? __lcls__.dumb : "dumb") + '"></div> <div class="' + (__lcls__["pic-wrap"] ? __lcls__["pic-wrap"] : "pic-wrap") + '" style="background-image:url(' + l + ');"></div> <div class="' + (__lcls__.spinner ? __lcls__.spinner : "spinner") + '" style="', styles.mainColor && (t += "color:" + styles.mainColor + ";"), t += '"> </div> </li> ';
                        t += ' </ul> </div> <div class="' + (__lcls__.info ? __lcls__.info : "info") + '"> ', 1 == i.type && (t += ' <span class="' + (__lcls__["icon-app"] ? __lcls__["icon-app"] : "icon-app") + '" style="', styles.mainColor && (t += "color:" + styles.mainColor + ";"), t += '">打开头条阅读</span> '), t += ' <span class="' + (__lcls__.date ? __lcls__.date : "date") + '" data-stamp="' + i.timeStamp + '">' + i.date + "</span> </div> "
                    } else t += " ", t += i.imgs.length ? ' <div class="' + (__lcls__.desc ? __lcls__.desc : "desc") + '"> ' : ' <div class="' + (__lcls__.desc ? __lcls__.desc : "desc") + " " + (__lcls__.only ? __lcls__.only : "only") + '"> ', t += ' <div class="' + (__lcls__.title ? __lcls__.title : "title") + '">' + i.title + '</div> <div class="' + (__lcls__.info ? __lcls__.info : "info") + '"> ', 1 == i.type && (t += ' <span class="' + (__lcls__["icon-app"] ? __lcls__["icon-app"] : "icon-app") + '" style="', styles.mainColor && (t += "color:" + styles.mainColor + ";"), t += '">打开头条阅读</span> '), t += ' <span class="' + (__lcls__.date ? __lcls__.date : "date") + '">' + i.date + "</span> </div> </div> ", i.imgs.length && (t += ' <div class="' + (__lcls__["pic-right"] ? __lcls__["pic-right"] : "pic-right") + '"> <div class="' + (__lcls__.dumb ? __lcls__.dumb : "dumb") + '"></div> <div class="' + (__lcls__["pic-wrap"] ? __lcls__["pic-wrap"] : "pic-wrap") + '" style="background-image:url(' + i.imgs[0] + ');"> ', i.hasVideo && (t += ' <div class="' + (__lcls__["vid-info"] ? __lcls__["vid-info"] : "vid-info") + '"><span>' + i.videoDuration + "</span></div> "), t += ' </div> <div class="' + (__lcls__.spinner ? __lcls__.spinner : "spinner") + '" style="', styles.mainColor && (t += "color:" + styles.mainColor + ";"), t += '"> </div> </div> '), t += " ";
                    t += " </a> "
                }
                t += "</div></div>"
            }
            return t
        }
    }, function(e, t) {
        e.exports = function(e) {
            var t = "";
            if (__lcls__ = e.locals || {}, e && e.length) {
                styles = e.styles || {}, t += '<div id="' + (__lcls__.reset ? __lcls__.reset : "reset") + '">', styles.noHeader || (t += '<div class="' + (__lcls__.header ? __lcls__.header : "header") + '" style="', styles.mainColor && (t += "border-color:" + styles.mainColor + ";"), t += '">相关推荐</div>'), t += '<div class="' + (__lcls__.section ? __lcls__.section : "section") + '"> ';
                var n = e;
                if (n) for (var i, o = -1, r = n.length - 1; r > o;) i = n[o += 1], t += ' <a href="' + i.url + '" class="' + (__lcls__.item ? __lcls__.item : "item") + '" data-id="' + i.id + '"> ', i.imgs.length && (t += ' <div class="' + (__lcls__.pic ? __lcls__.pic : "pic") + '"> <div class="' + (__lcls__.dumb ? __lcls__.dumb : "dumb") + '"></div> <div class="' + (__lcls__["pic-wrap"] ? __lcls__["pic-wrap"] : "pic-wrap") + '" style="background-image:url(' + i.imgs[0] + ');"></div> <div class="' + (__lcls__.spinner ? __lcls__.spinner : "spinner") + '" style="', styles.mainColor && (t += "color:" + styles.mainColor + ";"), t += '"></div> </div> '), t += ' <div class="' + (__lcls__.title ? __lcls__.title : "title") + '">' + i.title + '</div> <div class="' + (__lcls__.info ? __lcls__.info : "info") + '"> ', 1 == i.type && (t += ' <span class="' + (__lcls__["icon-app"] ? __lcls__["icon-app"] : "icon-app") + '" style="', styles.mainColor && (t += "color:" + styles.mainColor + ";"), t += '">打开头条阅读</span> '), t += ' <span class="' + (__lcls__.date ? __lcls__.date : "date") + '">' + i.date + "</span> </div> </a> ";
                t += "</div></div>"
            }
            return t
        }
    }, function(e, t) {
        e.exports = function(e) {
            var t = "";
            if (__lcls__ = e.locals || {}, e && e.length) {
                styles = e.styles || {}, t += '<div id="' + (__lcls__.reset ? __lcls__.reset : "reset") + '"><svg style="position: absolute; width: 0; height: 0;" width="0" height="0" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><symbol id="' + (__lcls__["icon-schedule"] ? __lcls__["icon-schedule"] : "icon-schedule") + '" viewBox="-4 4 48 48"><title>schedule</title><path d="M25.031 13.969v10.5l9 5.344-1.5 2.531-10.5-6.375v-12h3zM24 40.031q6.563 0 11.297-4.734t4.734-11.297-4.734-11.297-11.297-4.734-11.297 4.734-4.734 11.297 4.734 11.297 11.297 4.734zM24 4.031q8.25 0 14.109 5.859t5.859 14.109-5.859 14.109-14.109 5.859-14.109-5.859-5.859-14.109 5.859-14.109 14.109-5.859z"></path></symbol></defs></svg>', styles.noHeader || (t += '<div class="' + (__lcls__.header ? __lcls__.header : "header") + '" style="', styles.mainColor && (t += "background-color:" + styles.mainColor + ";"), t += '">相关推荐</div>'), t += '<div class="' + (__lcls__.section ? __lcls__.section : "section") + '"> ';
                var n = e;
                if (n) for (var i, o = -1, r = n.length - 1; r > o;) i = n[o += 1], t += ' <a href="' + i.url + '" class="' + (__lcls__.item ? __lcls__.item : "item") + '" data-id="' + i.id + '"> ', t += 1 == i.type ? ' <span class="' + (__lcls__.title ? __lcls__.title : "title") + " " + (__lcls__.ad ? __lcls__.ad : "ad") + '"> ' : ' <span class="' + (__lcls__.title ? __lcls__.title : "title") + '"> ', t += ' <strong class="' + (__lcls__["icon-app"] ? __lcls__["icon-app"] : "icon-app") + '" style="', styles.mainColor && (t += "color:" + styles.mainColor + ";"), t += '">热</strong> ' + i.title + ' </span> <span class="' + (__lcls__.date ? __lcls__.date : "date") + '"> <svg class="' + (__lcls__["icon-date"] ? __lcls__["icon-date"] : "icon-date") + '"><use xlink:href="#icon-schedule"></use></svg> ' + i.date + " </span> </a> ";
                t += "</div></div>"
            }
            return t
        }
    }, function(e, t, n) {
        function i(e) {
            var t = e.toString(),
                n = document.createElement("style");
            return n.type = "text/css", document.head.appendChild(n), n.styleSheet ? n.styleSheet.cssText = t : n.appendChild(document.createTextNode(t)), function() {
                document.head.removeChild(n)
            }
        }
        function o(e, t) {
            var n = e.locals || {},
                i = e.length;
            return Object.keys(n).forEach(function(e) {
                t = t.replace(new RegExp(e, "g"), n[e])
            }), e.push([null, t, ""]), i
        }
        var r = n(15);
        "string" == typeof r && (r = [
            [e.id, r, ""]
        ]);
        var a, l = 0;
        e.exports.use = function() {
            l++ || (a = i(r))
        }, e.exports.unuse = function() {
            --l || (a(), a = null)
        }, e.exports.append = function(e) {
            return o(r, e)
        }, e.exports.remove = function(e) {
            delete r[e]
        }, e.exports.locals = r.locals
    }, function(e, t, n) {
        function i(e) {
            var t = e.toString(),
                n = document.createElement("style");
            return n.type = "text/css", document.head.appendChild(n), n.styleSheet ? n.styleSheet.cssText = t : n.appendChild(document.createTextNode(t)), function() {
                document.head.removeChild(n)
            }
        }
        function o(e, t) {
            var n = e.locals || {},
                i = e.length;
            return Object.keys(n).forEach(function(e) {
                t = t.replace(new RegExp(e, "g"), n[e])
            }), e.push([null, t, ""]), i
        }
        var r = n(16);
        "string" == typeof r && (r = [
            [e.id, r, ""]
        ]);
        var a, l = 0;
        e.exports.use = function() {
            l++ || (a = i(r))
        }, e.exports.unuse = function() {
            --l || (a(), a = null)
        }, e.exports.append = function(e) {
            return o(r, e)
        }, e.exports.remove = function(e) {
            delete r[e]
        }, e.exports.locals = r.locals
    }, function(e, t, n) {
        function i(e) {
            var t = e.toString(),
                n = document.createElement("style");
            return n.type = "text/css", document.head.appendChild(n), n.styleSheet ? n.styleSheet.cssText = t : n.appendChild(document.createTextNode(t)), function() {
                document.head.removeChild(n)
            }
        }
        function o(e, t) {
            var n = e.locals || {},
                i = e.length;
            return Object.keys(n).forEach(function(e) {
                t = t.replace(new RegExp(e, "g"), n[e])
            }), e.push([null, t, ""]), i
        }
        var r = n(17);
        "string" == typeof r && (r = [
            [e.id, r, ""]
        ]);
        var a, l = 0;
        e.exports.use = function() {
            l++ || (a = i(r))
        }, e.exports.unuse = function() {
            --l || (a(), a = null)
        }, e.exports.append = function(e) {
            return o(r, e)
        }, e.exports.remove = function(e) {
            delete r[e]
        }, e.exports.locals = r.locals
    }, function(e, t, n) {
        "use strict";

        function i(e) {
            if (e && e.__esModule) return e;
            var t = {};
            if (null != e) for (var n in e) Object.prototype.hasOwnProperty.call(e, n) && (t[n] = e[n]);
            return t["default"] = e, t
        }
        function o(e) {
            return e && e.__esModule ? e : {
                "default": e
            }
        }
        function r(e, t) {
            if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
        }
        function a(e, t) {
            if (!e) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            return !t || "object" != typeof t && "function" != typeof t ? e : t
        }
        function l(e, t) {
            if ("function" != typeof t && null !== t) throw new TypeError("Super expression must either be null or a function, not " + typeof t);
            e.prototype = Object.create(t && t.prototype, {
                constructor: {
                    value: e,
                    enumerable: !1,
                    writable: !0,
                    configurable: !0
                }
            }), t && (Object.setPrototypeOf ? Object.setPrototypeOf(e, t) : e.__proto__ = t)
        }
        Object.defineProperty(t, "__esModule", {
            value: !0
        });
        var s = function() {
                function e(e, t) {
                    for (var n = 0; n < t.length; n++) {
                        var i = t[n];
                        i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
                    }
                }
                return function(t, n, i) {
                    return n && e(t.prototype, n), i && e(t, i), t
                }
            }(),
            c = n(1),
            d = o(c),
            u = n(4),
            _ = o(u),
            p = n(12),
            f = o(p),
            h = n(14),
            m = o(h),
            g = n(2),
            v = i(g),
            y = function(e) {
                function t(e) {
                    var n = e.id,
                        i = void 0 === n ? "" : n,
                        o = e.imgType,
                        l = void 0 === o ? "medium_image" : o,
                        s = e.dateType,
                        c = void 0 === s ? "b" : s,
                        u = e.theme,
                        p = void 0 === u ? "default" : u,
                        h = e.mainColor,
                        g = void 0 === h ? void 0 : h,
                        v = e.noHeader,
                        y = void 0 === v ? !1 : v,
                        b = e.api,
                        w = void 0 === b ? "//partner.toutiao.com/partner_recommend/1/" : b,
                        x = e.num,
                        k = void 0 === x ? 10 : x,
                        j = e.data_format,
                        P = void 0 === j ? "jsonp" : j,
                        A = e.openAd,
                        E = void 0 === A ? !1 : A,
                        q = e.plugins,
                        C = void 0 === q ? null : q;
                    r(this, t);
                    var S = a(this, Object.getPrototypeOf(t).call(this));
                    return S.el = document.getElementById(i) || document.body, S.theme = p && m["default"][p], S.mainColor = g, S.noHeader = y, S.imgType = l, S.dateType = c, S.api = w, S.args = {
                        num: k,
                        data_format: P
                    }, S.openAd = E, C && Object.keys(C).forEach(function(e) {
                        return S.plugin(e, C[e])
                    }), (0, _["default"])("event", "Work", "Fetch"), d["default"].then(function(e) {
                        return S.fetch(e).then(S.render.bind(S))
                    }), (0, f["default"])(S), S
                }
                return l(t, e), s(t, [{
                    key: "fetch",
                    value: function() {
                        var e = this,
                            t = arguments.length <= 0 || void 0 === arguments[0] ? "" : arguments[0],
                            i = this.applyPluginsBailResult("presetData");
                        if (void 0 !== i) try {
                            return this.setCache(i), v.Promise.resolve(this.getCache())
                        } catch (o) {
                            return (0, _["default"])("event", "Error", "PresetError"), v.Promise.reject("Wrong preset")
                        }
                        var r = this.api,
                            a = this.args,
                            l = a.data_format,
                            s = this.applyPluginsWaterfall("fetchParam", a);
                        if ("ajax" !== l && "jsonp" !== l) return v.Promise.reject("Wrong data format");
                        if ("ajax" === l && /^(.+?:)?\/\/.*/.test(r)) return v.Promise.reject("Wrong api");
                        s.url = s.url || location.href, s.title = s.title || document.title, s.site_id = n.s.id, s.timestamp = Math.floor((new Date).getTime() / 1e3), s.context_info = JSON.stringify({
                            user_agent: window.navigator.userAgent
                        }), s.user_id = t, this._request = v[l](r, s);
                        var c = new Image;
                        return c.src = "//partner.toutiao.com/stats/" + n.s.id + "/?type=fetch", this._request["catch"](function() {
                            (0, _["default"])("event", "Error", "RequestError")
                        }), this._request.then(function(t) {
                            if (delete e._request, t.success === !0) return e.setCache(t.data), e.getCache();
                            throw "Core::RemoteError:" + t.msg
                        })
                    }
                }, {
                    key: "setCache",
                    value: function(e) {
                        var t = this.imgType,
                            n = this.dateType;
                        this._cache = e.map(function(e) {
                            return {
                                id: "c" + Math.random().toString(36).substring(2, 8),
                                url: e.url,
                                title: e.title,
                                type: e.type,
                                source: e.source,
                                imgs: e.image_list.slice(0, 3).map(function(e) {
                                    return e[t]
                                }),
                                timeStamp: 1e3 * e.publish_time,
                                date: v.formatDate(1e3 * e.publish_time, n),
                                hasVideo: e.has_video,
                                videoDuration: e.video_duraion
                            }
                        })
                    }
                }, {
                    key: "getCache",
                    value: function(e) {
                        return e ? this._cache.filter(function(t) {
                            return t.id === e
                        })[0] : this._cache
                    }
                }, {
                    key: "render",
                    value: function() {
                        function e(e) {
                            var t = e.currentTarget,
                                n = s.getCache(t.getAttribute("data-id")),
                                i = 1 == n.type,
                                o = i && "true" === t.getAttribute("blocked");
                            if ((0, _["default"])("event", "Click", i ? "Ad" : "Ar"), o) return !1;
                            if (!i || !l) return s.applyPluginsBailResult(i ? "adClick" : "artClick", t);
                            var r, a;
                            try {
                                a = n.url, r = /(\?|&)group_id=(\d+?)(&|$)/.exec(a)[2]
                            } catch (e) {
                                return void(0, _["default"])("event", "Error", "GroupidError")
                            }
                            return t.setAttribute("blocked", "true"), v.callApp(r, function(e) {
                                t.removeAttribute("blocked");
                                var n = s.applyPluginsBailResult("adClick", t);
                                e && n !== !1 && (location.href = a)
                            }), !1
                        }
                        var n = arguments.length <= 0 || void 0 === arguments[0] ? [] : arguments[0];
                        (0, _["default"])("event", "Work", "Render");
                        var i = this.theme,
                            o = this.mainColor,
                            r = this.noHeader,
                            a = this.el,
                            l = this.openAd,
                            s = this,
                            c = a.offsetWidth * a.offsetHeight;
                        if (n = n.filter(function(e) {
                                return 1 != e.type || !v.browser.toutiao
                            }), i === !1) this.applyPlugins("render", n);
                        else {
                            n = this.applyPluginsWaterfall("prepareData", n), n.styles = {
                                mainColor: o,
                                noHeader: r
                            };
                            try {
                                var d = i.tpl(n)
                            } catch (u) {
                                return (0, _["default"])("event", "Error", "TempletError"), !1
                            }
                            this.applyPlugins("prepareStyle", i.style), i.style.use(), a.innerHTML = d
                        }
                        this._removeListener && this._removeListener(), this._removeListener = v.event.add(a, "click", "a", e), (0, _["default"])("event", "Work", "Done"), this.applyPlugins("done"), setTimeout(function() {
                            a.offsetWidth * a.offsetHeight - c > 100 && t._stats(s)
                        }, 1e3)
                    }
                }, {
                    key: "destroy",
                    value: function() {
                        for (; this.el.firstChild;) this.el.removeChild(this.el.firstChild);
                        this._request && (this._request._defered.reject("Component destroyed"), delete this._request), this._removeListener && (this._removeListener(), delete this._removeListener)
                    }
                }], [{
                    key: "_stats",
                    value: function(e) {
                        var t = new Image;
                        t.src = "//partner.toutiao.com/stats/" + n.s.id + "/?type=pv";
                        var i, o, r = e.el,
                            a = [].slice.call(r.querySelectorAll("[data-id]")),
                            l = a[0],
                            s = /(\?|&)req_id=(\d+?)(&|$)/.exec(e.getCache()[0].url)[2],
                            c = a.map(function(t) {
                                try {
                                    var n = e.getCache(t.getAttribute("data-id")),
                                        i = n.url,
                                        o = n.type;
                                    return {
                                        item_id: /partner_article\/(\d+)\//.exec(i)[1],
                                        item_type: o
                                    }
                                } catch (r) {
                                    return null
                                }
                            }).filter(function(e) {
                                return null !== e
                            }),
                            d = function u() {
                                clearTimeout(i), i = setTimeout(function() {
                                    var e = new Image,
                                        t = encodeURIComponent(JSON.stringify(c));
                                    e.src = "//partner.toutiao.com/stats/" + n.s.id + "/?type=impression&req_id=" + s + "&item_info=" + t;
                                    o = !0, v.event.remove(window, "scroll", u);
                                }, 200)
                            };
                        v.event.add(window, "scroll", d), d()
                    }
                }, {
                    key: "find",
                    value: function(e) {
                        for (var n = 0, i = t._pools.length; i > n; n++) if (t._pools[n].el === e) return t._pools[n];
                        return null
                    }
                }, {
                    key: "remove",
                    value: function(e) {
                        var n = t.find(e);
                        return n ? (n.destroy(), t._pools.splice(t._pools.indexOf(n), 1), !0) : !1
                    }
                }, {
                    key: "run",
                    value: function(e) {
                        try {
                            t._pools.push(new t(e))
                        } catch (n) {
                            (0, _["default"])("event", "Error", "InitError")
                        }
                    }
                }]), t
            }(v.Tapable);
        y._pools = [], t["default"] = y
    }, function(e, t, n) {
        "use strict";

        function i(e) {
            return e && e.__esModule ? e : {
                "default": e
            }
        }
        Object.defineProperty(t, "__esModule", {
            value: !0
        }), t["default"] = function(e) {
            "5000059" === n.s.id && r["default"].then(function(t) {
                var n = t && t.substr(t.length - 2) % 100 < 50 ? "button_1" : "button_2";
                e.plugin("prepareData", function(e) {
                    return e.forEach(function(e) {
                        return e.url += "&test=" + n
                    }), e
                }), e.plugin("prepareStyle", function(e) {
                    if ("button_1" === n) {
                        var t = ["#reset .info span.icon-app { border:none;color:#fff;background:#ab1324;}"].join("\n");
                        e.append(t)
                    }
                })
            })
        };
        var o = n(1),
            r = i(o)
    }, function(e, t, n) {
        "use strict";

        function i(e) {
            return e && e.__esModule ? e : {
                "default": e
            }
        }
        var o = n(4),
            r = i(o),
            a = n(11),
            l = i(a),
            s = n(2),
            c = "readsByToutiao",
            d = "//partner.toutiao.com/get_version/",
            u = function() {
                (0, r["default"])("pageview"), window[c] = window[c] || [];
                for (var e = window[c]; e.length;) l["default"].run(e.shift());
                window[c] = {
                    push: l["default"].run,
                    find: l["default"].find,
                    remove: l["default"].remove
                }
            },
            _ = (0, s.jsonp)(s.path.join(d, n.s.id, "/"));
        _.then(function(e) {
            return e.success && n.h !== e.version ? (0, s.loadScript)(s.path.join(n.p, e.version + ".js")) : void u()
        }), _["catch"](u)
    }, function(e, t, n) {
        "use strict";

        function i(e) {
            return e && e.__esModule ? e : {
                "default": e
            }
        }
        function o(e, t) {
            return t ?
                function(n) {
                    return n.locals = t, e(n)
                } : e
        }
        function r(e) {
            try {
                e.append(n.s.style)
            } catch (t) {}
            return e
        }
        Object.defineProperty(t, "__esModule", {
            value: !0
        });
        var a = n(8),
            l = i(a),
            s = n(5),
            c = i(s),
            d = n(9),
            u = i(d),
            _ = n(6),
            p = i(_),
            f = n(10),
            h = i(f),
            m = n(7),
            g = i(m),
            v = {
                "default": {
                    style: r(l["default"]),
                    tpl: o(c["default"], l["default"].locals)
                },
                illustrated: {
                    style: r(u["default"]),
                    tpl: o(p["default"], u["default"].locals)
                },
                literal: {
                    style: r(h["default"]),
                    tpl: o(g["default"], h["default"].locals)
                }
            };
        t["default"] = v
    }, function(e, t, n) {
        t = e.exports = n(3)(), t.push([e.id, '#_1X2jnq9Z,#_1X2jnq9Z *{background-attachment:scroll;background-color:transparent;background-image:none;background-position:0 0;background-repeat:repeat;background:none;border:none;border-collapse:separate;border-spacing:0;border:0;bottom:auto;caption-side:top;clear:none;clip:auto;color:#333;content:normal;cursor:auto;direction:ltr;display:inline;float:none;font-size:0;height:auto;left:auto;letter-spacing:normal;line-height:normal;list-style-image:none;list-style-position:outside;list-style-type:disc;margin:0;max-height:none;max-width:none;min-height:0;min-width:0;outline:none;overflow:visible;padding:0;position:static;right:auto;table-layout:auto;text-align:start;text-decoration:none!important;text-indent:0;text-transform:none;top:auto;vertical-align:baseline;visibility:visible;white-space:normal;width:auto;word-break:normal;word-spacing:normal;word-wrap:normal;z-index:auto;box-sizing:content-box;resize:none;text-overflow:clip;font-size-adjust:none;font-weight:400;transition:none;transform:none}#_1X2jnq9Z{clear:both}#_1X2jnq9Z,#_1X2jnq9Z div,#_1X2jnq9Z h1,#_1X2jnq9Z h2,#_1X2jnq9Z h3,#_1X2jnq9Z p{display:block}#_1X2jnq9Z ul,#_1X2jnq9Z ul li{list-style:none;display:block}@-webkit-keyframes _3DQ1H-z2{0%{-webkit-transform:rotate(0)}to{-webkit-transform:rotate(1turn)}}@keyframes _3DQ1H-z2{0%{transform:rotate(0)}to{transform:rotate(1turn)}}#_1X2jnq9Z ._2K8CDrqX{display:inline-block;border-left:12px solid #f94a47;padding-left:.5em;font-size:18px;line-height:1.5;color:#999}#_1X2jnq9Z .E2NWm0PE{display:block;-webkit-user-select:none;-webkit-tap-highlight-color:rgba(0,0,0,0);padding:12px 0;border-bottom:1px solid #efefef}#_1X2jnq9Z .E2NWm0PE:after{content:"";display:table;clear:both}#_1X2jnq9Z .E2NWm0PE:last-child{border-bottom:none}#_1X2jnq9Z .ufuHpmO2{font-size:18px;color:#333;line-height:1.125;display:-webkit-box;display:box;text-overflow:ellipsis;overflow:hidden;-webkit-box-orient:vertical;box-orient:vertical;line-height:1.125em;-webkit-line-clamp:2}#_1X2jnq9Z .E2NWm0PE:visited .ufuHpmO2{color:#999}#_1X2jnq9Z ._15yLEhVa{margin-top:12px}#_1X2jnq9Z ._1nhff0ZO ._23GdR88y{display:inline-block;box-sizing:border-box;width:33.3%;position:relative;overflow:hidden}#_1X2jnq9Z ._1nhff0ZO ._23GdR88y:first-child{border-right:2px solid transparent}#_1X2jnq9Z ._1nhff0ZO ._23GdR88y:last-child{border-left:2px solid transparent}#_1X2jnq9Z ._1nhff0ZO ._23GdR88y:nth-child(2){border-right:1px solid transparent;border-left:1px solid transparent}#_1X2jnq9Z ._294YtkvS{position:absolute;top:0;left:0;bottom:0;right:0;background-position:50%;background-size:cover;z-index:1}#_1X2jnq9Z .oAQjEOGR{width:100%;padding-top:65.26%}#_1X2jnq9Z ._2ubaoT7H{margin-top:12px}#_1X2jnq9Z ._2ubaoT7H span{vertical-align:middle;font-size:14.4px;color:#999;display:inline-block}#_1X2jnq9Z ._2ubaoT7H span._1QDMBn-r{width:7em;border:1px solid;color:#f94a47;text-align:center;border-radius:4px;box-sizing:border-box;padding:2px;margin-right:.5em}#_1X2jnq9Z ._2ubaoT7H span._3xv_6CpG{padding-top:1px}#_1X2jnq9Z ._3lB9sJFp{float:left;width:66.6%}#_1X2jnq9Z ._3lB9sJFp.KTkx3yR_{float:none;width:100%}#_1X2jnq9Z ._15JxMekq{float:left;width:33.3%;position:relative;border-left:3px solid transparent;box-sizing:border-box;overflow:hidden}#_1X2jnq9Z ._15JxMekq ._2jRqJjIl{position:absolute;height:16px;font-size:12px;bottom:0;right:0;padding:4px 8px 4px 30px;z-index:1;color:#fff;background:rgba(0,0,0,.5) url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAkCAYAAADPRbkKAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAZVJREFUWMNj+P//P8NQxgyjHhhEHmAF4lIgvgrEP//TB3wH4tNAnAjEjJR4gA2Id/8fWDCfHE/AGDX/BwcII9LhoBjbAsR+MIFbSIZMBWJuOqVhISDehGT3VgLqOYB4DpL6jzCJP0iCSnTOiM5Idl/Co04OiE+hRxlMEhnI0dkDNkR4wAmIX2FLc4PBA6JA/AEpIyPLgTJ1CVoK+TvYPADC0kDsAsQsSGI8QLwKzW0vgNh3MHoAHatD6yNkcAzqUf7B7gF/UOmC5qZpQMwOlR+0HmAG4hYg/odWUyegqRuUHmjAkmQeALERFrWDzgOGWEpHULNGGId6/sFcD4CKyDZocmIYih64RIT6UQ+MemDUA0PEA2pAPBOIU9F6akPGA1uRxFdCG3dDygPT0dx1BRorQ8YD7Fg8Aeo3xA61TJwEbdT9H6w9MmJKIWMgfojPA0OhUy8CxHtweeA22rAK1yAdVgE18jqR+gxvh+rAljcQLwRix2EztAgb3C0D4mtA/ItOjv4BxGegJQ1Fg7uj8wOjHiATAwCwU/nx3nM/GAAAAABJRU5ErkJggg==) no-repeat;background-position:8px;background-size:16px;line-height:1.5}#_1X2jnq9Z ._3eR2FVDh{color:#f94a47;position:absolute;content:" ";width:16px;height:16px;top:50%;left:50%;margin-left:-10px;margin-top:-10px;border:2px solid;border-radius:50%;border-left-color:transparent;z-index:0;-webkit-animation:_3DQ1H-z2 .8s linear infinite;animation:_3DQ1H-z2 .8s linear infinite}@media (min-device-width:480px){#_1X2jnq9Z{max-width:480px}}', ""]), t.locals = {
            reset: "_1X2jnq9Z",
            header: "_2K8CDrqX",
            item: "E2NWm0PE",
            title: "ufuHpmO2",
            pics: "_15yLEhVa",
            gallery: "_1nhff0ZO",
            list: "_23GdR88y",
            "pic-wrap": "_294YtkvS",
            dumb: "oAQjEOGR",
            info: "_2ubaoT7H",
            "icon-app": "_1QDMBn-r",
            date: "_3xv_6CpG",
            desc: "_3lB9sJFp",
            only: "KTkx3yR_",
            "pic-right": "_15JxMekq",
            "vid-info": "_2jRqJjIl",
            spinner: "_3eR2FVDh",
            rotate: "_3DQ1H-z2"
        }
    }, function(e, t, n) {
        t = e.exports = n(3)(), t.push([e.id, '#_3ffdTPlq,#_3ffdTPlq *{background-attachment:scroll;background-color:transparent;background-image:none;background-position:0 0;background-repeat:repeat;background:none;border:none;border-collapse:separate;border-spacing:0;border:0;bottom:auto;caption-side:top;clear:none;clip:auto;color:#333;content:normal;cursor:auto;direction:ltr;display:inline;float:none;font-size:0;height:auto;left:auto;letter-spacing:normal;line-height:normal;list-style-image:none;list-style-position:outside;list-style-type:disc;margin:0;max-height:none;max-width:none;min-height:0;min-width:0;outline:none;overflow:visible;padding:0;position:static;right:auto;table-layout:auto;text-align:start;text-decoration:none!important;text-indent:0;text-transform:none;top:auto;vertical-align:baseline;visibility:visible;white-space:normal;width:auto;word-break:normal;word-spacing:normal;word-wrap:normal;z-index:auto;box-sizing:content-box;resize:none;text-overflow:clip;font-size-adjust:none;font-weight:400;transition:none;transform:none}#_3ffdTPlq{clear:both}#_3ffdTPlq,#_3ffdTPlq div,#_3ffdTPlq h1,#_3ffdTPlq h2,#_3ffdTPlq h3,#_3ffdTPlq p{display:block}#_3ffdTPlq ul,#_3ffdTPlq ul li{list-style:none;display:block}@-webkit-keyframes _29Ef2yh5{0%{-webkit-transform:rotate(0)}to{-webkit-transform:rotate(1turn)}}@keyframes _29Ef2yh5{0%{transform:rotate(0)}to{transform:rotate(1turn)}}#_3ffdTPlq ._6vlSpEhf{display:inline-block;border-left:12px solid #f94a47;padding-left:.5em;font-size:18px;line-height:1.5;color:#999}#_3ffdTPlq ._3omgu9pS{display:block;-webkit-user-select:none;-webkit-tap-highlight-color:rgba(0,0,0,0);padding:6px 0;border-bottom:1px solid #efefef}#_3ffdTPlq ._3omgu9pS:after{content:"";display:table;clear:both}#_3ffdTPlq ._3omgu9pS:last-child{border-bottom:none}#_3ffdTPlq ._3QaVF3Sh{font-size:15px;color:#333;text-overflow:ellipsis;overflow:hidden;line-height:1.125em;white-space:nowrap}#_3ffdTPlq ._3omgu9pS:visited ._3QaVF3Sh{color:#999}#_3ffdTPlq ._3nXxAnC5{position:absolute;top:0;left:0;bottom:0;right:0;background-position:50%;background-size:cover;z-index:1}#_3ffdTPlq .V3u53u_x{width:100%;padding-top:65.26%}#_3ffdTPlq ._7a0WGnO0{margin-top:12px}#_3ffdTPlq ._7a0WGnO0 span{vertical-align:middle;font-size:14.4px;color:#999;display:inline-block}#_3ffdTPlq ._7a0WGnO0 span._1r2_CEyR{width:7em;border:1px solid;color:#f94a47;text-align:center;border-radius:4px;box-sizing:border-box;padding:2px;margin-right:.5em}#_3ffdTPlq ._7a0WGnO0 span.XJGX3DGf{padding-top:1px}#_3ffdTPlq ._2GSpzQfE{float:left;width:30%;position:relative;border-right:8px solid transparent;box-sizing:border-box;overflow:hidden}#_3ffdTPlq .c1kAjX7t{color:#f94a47;position:absolute;content:" ";width:16px;height:16px;top:50%;left:50%;margin-left:-10px;margin-top:-10px;border:2px solid;border-radius:50%;border-left-color:transparent;z-index:0;-webkit-animation:_29Ef2yh5 .8s linear infinite;animation:_29Ef2yh5 .8s linear infinite}@media (min-device-width:480px){#_3ffdTPlq{max-width:480px}}', ""]), t.locals = {
            reset: "_3ffdTPlq",
            header: "_6vlSpEhf",
            item: "_3omgu9pS",
            title: "_3QaVF3Sh",
            "pic-wrap": "_3nXxAnC5",
            dumb: "V3u53u_x",
            info: "_7a0WGnO0",
            "icon-app": "_1r2_CEyR",
            date: "XJGX3DGf",
            pic: "_2GSpzQfE",
            spinner: "c1kAjX7t",
            rotate: "_29Ef2yh5"
        }
    }, function(e, t, n) {
        t = e.exports = n(3)(), t.push([e.id, '#_3ine3Bmf,#_3ine3Bmf *{background-attachment:scroll;background-color:transparent;background-image:none;background-position:0 0;background-repeat:repeat;background:none;border:none;border-collapse:separate;border-spacing:0;border:0;bottom:auto;caption-side:top;clear:none;clip:auto;color:#333;content:normal;cursor:auto;direction:ltr;display:inline;float:none;font-size:0;height:auto;left:auto;letter-spacing:normal;line-height:normal;list-style-image:none;list-style-position:outside;list-style-type:disc;margin:0;max-height:none;max-width:none;min-height:0;min-width:0;outline:none;overflow:visible;padding:0;position:static;right:auto;table-layout:auto;text-align:start;text-decoration:none!important;text-indent:0;text-transform:none;top:auto;vertical-align:baseline;visibility:visible;white-space:normal;width:auto;word-break:normal;word-spacing:normal;word-wrap:normal;z-index:auto;box-sizing:content-box;resize:none;text-overflow:clip;font-size-adjust:none;font-weight:400;transition:none;transform:none}#_3ine3Bmf{clear:both}#_3ine3Bmf,#_3ine3Bmf div,#_3ine3Bmf h1,#_3ine3Bmf h2,#_3ine3Bmf h3,#_3ine3Bmf p{display:block}#_3ine3Bmf ul,#_3ine3Bmf ul li{list-style:none;display:block}#_3ine3Bmf ._24ZlpUFR{font-size:1.2rem;background-color:#f94a47;text-indent:.25em;color:#fff;line-height:1.5}#_3ine3Bmf ._2IKsP_eS{display:block;padding:.5rem 0;background:linear-gradient(180deg,transparent 50%,#dadada 0,#dadada) no-repeat bottom;background-size:100% 1px;font-size:14.004px}#_3ine3Bmf ._2IKsP_eS:after{content:"";clear:both;display:table}#_3ine3Bmf ._2IKsP_eS span{display:inline-block;vertical-align:middle;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}#_3ine3Bmf ._2IKsP_eS span.uLY6sK9a{line-height:1.5;font-size:100%;width:80%;float:left}#_3ine3Bmf ._2IKsP_eS span.uLY6sK9a .iCVsJjGt{display:none}#_3ine3Bmf ._2IKsP_eS span.uLY6sK9a._2-sd4DmA{padding-left:1.5em;box-sizing:border-box;position:relative}#_3ine3Bmf ._2IKsP_eS span.uLY6sK9a._2-sd4DmA .iCVsJjGt{display:inline-block;font-size:75%;width:1.5em;height:1.5em;vertical-align:middle;border:1px solid;line-height:1.5;text-align:center;border-radius:20%;color:#f94a47;position:absolute;top:50%;margin-top:-10.5px;left:0;box-shadow:2px 2px 0 0 rgba(0,0,0,.1)}#_3ine3Bmf ._2IKsP_eS span._1p5LtgLR{line-height:2;font-size:75%;width:20%;text-align:right;color:#999;float:right}#_3ine3Bmf ._2IKsP_eS span._1p5LtgLR ._7EulRMoE{font-size:100%;width:1em;height:1em;fill:#999;vertical-align:middle;display:inline-block}', ""]), t.locals = {
            reset: "_3ine3Bmf",
            header: "_24ZlpUFR",
            item: "_2IKsP_eS",
            title: "uLY6sK9a",
            "icon-app": "iCVsJjGt",
            ad: "_2-sd4DmA",
            date: "_1p5LtgLR",
            "icon-date": "_7EulRMoE"
        }
    }]);