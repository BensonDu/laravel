(function(){
    var self = this;

    this.w = $(window);
    this.d = $(document);
    this.content = $('#site-content');
    this.mid = $('#mid');
    /*专题页*/
    this.backgound = $('#background');
    /*首页*/
    this.filter = $('#filter');
    this.star = $('#star-album');
    this.star_contaner = self.star.children('.item-container');

}).call(define('dom'));

(function(){
    var self = this;

    //重写Nav.left fold,unfold 方法
    window.view_nav_left.fold = function(){
        return dom.mid.css('left',60), dom.content.css('padding-left',310),dom.backgound.css('padding-left',310), dom.filter.addClass('trans').removeClass('unfold');
    };

    window.view_nav_left.unfold = function(){
        return dom.mid.css('left',140),dom.content.css('padding-left',390),dom.backgound.css('padding-left',390), dom.filter.addClass('trans').addClass('unfold');
    };

}).call(define('view_page_response'));

(function(){
    var self = this;

    //分类筛选置顶
    this.filter_fixed = function(){
        var top = !!dom.filter.length && dom.filter.offset().top;
        return function(){
            return dom.d.scrollTop()>top ? dom.filter.addClass('fixed').removeClass('trans') : dom.filter.removeClass('fixed');
        };
    };

    this.scroll_event = function(){
        dom.w.scroll(self.filter_fixed());
    };

    !!dom.star && self.scroll_event();

}).call(define('view_home_page'));

/*与平台同步登录信息*/
(function(){
    var self = this;
    if(!!global && global.hasOwnProperty('uid') && global.uid == ''){
        request.jsonp(global.platform.home+'/account/status',function(ret){
            if(ret.data && ret.data.login){
                location.href = global.platform.home+'/account/login?redirect='+encodeURIComponent(location.href);
            }
        })
    }
}).call(define('controller_login_syn'));