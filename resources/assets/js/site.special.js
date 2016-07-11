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
                    self.imgPreLoad(this.index);
                }
            },
            _over:function (index) {
                if(self.vue.list[index].bg_image != background)self.backgound(self.vue.list[index].bg_image);
            }
        }
    });
    //两层背景层,交换显示;
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
    //预加载图片
    this.preLoad = function (imgs,start,end) {
        var t = imgs.length,s = 0;
        typeof start == 'function' && start();
        for(var i = 0;i < t;i++){
            self.imgLoader(imgs[i],function () {
                s++;
                if(s == t && typeof end == 'function') end();
            })
        }

    };
    //图像加载
    this.imgLoader = function (url,call) {
        var img = new Image;
        img.onload = call;
        img.onerror = call;
        img.src = url;
    };
    //加载下一页图片
    this.imgPreLoad = function (index,start,end) {
        var l = images.length,t=Math.ceil(images.length/3),imgs = [];
        if(index+1 <= t){
            for(var i = 0; i < l ;i++){
                if(i >= index*3 && i < ((index+1)*3) && !images[i].loaded){
                    imgs.push(images[i].url);
                    images[i].loaded = true;
                }
            }
            if(imgs.length>0)self.preLoad(imgs,start,end);
        }
    };
    //初始化
    this.init = (function () {
        var l = data.list.length;
        for(var i = 0; i < l; i++){
            images.push({
                loaded : false,
                url    :data.list[i].bg_image
            });
        }
        //加载第一页背景图片
        self.imgPreLoad(0,
            function () {
                self.vue.display = false;
                 c_background.vue.loading = true;
            },
            function () {
                 self.vue.display = true;
                 c_background.vue.loading = false;
            }
        );
        //加载第二页背景图片
        self.imgPreLoad(1);
        //设置默认背景图片
        self.backgound(images[0].url);
        return true;
    })();

}).call(define('c_special'));