(function(){
    var self = this,
        $success = $('#find-success');

    //开启Debug
    Vue.config.debug =true;

    this.data = {
        username : {
            val : '',
            lock : false,
            error : false,
            error_tip : ''
        },
        captcha : {
            val : '',
            error : false,
            error_tip : '',
            lock : true,
            text : '验证',
            disable : 0
        },
        password : {
            val : '',
            error : false,
            error_tip : '',
            lock : true
        },
        password_re : {
            val : '',
            error : false,
            error_tip : '',
            lock : true
        }
    };
    this.methods = {};
    this.methods.get_captcha = function(){
        if(!self.data.captcha.disable){
            self.verify_username(self.captcha_send);
        }
    };
    this.methods.submit = function(){
        self.verify_all(function(){
            self.find();
        });
    };
    //验证用户名或手机号码
    this.verify_username = function(call){
        var username = self.data.username;
        if(username.val == ''){
            return self.error_show(username,'用户名/手机号码为空');
        }
        return request.post('/account/exist',function(ret){
            if(ret.hasOwnProperty('code') && ret.code != '0'){
                self.error_show(username,'用户不存在');
            }
            else{
                self.step_two_open(0);
                if(typeof  call == 'function'){
                    call();
                }
            }
        },{username:username.val});
    };
    //前端验证全部表单
    this.verify_all = function(call){
        var base = self.data;
        if(base.captcha.val == '') return self.error_show(base.captcha,'验证码为空');
        if(base.captcha.val.length != 6) return self.error_show(base.captcha,'验证码输入有误');
        if(base.password.val.length<6) return self.error_show(base.password,'密码需大于6位');
        if(base.password.val != base.password_re.val)return self.error_show(base.password_re,'两次输入密码不一致');
        if(typeof  call == 'function') return call();
    };
    //发送验证码
    this.captcha_send = function(){
        var username = self.data.username;
        return request.post('/account/captcha',function(ret){
            if(ret.hasOwnProperty('code') && ret.code=='0'){
                self.captcha_count(new Date().getTime());
            }
            else{
                self.error_show(username, ret.msg || '发送失败,请重试');
            }
        },{username:username.val});
    };
    //验证码计时
    this.captcha_count = function(send_time){
        var down,timer;
        self.data.captcha.disable = true;
        return timer = setInterval(function(){
            down = 60-(new Date().getTime() - send_time)/1000;
            if(down>=0){
                self.data.captcha.text=Math.floor(down)+' 秒后重发'
            }
            else{
                clearInterval(timer);
                self.data.captcha.disable = false;
                self.data.captcha.text='重新发送';
            }
        },500);
    };
    //找回密码
    this.find = function(){
        var base = self.data,
            form = {
                username : base.username.val,
                captcha :base.captcha.val,
                password : base.password.val
            };
        return request.post('/account/find',function(ret){
            if(ret.hasOwnProperty('code')){
                if(ret.code == '0')return self.find_success();
                if(ret.code == '20001')return self.error_show(base.username,ret.msg);
                if(ret.code == '20002')return self.error_show(base.captcha,ret.msg);
                if(ret.code == '20003')return self.error_show(base.password,ret.msg);
            }
        },form);
    };
    this.find_success = function(){
        return $success.addClass('active');
    };
    this.step_two_open = function(is_lock){
        return self.data.username.lock = !is_lock ,self.data.captcha.lock = self.data.password.lock = self.data.password_re.lock = is_lock;
    };

    this.vue = new Vue({
        el: '#find-form',
        data: self.data,
        methods: self.methods
    });

    this.error_show = function(obj,txt){
        obj.error_tip = txt;
        obj.error = 1;
        setTimeout(function(){obj.error = 0},2000);
    }

}).call(define('controller_find'));