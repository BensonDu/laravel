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
            buttons: ['bold', 'italic', 'underline','h2','h3',
                {
                    name: 'anchor',
                    action: 'createLink',
                    aria: 'link',
                    tagNames: ['a'],
                    contentDefault: '<i class="fa fa-link"></i>',
                    contentFA: '<i class="fa fa-link"></i>'
                },
                'orderedlist','unorderedlist', 'quote']
        },
        anchor: {
            customClassOption: null,
            customClassOptionText: 'Button',
            linkValidation: false,
            placeholderText: '粘贴或输入链接',
            targetCheckbox: false,
            targetCheckboxText: 'Open in new window'
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
                var _this = this.$els.image;
                if(!_this.files)return pop.error( '浏览器兼容性错误','确定' ).one();
                imageCrop(_this.files,{
                    aspectRatio : 16 / 9,
                    croppedable : true,
                    finish : function (url) {
                        self.model.data.image.val = url;
                    },
                    error : function (text) {
                        pop.error( text || '上传失败','确定').one();
                    }
                });
                _this.value = '';
            },
            _del_image : function () {
                self.model.data.image.val = '';
            },
            //标题输入框 如果为默认 无标题 操作清空
            _default_clear : function () {
                if(this.title == '无标题') this.title = '';
            },
            //如果 失去焦点 为空 补全为 无标题
            _default_fill : function () {
                if(this.title == '') this.title = '无标题';
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
                if(e.keyCode == 13){
                    input = this.tag.input.replace(/ {2,20}/g,' ');
                    index = $.inArray(input, this.tag.items);
                    if(this.tag.items.length >= 5){
                        return pop.error('最多5个标签','确认').one();
                    }
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
}).call(define('controller_info'));
//文章顶部操作
(function(){
    var self = this;
    //当前文章ID;
    this.article_id = 0;
    //获取当前文章信息
    this.get_editing_article = function(){
        return {
            id : self.article_id,
            title : controller_info.model.data.title,
            summary : controller_info.model.data.summary,
            image : controller_info.model.data.image.val,
            tags : JSON.stringify(controller_info.model.data.tag.items),
            content : plugin_editor.content.serialize()['content-editor'].value
        }
    };
    //设置文章信息
    this.set_article = function(info){
        if(info.hasOwnProperty("title"))controller_info.model.data.title = info.title;
        if(info.hasOwnProperty("summary"))controller_info.model.data.summary = info.summary;
        if(info.hasOwnProperty("image"))controller_info.model.data.image.val = info.image;
        if(info.hasOwnProperty("tags"))controller_info.model.data.tag.items = info.tags;
        if(info.hasOwnProperty("content"))plugin_editor.content.setContent(info.content);
        if(info.hasOwnProperty("lastmodify"))self.vue.lastmodify = info.lastmodify;
        if(info.hasOwnProperty("post_status"))self.vue.post_status = info.post_status;
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
                post_status : 0,
                id : 0
            });
            controller_save.start();
            request.post('/user/article/save',function(ret){
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
        return true;
    };
    //发布检查文章
    this.post_check = function () {
        var info = self.get_editing_article();
        if(!info.title || info.title == ''){
            pop.error('请填写标题','确认').one();
            return false;
        }
        if(!info.summary || info.summary == ''){
            pop.error('请填写摘要','确认').one();
            return false;
        }
        if(!info.content || info.content == ''){
            pop.error('请输入正文','确认').one();
            return false;
        }
        if(!info.tags || info.tags == '[]'){
            pop.error('请设置标签','确认').one();
            return false;
        }
        if(!controller_save.check()){
            pop.error('当前文章未保存','确定').one();
            return false;
        }

        return true;
    };
    //保存当前文章
    this.save_article = function(){
        if(self.check_article()){
            self.vue.save = 'loading';
            request.post('/user/article/save',function(ret){

                if(ret.hasOwnProperty('code') && ret.code == 0){
                    controller_save.end();
                    self.vue.lastmodify = ret.data.time;
                    self.article_id = ret.data.id;
                    controller_list.update_list(self.article_id);
                    setTimeout(function(){
                        self.vue.save = 'success';
                    },600);
                }
                else{
                    pop.error('保存失败','确定').one();
                }
                setTimeout(function(){
                    self.vue.save = '';
                },1500);

            },self.get_editing_article());
        }
    };
    //保存文章 公共方法
    this.update_article_common = function(call){
        if(self.check_article()){
            request.post('/user/article/save',function(ret){

                if(ret.hasOwnProperty('code') && ret.code == 0 && ret.data.id){
                    self.vue.lastmodify = ret.data.time;
                    self.article_id = ret.data.id;
                    call();
                }
                else{
                    pop.error('保存失败','确定').one();
                }

            },self.get_editing_article());
        }
    };
    //VUE Model
    this.model = {
        el : '#article-handle',
        data : {
            lastmodify : '',
            save : '',
            contribute : 0,
            post_status : 0
        },
        methods : {
            _save : function(){
                self.save_article();
            },
            _contribute : function(){
                if(self.post_check()){
                    controller_pop.show(self.article_id);
                }
            }
        }
    };
    //绑定Vue content model;
    this.vue = new Vue(self.model);
}).call(define('controller_handle'));
//弹窗部分
(function () {
    var self = this,
        $bk  = $('#pop-background'),
        $pop = $('#pop-container');

    this.article_id = '';
    this.show = function (id) {
        //投稿管理按钮 Loading 状态
        controller_handle.model.data.contribute = 1;
        self.get_article_post(id,function () {
            $pop.addClass('post');
            $bk.addClass('show');
        });
    };
    this.hide = function () {
        $pop.removeClass('post');
        $bk.removeClass('show');
        self.article_id = '';
        self.vue.slider = '';
    };
    //获取站点
    this.get_article_post = function (id,call) {
        self.article_id = id;
        request.get('/user/post/list',function (ret) {
            if(ret.hasOwnProperty('code') && ret.code == 0){
                typeof call == 'function' && call();
                if(!!ret.data.auth){
                    self.vue.auth = ret.data.auth
                }
                else{
                    self.vue.auth = [];
                }
                if(!!ret.data.contribute){
                    self.vue.contribute = ret.data.contribute;
                }
                else{
                    self.vue.contribute = [];
                }
            }
            else{
                pop.error('获取发布信息失败','确定').one();
            }
            //投稿管理按钮 Loading 结束
            controller_handle.model.data.contribute = 0;
        },{id:id});
    };
    //获得站点分类列表
    this.get_site_category = function (id) {
        request.get('/site/category',function (ret) {
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.vue.post.category.list = ret.data;
                self.vue.post.category.text = self.get_category_name(self.vue.post.category.val);
            }
            else{
                pop.error('获得分类列表失败','确定').one();
            }
        },{
            site_id : id
        });
    };
    //获得分类名称
    this.get_category_name = function(id){
        var r = '',l = self.vue.post.category.list;
        for(var i in l){
            if(l[i].id == id){
                r = l[i].name;
            }
        }
        return r;
    };
    //投稿
    this.contribute = function (id) {
        if(self.lock) return false;
        self.lock = true;
      request.get('/user/post/contribute',function (ret) {
          if(ret.hasOwnProperty('code') && ret.code == 0){
              self.get_article_post(self.article_id);
          }
          else{
              pop.error(ret.msg || '投稿失败','确定').one();
          }
          self.lock = false
      },{
          id:self.article_id,
          site_id : id
      });
    };
    //搜索站点
    this.search = function (keyword) {
        var k = keyword || '';
        request.get('/user/site/search',function (ret) {
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.vue.search.list = ret.data;
            }
        },{
            keyword : k
        })
    };
    //添加站点
    this.add_site = function (id) {
        var l, n = [],length = self.vue.search.list.length;
        request.get('/user/site/add',function (ret) {
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.get_article_post(self.article_id);
                for(var i = 0; i < length;i++){
                    if(self.vue.search.list[i].id != id)n.push(self.vue.search.list[i]) ;
                }
                self.vue.search.list = n;
            }
        },{
            site_id : id
        })
    };
    //移除站点
    this.remove_site = function (id,name) {
        request.get('/user/site/remove',function (ret) {
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.get_article_post(self.article_id,function () {
                    self.vue.search.list.push({
                        id:id,
                        name:name
                    });
                });
            }
        },{
            site_id : id
        })  
    };
    //操作互斥锁
    this.lock = false;
    this.vue = new Vue({
        el : '#pop-container',
        data : {
            slider : '',
            auth:[],
            contribute : [],
            //分类
            post : {
                site_id : '',
                category: {
                    val : '',
                    active :false,
                    text : '',
                    list : []
                },
                type : {
                    val : '',
                    time : ''
                }
            },
            search : {
                keyword:'',
                list : []
            }

        },
        methods : {
            _close : function () {
                self.hide();
            },
            _fold : function () {
                this.slider = '';
            },
            _add : function () {
                this.slider = this.slider == 'add-site' ? '' : 'add-site';
                this._search();
            },
            _post_admin:function (site_id,category,post_status,post_time) {
                if(post_status != 'del'){
                    this.slider = 'post-admin';
                    this.post.site_id = site_id;
                    this.post.type.val  = post_status == 'start' ? 'cancel' : post_status;
                    this.post.type.time = post_time;
                    this.post.category.val = category;
                    self.get_site_category(site_id);
                }
            },
            _push_admin : function (id,enable) {
                if(enable != 'enable')return false;
                request.get('/user/push/site',function(ret){
                        if(ret.hasOwnProperty('code') && ret.code ==0){
                            self.get_article_post(self.article_id);
                        }
                        else{
                            pop.error(ret.msg || '推送更新失败','确定').one();
                        }
                    },
                    {
                        id:self.article_id,
                        site_id:id
                    }
                );
            },
            _confirm_post_admin : function () {
                var d = self.vue.post,
                    data = {
                        id : self.article_id,
                        site_id:d.site_id,
                        category : d.category.val,
                        type : d.type.val,
                        time : d.type.time
                    };
                //发布必须选择分类
                if((data.type == 'now' || data.type=='time') && (self.vue.post.category.list[0].id != '0' && (data.category == '0' || data.category == '')))return pop.error('请选择分类','确定').one();
                if(self.lock) return false;
                self.lock = true;
                request.post('/user/post/site',function(ret){
                        if(ret.hasOwnProperty('code') && ret.code ==0){
                            self.get_article_post(self.article_id);
                            self.vue.slider = '';
                        }
                        else{
                            pop.error(ret.msg || '设置失败','确定').one();
                        }
                        self.lock = false
                    },
                    data
                );
            },
            _contribute : function (id,status) {
                if(status != 'disable')self.contribute(id);
            },
            _category_display : function () {
                this.post.category.active = !this.post.category.active;
            },
            _category_select : function (c, n) {
                this.post.category.val = c;
                this.post.category.text = n;
            },
            _type_select : function (t) {
                this.post.type.val = t;
                if(t == 'time'){
                    this.post.type.time = moment().format('YYYY-MM-DD HH:mm')
                }
            },
            _add_site : function (id) {
                self.add_site(id);
            },
            _remove_site : function (id,name) {
                self.remove_site(id,name);
            },
            _search : function () {
                if(this.slider == 'add-site')self.search(this.search.keyword);
            }

        }
    });
}).call(define('controller_pop'));
//时间选择插件
(function(){
    $('#datetimepicker').datetimepicker({
        inline: true,
        sideBySide: true
    }).on('changeDate', function(){
        controller_pop.vue.post.type.time = $(this).data('date');
    });
}).call(define('controller_timerpicker'));
//文章列表
(function(){
    var self = this,
        index= 1,
        size = 20,
        total = parseInt(data.total);

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
                        content : data.content,
                        post_status : data.post_status,
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
            if(self.vue.list.length > 0 && self.vue.list[i].hasOwnProperty('id')){
                self.open_article(self.vue.list[i].id);
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
               self.fill_list(ret.data.list);
               total = parseInt(ret.data.total);
               //刷新列表之后,默认打开文章
               typeof id == 'undefined' ?  self.open_first() : self.open_article(id);
               self.btn_sta();
           }
           else{
               pop.error('数据请求错误','确定').one();
           }
       },{
           index:1,
           size : index*size,
           keyword : self.vue.search,
           type : self.vue.type
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
            list : [],
            load : 'more',
            search : '',
            type : 'all'
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
            },
            _type : function (t) {
                this.type = t;
                index = 1;
                size = 20;
                self.update_list();
            },
            _search : function () {
                index = 1;
                size = 20;
                self.update_list();
            },
            _load : function () {
                if(self.has_more()){
                    index = index+1;
                    self.vue.load = 'loading';
                    self.update_list();
                }
            }
        }
    };
    //是否有更多条目
    this.has_more = function(){
        return (index+1)*size<total;
    };
    //按钮状态
    this.btn_sta = function () {
       return self.has_more() ? (self.vue.load = 'more') : (self.vue.load = 'end');
    };
    this.vue = new Vue(self.model);

    //初始化数据渲染列表
    self.fill_list(data.list);
    //初始化加载按钮状态
    self.btn_sta();
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
//文章保存状态
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
//页面初始化
(function(){
    var self = this;

    (function(r){
        return r == '' ? controller_list.open_first() : (r == 'create' ? controller_handle.create_article() : controller_list.open_article(r))
    })(data.route);

}).call(define('controller_route'));