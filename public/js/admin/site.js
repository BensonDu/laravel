(function () {
    var self = this;

    if(!data.hasOwnProperty('base'))return false;

    this.vue = new Vue({
        el : '#admin-site-base',
        data : {
            save : '',
            domain : data.base.domain,
            name:data.base.name,
            slogan:data.base.slogan,
            keywords : data.base.keywords,
            description: data.base.description
        },
        methods : {
            _clear : function () {
                this.name = this.slogan = this.keywords = this.description = '';
            },
            _save : function () {
                if(self.check()){
                    self.vue.save = 'loading';
                    request.post('/admin/site/base',function (ret) {
                        self.vue.save = 'done';
                        if(ret.hasOwnProperty('code') && ret.code == 0){
                            setTimeout(function () {
                                location.reload();
                            },1000);
                        }
                    },self.form());
                }
            }
        }
    });
    //获取表单
    this.form = function () {
        var d = self.vue;
        return {
            name : d.name,
            slogan : d.slogan,
            keywords : d.keywords,
            description : d.description
        }
    };
    //检查表单
    this.check = function () {
      var f = self.form(),t = '';
        if(f.description == '')t = '站点描述为空';
        if(f.keywords == '')t = '站点关键词为空';
        if(f.slogan == '')t = '站点 Slogan 为空';
        if(f.name == '')t = '站点名称为空';
        if(t != ''){
            pop.error(t,'确定').one();
            return false;
        }
        return true;
    };
}).call(define('c_base'));

(function () {
    var self = this;
    if(!data.hasOwnProperty('logo'))return false;
    this.vue = new Vue({
        el : '#admin-site-logo',
        data : {
            save : '',
            logo : data.logo.logo,
            mobile_logo : data.logo.mobile_logo,
            thirdparty_logo : data.logo.thirdparty_logo,
            favicon : data.logo.favicon
        },
        methods : {
            _clear : function (type) {
                this[type] = '';
            },
            _upload : function (type) {
                var _this = this.$els[type];
                if(!_this.files)return pop.error( '浏览器兼容性错误','确定' ).one();
                imageCrop(_this.files,{
                    aspectRatio :  (type == 'mobile_logo' || type == 'thirdparty_logo') ? 3 : 1,
                    croppedable : true,
                    finish : function (url) {
                        self.vue[type] = type == 'favicon' ? url+'?imageView2/2/w/32' : url;
                    },
                    error : function (text) {
                        pop.error( text || '上传失败','确定').one();
                    }
                });
                _this.value = '';
            },
            _save : function () {
                if(self.check()){
                    self.vue.save = 'loading';
                    request.post('/admin/site/logo',function (ret) {
                        self.vue.save = 'done';
                        if(ret.hasOwnProperty('code') && ret.code == 0){
                            setTimeout(function () {
                                location.reload();
                            },1000);
                        }
                    },self.form());
                }
            }
        }
    });
    this.form = function () {
        return {
            logo : self.vue.logo,
            mobile_logo : self.vue.mobile_logo,
            thirdparty_logo : self.vue.thirdparty_logo,
            favicon : self.vue.favicon
        };
    };
    this.check = function () {
        var f = self.form(),t = '';
        if(f.logo == '')t = '请上传站点 Logo';
        if(f.mobile_logo == '')t = '请上传移动站点 Logo';
        if(f.thirdparty_logo == '')t = '请上传渠道 Logo';
        if(f.favicon == '')t = '请上传站点 icon';
        if(t != ''){
            pop.error(t,'确定').one();
            return false;
        }
        return true;
    };
}).call(define('c_logo'));

(function () {
    var self = this;
    if(!data.hasOwnProperty('social'))return false;
    this.vue = new Vue({
        el : '#admin-site-social',
        data : {
            save : '',
            weixin : data.social.weixin,
            weibo : data.social.weibo,
            email : data.social.email
        },
        methods : {
            _clear : function (type) {
                this[type] = '';
            },
            _upload : function (type) {
                var _this = this.$els[type];
                if(!_this.files)return pop.error( '浏览器兼容性错误','确定' ).one();
                imageCrop(_this.files,{
                    aspectRatio :  1,
                    croppedable : true,
                    finish : function (url) {
                        self.vue[type] =  url;
                    },
                    error : function (text) {
                        pop.error( text || '上传失败','确定').one();
                    }
                });
                _this.value = '';
            },
            _save : function () {
                if(self.check()){
                    self.vue.save = 'loading';
                    request.post('/admin/site/social',function (ret) {
                        self.vue.save = 'done';
                        if(ret.hasOwnProperty('code') && ret.code == 0){
                            setTimeout(function () {
                                location.reload();
                            },1000);
                        }
                    },self.form());
                }
            }
        }
    });
    this.form = function () {
        return {
            weixin : self.vue.weixin,
            weibo : self.vue.weibo,
            email : self.vue.email
        };
    };
    this.check = function () {
        var f = self.form(),t = '';
        if(f.weixin == '')t = '请上传微信二维码';
        if(f.weibo == '')t = '请填写微博地址';
        if(!constant.regex().mail.test(f.email.toLowerCase()))t = '请输入正确E-mail';
        if(t != ''){
            pop.error(t,'确定').one();
            return false;
        }
        return true;
    };
}).call(define('c_social'));

(function(){
    var self = this;
    
    if(!data.hasOwnProperty('nav'))return false;
    
    this.model = {
        el : '#admin-content',
        data : {
            background : false,
            list : data.nav.list
        },
        methods : {
            _add : function () {
                c_pop.display.add.show();
            },
            _edit : function(id,name,type,display,link){
                !!id && c_pop.display.edit.show(id,name,type,display,link);
            },
            _del : function(id,type){
                if(type == 2){
                    pop.confirm('确认删除 ?','确定',function(){
                        self.del(id);
                    });
                }
            }
        }
    };
   //刷新列表
    this.refresh = function () {
        request.get('/admin/site/nav/list', function(ret){
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.model.data.list = ret.data;
            }
            else{
                pop.error('获取数据失败','确定');
            }
        });
    };
    //删除导航
    this.del = function(id){
        request.get('/admin/site/nav/del', function(ret){
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.refresh();
            }
            else{
                pop.error('操作失败','确定');
            }
        },{id : id});
    };
    this.vue = new Vue(self.model);
}).call(define('c_nav'));

(function () {
    var self = this;
    this.vue = new Vue({
        el : '#pop-container',
        data : {
            display : '',
            nav : {
                id : '',
                name : '',
                type : '',
                link : '',
                display : ''
            }
        },
        methods : {
            _slide : function () {
                this.nav.type != '1' && (this.nav.display = !this.nav.display);
            },
            _close:function () {
                self.display.hide();
            },
            _confirm : function () {
                return self.check() && (this.nav.id == '' ? self.add() : self.update());
            }
        }
    });
    this.check = function () {
        var d = self.form(),t = '';
        if(d.name == '') t = '请输入导航名称';
        if(d.type == '2' && d.link == '') t= '请输入导航链接';
        if(t != ''){
            pop.error(t,'确定').one();
            return false;
        }
        return true;
    };
    this.form = function () {
        var f = self.vue.nav;
        return {
            id:f.id,
            name:f.name,
            type:f.type,
            link:f.link,
            display:f.display
        }
    };
    this.add = function () {
        request.post('/admin/site/nav/add', function(ret){
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.display.hide();
                c_nav.refresh();
            }
            else{
                pop.error('添加导航失败','确定').one();
            }
        },self.form());
    };
    this.update = function () {
        request.post('/admin/site/nav/update', function(ret){
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.display.hide();
                c_nav.refresh();
            }
            else{
                pop.error('更新导航失败','确定').one();
            }
        },self.form());
    };
    this.display = {
        add : {
            show:function(){
                c_nav.model.data.background = true;
                self.vue.display = 'add';
            }
        },
        edit : {
            show: function (id,name,type,display,link) {
                var d = self.vue.nav;
                c_nav.model.data.background = true;
                self.vue.display = 'edit';
                d.id = id;
                d.name = name;
                d.type = type;
                d.display = display;
                d.link = link;
            }
        },
        hide : function(){
            var d = self.vue.nav;
            c_nav.model.data.background = false;
            self.vue.display = '';
            d.id = '';
            d.name = '';
            d.type = 2;
            d.display = 0;
            d.link = '';
        }
    };
}).call(define('c_pop'));

(function () {
    var self = this;
    if(!data.hasOwnProperty('contribution'))return false;
    this.vue = new Vue({
        el : '#admin-site-contribution',
        data : {
            save : '',
            contribute : data.contribution.contribute
        },
        methods : {
            _slide : function (item) {
                this[item] = ! this[item];
            },
            _save : function () {
                self.vue.save = 'loading';
                request.post('/admin/site/contribution',function (ret) {
                    self.vue.save = 'done';
                    setTimeout(function () {
                        self.vue.save = '';
                    },1000);
                },self.form());
            }
        }
    });
    this.form = function () {
        return {
            contribute : self.vue.contribute
        };
    };
}).call(define('c_contribution'));

(function () {
    var self = this;
    if(!data.hasOwnProperty('comment'))return false;
    this.vue = new Vue({
        el : '#admin-site-comment',
        data : {
            save : '',
            open : data.comment.open,
            ex : data.comment.ex
        },
        methods : {
            _slide : function (item) {
                this[item] = this[item] =='true' ? 'false' : 'true';
                if(item == 'open' && this[item] != 'true' ){
                    this.ex = false;
                }
            },
            _save : function () {
                self.vue.save = 'loading';
                request.post('/admin/site/comment',function (ret) {
                    self.vue.save = 'done';
                    setTimeout(function () {
                        self.vue.save = '';
                    },1000);
                },self.form());
            }
        }
    });
    this.form = function () {
        return {
            comment : self.vue.open,
            comment_ex : self.vue.ex
        };
    };
}).call(define('c_comment'));