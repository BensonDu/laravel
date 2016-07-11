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
    var self = this,
        ua   = navigator.userAgent.toLowerCase();
    this.isandrod = ua.indexOf("android") != -1;
}).call(define('env'));

(function(){
    var self = this;

    this.post = function(url,call,data,error){
        self.base(url,call,data,error,'post');
    };

    this.get = function(url,call,data,error){
        self.base(url,call,data,error,'get');
    };
    this.jsonp = function(url,call,data,error){
        self.base(url,call,data,error,'jsonp');
    };
    this.base = function(url,call,data,error,type){
        var option = {
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
                if( !!error ){
                    error(e);
                }
                else{
                    pop.error('服务器错误','确定').one();
                }
            }
        };
        if(type == 'jsonp'){
            option.jsonp = 'callback';
            option.dataType = 'jsonp';
        }
        $.ajax(option);
    };
    this.quick = function(url,data){
        var img = new Image();
        return img.src = url+input.create_param(data || {});
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
    this.set = function(cname, cvalue, exmins){
        var d = new Date(),expires;
        d.setTime(d.getTime() + (exmins*60*1000));
        expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    };
    this.get = function(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
        }
        return "";
    }
}).call(define('cookie'));

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
    this.date = function(date, format){
        if(format === undefined){
            format = date;
            date = new Date();
        }
        var map = {
            "M": date.getMonth() + 1, //月份
            "d": date.getDate(), //日
            "h": date.getHours(), //小时
            "m": date.getMinutes(), //分
            "s": date.getSeconds(), //秒
            "q": Math.floor((date.getMonth() + 3) / 3), //季度
            "S": date.getMilliseconds() //毫秒
        };
        format = format.replace(/([yMdhmsqS])+/g, function(all, t){
            var v = map[t];
            if(v !== undefined){
                if(all.length > 1){
                    v = '0' + v;
                    v = v.substr(v.length-2);
                }
                return v;
            }
            else if(t === 'y'){
                return (date.getFullYear() + '').substr(4 - all.length);
            }
            return all;
        });
        return format;
    }
}).call(define('helper'));

(function(){
    $.fn.extend({
        tap:function(fn,delay){
            var x, y,s;
            if(typeof document.ontouchstart!='undefined'){
                $(this).bind('touchstart',function(e){
                    x= e.originalEvent.touches[0].pageX;
                    y= e.originalEvent.touches[0].pageY;
                    s = new Date().getTime();
                });
                $(this).bind('touchend',function(e){
                    var event=e.originalEvent.changedTouches[0],move=Math.pow(event.pageX-x,2)+Math.pow(event.pageY-y,2),o = new Date().getTime(),self = $(this);
                    if(o-s<150 && move<81){
                        !delay?fn.call(self):setTimeout(function(){fn.call(self)},200);
                    }
                });
            }
            else{
                $(this).click(fn);
            }

        }
    });
}).call(window);

(function(){
    var self = this, $pop, $title, $vice, $btn;

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
    this.confirm = function(msg,btn,call,cancel){
        return self.base('confirm',msg,btn,call,cancel || '取消',function(){});
    };
    this.base = function(type,msg,btn,call,vice,vice_call){
        self.init();
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
    this.init = function () {
        var template = '<div id="global-pop" class="global-pop"> <div class="box"> <div class="img"> <em></em> </div> <div class="title"> <h5></h5> </div> <div class="btn-group"> <a class="vice">返回首页</a> <a class="main"></a> </div> </div> </div>';
        if(!$pop){
            $(document.body).append(template);
            $pop   = $('#global-pop');
            $title = $pop.find('h5');
            $vice = $pop.find('.vice');
            $btn   = $pop.find('.main');
            $vice.tap(self.vice);
            $btn.tap(self.btn);
        }
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
}).call(define('pop'));