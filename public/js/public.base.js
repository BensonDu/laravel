/*全局定义*/
(function(){
    this.define = this._define = function (s) {
        return (typeof  s != 'undefined' && typeof  this[s] == 'undefined') ? this[s] = {} : (this[s] || {});
    };
}).call(this);

(function(){
    this.regex = function(){
        return {
            phone: /^\d{11}$/,
            strict_validation_phone: /^(1(([35][0-9])|(47)|[8][0126789]))\d{8}$/,
            mail: /^[a-z\d]+(\.[a-z\d]+)*@([\da-z](-[\da-z])?)+(\.{1,2}[a-z]+)+$/,
            qq: /^\d{5,10}$/,
            chinese_Unicode: /^[\u2E80-\u9FFF]+$/,
            chinese_Name: /^[\u2E80-\u9FFF]{2,5}$/
        };
    }
}).call(define('constant'));

(function(){
    var self = this;
    this.post = function(url,call,data,error){
        $.ajax({
            url:url,
            type:'post',
            cache:false,
            data:data,
            dataType:'json',
            success:function(ret) {
                var json={};
                if(typeof ret=='string'){
                    try{
                        json=JSON.parse(ret);
                    }
                    catch(e){

                    }
                }
                if(typeof ret=='object'){
                    json=ret;
                }
                call(json);
            },
            error:function(e){
                if(typeof  error == 'function'){
                    error(e);
                }
            }
        });
    };
}).call(define('request'));

(function(){
    var self = this;
    //GET 传参
    this.get = function(k){
        var url=window.location.href.split('?'),rl = url.length, a, h, l, e, f={};

        if(rl>1){
            for(var m = 1 ; m < rl;m++){
                h= url[m].split('#');
                a=h[0].split('&');
                l= a.length;
                for(var i=0;i<l;i++){
                    e=a[i].split('=');
                    f[e[0]]= e.length>1?e[1]:'';
                }
            }
            return (typeof k =='string') ? (f.hasOwnProperty(k) ? f[k] :null) : f;
        }
    };
    //对象转参数字符串
    this.create_param = function(data){
        var s='',c='?';
        if(typeof data == 'object'){
            for(var i in data){
                s+=c+i+'='+encodeURIComponent(data[i]);
                if(c='?')c='&';
            }
            return s;
        }
    };
}).call(define('input'));

(function(){
    var self = this,
        $pop   = $('#global-pop'),
        $title = $pop.find('h5'),
        $btn   = $pop.find('.call');

    this.error = function(msg,btn,call){
        self.base('error',msg,btn,call);
    };
    this.success = function(msg,btn,call){
        self.base('success',msg,btn,call);
    };
    this.warning = function(msg,btn,call){
        self.base('warning',msg,btn,call);
    };
    this.base = function(type,msg,btn,call){
        $pop.removeClass('error').removeClass('warning').removeClass('success').addClass('active').addClass(type);
        $title.html(msg);
        $btn.html(btn);
        if(typeof call == 'function')self.click_hook = call;
    };
    this.click_hook = function(){};
    this.btn = function(){
        $pop.removeClass('active');
        self.click_hook();
    };
    this.click_event = $btn.click(self.btn);

}).call(define('pop'));