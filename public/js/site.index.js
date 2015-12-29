(function(){
    var self = this;

    this.w = $(window);
    this.d = $(document);
    this.filter = $('#filter');
    this.content = $('#site-content');
    this.mid = $('#site-mid');
    this.star = $('#star-album');
    this.star_contaner = self.star.children('.item-container');

}).call(define('dom'));

(function(){
    var self = this;

    //分类筛选置顶
    this.filter_fixed = function(){
        var top = dom.filter.offset().top;
        return function(){
            return dom.d.scrollTop()>top ? dom.filter.addClass('fixed').removeClass('trans') : dom.filter.removeClass('fixed');
        };
    };

    //重写Nav.left fold,unfold 方法
    window.view_nav_left.fold = function(){
        return dom.mid.css('left',60), dom.content.css('padding-left',310),dom.filter.addClass('trans').removeClass('unfold');
    };

    window.view_nav_left.unfold = function(){
        return dom.mid.css('left',140),dom.content.css('padding-left',390), dom.filter.addClass('trans').addClass('unfold');
    };

    this.resize_event = dom.w.resize(function(){});

    this.scroll_event = dom.w.scroll(self.filter_fixed());

}).call(define('view_page_response'));

//精选新闻焦点图
(function(){
    var self = this,
        next = dom.star.children('.next'),
        prev = dom.star.children('.prev'),
        items = dom.star_contaner.children('a'),
        count = items.length,
        win_width = dom.star.width(),
        width = 441,
        already_hide = 0,
        last_one = false,
        resize = {
            lock : false,
            timer : 0,
            start_width : 0,
            start_margin : 0,
            delay : 1000
        };

    this.btn_display_syn = function(){
        //(already_hide == 0 ? prev.addClass('default-hide') : prev.removeClass('default-hide'));
        //last_one ? next.addClass('default-hide') : next.removeClass('default-hide');
    };

    this.win_contain_count = function(){
        return Math.floor(win_width/width);
    };

    this.is_to_be_the_last = function(){
        return Math.ceil(win_width/width)+already_hide == count;
    };
    
    this.get_last_width = function(){
        return width-win_width%width;
    };

    this.move = function(distance){
        return dom.star_contaner.css('margin-left',distance);
    };

    this.next= function(){
        var move = 0;
        if(already_hide < count-2){
            if(self.is_to_be_the_last()){
                move = -(already_hide*width+self.get_last_width()-5);
                last_one = 1;
            }
            else{
                already_hide++;
                move = -(already_hide)*(width);
            }
            self.move(move);
            self.btn_display_syn();
        }
    };

    this.prev = function(){
        var move = 0;
        if(already_hide > 0){
            if(last_one){
                move = -(already_hide*width);
                last_one = 0;
            }
            else{
                already_hide--;
                move = -already_hide*(width);
            }
            self.move(move);
            self.btn_display_syn();
        }
    };

    this.keep_right_full = function(){
        if(last_one){
            if(!resize.lock){
                resize.start_width = dom.star.width();
                resize.start_margin = parseInt(dom.star_contaner.css('margin-left'));
                resize.lock = true;
            }
            else{
                if(resize.timer){
                    clearTimeout(resize.timer);
                }
                self.move(resize.start_margin+(dom.star.width() - resize.start_width));
                resize.timer = setTimeout(function(){
                    resize.lock = false;
                },resize.delay);
            }
        }
    };

    this.resize_event = dom.w.resize(function(){
        win_width = dom.star.width();
        self.btn_display_syn();
        self.keep_right_full();
    });

    this.next_event = next.click(self.next);

    this.prev_event = prev.click(self.prev);

}).call(define('view_star'));