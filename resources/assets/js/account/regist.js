(function(){
    var self = this;

    this.vue = new Vue({
        el: '#regist-form',
        data: {
            username : {
                val : '',
                error : false,
                error_tip : ''
            },
            phone : {
                val : '',
                error : false,
                error_tip : ''
            },
            captcha : {
                val : '',
                error : false,
                error_tip : '',
                text : '发送验证码',
                disable : 0
            },
            password : {
                val : '',
                error : false,
                error_tip : ''
            },
            password_re : {
                val : '',
                error : false,
                error_tip : ''
            }
        },
        methods: {
            _check_username : function(){
                self.verify_username();
            },
            _check_phone : function () {
                self.verify_phone();
            },
            _get_captcha : function () {
                if(!self.vue.captcha.disable){
                    self.verify_phone(function(){
                        libGeetest.start(function () {
                            self.captcha_send();
                        });
                    });
                }
            },
            _submit : function () {
                self.verify_all(function(){
                    self.regist();
                });
            }
        }
    });
    //验证手机号码
    this.verify_phone = function(call){
        var phone = self.vue.phone;
        if(phone.val == ''){
            return self.error(phone,'手机号码为空');
        }
        if(!constant.regex().phone.test(phone.val)){
            return self.error(phone,'格式错误');
        }
        return request.post('/account/exist',function(ret){
            if(ret.hasOwnProperty('code') && ret.code=='0'){
                self.error(phone,'手机号码已存在');
            }
            else{
                if(typeof  call == 'function'){
                    call();
                }
            }
        },{username:phone.val});
    };
    //验证用户名
    this.verify_username = function(call){
        var username = self.vue.username;
        if(username.val == ''){
            return self.error(username,'用户名为空');
        }
        if(/^\d+$/.test(username.val)){
            return self.error(username,'用户名不能为纯数字');
        }
        if(!/^[a-zA-Z0-9_]+$/.test(username.val)){
            return self.error(username,'只能为英文、数字组合');
        }
        return request.post('/account/exist',function(ret){
            if(ret.hasOwnProperty('code') && ret.code=='0'){
                self.error(username,'用户已存在');
            }
            else{
                if(typeof  call == 'function'){
                    call();
                }
            }
        },{username:username.val});
    };
    //验证全部表单
    this.verify_all = function(call){
          var base = self.vue;
        if(base.username.val == '') return self.error(base.username,'用户名为空');
        if(base.phone.val == '') return self.error(base.phone,'手机号码为空');
        if(base.captcha.val == '') return self.error(base.captcha,'验证码为空');
        if(base.captcha.val.length != 6) return self.error(base.captcha,'验证码输入有误');
        if(base.password.val.length<6) return self.error(base.password,'密码需大于6位');
        if(base.password.val != base.password_re.val)return self.error(base.password_re,'两次输入密码不一致');
        if(typeof  call == 'function') return call();

    };
    //提交表单
    this.regist = function(){
      var base = self.vue,
          form = {
                username : base.username.val,
                phone : base.phone.val,
                captcha :base.captcha.val,
                password : base.password.val
          };
        return request.post('/account/regist',function(ret){
            if(ret.hasOwnProperty('code')){
                if(ret.code == '0')return self.redirect(ret.data.session,ret.data.site);
                if(ret.code == '20001')return self.error(base.username,ret.msg);
                if(ret.code == '20002')return self.error(base.phone,ret.msg);
                if(ret.code == '20003')return self.error(base.captcha,ret.msg);
                if(ret.code == '20004')return self.error(base.password,ret.msg);
            }
        },form);
    };
    this.redirect = function(session,site){
        var urlReg = /[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/,
            r = input.get('redirect'),
            redirect = !!r ? decodeURIComponent(r) : '/',
            url = urlReg.exec(redirect);
        if(site && !!url && !!url[0]){
            return location.href = 'http://'+url[0]+'/sso/'+ input.create_param({session:session,redirect:redirect});
        }
        else{
            return location.href = redirect;
        }
    };
    //发送验证码
    this.captcha_send = function(){
        var phone = self.vue.phone;
        return request.post('/account/captcha',function(ret){
            if(ret.hasOwnProperty('code') && ret.code=='0'){
                self.captcha_count(new Date().getTime());
            }
            else{
                self.error(phone, ret.msg || '发送失败,请重试');
            }
        },{phone:phone.val});
    };
    //验证码计时
    this.captcha_count = function(send_time){
        var down,timer;
        self.vue.captcha.disable = true;
        return timer = setInterval(function(){
            down = 60-(new Date().getTime() - send_time)/1000;
            if(down>=0){
                self.vue.captcha.text=Math.floor(down)+' 秒后重发'
            }
            else{
                clearInterval(timer);
                self.vue.captcha.disable = false;
                self.vue.captcha.text='重新发送';
            }
        },500);
    };
    //注册成功
    this.regist_success = function(){
        if(input.get('redirect'))return location.href = decodeURIComponent(input.get('redirect'));
        return pop.success('注册成功','返回登录',function () {
            location.href = '/account/login';
        });
    };

    this.error = function(obj,txt){
        obj.error_tip = txt;
        obj.error = 1;
        setTimeout(function(){obj.error = 0},2000);
    }

}).call(define('c_regist'));