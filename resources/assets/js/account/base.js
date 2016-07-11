(function(){
    var self = this;

    this.w = $(window);
    this.d = $(document);
    this.content = $('#content');
    this.mid = $('#mid');
    this.backgound = $('#background');

}).call(define('dom'));

(function(){
    var self = this;

    //重写Nav.left fold,unfold 方法
    window.view_nav_left.fold = function(){
        return dom.content.css('padding-left',60),dom.backgound.css('left',60);
    };

    window.view_nav_left.unfold = function(){
        return dom.content.css('padding-left',140),dom.backgound.css('left',140);
    };

    this.resize_event = dom.w.resize(function(){});

    this.scroll_event = dom.w.scroll(function(){});

}).call(define('view_page_response'));