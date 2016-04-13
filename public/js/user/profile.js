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
        imageUploader(
            function () {
                self.data.avatar.progress.active = true;
            },
            function (p) {
                self.data.avatar.progress.percent = p+'%'
            },
            function (url) {
                self.data.avatar.val = url+'?imageMogr2/thumbnail/!100x100r/gravity/Center/crop/100x100';
                setTimeout(function(){
                    self.data.avatar.progress.active = false;
                },1000);
            },
            function (t) {
                self.data.avatar.progress.active = false;
                pop.error( t || '上传失败','确定').one();
            }
        ).upload(_this.files);
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
        }if(self.data.nickname.val.length > 10){
            self.data.nickname.error = '昵称过长';
            setTimeout(function(){self.data.nickname.error = false},2000);
            return false;
        }
        return true;
    };

}).call(define('profile'));