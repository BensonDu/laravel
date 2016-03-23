(function(){
    var self = this,
        uploader   = simple.uploader({});
    this.data = {
        wechat : {
            val : default_data.wechat,
            file : '',
            progress : {
                active : false,
                percent : '0 %'
            },
            error : ''
        },
        weibo : {
            val : default_data.weibo,
            error : ''
        },
        email : {
            val : default_data.email
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
        var _this = this.$els.wechat,
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
    //IMG ID 转化为 URL
    this.get_img_url=function(id, option){
        return 'http://dn-noman.qbox.me/' + id + '?imageMogr2' + (option ? "/crop/!" + get_crop(option) : "") + "/auto-orient/thumbnail/480x";
    };
    //上传进度显示
    this.uploading = function(loaded, total){
        return self.data.wechat.progress.percent = parseFloat(((loaded / total) * 100).toFixed(0))+' %';
    };
    //上传错误显示
    this.upload_error = function(text){
        self.data.wechat.error = text;
        setTimeout(function(){self.data.wechat.error = false;},3000);
    };
    //初始化
    uploader.on("beforeupload", function (e, file, r) {
        self.data.wechat.progress.active = true;
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
            self.data.wechat.val = self.get_img_url(r.key,r);
            setTimeout(function(){
                self.data.wechat.progress.active = false;
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

}).call(define('social'));