/**
 * Created by Benson on 16/7/11.
 */
(function () {
    var self = this,
        content = jQuery('#site-content'),
        loading = jQuery('.loading-cover'),
        background = jQuery('.special-background');

    //背景图加载
    this.imgLoader = function (url,call) {
        var img = new Image;
        img.onload = call;
        img.onerror = call;
        img.src = url;
    };

    this.background = data.image;

    this.timer = setTimeout(function () {
        self.imgLoader(self.background,function () {
            background.css('background-image','url('+self.background+')');
            setTimeout(function () {
                content.addClass('active');
                loading.addClass('disable');
            },800);
        })
    },0);

}).call(define('data'));
