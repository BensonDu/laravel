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
            chinese_Name: /^[\u2E80-\u9FFF]{2,5}$/,
            weibo : /http:\/\/weibo.com\/\w.*/
        };
    }
}).call(define('constant'));

(function(){
    var self = this;

    this.post = function(url,call,data,error){
        self.base(url,call,data,error,'post');
    };

    this.get = function(url,call,data,error){
        self.base(url,call,data,error,'get');
    };

    this.base = function(url,call,data,error,type){
        $.ajax({
            url:url,
            type:type,
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
                else{
                    pop.error('服务器错误','确定').one();
                }
            }
        });
    }
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
    var self = this;
    this.now = function(){
        var tt =new Date(),
            full = function(t){
                return t < 9 ? '0'+ t : t;
            },
            m = tt.getMonth()+ 1,
            d = tt.getDate(),
            h = tt.getHours(),
            i = tt.getMinutes(),
            s = tt.getSeconds(),
            yy = tt.getFullYear(),
            mm = full(m),
            dd = full(d),
            hh = full(h),
            ii = full(i),
            ss = full(s);
        return yy+'-'+mm+'-'+dd+' '+hh+':'+ii+':'+ss;
    };
}).call(define('helper'));

(function(){
    var self = this,
        $pop   = $('#global-pop'),
        $title = $pop.find('h5'),
        $vice = $pop.find('.vice'),
        $btn   = $pop.find('.main');

    this.one = function(){
       $pop.addClass('one');
        return this;
    };
    this.error = function(msg,btn,call){
        return self.base('error',msg,btn,call);
    };
    this.success = function(msg,btn,call){
        return self.base('success',msg,btn,call);
    };
    this.warning = function(msg,btn,call){
        return self.base('warning',msg,btn,call);
    };
    this.confirm = function(msg,btn,call){
        return self.base('confirm',msg,btn,call,'取消',function(){});
    };
    this.base = function(type,msg,btn,call,vice,vice_call){
        $pop.removeClass('error warning success one confirm').addClass('active '+type);
        $title.html(msg);
        $btn.html(btn);
        if(typeof call == 'function'){
            self.click_hook = call;
        }
        else{
            self.click_hook = function(){}
        }
        $vice.html(!!vice ? vice : '返回首页');
        if(typeof vice_call == 'function'){
            self.vice_hook = vice_call;
        }
        else{
            self.vice_hook = function(){
                location.href = '/';
            }
        }
        return this;
    };
    this.click_hook = function(){};
    this.btn = function(){
        $pop.removeClass('active');
        self.click_hook();
    };
    this.vice = function(){
        $pop.removeClass('active');
        self.vice_hook();
    };
    this.vice_event  = $vice.click(self.vice);
    this.click_event = $btn.click(self.btn);

}).call(define('pop'));