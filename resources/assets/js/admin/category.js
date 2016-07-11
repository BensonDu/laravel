(function () {
    var self = this;
    Sortable.create(document.getElementById('sort-list'), {
        draggable: '.drag',
        animation: 150,
        ghostClass: "drag-ghost",
        onUpdate: function () {
            self.order();
        }
    });
    this.order = function () {
        var order = [],id;
        $('#sort-list').children().each(function () {
            id = $(this).data('id');
            !!id && order.push(id);
        });
        request.get('/admin/category/order/save',function(ret){
            var data;
            if(!ret.hasOwnProperty('code') || ret.code != 0){
                pop.error('排序错误','确定').one();
            }
        },{order:order});
    }
}).call(define('c_order'));
(function () {
    var self = this;

    this.format = function (list) {
        var c,cl;
        if(list.custom){
            c = list.custom;
            cl= list.custom.length;
            for(var m = 0; m < cl; m++){
                list.custom[m].edit = 0;
                list.custom[m].last = '';
            }
        }
        return list;
    };

    this.vue = new Vue({
        el : '#admin-content',
        data : {
            background:false,
            add : false,
            input : '',
            list : self.format(data.list),
            del : {
                transfer : false,
                display : false,
                list : [],
                id : '',
                count : '',
                active : 0,
                loading: false
            }
        },
        methods : {
            _add : function () {
                self.add(this.input);
            },
            _add_display : function(){
                this.add = !this.add;
                this.input = '';
            },
            _display : function (id,deleted,index){
                var del = deleted == '0' ? 1 : 0;
                this.list.custom[index].deleted = del;
                self.display_switch(id,del);
            },
            _edit_show : function(index){
                this.list.custom[index]['edit'] = true;
                this.list.custom[index]['last'] = this.list.custom[index]['name'];
            },
            _edit_show_confirm : function(index){
                self.edit(this.list.custom[index]['id'],this.list.custom[index]['name'],function(){
                    self.vue.list.custom[index]['edit'] = false;
                });
            },
            _edit_show_cancel : function(index){
                this.list.custom[index]['edit'] = false;
                this.list.custom[index]['name'] = this.list.custom[index]['last'];
            },
            _transfer : function () {
                if(this.list.custom.length > 0 && this.list.default.count != '0'){
                    this.del.transfer = true;
                    this.del.active = this.list.custom[0].id;
                    self.display.del.show(0,this.list.default.count);
                }
            },
            _del : function (id) {
                var info = self.get_category_info(id);
                if(!!info.count) {
                    this.del.transfer = false;
                    self.display.del.show(id,info.count);
                }
                //无关联文章 直接删除
                else{
                    pop.confirm('确认删除 ?','确定',function(){
                        self.delete(id);
                    });
                }
            },
            _del_select:function(id){
                this.del.active = id;
            },
            _del_cancel:function(){
                self.display.del.hide();
            } ,
            _del_confirm : function(){
                self.delete();
            }
        }
    });
    //视图组
    this.display = {
        del : {
            show : function (id,count) {
                self.vue.background = true;
                self.vue.del.display = true;
                self.vue.del.list = self.get_category_list(id);
                self.vue.del.count = count;
                self.vue.del.id = id;
            },
            hide : function () {
                self.vue.background = false;
                self.vue.del.display = false;
                self.vue.del.list = [];
                self.vue.del.active = 0;
                self.vue.del.count = '';
                self.vue.del.id = '';
            }
        }
    };
    //获得分类列表 Exclude ID 可选
    this.get_category_list = function (id) {
        var list = [self.vue.list.default],
            c    = self.vue.list.custom,
            n    = list.concat(c),
            l    = n.length,
            r    = [];
        if(typeof id != 'undefined'){
            for(var i = 0; i < l; i++){
                (n[i].id != id) && r.push(n[i]);
            }
        }
        else{
            r = n;
        }
        return r;
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
    //删除已有文章分类
    this.delete = function(id){
        var id = id || self.vue.del.id,move = self.vue.del.active;
        self.vue.del.loading = true;
        request.get('/admin/category/delete',function(ret){
            var data;
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.update_list();
                self.display.del.hide();
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
    //更新列表
    this.update_list = function(){
        request.get('/admin/category/list',function(ret){
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.vue.list = self.format(ret.data);
            }
        });
    };

    //显示状态
    this.display_switch = function (id,deleted) {
        request.get('/admin/category/display',function(ret){
            if(ret.hasOwnProperty('code') && ret.code != 0){
                pop.error('设置失败','确定').one();
                self.update_list();
            }
        },{
            deleted : deleted,
            id : id
        });
    }

}).call(define('c_list'));