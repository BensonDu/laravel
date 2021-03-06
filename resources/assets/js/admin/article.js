/**
 * Created by Benson on 16/2/25.
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
            orderby : preload.orderby,
            order : 'desc',
            pagination : {
                total : preload.total,
                size : 10,
                index : 1,
                btns : [],
                all : 0
            },
            list : preload.list
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
            _post : function(id){
                controller_pop.display.post(id);
            },
            _edit : function(id){
                !!id && controller_pop.display.article.show(id);
            },
            _del : function(id){
                pop.confirm('确认删除 ?','确定',function(){
                    self.delete_article(id);
                });
            },
            _recovery : function(id){
                pop.confirm('还原文章 ?','确定',function(){
                    self.recovery_article(id);
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
        request.get(preload.api.get_list,function(ret){
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
    //删除文章
    this.delete_article = function(id){
        request.get(preload.api.del_article, function(ret){
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.get_data();
            }
            else{
                pop.error('操作失败','确定');
            }
        },{id : id});
    };
    //还原文章
    this.recovery_article = function(id){
        request.get(preload.api.recovery_article, function(ret){
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

    this.model = {
        el : '#pop-container',
        data : {
            display : '',
            post : {
                id : '',
                category : {
                    active : false,
                    text : '',
                    val : '',
                    list : preload.categories
                },
                type : {
                    val : 'now',/*now | time | cancel*/
                    time : ''
                }
            },
            article : {
                id : '',
                title : '',
                summary : '',
                image : {
                    val : '',
                    progress : {
                        active : false,
                        percent : '0 %'
                    }
                },
                tag : {
                    items : [],
                    input : ''
                }
            }
        },
        methods : {
            _close : function(){
                var t = this;
                self.save_confirm(function(){
                    t.display = '';
                    t.post.category.active = 0;
                    if(controller_admin.model.data.background) controller_admin.model.data.background = false;
                });
            },
            _category_display : function(){
                this.post.category.active = !this.post.category.active;
            },
            _category_select : function(c,n){
                this.post.category.val = c;
                this.post.category.text = n;
            },
            _type_select : function(t){
                this.post.type.val = t;
                t = 'time' && (this.post.type.time = moment().format('YYYY-MM-DD HH:mm'));
            },
            _confirm_post : function(){
                var t = this;
                self.confirm_post(function(){
                    t.display = '';
                    t.post.category.active = 0;
                });
            },
            _confirm_article : function(){
                self.save_article();
            },
            //删除tag
            _tag_del : function(){
                var t = this.article.tag;
                t.items.splice($.inArray($(event.target).prev().html(), t.items),1);
            },
            //空格回车生成标签; 为空 退格删除标签;绕过了满满的坑;
            _tag_keydown : function(e){
                var input,index,t = this.article.tag;
                if(e.keyCode == 8){
                    t.input == '' && t.items.pop();
                }
                if(e.keyCode == 13){
                    input = t.input.replace(/ {2,20}/g,' ');
                    index = $.inArray(input, t.items);
                    if(t.items.length >= 5){
                        return pop.error('最多5个标签','确认').one();
                    }
                    if(t.items.length <5 && input.length > 1){
                        if(index > -1){
                            t.items.splice(index,1);
                        }
                        t.items.push(input);
                        t.input = '';
                    }
                }
            },
            //删除图片
            _del_image : function(){
                this.article.image.val = '';
            },
            //上传图片
            _upload_image : function(){
                var _this = this.$els.image,
                    d = self.model.data.article;
                if(!_this.files)return pop.error( '浏览器兼容性错误','确定' ).one();
                imageCrop(_this.files,{
                    aspectRatio : 16 / 9,
                    croppedable : true,
                    finish : function (url) {
                        d.image.val = url;
                    },
                    error : function (text) {
                        pop.error( text || '上传失败','确定').one();
                    }
                });
                _this.value = '';
            }
        }
    };
    this.display = {
        post : function(id){
           self.model.data.display = 'post';
           self.get_post_info(id);
        },
        article : {
            show : function(id){
                //列表遮盖
                controller_admin.model.data.background = true;
                //弹窗
                self.model.data.display = 'article';
                //填充文章信息
                self.get_article_info(id);
            },
            hide : function(){
                //列表遮盖
                controller_admin.model.data.background = false;
                //弹窗
                self.model.data.display = '';
            }
        }
    };
    //确认保存
    this.save_confirm = function(call){
        if(controller_save.check()){
            call();
        }
        else{
            pop.confirm('当前文章未保存','放弃修改',function(){
                call();
                controller_save.end();
            },'返回');
        }

    };
    //请求文章信息
    this.get_article_info = function(id){
        request.get(preload.api.get_article_info,function(ret){
            var data;
            if(ret.hasOwnProperty('code') && ret.code == 0){
                data = ret.data;
                self.set_article({
                    title : data.title,
                    summary : data.summary,
                    image : data.image,
                    tags : data.tags,
                    id : id,
                    content : data.content
                });
                controller_save.start();
            }
            else{
                pop.error('请求数据出错','确定').one();
            }
        },{id:id});
    };
    //获取编辑中文章信息
    this.get_editing_article = function(){
        var d = self.model.data.article;
        return {
            id : d.id,
            title : d.title,
            summary : d.summary,
            image : d.image.val,
            tags : JSON.stringify(d.tag.items),
            content : plugin_editor.content.serialize()['content-editor'].value
        }
    };
    //保存当前文章
    this.save_article = function(){
        if(self.check_article()){
            request.post(preload.api.save_article,function(ret){
                if(ret.hasOwnProperty('code') && ret.code == 0){
                    self.display.article.hide();
                    self.update_list();
                    controller_save.end();
                }
                else{
                    pop.error('保存失败','确定').one();
                }
            },self.get_editing_article());
        }
    };
    //设置文章信息
    this.set_article = function(info){
        var d = self.model.data.article;
        if(info.hasOwnProperty("title"))d.title = info.title;
        if(info.hasOwnProperty("summary"))d.summary = info.summary;
        if(info.hasOwnProperty("image"))d.image.val = info.image;
        if(info.hasOwnProperty("tags"))d.tag.items = info.tags;
        if(info.hasOwnProperty("content"))plugin_editor.content.setContent(info.content);
        if(info.hasOwnProperty("id"))d.id = info.id;
    };
    //检查文章
    this.check_article = function(){
        var info = self.get_editing_article();
        if(!info.title || info.title == ''){
            pop.error('请填写标题','确认').one();
            return false;
        }
        if(!info.summary || info.summary == ''){
            pop.error('请填写摘要','确认').one();
            return false;
        }
        return true;
    };
    //Get article post info
    this.get_post_info = function(id){
        request.get(preload.api.get_post_info,function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.set_post(id,ret.data.category,ret.data.type,ret.data.time);
                }
                else{
                    pop.error('获取数据失败','确定').one();
                }
            },
            {
                id : id
            }
        );
    };
    //Get category name
    this.get_category_name = function(id){
        var r = '',l = self.model.data.post.category.list;
           for(var i in l){
               if(l[i].id == id){
                   r = l[i].name;
               }
           }
        return r;
    };
    //Default post
    this.set_post = function(id,category,type,time){
        var d = self.model.data.post;
        d.id = id;
        d.category.val = category;
        d.category.text = self.get_category_name(category);
        d.type.val = type;
        !!time && (d.type.time = time);
    };
    //Save post
    this.confirm_post = function(call){
        var d = self.model.data.post,
            data = {
                id : d.id,
                category : d.category.val,
                type : d.type.val,
                time : d.type.time
            };
        //发布必须选择分类
        if((data.type == 'now' || data.type=='time') && (d.category.list[0].id != '0' && (data.category == '0' || data.category == '')))return pop.error('请选择分类','确定').one();
        request.get(preload.api.save_post,function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.update_list();
                    call();
                }
                else{
                    pop.error('设置失败','确定').one();
                }
            },
            data
        );

    };
    this.update_list = function(){
        controller_admin.get_data();
    };
    this.vue = new Vue(self.model);
}).call(define('controller_pop'));

(function(){
    $('#datetimepicker').datetimepicker({
        inline: true,
        sideBySide: true
    }).on('changeDate', function(){
        controller_pop.model.data.post.type.time = $(this).data('date');
    });
}).call(define('controller_timerpicker'));

//是否文章变动未保存
(function(){
    var self = this,
        can = true,
        cache = {};

    this.get_cur = function(){
        var start = controller_pop.get_editing_article(),tag;
        return start.title+start.summary+start.image+start.tags+start.content;
    };
    //已打开文章,缓存起始状态
    this.start = function(){
        can = false;
        cache = self.get_cur();
    };
    //终止检查,文章被删除
    this.end = function(){
        can = true;
    };
    //是否已经打开文章,且文章未变动
    this.check = function(){
        return can || cache == self.get_cur();
    };

    // 关闭窗口时弹出确认提示
    dom.w.bind('beforeunload', function(){
        if(!self.check())
            return '当前文章未保存,确定关闭?';
    });

}).call(define('controller_save'));