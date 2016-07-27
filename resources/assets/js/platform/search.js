/**
 * Created by Benson on 16/7/26.
 */
(function(){
    var self = this,
        keyword = data.search.keyword,
        total = data.search.total,
        index = 0,
        size = 10;

    this.vue = new Vue({
        el : '#content-container',
        data : {
            total : total,
            keyword : keyword,
            load:'More',
            list : data.search.list,
            sites : data.sites,
            filter : {
                selected : [],
                fold : true
            }
        },
        methods : {
            _fold : function () {
                this.filter.fold = !this.filter.fold;
            },
            _clear : function () {
                this.filter.selected = [];
                self.selected_syn();
                self.sites_syn();
                self.getlist();
            },
            _selected : function (id) {
                var info = self.get_site_info(id);
                if(info && this.filter.selected.length < 6){
                    this.filter.selected.push(info);
                    self.selected_syn();
                    self.sites_syn();
                    self.getlist();
                }
            },
            _unselected : function (id) {
                this.filter.selected = self.arrayRemove(this.filter.selected,'id',id);
                self.selected_syn();
                self.sites_syn();
                self.getlist();
            },
            _more : function(){
                self.getlist(true);
            },
            _search : function(){
                location.href = '/search?keyword='+encodeURIComponent(self.vue.keyword);
            }
        }
    });
    //筛选的ID数组
    this.selected_site = [];
    //已筛选的站点,同步到纯ID的数组
    this.selected_syn  = function () {
        var l = self.vue.filter.selected.length,ret = [];
        for(var i = 0 ; i < l; i++){
            ret.push(self.vue.filter.selected[i].id);
        }
        self.selected_site = ret;
    };
    //待选站点列表同步
    this.sites_syn = function () {
        var l = data.sites.length,ret = [];
        for(var i = 0; i < l; i++){
            if($.inArray(data.sites[i].id,self.selected_site) < 0 )ret.push(data.sites[i]);
        }
        self.vue.sites = ret;
    };
    //获得站点信息
    this.get_site_info = function (id) {
        var l = data.sites.length;
        for(var i = 0; i < l; i++){
            if(data.sites[i].id == id)return data.sites[i];
        }
        return false;
    };
    //数组中删除
    this.arrayRemove = function (array,key,value) {
        var l = array.length,ret = [];
        for(var i = 0; i < l; i++){
            if(array[i][key] != value)ret.push(array[i])
        }
        return ret;
    };
    //缓存搜索结果列
    this.list = data.search.list;
    //插入搜索结果
    this.insert_list = function(list){
        self.list = self.list.concat(list);
        self.vue.list = $.extend([],self.list);
    };
    //是否有更多条目
    this.has_more = function(){
        return (index+1)*size<total;
    };
    //加载按钮应有状态
    this.btn_sta = function(){
        return total==0 ? self.vue.load = 'End' :(self.has_more() ? self.vue.load = 'More' : self.vue.load = 'End');
    };
    //获取列表
    this.getlist = function(more){
        //是否获取更多列表 以及是否有更多列表
        if(more && !self.has_more())return;
        //按钮Loading状态
        self.vue.load = 'loading';
        //不为获取更多 index 重置 列表清空
        if(!more){
            index = -1;
            self.list = [];
        }
        request.get('/search/results',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    //更新结果总数
                    self.vue.total = total = ret.data.total;
                    index++;
                    setTimeout(function(){
                        self.btn_sta();
                        self.insert_list(ret.data.list);
                    },0);
                }
            },
            {
                index : index+1,
                keyword : encodeURIComponent(self.vue.keyword),
                sites : self.selected_site
            });
    };
    //加载更多按钮应有状态
    self.btn_sta();
}).call(define('c_search'));