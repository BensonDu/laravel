(function(){
    var self = this;
    this.data = {
        password : {
            val : '',
            error : false
        },
        newpassword : {
            val : '',
            error : false
        },
        newpassword_re : {
            val : '',
            error : false
        },
        save : ''
    };
    this.methods = {};
    this.methods.submit = function(){
        var data = self.data,
            form = self.form();
        if(self.verify_all() == 'pass'){
            data.save = 'loading';
            return request.post('/user/password',function(ret){
                if(ret.hasOwnProperty('code') && (ret.code=='0' || ret.code=='10001')){
                    if(ret.code=='0'){
                        return setTimeout(function(){
                            data.save = 'done';
                            self.form_empty();
                            setTimeout(function(){
                                data.save = '';
                            },3000);
                        },700);
                    }
                    if(ret.code == '10001'){
                        data.save = '保存';
                        return self.error(data.password,'密码错误');
                    }
                }
                data.save = '保存';
                pop.error('网络错误','确定');
            },form);
        }
    };
    this.form = function(){
        return {
            password :   self.data.password.val,
            newpassword :   self.data.newpassword.val,
            newpassword_re :   self.data.newpassword_re.val
        };
    };
    this.error = function(obj,text){
        obj.error = text;
        setTimeout(function(){obj.error = false;},2000);
    };
    this.form_empty = function(){
      self.data.password.val = '';
      self.data.newpassword.val = '';
      self.data.newpassword_re.val = '';
    };
    this.verify_all = function(){
        var form = self.form();
        if(form.password == '')return self.error(self.data.password,'密码为空');
        if(form.newpassword == '' )return self.error(self.data.newpassword,'密码为空');
        if(form.newpassword.length < 6 )return self.error(self.data.newpassword,'密码格式错误');
        if(form.newpassword_re != form.newpassword )return self.error(self.data.newpassword_re,'两次密码输入不一致');
        return 'pass';
    };
    this.vue = new Vue({
        el: '#password',
        data: self.data,
        methods: self.methods
    });
}).call(define('password'));