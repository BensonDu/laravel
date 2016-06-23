/**
 * Created by Benson on 16/6/22.
 */
(function () {
    var self = this;
    this.vue = new Vue({
        el : '#list-container',
        data : {
            list : []
        },
        methods : {}
    });
    this.render = function (data) {
        var l = data.length,ret = [];
        for(var i = 0; i < l ; i++){
            ret[i] = {
                id : data[i].id,
                type : data[i].type,
                title : data[i].title,
                date : data[i].date,
                url : data[i].url,
                image : !!data[i].imgs && data[i].imgs.length > 0 ? data[i].imgs[0] : ''
            };
        }
        self.vue.list = ret;
    };
}).call(define('controller'));

(function () {
    var self    = this,
        id      = location.pathname.match(/\d{3,10}/)[0],
        redirect= global.platform.home+'/account/login?redirect='+encodeURIComponent(location.href);

    this.vue = new Vue({
        el : '#social-bar',
        data : {
            like : data.like,
            favorite : data.favorite,
            likes : data.likes,
            favorites : data.favorites
        },
        methods : {
            _like : function () {
                self.like();
            },
            _favorite : function () {
                self.fav();
            },
            _input : function () {
                c_textarea.vue.display = true;
                c_textarea.vue.id = 0;
                c_textarea.vue.call = function () {};
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
    this.direct_login = function () {
        return location.href = redirect;
    };
}).call(define('c_social'));

(function () {
    var self = this;
    this.vue = new Vue({
        el : '#comment-textarea',
        data : {
            display :false,
            input : '',
            placeholder : '请输入评论内容',
            login : global.user.id != '',
            id : 0,
            //当前时间触发 DOM 高度
            top: 0,
            call : function () {}
        },
        methods : {
            _cancel : function () {
                this.display = false;
                this.input = '';
                this.placeholder = '请输入评论内容';
                this.id = 0;
            },
            _submit : function () {
                c_comment.comment_submit(this.id,this.input,function () {
                    self.vue._cancel();
                    c_comment.update_comment();
                    self.vue.call();
                });
            },
            _login : function () {
                c_social.direct_login();
            },
            _blur : function () {
                var top;
                setTimeout(function () {
                    top = this.top > 150 ? this.top-150 : jQuery('#comment-list').offset().top;
                    !!top && jQuery(window).scrollTop(top);
                },500);
            }
        }
    });
}).call(define('c_textarea'));

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
            avatar : global.user.avatar || 'http://dn-noman.qbox.me/question%20mark.png',
            name : global.user.name,
            role : global.user.role
        },
        redirect= global.platform.home+'/account/login?redirect='+encodeURIComponent(location.href),
        lock = false;
    //站点关闭评论
    if(data.comment != '1')return false;
    //评论列表初始化
    this.init = function (list) {
        var l = list.length;
        for(var i = 0; i < l; i ++){
            //数据结构增加回复列表
            list[i].replies = [];
            //评论展开
            list[i].comment_fold = false;
        }
        return list;
    };
    this.save = [];
    this.vue = new Vue({
        el : '#comment-list',
        data : {
            site : site,
            user : user,
            orderby : 'hot',
            reply : false,
            input : '',
            list : [],
            all : false
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
            _all : function () {
                this.all = true;
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
                                self.vue.list[index].replies = self.init(list);
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
            _comment : function () {
                c_textarea.vue.display = true;
                c_textarea.vue.id = 0;
                c_textarea.vue.call = function () {};
            },
            _reply : function (e,name,index,deep) {
                var y = jQuery(e.target).offset().top;

                if(!c_textarea.vue.display){
                    c_textarea.vue.placeholder = '@'+name;
                    c_textarea.vue.top         = y;
                    c_textarea.vue.call = function () {
                        self.vue._comment_fold(index,1);
                    };
                    if(typeof deep != 'undefined'){
                        c_textarea.vue.id = this.list[index].replies[deep].id;

                    }
                    else{
                        c_textarea.vue.id = this.list[index].id;
                    }
                    c_textarea.vue.display = true;
                }
            },
            _orderby : function (orderby) {
                this.orderby = orderby;
                self.update_comment();
            }
        }
    });
    //刷新文章评论列表
    this.update_comment = function () {
        request.get('/comment/comments',function (ret) {
                if(ret.hasOwnProperty('code') && ret.code == '0'){
                    self.vue.list= self.save = self.init(ret.data);
                }
            },
            {
                id : data.article.source,
                orderby : self.vue.orderby
            }
        );
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
    //滚动到文章底部加载评论内容
    this.timer = setInterval(function () {
        var dom = document.getElementById('comment-list'),rect;
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

}).call(define('c_comment'));