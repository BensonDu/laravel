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

    this.check_username = function(){
        var username = $username_input.val();
        username_check = false;
        if(username == ''){
            return self.username_error('用户名为空');
        }
        request.post('/account/exist',function(ret){
            if(ret.hasOwnProperty('code') && ret.code=='10001'){
                self.username_error(ret.msg);
            }
            else{
                username_check = true;
            }
        },{username:username});
    };

    this.do_login = function(){
        var data = {
            username:$username_input.val(),
            password:$password_input.val(),
            redirect : input.get('redirect')
        };
        if(!username_check){
            return self.username_error('请检查用户名');
        }
        else{
            request.post('/account/login',function(ret){
                if(ret.hasOwnProperty('code') && ret.code == '0'){
                    self.sso(ret.data.session);
                }
                else{
                    self.username_error('用户名或密码错误');
                }

            },data);
        }
    };
    this.sso = function(session){
        var after,cur = location.href.split('/account'),param = {};
        if(input.get('redirect')){
            after = decodeURIComponent(input.get('redirect'));
        }
        else{
            after = decodeURIComponent(cur[0] || location.href);
        }
        param = input.create_param({session:session,redirect:after});
        location.href = 'http://tech2ipo.com/sso/'+param;
    };

    this.redirect= function(){
        if(input.get('redirect'))return location.href = decodeURIComponent(input.get('redirect'));
        location.href = '/';
    };

    $confirm.click(self.do_login);
    $password_input.keypress(function(e){
        e.which ==13 && self.do_login();
    });
    $username_input.blur(function(){
        self.check_username($(this));
    });
}).call(define('controller_login'));