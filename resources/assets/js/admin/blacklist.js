/**
 * Created by Benson on 16/5/24.
 */
(function(){
    var self = this;

    this.model = {
        el : '#admin-content',
        data : {
            background : false,
            search : {
                keyword : ''
            },
            orderby : data.orderby,
            order : 'desc',
            pagination : {
                total :data.total,
                size : 10,
                index : 1,
                btns : [],
                all : 0
            },
            list : data.list
        },
        methods : {
            _search:function(){
                self.page_search();
            },
            _size : function(size){
                self.page_size(size)
            },
            _turn : function(index){
                if(typeof index == 'number')self.page_turn(index);
            },
            _next : function(){
                if(this.pagination.index + 1 <= this.pagination.all)self.page_turn(this.pagination.index+1);
            },
            _prev : function(){
                if(this.pagination.index - 1 >= 1)self.page_turn(this.pagination.index-1);
            },
            _order : function(by){
                this.orderby = by;
                this.order  = this.order == 'desc' ? 'asc' : 'desc';
                self.page_order();
            },
            _add : function(){
                controller_pop.display.add();
            },
            _del : function(id){
                pop.confirm('将该用户移出黑名单 ?','确定',function(){
                    self.delete_user(id);
                });
            }
        }
    };
    //搜索
    this.page_search = function(){
        self.get_data();
    };
    //切换排序
    this.page_order = function(){
        self.get_data();
    };
    //跳转
    this.page_turn = function(index){
        self.model.data.pagination.index = index;
        self.get_data();
    };
    //切换每页数量
    this.page_size = function(size){
        var d = self.model.data.pagination;
        d.size = size;
        d.all = Math.ceil(d.total/ d.size);
        if(d.index > d.all)d.index = d.all;
        self.get_data();
    };
    //更新页码表
    this.update_pagination_btn = function(){
        var d = self.model.data.pagination;
        d.all = Math.ceil(d.total/ d.size);
        return d.btns = self.create_pagination_btn(d.index, d.size, d.total);
    };
    //生成页码列表
    this.create_pagination_btn = function(index,size,total){
        var t = Math.ceil(total/size),
            a = [],
            s, f,
            n = function(act,i){
                return a.push({
                    active : act,
                    index : i
                })
            },
            e = function(){
                return a.push({
                    active : false,
                    index : '...'
                });
            };
        if(t <= 7){
            for(var i = 1; i <= t ;i++){
                n(i==index, i);
            }
        }
        else {
            s = index - 3 < 1 ? 1 : index - 3;
            f = index + 3 > t ? t : index + 3;
            if(s>1)n(false,1);
            if(s>2)e();
            for (var i = s; i <= f; i++) {
                n(i == index, i);
            }
            if(f< t-1)e();
            if(f<t)n(false, t);
        }
        return a;
    };
    //获取数据
    this.get_data = function(){
        var d = self.model.data;
        request.get('/admin/user/blacklist/users',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.model.data.pagination.total = ret.data.total;
                    self.model.data.list = ret.data.list;
                    self.update_pagination_btn();
                }
            },
            {
                index : d.pagination.index,
                size : d.pagination.size,
                order : d.order,
                orderby : d.orderby,
                keyword: d.search.keyword
            });

    };
    //删除
    this.delete_user = function(id){
        request.get('/admin/user/blacklist/del', function(ret){
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.get_data();
            }
            else{
                pop.error('操作失败','确定');
            }
        },{id : id});
    };
    this.vue = new Vue(self.model);
    //执行初始化页码
    self.update_pagination_btn();
}).call(define('controller_admin'));

(function () {
    var self = this;
    this.vue = new Vue({
        el : '#pop-container',
        data : {
            display : '',
            search : {
                keyword : '',
                list : []
            }
        },
        methods : {
            _search : function () {
                self.search();
            },
            _close : function () {
                self.display.hide();
            },
            _add : function (id) {
                self.add(id);
            }
        }
    });
    this.display = {
        add : function () {
            self.vue.display = 'add';
        },
        hide : function () {
            self.vue.display = '';
            self.vue.search.keyword = '';
            self.vue.search.list = [];
        }
    };
    //搜索用户
    this.search = function(){
        var k = self.vue.search.keyword;
        if(k == '')return false;
        request.get('/admin/user/search',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.vue.search.list = ret.data;
                }
                else{
                    pop.error('请求错误','确定').one();
                }
            },
            {
                keyword : k
            }
        );
    };
    //搜索列表去除
    this.remove = function (id) {
        var list = self.vue.search.list,l = list.length, ret = [];
        for(var i = 0; i < l; i ++){
            if(list[i].id && list[i].id != id)ret.push(list[i]);
        }
        self.vue.search.list = ret;
    };
    //添加用户进黑名单
    this.add = function (id) {
        request.get('/admin/user/blacklist/add',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.remove(id);
                    controller_admin.get_data();
                }
                else{
                    pop.error('请求错误','确定').one();
                }
            },
            {
                id : id
            }
        );
    };
}).call(define('controller_pop'));