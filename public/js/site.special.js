(function(){
    var self = this;

    this.w = $(window);
    this.d = $(document);
    this.content = $('#site-content');
    this.mid = $('#site-mid');
    this.backgound = $('#background');

}).call(define('dom'));

(function(){
    var self = this;

    //重写Nav.left fold,unfold 方法
    window.view_nav_left.fold = function(){
        return dom.mid.css('left',60), dom.content.css('padding-left',310),dom.backgound.css('left',310);
    };

    window.view_nav_left.unfold = function(){
        return dom.mid.css('left',140),dom.content.css('padding-left',390),dom.backgound.css('left',390);
    };

    this.resize_event = dom.w.resize(function(){});

    this.scroll_event = dom.w.scroll(function(){});

}).call(define('view_page_response'));