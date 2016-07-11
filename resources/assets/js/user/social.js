(function(){
    var self = this;
    this.data = {
        wechat : {
            val : data.form.wechat,
            file : '',
            progress : {
                active : false,
                percent : '0 %'
            },
            error : ''
        },
        weibo : {
            val : data.form.weibo,
            error : ''
        },
        email : {
            val : data.form.email
        },
        save : ''
    };
    this.methods = {};
    this.methods.clear  = function(){
        self.data.wechat.val='';
        self.data.email.val = '';
        self.data.weibo.val = '';
    };
    this.methods.upload = function(){
        var _this = this.$els.wechat;
        if(!_this.files)return pop.error( '浏览器兼容性错误','确定' ).one();
        imageCrop(_this.files,{
            aspectRatio : 1,
            croppedable : true,
            finish : function (url) {
                self.data.wechat.val = url;
            },
            error : function (text) {
                pop.error( text || '上传失败','确定').one();
            }
        });
        _this.value = '';
    };
    this.methods.submit = function(){
        var data = self.data,
            form = {
                wechat :   data.wechat.val,
                weibo  : data.weibo.val,
                email : data.email.val
            };
        self.verify_all(function(){
            data.save = 'loading';
            return request.post('/user/social',function(ret){
                if(ret.hasOwnProperty('code') && ret.code=='0'){
                    return setTimeout(function(){
                        data.save = 'done';
                        setTimeout(function(){
                            data.save = '';
                            location.reload();
                        },1000);
                    },700);
                }
                pop.error('网络错误','确定');
            },form);
        });
    };
    this.verify_all = function(call){
        if(self.data.weibo.val != '' && !constant.regex().username.test(self.data.weibo.val))return self.error(self.data.weibo,'格式错误');
        call();
    };
    this.error = function(obj,text){
        obj.error = text;
        setTimeout(function(){obj.error = ''},2000);
    };
    this.vue = new Vue({
        el: '#social',
        data: self.data,
        methods: self.methods
    });

}).call(define('social'));