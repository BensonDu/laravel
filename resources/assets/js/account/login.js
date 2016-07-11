//登录部分
(function(){
    var self = this,
        timer;

    this.vue = new Vue({
        el : '#login-container',
        data : {
            username : {
                val : '',
                error : {
                    active : false,
                    text : ''
                }
            },
            password : {
                val : ''
            }
        },
        methods : {
            _check : function () {
                self.check();
            },
            _login : function () {
                self.login();
            }
        }
    });

    this.error = function (text) {
        if(timer)clearTimeout(timer);
        self.vue.username.error.active = true;
        self.vue.username.error.text = text;
        timer = setTimeout(function () {
            self.vue.username.error.active = false;
        },2000);
    };

    this.check = function (call) {
        var username = self.vue.username.val;
        if(username == '') return self.error('用户名为空');
        request.post('/account/exist',function(ret){
            if(ret.hasOwnProperty('code') && ret.code=='10001'){
                self.error(ret.msg || '请检查用户名');
            }
            else{
                !!call && call();
            }
        },{username:username});
    };

    this.login = function () {
        var data = {
            username:self.vue.username.val,
            password:self.vue.password.val,
            redirect : input.get('redirect')
        };
        self.check(function () {
            request.post('/account/login',function(ret){
                if(ret.hasOwnProperty('code') && ret.code == '0'){
                    if(ret.data.hasOwnProperty('site') && ret.data.hasOwnProperty('session')){
                        self.redirect(ret.data.session,ret.data.site);
                    }
                }
                else{
                    self.error('用户名或密码错误');
                }

            },data);
        });
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

}).call(define('c_login'));