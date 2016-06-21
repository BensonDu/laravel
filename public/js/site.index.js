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

        }
        self.move(move);
        self.btn_display_syn();
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

(function(){
    var self = this;

    this.model = {
        el : '#filter',
        data : {
            category : {
                active : 0
            },
            keyword : ''
        },
        methods : {
            filter : function(id){
                self.model.data.category.active = id;
                controller_list.get_category_list(id);
            },
            search : function(){
                window.open('/search/'+encodeURIComponent(self.model.data.keyword), '_blank');
            }
        }
    };

    this.vue = new Vue(self.model);

}).call(define('controller_filter'));

(function(){
    var self = this,
        total = default_data.article.total,
        index = 0,
        size = 15;

    this.model = {
        el : '#list-container',
        data : {
            visible:'visible',
            none : 1,
            load:'More',
            list : []
        },
        methods : {
            get_list : function(){
                self.get_list();
            }
        }
    };
    //当前分类
    this.category = 0;
    //列表缓存
    this.list = [];
    //处于筛选模式下
    this.filter_mode = function(){
        var $dom = $('.default-list');
        return !!$dom.length && $dom.remove();
    };
    //新增条目
    this.insert_list = function(list){
        self.list = self.list.concat(list);
        self.model.data.list = $.extend([],self.list);
    };
    //切换分类
    this.swith_list  = function (list) {
        self.filter_mode();
        self.list = self.model.data.list = list;
    };
    //是否有更多条目
    this.has_more = function(){
        return (index+1)*size<total;
    };
    //加载按钮应有状态,以及是否显示暂无内容
    this.btn_sta = function(){
        return self.list_is_empty(!total),total==0 ? self.vue.load = '' :((self.has_more() ? self.vue.load = 'More' : self.vue.load = 'End'));
    };
    //无内容
    this.list_is_empty = function(i){
        return self.model.data.none = i;
    };
    //按钮Loading态
    this.btn_loading = function(){
        return self.vue.load = 'loading';
    };
    //获取数据
    this.get_data = function(i,call,hasmore){
        if(!self.has_more() && !hasmore)return;
        self.btn_loading();
        request.get('/index/list',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    total = ret.data.total;
                    setTimeout(function(){
                        self.btn_sta();
                        call(ret.data.list);
                    },0);
                }
            },
            {
                category: self.category,
                index : i
            });
    };
    //获取更多
    this.get_list = function(){
        index++;
        self.get_data(index,function(list){
            self.insert_list(list);
        });
    };
    //获取分类
    this.get_category_list = function(category_id){
        index = 0;
        self.category = category_id;
        self.get_data(index,function(list){
            self.swith_list(list);
        },1);

    };

    this.vue = new Vue(self.model);

    self.btn_sta();
}).call(define('controller_list'));