(function () {
    var self    = this,
        id      = location.pathname.match(/\d{3,10}/)[0],
        redirect= global.platform.home+'/account/login?redirect='+encodeURIComponent(location.href),
        qrcreated = false;
    this.vue = new Vue({
        el : '#detail-handle',
        data : {
            like : data.like,
            favorite : data.favorite,
            likes : data.likes,
            favorites : data.favorites,
            qr : 'http://dn-acac.qbox.me/tech2ipoqrcode.jpg'
        },
        methods : {
            _like : function () {
                self.like();
            },
            _favorite : function () {
                self.fav();
            },
            _qr : function () {
                !qrcreated && self.qr();
            }
        }
    });
    this.like = function () {
        request.get('/social/like',function(ret){
            if(ret.hasOwnProperty('code')){
                if(ret.code ==0){
                    self.vue.like  = (ret.data.valid == 'LIKE');
                    self.vue.likes = ret.data.count;
                }
                else if(ret.code ==40003){
                    self.login();
                }
            }

        },{
            id : id
        });
    };
    this.fav = function(){
        request.get('/social/favorite',function(ret){
            if(ret.hasOwnProperty('code')){
                if(ret.code ==0){
                    self.vue.favorite = (ret.data.valid == 'FAV');
                    self.vue.favorites = ret.data.count;
                }
                else if(ret.code ==40003){
                    self.login();
                }
            }

        },{
            id : id
        });
    };
    this.login = function(){
        pop.confirm('操作首先需登录','前往登录', function(){
            location.href = redirect;
        },'返回');
    };
    this.qr = function () {
        var dom = $('#qr-container'),
        url=location.href.split('?')[0];
        dom.qrcode({
            render:'image',
            width: 200,
            height: 200,
            color: "#3a3",
            text: url,
            showCloseButton: false
        });
        qrcreated = true;
    }
}).call(define('c_handle'));

(function () {
    var self = this,
        //站点
        site = {
            id : data.site.id,
            name : data.site.name
        },
        //登录用户信息
        user = {
            id : global.user.id,
            avatar : global.user.avatar || 'http://qiniu.cdn-chuang.com/question%20mark.png',
            name : global.user.name,
            role : global.user.role
        },
        redirect= global.platform.home+'/account/login?redirect='+encodeURIComponent(location.href),
        shortcut = navigator.userAgent.indexOf('Mac OS X') != -1 ? 'Command + Enter 键回复' : 'Ctrl + Enter 键回复',
        lock = false;
    //站点关闭评论
    if(data.comment != '1')return false;
    this.save = [];
    this.vue = new Vue({
        el : '#comment-container',
        data : {
            site : site,
            user : user,
            orderby : 'hot',
            reply : false,
            input : '',
            list : [],
            shortcut : shortcut
        },
        methods : {
            _login : function () {
                location.href = redirect;
            },
            _like : function (id,index,valid,deep) {
                self.comment_like(id,valid,function () {
                    if(typeof deep == 'undefined'){
                        self.vue.list[index].like = valid;
                    }
                    else{
                        self.vue.list[index].replies[deep].like = valid;
                    }

                });
            },
            _del : function (id,deep) {
                return  self.comment_delete(id,function () {
                    if(typeof deep != 'undefined'){
                        self.vue._comment_fold(deep,1);
                    }
                    else{
                        self.update_comment();
                    }
                });

            },
            _hide : function (id,deep) {
                return self.comment_hide(id,function () {
                    if(typeof deep != 'undefined'){
                        self.vue._comment_fold(deep,1);
                    }
                    else{
                        self.update_comment();
                    }
                });
            },
            //组合键提交评论
            _multi_key : function (e,index,deep) {
                if((e.ctrlKey || e.metaKey) && event.keyCode == 13){
                    //根评论
                    if(typeof index == 'undefined'){
                        return this._submit();
                    }
                    //子评论
                    if(typeof deep == 'undefined'){
                        return this._reply_submit(index);
                    }
                    //子评论回复
                    if(typeof deep != 'undefined'){
                        return this._reply_submit(index,deep);
                    }
                }
            },
            //提交根评论
            _submit : function () {
                self.comment_submit(0,this.input,function () {
                    self.vue.input = '';
                    self.update_comment();
                });
            },
            //提交回复
            _reply_submit : function (index,deep) {
                if(typeof deep != 'undefined'){
                    self.comment_submit(this.list[index].replies[deep].id,this.list[index].replies[deep].reply_input,function () {
                        self.vue.list[index].replies[deep].reply_fold = 0;
                        self.vue.list[index].replies[deep].reply_input = '';
                        self.vue._comment_fold(index,1);
                    });
                }
                else{
                    self.comment_submit(this.list[index].id,this.list[index].reply_input,function () {
                        self.vue.list[index].reply_fold = 0;
                        self.vue.list[index].reply_input = '';
                        self.vue._comment_fold(index,1);
                    });
                }
            },
            //回复展开
            _reply_fold : function (index,deep) {
                if(user.id == ''){
                    return pop.confirm('操作首先需登录','前往登录', function(){
                        location.href = redirect;
                    },'取消');
                }
                if(typeof deep != 'undefined'){
                    if(this.list[index].replies[deep].reply_fold){
                        this.list[index].replies[deep].reply_fold = 0;
                    }
                    else{
                        this.list[index].replies[deep].reply_fold = 1;
                    }
                }
                else{
                    this.list[index].comment_fold = 0;
                    if(this.list[index].reply_fold){
                        this.list[index].reply_fold = 0;
                    }
                    else{
                        if(user.id == ''){
                            return pop.confirm('操作首先需登录','前往登录', function(){
                                location.href = redirect;
                            },'取消');
                        }
                        this.list[index].reply_fold = 1;
                    }
                }
            },
            //根评论展开
            _comment_fold : function (index,force) {
                var info = this.list[index],list = [];
                this.list[index].reply_fold = 0;
                if(self.vue.list[index].comment_fold && !force)return self.vue.list[index].comment_fold = 0;
                request.get('/comment/comments',function (ret) {
                        if (ret.hasOwnProperty('code') && ret.code == '0'){
                            list = ret.data;
                            if(list.length > 0){
                                self.vue.list[index].replies = list;
                                self.vue.list[index].comment_fold = 1;
                            }

                        }
                    },
                    {
                        id :  data.article.source,
                        root : info.id,
                        order : 'asc',
                        orderby : 'new'
                    }
                )
            },
            _orderby : function (orderby) {
                this.orderby = orderby;
                self.update_comment();
            }
        }
    });
    //刷新文章评论列表
    this.update_comment = function (call) {
        request.get('/comment/comments',function (ret) {
                if(ret.hasOwnProperty('code') && ret.code == '0'){
                    self.vue.list= self.save = ret.data;
                    !!call && call();
                }
            },
            {
                id : data.article.source,
                orderby : self.vue.orderby
            }
        );
    };

    this.comment_delete = function (id,fun) {
        pop.confirm('确认删除该评论','确定',function () {
            request.get('/comment/delete',function (ret) {
                    if(ret.hasOwnProperty('code') && ret.code == '0'){
                        fun();
                    }
                },
                {
                    id:id
                }
            );
        },'取消');
    };

    this.comment_hide = function (id,fun) {
        pop.confirm('确认隐藏该评论','确定',function () {
            request.get('/comment/hide',function (ret) {
                    if(ret.hasOwnProperty('code') && ret.code == '0'){
                        fun();
                    }
                },
                {
                    id:id
                }
            );
        },'取消');
    };

    this.comment_like = function (id,valid,fun) {
        if(user.id == ''){
            return pop.confirm('操作首先需登录','前往登录', function(){
                location.href = redirect;
            },'取消');
        }
        request.get('/comment/like',function (ret) {
                if(ret.hasOwnProperty('code') && ret.code == '0'){
                    fun();
                }
            },
            {
                id:id,
                valid:valid ? 1 : 0
            }
        );
    };
    //提交回复 && 评论
    this.comment_submit = function (parent,content,fun) {
        var submit;
        if(lock) return false;
        if(user.id == ''){
            return pop.confirm('操作首先需登录','前往登录', function(){
                location.href = redirect;
            },'取消');
        }
        if(content.length<5)return pop.error('评论过短','确定').one();
        lock = true;
        submit = function(){
            request.get('/comment/submit',function (ret) {
                    lock = false;
                    if(!ret.hasOwnProperty('code')) return pop.error('评论失败,请重试','确定').one();
                    if(ret.code == '0'){
                        fun();
                    }
                    else if(ret.code == '40003'){
                        libGeetest.start(submit);
                    }
                    else{
                        pop.error(ret.msg || '评论失败,请重试','确定').one();
                    }
                },
                {
                    id:data.article.source,
                    content:content,
                    parent : parent
                }
            )
        };
        //提交回复
        submit();
    };

    //自执行
    (function () {
        var id = input.get('comment');
        //默认到达评论锚点,所以默认加载全部评论,加载完成之后,跳转到锚点;
        if(id){
            self.update_comment(function () {
                comment_anchor.init(id);
            });
        }
        //滚动到文章底部加载评论内容
        else{
            this.timer = setInterval(function () {
                var dom = document.getElementById('comment-container'),rect;
                if(dom.getBoundingClientRect){
                    rect = dom.getBoundingClientRect();
                    if(rect.top-300 > (window.innerHeight || document.documentElement.clientHeight)){
                        return false;
                    }
                }
                //初始评论加载
                self.update_comment();
                clearInterval(self.timer);
            },300);
        }
    }).call(self);

}).call(define('comment'));

(function () {
    var self        = this,
        highLight   = 0,
        dom,
        comment,
        timer;

    //初始评论锚点
    this.init        = function (id) {
        var top;
        comment = id;
        self.position();
        self.scrollTo();
        location.hash = '';
    };

    //滚到到相应位置
    this.scrollTo = function () {
        highLight = 0;
        if(timer)clearTimeout(timer);
        //此处延迟 500ms 等待 VUE 进行 Dom 构建完成
        timer = setTimeout(function () {
            dom = jQuery("#comment-"+comment);
            if(!dom.length)return false;
            document.body.scrollTop = dom.offset().top-100;
            self.highLight();
        },500);

    };

    //高亮评论
    this.highLight = function () {

        //高亮闪烁次数
        if(highLight >= 3)return false;

        dom.addClass('breathing');

        setTimeout(function () {
            dom.removeClass('breathing');
            highLight++;
            setTimeout(function () {
                self.highLight(dom);
            },800);
        },800);

    };

    //随着图片加载完成,同步位置
    this.position = function () {
        var ele = jQuery('#site-content'),
            image = ele.find('img'),
            height = ele.height(),
            current;
        image.load(function () {
            current = ele.height();
            if(current != height){
                self.scrollTo();
                height = current;
            }
        });
    }

}).call(define('comment_anchor'));