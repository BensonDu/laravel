(function(){
    var self = this,
        uploader   = simple.uploader({});
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
        var _this = this.$els.avatar,
            $this = $(_this),
            exist_file = $($this).attr('exist-file');
        if(exist_file){
            uploader.cancel(exist_file);
        }
        $this.attr('exist-file', $this.val());
        uploader.upload(_this.files);
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
                        },3000);
                    },700);
                }
                pop.error('网络错误','确定');
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
        return true;
    };
    //IMG ID 转化为 URL
    this.get_img_url=function(id, option){
        return 'http://dn-xswe.qbox.me/' + id + '?imageMogr2' + (option ? "/crop/!" + get_crop(option) : "") + "/auto-orient/thumbnail/480x";
    };
    //上传进度显示
    this.uploading = function(loaded, total){
        return self.data.avatar.progress.percent = parseFloat(((loaded / total) * 100).toFixed(0))+' %';
    };
    //上传错误显示
    this.upload_error = function(text){
        self.data.avatar.error = text;
        setTimeout(function(){self.data.avatar.error = false;},3000);
    };
    //初始化
    uploader.on("beforeupload", function (e, file, r) {
        self.data.avatar.progress.active = true;
        self.uploading(5,100);
    });
    //进行中
    uploader.on("uploadprogress", function (e, file, loaded, total) {
        self.uploading(loaded*0.9,total);
    });
    //成功
    uploader.on("uploadsuccess", function (e, file, r) {
        if(r.hasOwnProperty('key')){
            self.uploading(100,100);
            self.data.avatar.val = self.get_img_url(r.key,r);
            setTimeout(function(){
                self.data.avatar.progress.active = false;
            },1000);
        }
        else{
            self.upload_error('上传失败');
        }
    });
    //完成
    uploader.on('uploadcomplete', function (e, file, r) {


    });
    //错误
    uploader.on('uploaderror', function (e, file, xhr, status) {
        self.upload_error('上传失败');
    });

}).call(define('profile'));