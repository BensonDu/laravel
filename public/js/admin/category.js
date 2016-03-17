'use strict';
(function () {
    var self = this;
    
    this.left = function(pull,put){
        self.sortable_left.option("group",{
            name : 'category',
            pull :pull,
            put : put
        })  
    };
    this.right = function(pull,put){
        self.sortable_right.option("group",{
            name : 'category',
            pull :pull,
            put : put
        })
    };
    this.sortable_left = {};
    this.sortable_right = {};
    this.active = function(){
        self.sortable_left = Sortable.create(document.getElementById('sort-list-1'), {
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
        self.sortable_right =  Sortable.create(document.getElementById('sort-list-2'), {
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
    this.lock = function(show,hide){
        if(show >= 5){
            self.left(true,false);
        }
        else{
            self.left(true,true);
        }
        if(hide >= 5){
            self.right(true,false);
        }
        else{
            self.right(true,true);
        }
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
        self.lock(show.length,hide.length);
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
            input : ''
        },
        created: function () {
            plugin_sortable.active();
            plugin_sortable.lock(this.show.total,this.hide.total);
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
                pop.confirm('确认删除 ?','确定',function(){
                    self.del(id);
                });
            },
            _add_display : function(){
                this.add = !this.add;
                this.input = '';
            },
            _add : function(){
                self.add(this.input);
            }
        }
    });
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
                plugin_sortable.lock(self.vue.show.total,self.vue.hide.total);
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