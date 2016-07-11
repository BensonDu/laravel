/*
    注册左导航栏切换事件
    使用左导航的页面重写fold unfold方法
*/
(function(){
    var self = this,
        $dom = $('#nav-left'),
        $switch = $('#nav-left-switch');

    this.fold_self = function(){
        return $dom.removeClass('unfold'), self.fold();
    };

    this.unfold_self = function(){
        return $dom.addClass('unfold'), self.unfold();
    };

    this.fold = function(){};

    this.unfold = function(){};

    this.swith_event = $switch.click(function(){
        return $dom.hasClass('unfold') ? self.fold_self() : self.unfold_self();
    });

}).call(define('view_nav_left'));