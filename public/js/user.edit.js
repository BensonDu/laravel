(function(){
    var self = this;

    this.w = $(window);
    this.d = $(document);
    this.content = $('#content');
    this.handle = $('#article-handle');
    this.mid = $('#mid');
    this.sw = $('#summary-switch');
    this.sw_btn = self.sw.children('.switch').children('a');
    this.sw_ms = self.sw.children('.modules').children('.summary');

}).call(define('dom'));

(function(){
    var self = this;

    //重写Nav.left fold,unfold 方法
    window.view_nav_left.fold = function(){
        return dom.mid.css('left',60), dom.content.css('padding-left',310), dom.handle.css('padding-left',340);
    };

    window.view_nav_left.unfold = function(){
        return dom.mid.css('left',140),dom.content.css('padding-left',390), dom.handle.css('padding-left',420);
    };

    this.resize_event = dom.w.resize(function(){});

    this.scroll_event = dom.w.scroll(function(){});

}).call(define('view_page_response'));

(function(){
    var self =this;

    this.content = new MediumEditor('#content-editor',{
        placeholder: {
            text: '输入文章内容'
        },
        toolbar: {
            buttons: ['bold', 'italic', 'underline','h2','h3','anchor', 'orderedlist','unorderedlist', 'quote']
        }
    });
    this.insert_plugin = $('#content-editor').mediumInsert({
        editor: self.content
    });

}).call(define('plugin_editor'));

(function(){
    var self = this,
        per_module = 100;

    this.active = function(offset){
        return dom.sw_btn.removeClass('active').eq(offset).addClass('active');
    };
    this.move = function(offset){
        return dom.sw_ms.css('margin-top',-offset*per_module);
    };
    this.get_offset = function(obj){
        var $obj = $(obj);
        return ($obj.hasClass('summary') && 0) || ($obj.hasClass('image') && 1 ) || ($obj.hasClass('tag') && 2);
    };
    this.hover_event = dom.sw_btn.hover(
        function(){
            var o = self.get_offset(this);
            self.move(o);
            self.active(o);
        }
    );

}).call('view_article_switch');