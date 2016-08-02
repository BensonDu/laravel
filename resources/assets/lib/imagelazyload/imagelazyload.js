/*
 *   @Author Benson Du
 *
 *   相比其它 Lazy Load 插件
 *   增加对异步插入图片及移动设备的支持
 *
 *
 */
(function() {
    var attrName = 'data-lazy-src';

    function loadImage(el, fn) {
        var img = new Image(),
            src = el.getAttribute(attrName);
        img.onload = function() {
            el.src = src;
            !!el.removeAttribute && el.removeAttribute(attrName);
            fn ? fn() : null;
        };
        img.onerror = function () {
            !!el.removeAttribute && el.removeAttribute(attrName);
            fn ? fn() : null;
        };
        img.src = src;
    }

    function elementInViewport(el) {
        var rect = el.getBoundingClientRect();
        return (rect.top >= 0 && rect.left >= 0 && rect.top-200 <= (window.innerHeight || document.documentElement.clientHeight));
    }

    function imageReplace() {
        var query = document.querySelectorAll('img['+attrName+']');
        for (var i = 0; i < query.length; i++) {
            if (elementInViewport(query[i])) loadImage(query[i]);
        }
    }

    setInterval(imageReplace,300);

}).call(this);