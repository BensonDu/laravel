/**
 * Created by Benson on 16/6/15.
 */
(function () {
    var self = this;
    this.vue = new Vue({
        el : '#background',
        data : {
            active : 1,
            displayFirst : true,
            displaySecond : false,
            styleFirst : {
                backgroundImage:''
            },
            styleSecond :{
                backgroundImage:''
            },
            loading : true
        },
        methods : {}
    });
}).call(define('c_background'));

(function () {
    var self = this,
        images = [],
        background = '';

    this.vue = new Vue({
        el : '#site-content',
        data : {
            display : false,
            index:1,
            prev : false,
            next : data.total>1,
            list : data.list
        },
        methods : {
            _prev : function () {
                if(this.index>1){
                    this.index-=1;
                    this.prev = this.index>1;
                    this.next = this.index<data.total;
                }
            },
            _next : function () {
                if(this.index<data.total){
                    this.index+=1;
                    this.prev = this.index>1;
                    this.next = this.index<data.total;
                }
            },
            _over:function (index) {
                if(self.vue.list[index].bg_image != background)self.backgound(self.vue.list[index].bg_image);
            }
        }
    });

    this.backgound = function (url) {
        var sta = c_background.vue.active == '1',
            now = sta ? 'First' : 'Second',
            next= sta ? 'Second': 'First';

        if(background == '')return background = url,c_background.vue['style'+now].backgroundImage = 'url('+url+')';
        
        background = url;
        c_background.vue['display'+next] = true;
        c_background.vue['style'+next].backgroundImage = 'url('+url+')';
        c_background.vue['display'+now] = false;
        c_background.vue.active = sta ? '2' : '1';
    };

    this.load = function (imgs,call) {
        var t = imgs.length,s = 0;
        self.vue.display = false;
        c_background.vue.loading = true;
        for(var i = 0;i < t;i++){
            self.imgLoader(imgs[i],function () {
                s++;
                if(s == t){
                    call();
                    self.vue.display = true;
                    c_background.vue.loading = false;
                }
            })
        }

    };

    this.imgLoader = function (url,call) {
        var img = new Image;
        img.onload = call;
        img.onerror = call;
        img.src = url;
    };

    this.init = (function () {
        var l = data.list.length;
        for(var i = 0; i < l; i++){
            images.push(data.list[i].bg_image);
        }
        self.backgound(data.list[0].bg_image);
        return true;
    })();

    this.timer = setInterval(function () {
        if(images.length>0){
            self.load(images,function () {
                images = [];
            })
        }
    },300);

}).call(define('c_special'));