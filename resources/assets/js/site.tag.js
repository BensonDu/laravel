(function(){
    var self = this,
        tag = data.tag,
        total = data.total,
        index = 0,
        size = 10;

    this.model = {
        el : '#site-content',
        data : {
            visible : 'visible',
            total : total,
            tag : tag,
            load:'More',
            list : data.list
        },
        methods : {
            get_list : function(){
                self.get_list();
            },
            tag : function(){
                location.href = '/tag/'+self.model.data.keyword;
            }
        }
    };
    this.list = data.list;
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
        request.get('/tag/'+encodeURIComponent(self.model.data.tag)+'/list',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    index++;
                    setTimeout(function(){
                        self.btn_sta();
                        self.insert_list(ret.data.list);
                    },0);
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