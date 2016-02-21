/**
 * Created by Benson on 16/2/19.
 */
(function(){
    var self = this;

    this.w = $(window);
    this.d = $(document);
    this.content = $('#site-content');
    this.mid = $('#mid');

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

(function(){
    var self = this,
        keyword = default_data.search.keyword,
        total = default_data.search.total,
        index = 0,
        size = 10;

    this.model = {
        el : '#site-content',
        data : {
            total : total,
            keyword : keyword,
            load:'More',
            list : default_data.search.list
        },
        methods : {
            get_list : function(){
                self.get_list();
            },
            search : function(){
                window.open('/search/'+self.model.data.keyword,'_self',false);
            }
        }
    };
    this.list = default_data.search.list;
    this.insert_list = function(list){
        self.list = self.list.concat(list);
        self.model.data.list = $.extend([],self.list);
    };
    //是否有更多条目
    this.has_more = function(){
        return (index+1)*size<total;
    };
    //加载按钮应有状态
    this.btn_sta = function(){
        return total==0 ? self.vue.load = '' :(self.has_more() ? self.vue.load = 'More' : self.vue.load = 'End');
    };
    //按钮Loading态
    this.btn_loading = function(){
        return self.vue.load = 'loading';
    };
    //获取更多
    this.get_list = function(){
        if(!self.has_more())return;
        self.btn_loading();
        request.get('/search/'+self.model.data.keyword+'/list',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    index++;
                    setTimeout(function(){
                        self.btn_sta();
                        self.insert_list(ret.data.list);
                    },800);
                }
            },
            {
                index : index+1
            });
    };
    this.vue = new Vue(self.model);
    //加载更多按钮应有状态
    self.btn_sta();
}).call(define('controller_search'));