(function(){
    var self = this;

    this.w = $(window);
    this.d = $(document);
    this.filter = $('#filter');
    this.content = $('#admin-content');
    this.mid = $('#mid');

}).call(define('dom'));

(function(){
    var self = this;

    //重写Nav.left fold,unfold 方法
    window.view_nav_left.fold = function(){
        return dom.mid.css('left',60), dom.content.css('padding-left',310),dom.filter.addClass('trans').removeClass('unfold');
    };

    window.view_nav_left.unfold = function(){
        return dom.mid.css('left',140),dom.content.css('padding-left',390), dom.filter.addClass('trans').addClass('unfold');
    };

}).call(define('view_page_response'));