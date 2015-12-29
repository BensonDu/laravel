(function(){
    var self = this;

    this.w = $(window);
    this.d = $(document);
    this.content = $('#user-content');
    this.nav = $('#user-nav');
    this.mid = $('#mid');

}).call(define('dom'));

(function(){
    var self = this;

    //重写Nav.left fold,unfold 方法
    window.view_nav_left.fold = function(){
        return dom.mid.css('left',60), dom.content.css('padding-left',310), dom.nav.css('padding-left',310);
    };

    window.view_nav_left.unfold = function(){
        return dom.mid.css('left',140),dom.content.css('padding-left',390), dom.nav.css('padding-left',390);
    };

    this.resize_event = dom.w.resize(function(){});

    this.scroll_event = dom.w.scroll(function(){});

}).call(define('view_page_response'));