/**
 * Created by Benson on 16/7/15.
 */

(function(){
    var self = this;

    //重写Nav.left fold,unfold 方法
    window.view_nav_left.fold = function(){
        return jQuery('#content-container').css('padding-left',60), jQuery('#nav-container').css('padding-left',60);
    };

    window.view_nav_left.unfold = function(){
        return jQuery('#content-container').css('padding-left',140),jQuery('#nav-container').css('padding-left',140);
    };

}).call(define('view_page_response'));

(function () {
    var self = this,
        total = 200,
        index = 0,
        size = 20;
    this.vue = new Vue({
        el : '#content-container',
        data : {
            orderby : 'hot',
            load:'More',
            //默认列表显示
            syn : true,
            //异步显示 隐藏默认列表
            rsyn : false,
            list : []
        },
        methods : {
            _orderby : function (orderby) {
                this.orderby = orderby;
                self.get_order();
            },
            _more : function () {
                return self.get_more();
            }
        }
    });
    //列表缓存
    this.list = [];
    //新增条目
    this.insert_list = function(list){
        self.list = self.list.concat(list);
        self.vue.list = $.extend([],self.list);
    };
    //是否有更多条目
    this.has_more = function(){
        return (index+1)*size<total;
    };
    //加载按钮应有状态,以及是否显示暂无内容
    this.btn_sta = function(){
        return total==0 ? self.vue.load = '' :((self.has_more() ? self.vue.load = 'More' : self.vue.load = 'End'));
    };
    //加载更多
    this.get_more = function () {
        index++;
        self.get_data(index,function(list){
            self.insert_list(list);
        });
    };
    //获取排序列表
    this.get_order = function () {
        index = 0;
        self.list = [];
        self.get_data(index,function (list) {
            self.vue.syn     = false;
            self.insert_list(list);
        })
    };
    //获取数据
    this.get_data = function(i,call){
        if(!self.has_more())return;
        self.vue.load = 'loading';
        request.get('/index/list',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    setTimeout(function(){
                        self.vue.rsyn = true;
                        self.btn_sta();
                        call(ret.data);
                    },0);
                }
            },
            {
                orderby: self.vue.orderby,
                index : i
            });
    };
}).call(define('index'));

(function () {
    var self = this;
    this.vue = new Vue({
        el : '#nav-container',
        data : {
            keyword : ''
        },
        methods : {
            _search : function () {
                window.open('http://tech2ipo.com/search/'+encodeURIComponent(self.vue.keyword), '_blank');
            }
        }
    });
}).call(define('nav'));