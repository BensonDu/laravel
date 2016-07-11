/**
 * Created by Benson on 16/6/24.
 */
(function () {
    var self = this,
        menu = jQuery('#site-head-menu'),
        ham  = menu.children('ul'),
        nav  = jQuery('#site-head-nav'),
        con  = nav.children('.nav-container'),
        qr   = jQuery('#site-qr'),
        wx   = jQuery('#weixin'),
        timer;

    this.container = function (active) {
        if(active){
            if(timer)clearTimeout(timer);
            timer = setTimeout(function () {
                con.addClass('active');
            },400);
        }
        else{
            con.removeClass('active');
        }

    };

    wx.click(function () {
        var src = jQuery(this).data('src');
        if(qr.hasClass('active')){
            qr.removeClass('active');
        }
        else{
            if(src){
                qr.children('img').attr("src", src);
                jQuery(this).data('src',null);
            }
            qr.addClass('active');
        }

    });

    menu.click(function () {
       if(nav.hasClass('active')){
           nav.addClass('negactive').removeClass('active');
           ham.removeClass('active');
           self.container(0);
       }
        else{
           nav.addClass('active').removeClass('negactive');
           ham.addClass('active');
           self.container(1);
       }
    });
}).call(define('c_menu'));