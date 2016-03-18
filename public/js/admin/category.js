'use strict';
(function () {
    var self = this;

    this.active = function(){
        Sortable.create(document.getElementById('sort-list-1'), {
            group: {
                name : 'category',
                pull :true,
                put : true
            },
            animation: 150,
            ghostClass: "drag-ghost",
            onUpdate: function () {
                self.order();
            },
            onEnd : function(){
                self.order();
            }
        });
        Sortable.create(document.getElementById('sort-list-2'), {
            group:{
                name : 'category',
                pull : true,
                put : true
            },
            animation: 150,
            ghostClass: "drag-ghost",
            onUpdate: function () {
                self.order();
            },
            onEnd : function(){
                self.order();
            }
        });
    };
    this.order = function(){
        var show = [], hide = [],
            left = $('#sort-list-1').children('li'),
            right = $('#sort-list-2').children('li'),
            l = [],r = [];
        left.each(function(){
            l = $(this).data('id');
            !!l && show.push(l);
        });
        right.each(function(){
            r = $(this).data('id');
            !!r && hide.push(r);
        });
        controller_list.save_order(show,hide);
    }
}).call(define('plugin_sortable'));

(function(){
    var self = this;

    this.list_format = function(list){
        var ret = [];
        for(var i in list){
            ret[i] = list[i];
            ret[i]['edit'] = false;
        }
        return ret;
    };

    this.list = {
        show : self.list_format(default_data.list.show),  
        hide : self.list_format(default_data.list.hide)  
    };


    this.vue = new Vue({
        el : '#admin-content',
        data : {
            background : false,
            add:false,
            show : {
                total : self.list.show.length,
                list : self.list.show
            },
            hide : {
                total : self.list.hide.length,
                list : self.list.hide
            },
            input : '',
            del : {
                display : false,
                list : [],
                id : '',
                count : '',
                active : '',
                loading: false
            }
        },
        created: function () {
            plugin_sortable.active();
        },
        methods : {
            _edit_show : function(index){
                this.show.list[index]['edit'] = true;
                this.show.list[index]['last'] = this.show.list[index]['name'];
            },
            _edit_show_confirm : function(index){
                self.edit(this.show.list[index]['id'],this.show.list[index]['name'],function(){
                    self.vue.show.list[index]['edit'] = false;
                });

            },
            _edit_show_cancel : function(index){
                this.show.list[index]['edit'] = false;
                this.show.list[index]['name'] = this.show.list[index]['last'];
            },
            _edit_hide : function(index){
                this.hide.list[index]['edit'] = true;
                this.hide.list[index]['last'] = this.hide.list[index]['name'];
            },
            _edit_hide_confirm : function(index){
                self.edit(this.hide.list[index]['id'],this.hide.list[index]['name'],function(){
                    self.vue.hide.list[index]['edit'] = false;
                });
            },
            _edit_hide_cancel : function(index){
                this.hide.list[index]['edit'] = false;
                this.hide.list[index]['name'] = this.hide.list[index]['last'];
            },
            _del : function(id){
                var info = self.get_category_info(id);
                if(self.get_category_list().length == 1){
                    return pop.error('禁止删除唯一分类','确定').one();
                }
                if(!!info.count){
                    self.del_show(info.id,info.count);
                }
                else{
                    pop.confirm('确认删除 ?','确定',function(){
                        self.del(id);
                    });
                }
            },
            _add_display : function(){
                this.add = !this.add;
                this.input = '';
            },
            _add : function(){
                self.add(this.input);
            },
            _del_select:function(id){
                this.del.active = id;
            },
            _del_cancel:function(){
                self.del_hide();
            },
            _del_confirm : function(){
                if(this.del.active != ''){
                    self.delete();
                }
            }
        }
    });
    //获得当前分类列表 exclude ID 可选
    this.get_category_list = function(id){
        var s = self.vue.show.list,
            h = self.vue.hide.list,
            r = s.concat(h),
            f = [];
        if(!!id){
            for(var i in r){
                if(!!r[i].id && r[i].id != id){
                    f.push(r[i]);
                }
            }
        }
        else{
            f = r;
        }
        return f;
    };
    //获得分类信息
    this.get_category_info = function(id){
        var a =self.get_category_list();
        for(var i in a){
            if(!!a[i].id && a[i].id == id)return a[i];
        }
        return {};
    };
    //修改名称
    this.edit = function(id,name,call){
        request.get('/admin/category/edit',function(ret){
            if(ret.hasOwnProperty('code')){
                if(ret.code != 0){
                    pop.error(ret.msg,'确定').one();
                }
                else{
                    call();
                }
            }
            else{
                pop.error('修改失败','确定').one();
            }

        },{
            id : id,
            name : name
        });
    };
    //添加
    this.add = function(name){
        request.get('/admin/category/add',function(ret){
            if(ret.hasOwnProperty('code')){
                if(ret.code != 0){
                    pop.error(ret.msg,'确定').one();
                }
                else{
                    self.update_list();
                    setTimeout(function(){
                        self.vue.add = false;
                        self.vue.input = '';
                    },300);
                }
            }
            else{
                pop.error('添加失败','确定').one();
            }

        },{
            name : name
        });
    };
    //更新列表
    this.update_list = function(){
        request.get('/admin/category/list',function(ret){
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.vue.show.list = self.list_format(ret.data.show);
                self.vue.show.total = ret.data.show.length;
                self.vue.hide.list = self.list_format(ret.data.hide);
                self.vue.hide.total = ret.data.hide.length;
            }
        });
    };
    //保存顺序
    this.save_order = function(show,hide){
        request.get('/admin/category/order/save',function(ret){
            var data;
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.vue.show.total = show.length;
                self.vue.hide.total = hide.length;
            }
            else{
                pop.error('排序错误','确定').one();
            }
        },{show:show,hide:hide});
    };
    //删除已有文章分类 候选 隐藏
    this.del_hide = function(){
        self.vue.background = false;
        self.vue.del.display = false;
        self.vue.del.list = [];
        self.vue.del.active = '';
        self.vue.del.count = '';
        self.vue.del.id = '';
    };
    //删除已有文章分类 候选 显示
    this.del_show = function(id,count){
        self.vue.background = true;
        self.vue.del.display = true;
        self.vue.del.list = self.get_category_list(id);
        self.vue.del.count = count;
        self.vue.del.id = id;
    };
    //删除已有文章分类
    this.delete = function(){
        var id = self.vue.del.id,move = self.vue.del.active;
        self.vue.del.loading = true;
        request.get('/admin/category/delete',function(ret){
            var data;
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.update_list();
                self.del_hide();
            }
            else{
                pop.error(ret.msg || '操作失败','确定').one();
            }
            self.vue.del.loading = false;
        },{
            id:id,
            move:move
        });
    };
    //删除分类
    this.del = function(id){
        request.get('/admin/category/del',function(ret){
            var data;
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.update_list();
            }
            else{
                pop.error('操作失败','确定').one();
            }
        },{id:id});
    };
}).call(define('controller_list'));