/**
 * Created by Benson on 16/7/25.
 */

(function(){
    var self = this;

    //重写Nav.left fold,unfold 方法
    window.view_nav_left.fold = function(){
        return jQuery('#content-container').css('padding-left',60), jQuery('#nav-container').css('padding-left',60);
    };

    window.view_nav_left.unfold = function(){
        return jQuery('#content-container').css('padding-left',140),jQuery('#nav-container').css('padding-left',140);
    };

}).call(define('view_page_response'));