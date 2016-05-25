/**
 * Created by Benson on 16/3/2.
 */
(function () {

    Sortable.create(document.getElementById('special-article-container'), {
        animation: 300,
        draggable: '.special-article',
        handle: '.special-article',
        ghostClass: "drag-ghost",
        onUpdate: function () {
            var d = $('.special-article'),order = [], i;
            d.each(function(){
                i = $(this).data('id');
                !!i && order.push(i);
            });
            controller_pop.selected = order;
        }
    });

}).call(define('plugin_sortable'));

(function(){
    var self = this;

    this.model = {
        el : '#admin-content',
        data : {
            background : false,
            search : {
                keyword : ''
            },
            orderby : default_data.orderby,
            order : 'desc',
            pagination : {
                total : default_data.total,
                size : 10,
                index : 1,
                btns : [],
                all : 0
            },
            list : default_data.list
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
            _edit : function(id){
                controller_pop.display.edit.show(id);
            },
            _post : function(id,status){
                controller_pop.display.post.show(id,status);
            },
            _add : function(){
                controller_pop.display.add.show();
            },
            _del : function(id){
                pop.confirm('确认删除 ?','确定',function(){
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
        request.get('/admin/special/list',function(ret){
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
        request.get('/admin/special/delete', function(ret){
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

(function(){
    var self = this;
    this.selected = [];
    this.vue = new Vue({
        el : '#pop-container',
        data : {
            display : '',
            unfold : false,
            special : {
                id : '',
                title : '',
                summary:'',
                cover : {
                    val : '',
                    progress : {
                        active : false,
                        percent : '0 %'
                    }
                },
                bk : {
                    val : '',
                    progress : {
                        active : false,
                        percent : '0 %'
                    }
                },
                list : [],
                post : ''
            },
            search :{
                keyword : '',
                list : []
            }
        },
        methods : {
            _close : function(){
                self.display.hide();
            },
            _fold : function(){
                this.unfold = !this.unfold;
                if(this.unfold)self.get_article_list();
            },
            _del_cover_image : function(){
                this.special.cover.val = '';
            },
            _del_bk_image : function(){
                this.special.bk.val = '';
            },
            _upload_cover_image : function(){
                var _this = this.$els.cover,
                    d = self.vue.special.cover;
                if(!_this.files)return pop.error( '浏览器兼容性错误','确定' ).one();
                imageCrop(_this.files,{
                    aspectRatio : 16 / 9,
                    croppedable : true,
                    finish : function (url) {
                        d.val = url;
                    },
                    error : function (text) {
                        pop.error( text || '上传失败','确定').one();
                    }
                });
                _this.value = '';
            },
            _upload_bk_image : function(){
                var _this = this.$els.bk,
                    d = self.vue.special.bk;
                if(!_this.files)return pop.error( '浏览器兼容性错误','确定' ).one();
                imageCrop(_this.files,{
                    aspectRatio : 16 / 9,
                    croppedable : false,
                    finish : function (url) {
                        d.val = url+'?imageMogr2/thumbnail/900000@';
                    },
                    error : function (text) {
                        pop.error( text || '上传失败','确定').one();
                    }
                });
                _this.value = '';
            },
            _confirm_special : function(){
                self.vue.special.id == '' ? self.add() : self.save();
            },
            _confirm_post : function(){
                self.post();
            },
            _remove_article : function(id){
                self.select_cancel(id);
            },
            _search : function(){
                self.get_article_list();
            },
            _select : function(id,index){
                self.select_article(id);
                this.search.list.splice(index,1);
            },
            _post_type : function(type){
                this.special.post = type;
            }
        }
    });
    this.display = {
        add : {
            show:function(){
                controller_admin.model.data.background = true;
                self.vue.display = 'add';
            }
        },
        edit : {
            show: function (id) {
                controller_admin.model.data.background = true;
                self.vue.display = 'edit';
                self.vue.special.id = id;
                self.get_special_info();
            }
        },
        post : {
            show:function(id,status){
                controller_admin.model.data.background = true;
                self.vue.display = 'post';
                self.vue.special.id = id;
                self.vue.special.post = status;
            }
        },
        hide : function(){
            controller_admin.model.data.background = false;
            self.vue.display = '';
            self.vue.special.id='';
            self.empty();
        }
    };
    //填充表单
    this.fill = function(data){
        !!data['bg_image'] && (self.vue.special.bk.val = data['bg_image']);
        !!data['image'] && (self.vue.special.cover.val = data['image']);
        !!data['title'] && (self.vue.special.title = data['title']);
        !!data['summary'] && (self.vue.special.summary = data['summary']);
        !!data['id'] && (self.vue.special.id = data['id']);
        if(data['list']){
            self.vue.special.list = data.list;
            for(var i in data.list){
                self.selected.push(data.list[i].id);
            }
        }
    };
    //获取表单
    this.form = function(){
      return {
          title : self.vue.special.title,
          summary : self.vue.special.summary,
          id : self.vue.special.id,
          image :self.vue.special.cover.val,
          bg_image :self.vue.special.bk.val,
          list :self.selected
      }
    };
    //检查表单
    this.check = function(){
        var form = self.form();
            w = (form.title == '' && '标题') || (form.summary == '' && '描述') || (form.image == '' && '封面图片') || (!form.bg_image && '背景图片') || (form.list.length == 0 && '文章列表') || false;
        if(!w){
            return true;
        }
        else{
            pop.error(w+'为空','确定').one();
            return false;
        }
    };
    //表单清空
    this.empty = function(){
        self.selected = [];
        self.vue.unfold = false;
        self.vue.special.id = '';
        self.vue.special.title = '';
        self.vue.special.summary = '';
        self.vue.special.list = [];
        self.vue.special.cover.val = '';
        self.vue.special.bk.val = '';
        self.vue.search.list = [];
        self.vue.search.keyword = '';
        self.vue.special.post = '';

    };
    //取消选择
    this.select_cancel = function(id){
        var list = self.vue.special.list,ret = [],removed = {};
        for(var i in list){
            if(list[i].id != id){
                ret.push(list[i]);
            }
            else{
                removed = list[i];
            }
        }
        self.selected.splice($.inArray(id,self.selected),1);
        self.vue.special.list = ret;
        self.vue.search.list.push(removed);
    };
    //选择文章
    this.select_article = function(id){
        var info = self.get_article_info(id);
        if(!!info.id && $.inArray(info.id,self.selected) == -1){
            self.vue.special.list.push(info);
            self.selected.push(info.id);
        }
    };
    //搜索结果过滤
    this.search_filter = function(list){
        var l = [];
        for(var i in list){
            if($.inArray(list[i].id, self.selected) == -1){
                l.push(list[i]);
            }
        }
        self.vue.search.list = l;
    };
    //保存专题
    this.save = function(){
        if(!self.check())return false;
        request.get('/admin/special/save',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.display.hide();
                    self.update_list();
                }
            },
            self.form());
    };
    //添加专题
    this.add = function(){
        if(!self.check())return false;
        request.get('/admin/special/add',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.display.hide();
                    self.update_list();
                }
            },
            self.form());
    };
    //获得文章信息
    this.get_article_info = function(id){
        var list = self.vue.search.list,ret = {};
        for(var i in list){
            if(!!list[i].id && list[i].id == id){
                ret = list[i];
            }
        }
        return ret;
    };
    //获取文章列表
    this.get_article_list = function(){
        request.get('/admin/special/articles',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.search_filter(ret.data);
                }
            },
            {
                keyword : self.vue.search.keyword
            }
        );
    };
    //获取专题信息
    this.get_special_info = function(){
        request.get('/admin/special/info',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.fill(ret.data);
                }
            },
            {
                id : self.vue.special.id
            }
        );
    };
    //发布
    this.post = function(){
        request.get('/admin/special/post',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.display.hide();
                    self.update_list();
                }
            },
            {
                id : self.vue.special.id,
                post : self.vue.special.post == 'now' ? 1 : 0
            }
        );
    };
    //更新列表
    this.update_list = function(){
        controller_admin.get_data();
    }
}).call(define('controller_pop'));