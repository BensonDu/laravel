(function(){
    var self = this;
    this.data = {
        username : {
            val : default_data.username
        },
        avatar : {
            val : default_data.avatar,
            file : '',
            progress : {
                active : false,
                percent : '0 %'
            },
            error : ''
        },
        nickname : {
            val : default_data.nickname,
            error : ''
        },
        slogan : {
            val : default_data.slogan
        },
        introduce : {
            val : default_data.introduce
        },
        save : ''
    };
    this.methods = {};
    this.methods.upload = function(){
        var _this = this.$els.avatar;
        if(!_this.files)return pop.error( '浏览器兼容性错误','确定' ).one();
        imageCrop(_this.files,{
            aspectRatio : 1,
            croppedable : true,
            finish : function (url) {
                self.data.avatar.val = url;
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
                nickname :   data.nickname.val,
                slogan  : data.slogan.val,
                introduce : data.introduce.val,
                avatar : data.avatar.val
            };
        if(self.check_nickname()){
            data.save = 'loading';
            return request.post('/user/profile',function(ret){
                if(ret.hasOwnProperty('code') && ret.code=='0'){
                    return setTimeout(function(){
                        data.save = 'done';
                        setTimeout(function(){
                            data.save = '';
                            location.reload();
                        },1000);
                    },700);
                }
                data.save = '';
                pop.error(ret.msg || '网络错误','确定').one();
            },form);
        }
    };
    this.vue = new Vue({
        el: '#profile',
        data: self.data,
        methods: self.methods
    });
    this.check_nickname = function(){
        if(self.data.nickname.val == ''){
            self.data.nickname.error = '昵称为空';
            setTimeout(function(){self.data.nickname.error = false},2000);
            return false;
        }
        if(!constant.regex().nickname.test(self.data.nickname.val)){
            self.data.nickname.error = '包含非法字符';
            setTimeout(function(){self.data.nickname.error = false},2000);
            return false;
        }if(self.data.nickname.val.length > 20){
            self.data.nickname.error = '昵称过长';
            setTimeout(function(){self.data.nickname.error = false},2000);
            return false;
        }
        return true;
    };

}).call(define('profile'));