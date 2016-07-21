/**
 * Created by Benson on 16/7/19.
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
            order : data.order,
            pagination : {
                total : data.total,
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
                controller_pop.display.add.show();
            },
            _open : function (index) {
                var id = this.list[index].id,valid = this.list[index].valid == '1' ? '0' : '1';
                request.get(
                    '/admin/site/open',
                    function(ret){
                        if(ret.hasOwnProperty('code') && ret.code ==0){
                            self.model.data.list[index].valid  = valid
                        }
                    },
                    {
                        id : id,
                        valid : valid
                    }
                );
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
        request.get('/admin/site/list',function(ret){
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
                keyword: d.search.keyword
            });

    };
    this.vue = new Vue(self.model);
    //执行初始化页码
    self.update_pagination_btn();
}).call(define('controller_admin'));

(function(){
    var self = this;

    this.vue = new Vue({
        el : '#pop-container',
        data : {
            display : '',
            add : {
                name : '',
                platform : true,
                domain : '',
                base : data.platform.domain,
                icp : data.icp
            }
        },
        methods : {
            _close : function(){
                self.display.add.hide();
            },
            _switch : function () {
                this.add.platform = !this.add.platform;
                this.add.domain = '';
                this.add.icp = this.add.platform ? data.icp : '';
            },
            _confirm_add : function(){
                self.add_site();
            }
        }
    });
    this.display = {
        add : {
            show : function(){
                self.vue.display = 'add';
                controller_admin.model.data.background = true;
            },
            hide : function(){
                self.vue.display = '';
                self.vue.add.domain = '';
                self.vue.add.icp = data.icp;
                self.vue.add.platform = true;
                self.vue.add.name = '';
                controller_admin.model.data.background = false;
            }
        }
    };
    //添加站点
    this.add_site = function () {
        var d = self.vue.add,jump = d.platform ? 'http://'+d.domain+'.'+data.platform.domain+'/admin/site' : 'http://'+d.domain+'/admin/site';
        request.post('/admin/site/add',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.update_list();
                    self.display.add.hide();
                    window.open(jump, '_blank');
                }
                else{
                    pop.error(ret.msg || '添加站点失败','确定').one()
                }
            },
            {
                platform : d.platform ? 'true' : 'false',
                name : d.name,
                domain : d.domain,
                icp : d.icp
            });
    };
    //刷新列表
    this.update_list = function(){
        controller_admin.get_data();
    };
}).call(define('controller_pop'));