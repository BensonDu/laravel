//登录部分
(function(){
    var self = this,
        $username = $('#login-username'),
        $username_input = $username.children('.input').children('input'),
        $username_error = $username.children('.error'),
        $password = $('#login-password'),
        $password_input = $password.children('.input').children('input'),
        $password_error = $password.children('.error'),
        $confirm  = $('#login-confirm'),
        username_check =false;

    this.username_error = function(text){
        $username_error.addClass('active').children('p').html(text);
        setTimeout(function(){$username_error.removeClass('active')},2000);

    };

    this.password_error = function(text){
        $password_error.addClass('active').children('p').html(text);
        setTimeout(function(){$password_error.removeClass('active')},2000);
    };

    this.check_username = function(call){
        var username = $username_input.val();
        if(username == ''){
            return self.username_error('用户名为空');
        }
        request.post('/account/exist',function(ret){
            if(ret.hasOwnProperty('code') && ret.code=='10001'){
                self.username_error(ret.msg || '请检查用户名');
            }
            else{
                call();
            }
        },{username:username});
    };

    this.do_login = function(){
        var data = {
            username:$username_input.val(),
            password:$password_input.val(),
            redirect : input.get('redirect')
        };
        self.check_username(function () {
            request.post('/account/login',function(ret){
                if(ret.hasOwnProperty('code') && ret.code == '0'){
                    if(ret.data.hasOwnProperty('site') && ret.data.hasOwnProperty('session')){
                        self.redirect(ret.data.session,ret.data.site);
                    }
                }
                else{
                    self.username_error('用户名或密码错误');
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

    $confirm.click(self.do_login);
    $password_input.keypress(function(e){
        e.which ==13 && self.do_login();
    });
    $username_input.blur(function(){
        self.check_username($(this));
    });
}).call(define('controller_login'));