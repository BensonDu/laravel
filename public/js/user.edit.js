(function(){
    var self = this;

    this.w = $(window);
    this.d = $(document);
    this.content = $('#content');
    this.handle = $('#article-handle');
    this.mid = $('#mid');
    this.sw = $('#summary-switch');
    this.sw_btn = self.sw.children('.switch').children('a');
    this.sw_ms = self.sw.children('.modules').children('.summary');

}).call(define('dom'));

(function(){
    var self = this;

    //重写Nav.left fold,unfold 方法
    window.view_nav_left.fold = function(){
        return dom.mid.css('left',60), dom.content.css('padding-left',340), dom.handle.css('padding-left',340);
    };

    window.view_nav_left.unfold = function(){
        return dom.mid.css('left',140),dom.content.css('padding-left',420), dom.handle.css('padding-left',420);
    };

    this.resize_event = dom.w.resize(function(){});

    this.scroll_event = dom.w.scroll(function(){});

}).call(define('view_page_response'));

(function(){
    var self = this,
        per_module = 100;

    this.active = function(offset){
        return dom.sw_btn.removeClass('active').eq(offset).addClass('active');
    };
    this.move = function(offset){
        return dom.sw_ms.css('margin-top',-offset*per_module);
    };
    this.get_offset = function(obj){
        var $obj = $(obj);
        return ($obj.hasClass('summary') && 0) || ($obj.hasClass('image') && 1 ) || ($obj.hasClass('tag') && 2);
    };
    this.hover_event = dom.sw_btn.hover(
        function(){
            var o = self.get_offset(this);
            self.move(o);
            self.active(o);
        }
    );

}).call('view_article_switch');
//编辑器配置,插入 图片|视频 插件引入
(function(){
    var self =this;

    this.content = new MediumEditor('#content-editor',{
        placeholder: {
            text: '输入文章内容'
        },
        toolbar: {
            buttons: ['bold', 'italic', 'underline','h2','h3','anchor', 'orderedlist','unorderedlist', 'quote']
        }
    });
    this.insert_plugin = $('#content-editor').mediumInsert({
        editor: self.content,
        insertBtnPositionFix : {
            left : -50
        }
    });

}).call(define('plugin_editor'));
//配图上传,标签添加,摘要填写
(function(){
    var self = this;
    this.model = {
        el : '#article-content',
        data : {
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
            },
            title : ''
        },
        methods : {
            upload : function(){
                var _this = this.$els.image,
                    $this = $(_this),
                    exist_file = $($this).attr('exist-file'),
                    uploader   = simple.uploader({}),
                    //拼接实际URL地址
                    get_img_url = function(id, option){
                        return 'http://dn-noman.qbox.me/' + id + '?imageMogr2/thumbnail/!600x400r/gravity/Center/crop/600x400';
                    },
                    //上传进度显示
                    uploading = function(loaded, total){
                        if(loaded == 5)self.model.data.image.progress.active = true;
                        return self.model.data.image.progress.percent = parseFloat(((loaded / total) * 100).toFixed(0))+' %';
                    };

                //初始化
                uploader.on("beforeupload", function (e, file, r) {
                    uploading(5,100);
                });
                //进行中
                uploader.on("uploadprogress", function (e, file, loaded, total) {
                    uploading(loaded*0.9,total);
                });
                //成功
                uploader.on("uploadsuccess", function (e, file, r) {
                    if(r.hasOwnProperty('key')){
                        uploading(100,100);
                        self.model.data.image.val = get_img_url(r.key,r);
                        setTimeout(function(){
                            self.model.data.image.progress.active = false;
                        },1000);
                    }
                    else{
                        pop.error('上传失败','确定').one();
                    }
                });
                //错误
                uploader.on('uploaderror', function (e, file, xhr, status) {
                    pop.error('上传失败','确定').one();
                });

                if(exist_file)uploader.cancel(exist_file);
                $this.attr('exist-file', $this.val());
                uploader.upload(_this.files);

            },
            //摘要 配图 标签 输入状态 Tab 光标统一到标题输入框
            default_keydown :function(){
                $(this.$els.title).focus();
            },
            tag_del : function(){
                this.tag.items.splice($.inArray($(event.target).prev().html(), this.tag.items),1);
            },
            //空格回车生成标签; 为空 退格删除标签;绕过了满满的坑;
            tag_keydown : function(e){
                var input,index;
                if(e.keyCode == 8){
                    this.tag.input == '' && this.tag.items.pop();
                }
                if(e.keyCode == 13 || e.keyCode ==32){
                    input = this.tag.input.replace(/ /g,'');
                    index = $.inArray(input, this.tag.items);
                    if(this.tag.items.length <5 && input.length > 1){
                        if(index > -1){
                            this.tag.items.splice(index,1);
                        }
                        this.tag.items.push(input);
                        this.tag.input = '';
                    }
                }
            }
        }
    };
    //绑定Vue content model;
    this.vue = new Vue(self.model);
}).call(define('controller_vue'));

(function(){
    var self = this;

    //当前文章ID;
    this.article_id = 0;
    //获取当前文章信息
    this.get_editing_article = function(){
        return {
            id : self.article_id,
            title : controller_vue.model.data.title,
            summary : controller_vue.model.data.summary,
            image : controller_vue.model.data.image.val,
            tags : JSON.stringify(controller_vue.model.data.tag.items),
            content : plugin_editor.content.serialize()['content-editor'].value
        }
    };
    //设置文章信息
    this.set_article = function(info){
        if(info.hasOwnProperty("title"))controller_vue.model.data.title = info.title;
        if(info.hasOwnProperty("summary"))controller_vue.model.data.summary = info.summary;
        if(info.hasOwnProperty("image"))controller_vue.model.data.image.val = info.image;
        if(info.hasOwnProperty("tags"))controller_vue.model.data.tag.items = info.tags;
        if(info.hasOwnProperty("content"))plugin_editor.content.setContent(info.content);
        if(info.hasOwnProperty("lastmodify"))self.vue.lastmodify = info.lastmodify;
        if(info.hasOwnProperty("ispost"))self.vue.ispost = info.ispost;
        if(info.hasOwnProperty("id"))self.article_id = info.id;
    };
    //新建文章 产生垃圾数据
    this.create_article = function(){
        controller_list.check_article(function(){
            controller_sta.loading();
            controller_handle.set_article({
                title : '无标题',
                summary : '',
                image : '',
                tags : [],
                content : '',
                lastmodify : helper.now(),
                ispost : 0,
                id : 0
            });
            controller_save.start();
            request.post('/user/save',function(ret){
                if(ret.hasOwnProperty('code') && ret.code == 0){
                    self.vue.lastmodify = ret.data.time;
                    self.article_id = ret.data.id;
                    controller_list.update_list(self.article_id);
                }
                controller_sta.loading(1);
            },self.get_editing_article());
        });
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
    //保存当前文章
    this.save_article = function(){
        if(self.check_article()){
            self.vue.handle_sta.save = 'loading';
            request.post('/user/save',function(ret){

                if(ret.hasOwnProperty('code') && ret.code == 0){
                    controller_save.end();
                    self.vue.lastmodify = ret.data.time;
                    self.article_id = ret.data.id;
                    controller_list.update_list(self.article_id);
                    setTimeout(function(){
                        self.vue.handle_sta.save = 'success';
                    },600);
                }
                else{
                    pop.error('保存失败','确定').one();
                }
                setTimeout(function(){
                    self.vue.handle_sta.save = '';
                },1500);

            },self.get_editing_article());
        }
    };
    //发布到个人主页
    this.post_article = function(){
        if(self.check_article()){
            self.vue.handle_sta.post = 'loading';
            request.post('/user/post',function(ret){

                if(ret.hasOwnProperty('code') && ret.code == 0){
                    controller_save.end();
                    self.vue.lastmodify = ret.data.time;
                    self.article_id = ret.data.id;
                    self.vue.ispost = true;
                    setTimeout(function(){
                        self.vue.handle_sta.post = 'success';
                    },600);
                    controller_list.update_list(self.article_id);
                }
                else{
                    pop.error('发布失败','确定').one();
                }
                setTimeout(function(){
                    self.vue.handle_sta.post = '';
                },1500);

            },self.get_editing_article());
        }
    };
    //取消发布
    this.cancel_post_article = function(){
        if(self.article_id != 0){
            request.post('/user/post/cancel',function(ret){

                if(ret.hasOwnProperty('code') && ret.code == 0){
                    controller_save.end();
                    self.vue.lastmodify = ret.data.time;
                    self.vue.ispost = false;
                    controller_list.update_list(self.article_id);
                }
                else{
                    pop.error('操作失败','确定').one();
                }

            },self.get_editing_article());
        }
    };
    //投稿到站点
    this.contribute_article = function(){
        var site_list = [];
        if(self.check_article()){
            for(var i in self.vue.site_list.items){
                if(self.vue.site_list.items[i].active){
                    site_list.push(self.vue.site_list.items[i].id);
                }
            }
            if(site_list.length>0){
                request.post('/user/contribute',function(ret){

                    if(ret.hasOwnProperty('code') && ret.code == 0){
                        controller_save.end();
                        self.vue.lastmodify = ret.data.time;
                        self.article_id = ret.data.id;
                        pop.success('投稿成功','确定').one();
                        self.vue.site_list.display = false;
                        controller_list.update_list(self.article_id);
                    }
                    else{
                        pop.error(ret.msg || '投稿失败','确定').one();
                    }

                }, $.extend({},{sites:JSON.stringify(site_list)},self.get_editing_article()));
            }
        }
    };
    //VUE Model
    this.model = {
        el : '#article-handle',
        data : {
            lastmodify : '',
            ispost : false,
            handle_sta : {
                save : '',
                post : '',
                contribute : ''
            },
            site_list : {
                display : false,
                items : [
                    {
                        id : 1,
                        name : 'TECH2IPO',
                        active : true
                    }
                ],
                confirm : true
            }
        },
        methods : {
            save : function(){
                self.save_article();
            },
            post : function(){
                this.ispost ? self.cancel_post_article() : self.post_article();
            },
            contribute : function(){
                this.site_list.display = true;
            },
            select : function(index){
                var confirm = false;
                this.site_list.items[index].active = !this.site_list.items[index].active;
                for(var i in this.site_list.items){
                    if(this.site_list.items[i].active){
                        confirm = true;
                        break;
                    }
                }
                this.site_list.confirm = confirm;
            },
            select_cancle : function(){
                this.site_list.display = false;
            },
            confirm_contribute : function(){
                self.contribute_article();
            }
        }
    };
    //绑定Vue content model;
    this.vue = new Vue(self.model);
}).call(define('controller_handle'));

(function(){
    var self = this;

    this.cur_article = 0;
    //打开文章
    this.open_article = function(id){
        self.check_article(function(){
            controller_sta.loading();
            self.active_article(id);
            request.get('/user/article',function(ret){
                var data;
                if(ret.hasOwnProperty('code') && ret.code == 0){
                    data = ret.data;
                    controller_handle.set_article({
                        title : data.title,
                        summary : data.summary,
                        image : data.image,
                        tags : data.tags,
                        id : data.id,
                        ispost : data.post_status == 2,
                        content : data.content,
                        lastmodify : data.update_time
                    });
                }
                else{
                    pop.error('请求数据出错','确定').one();
                }
                controller_save.start();
                controller_sta.loading(1);
            },{id:id || ''});
        });
    };
    //列表选中状态
    this.active_article = function(id){
        self.cur_article = id;
        setTimeout(function(){
            $('.article-list').removeClass('active').each(function(){
                if($(this).data('id')==id){
                    $(this).addClass('active');
                }
            })
        },50);
    };
    //删除文章
    this.del_article = function(id){
        var is_self = self.cur_article == id;
        self.check_article(function(){
            pop.confirm('确认删除 ?','确定',function(){
                if(is_self)controller_save.end();
                request.get('/user/article/delete', function(ret){
                    if(ret.hasOwnProperty('code') && ret.code == 0){
                        self.update_list();
                    }
                    else{
                        pop.error(ret.msg,'确定');
                    }
                },{id : id});
            });
        },is_self);
    };
    //检查文章修改是否保存
    this.check_article = function(fun,bypass){
        var con = false;
        if(typeof bypass != 'undefined')con = bypass;
        if(controller_save.check() || con){
            fun();
        }
        else{
            pop.confirm('当前文章未保存','放弃修改', fun,'返回');
        }
    };
    //打开第一个,列表为空,打开新建文章遮罩
    this.open_first = function(){
        var find = false;
        for(var i in self.vue.list){
            if(self.vue.list[i].length > 0 && self.vue.list[i][0].hasOwnProperty('id')){
                self.open_article(self.vue.list[i][0].id);
                find = true;
                break;
            }
        }
        !find && controller_sta.create();
    };
    //刷新列表
    this.update_list = function(id){
       request.get('/user/article/list', function(ret){
           if(ret.hasOwnProperty('code') && ret.code == 0 ){
               self.fill_list(ret.data);
               //刷新列表之后,默认打开文章
               return typeof id == 'undefined' ?  self.open_first() : self.open_article(id);
           }
       });
    };
    //填充列表,缓存
    this.fill_list = function(list){
        self.vue.list = list;
    };
    //VUE Model
    this.model = {
        el : '#mid',
        data : {
            list : []
        },
        methods : {
            open : function(id){
                self.open_article(id);
            },
            del : function(id){
                self.del_article(id);
            },
            create : function(){
                controller_handle.create_article();
            }
        }
    };
    this.vue = new Vue(self.model);
    //默认数据渲染列表
    self.fill_list(default_data.list);
}).call(define('controller_list'));
//文章编辑器状态控制
(function(){
    var self = this;

    this.vue = new Vue({
        el : '#editor-sta',
        data : {
            sta : ''
        },
        methods : {
            create : function(){
                controller_handle.create_article();
            }
        }
    });
    this.loading = function(hide){
        return self.base(hide,'loading');
    };
    this.create = function(hide){
        return self.base(hide,'create');
    };
    this.base = function(hide, sta){
        var timer = 0;
        if(!!hide){
            if(timer) clearTimeout(timer);
            timer = setTimeout(function(){self.vue.sta = ''},300);
        }
        else{
            self.vue.sta = sta;
        }
    }
}).call(define('controller_sta'));
//是否文章变动未保存
(function(){
    var self = this,
        can = true,
        cache = {};

    this.get_cur = function(){
        var start = controller_handle.get_editing_article(),tag;
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
//页面起始
(function(){
    var self = this;

    (function(r){
        return r == '' ? controller_list.open_first() : (r == 'create' ? controller_handle.create_article() : controller_list.open_article(r))
    })(default_data.route);

}).call(define('controller_route'));