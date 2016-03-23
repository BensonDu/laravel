'use strict';
(function () {

    this.active = function(){
        Sortable.create(document.getElementById('box-container'), {
            animation: 300,
            draggable: '.box',
            handle: '.star',
            ghostClass: "drag-ghost",
            onUpdate: function () {
                var d = $('.box'),order = [], i;
                d.each(function(){
                    i = $(this).data('index');
                    !!i && order.push(i);
                });
                controller_list.save_order(order);
            }
        });
    };

}).call(define('plugin_sortable'));

(function(){
    var self = this;

    this.vue = new Vue({
        el : '#admin-content',
        data : {
            background : false,
            total : default_data.list.length,
            list : default_data.list
        },
        created: function () {
            plugin_sortable.active();
        },
        methods : {
            _open : function(id){
                controller_pop.display.edit(id);
            },
            _del : function(id){
                pop.confirm('确认删除 ?','确定',function(){
                    self.del_star(id);
                });
            },
            _add : function(){
                controller_pop.display.add();
            }
        }
    });
    //更新列表
    this.update_list = function(){
        request.get('/admin/star/list',function(ret){
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.vue.list = ret.data;
                self.vue.total = ret.data.length;
            }
        });
    };
    //删除精选
    this.del_star = function(id){
        request.get('/admin/star/del',function(ret){
            var data;
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.update_list();
            }
            else{
                pop.error('操作失败','确定').one();
            }
        },{id:id});
    };
    //保存排序
    this.save_order = function(order){
        request.get('/admin/star/order/save',function(ret){
            var data;
            if(ret.hasOwnProperty('code') && ret.code == 0){

            }
            else{
                pop.error('排序错误','确定').one();
            }
        },{order:order});
    }

}).call(define('controller_list'));

(function(){
    var self = this;
    this.vue = new Vue({
        el : '#pop-container',
        data : {
            id :'',
            display : '',
            last_display : '',
            type : {
                name : '',
                link : '',
                article : {
                    id: 0,
                    title : '',
                    time : ''
                },
                special : {
                    id : 0,
                    title : '',
                    time : ''
                }
            },
            image : {
                val : '',
                progress : {
                    active : false,
                    percent : '0 %'
                }
            },
            search : {
                keyword : '',
                list : []
            },
            title : '',
            category : ''
        },
        methods : {
            _select_type : function(type){
                this.type.name = type;
            },
            _search_article : function(){
                this.last_display = this.display;
                this.display = 'search';
                self.get_article_list();
            },
            _search_special : function(){
                this.last_display = this.display;
                this.display = 'search';
                self.get_special_list();
            },
            _search_back : function(){
                this.display = this.last_display;
                this.search.keyword = '';
                this.search.list = [];
            },
            _search_action : function(){
                if(this.type.name == 'article'){
                    self.get_article_list();
                }
                if(this.type.name == 'special'){
                    self.get_special_list();
                }
            },
            _search_select : function(id,title,name,category,image){
                if(this.type.name == 'article'){
                    this.type.article.id = id;
                    this.type.article.title = this.title = title;
                    this.type.article.time = name;
                    this.category = category;
                    this.image.val = image;
                }
                if(this.type.name == 'special'){
                    this.type.special.id = id;
                    this.type.special.title = this.title = title;
                    this.type.special.time = name;
                    this.image.val = image;
                    this.category = category;
                }
                this._search_back();
            },
            //删除图片
            _del_image : function(){
                this.image.val = '';
            },
            //上传图片
            _upload_image : function(){
                var _this = this.$els.image,
                    $this = $(_this),
                    exist_file = $($this).attr('exist-file'),
                    uploader   = simple.uploader({}),
                    d = self.vue,
                //拼接实际URL地址
                    get_img_url = function(id, option){
                        return 'http://dn-noman.qbox.me/' + id + '?imageMogr2/thumbnail/!600x400r/gravity/Center/crop/600x400';
                    },
                //上传进度显示
                    uploading = function(loaded, total){
                        if(loaded == 5)d.image.progress.active = true;
                        return d.image.progress.percent = parseFloat(((loaded / total) * 100).toFixed(0))+' %';
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
                        d.image.val = get_img_url(r.key,r);
                        setTimeout(function(){
                            d.image.progress.active = false;
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
            _close : function(){
                self.display.hide();
            },
            _confirm : function(){
                if(this.id ==''){
                    self.add_star();
                }
                else{
                    self.save_star();
                }
            },
            _stop_tab : function(e){
                e.preventDefault();
                return false;
            }
        }
    });
    //添加精选
    this.add_star = function(){
        if(!self.check_form())return false;
        request.get('/admin/star/add',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.display.hide();
                    self.update_list();
                }
            },
            self.get_form());
    };
    //保存精选
    this.save_star = function(){
        if(!self.check_form())return false;
        request.get('/admin/star/save',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.display.hide();
                    self.update_list();
                }
            },
            self.get_form());
    };
    //获取文章列表
    this.get_article_list = function(){
        request.get('/admin/star/articles',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.vue.search.list = ret.data;
                }
            },
            {
                keyword : self.vue.search.keyword
             }
        );
    };
    //获取专题列表
    this.get_special_list = function(){
        request.get('/admin/star/specials',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.vue.search.list = ret.data;
                }
            },
            {
                keyword : self.vue.search.keyword
            }
        );
    };
    //刷新列表
    this.update_list = function(){
        controller_list.update_list();
    };
    //检查表单信息
    this.check_form = function(){
        var form = self.get_form(),
            w = (form.title == '' && '标题') || (form.category == '' && '类型') || (form.type == '' && '跳转信息') || (!form.jump_info && '跳转信息') || (form.image == '' && '配图') || false;
        if(!w){
            return true;
        }
        else{
            pop.error(w+'为空','确定').one();
            return false;
        }
    };
    //获取表单信息
    this.get_form = function(){
        var d = self.vue,
            ret = {
                id : d.id,
                title : d.title,
                category : d.category,
                image : d.image.val,
                type : d.type.name
            };
        if(d.type.name == 'article'){
            ret.jump_info = d.type.article.id;
        }
        if(d.type.name == 'special'){
            ret.jump_info = d.type.special.id;
        }
        if(d.type.name == 'link'){
            ret.jump_info = d.type.link;
        }
        return ret;
    };
    //填充信息
    this.fill_form = function(data){
        var d = self.vue;
        d.title = data.title;
        d.category =data.category;
        d.image.val = data.image;
        d.type.name = data.type;
        switch (data.type){
            case 'link':
                d.type.link = data.jump_info;
                break;
            case 'article':
                d.type.article.id = data.article.id;
                d.type.article.title = data.article.title;
                d.type.article.time = data.article.create_time;
                break;
            case 'special':
                d.type.special.id = data.special.id;
                d.type.special.title = data.special.title;
                d.type.special.time = data.special.create_time;
                break;
            default :
        }
    };
    //获取精选信息
    this.get_info = function(id){
        request.get('/admin/star/info',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                   self.fill_form(ret.data);
                }
            },
            {
                id:id
            });
    };
    //背景视图控制
    this.background = {
        show : function(){
            controller_list.vue.background = true;
        },
        hide : function(){
            controller_list.vue.background = false;
        }
    };
    //弹窗视图控制
    this.display = {
        add :  function(){
            self.vue.id = '';
            self.background.show();
            self.vue.display = 'add';
            self.vue.type.name = 'link';
        },
        edit : function(id){
            self.vue.id = id;
            self.background.show();
            self.vue.display = 'edit';
            self.get_info(id);
        },
        hide : function(){
            var d = self.vue;
            self.background.hide();
            d.display = '';
            d.id = '';
            d.type.article.title = '';
            d.type.article.id = 0;
            d.type.article.time = '';
            d.type.article.time = '';
            d.type.special.id = 0;
            d.type.special.title = '';
            d.type.special.time = '';
            d.type.link = '';
            d.type.name = 'link';
            d.search.list = [];
            d.search.keyword = '';
            d.image.val = '';
            d.title = '';
            d.category = '';
        }
    }
}).call(define('controller_pop'));