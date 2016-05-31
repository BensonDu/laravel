//VUE略奢侈
(function () {
    var self    = this,
        id      = location.pathname.match(/\d{3,10}/)[0],
        redirect= global.platform.home+'/account/login?redirect='+encodeURIComponent(location.href),
        qrcreated = false;
    this.vue = new Vue({
        el : '#detail-handle',
        data : {
            like : data.like,
            favorite : data.favorite,
            likes : data.likes,
            favorites : data.favorites,
            qr : 'http://dn-acac.qbox.me/tech2ipoqrcode.jpg'
        },
        methods : {
            _like : function () {
                self.like();
            },
            _favorite : function () {
                self.fav();
            },
            _qr : function () {
                !qrcreated && self.qr();
            }
        }
    });
    this.like = function () {
        request.get('/social/like',function(ret){
            if(ret.hasOwnProperty('code')){
                if(ret.code ==0){
                    self.vue.like  = (ret.data.valid == 'LIKE');
                    self.vue.likes = ret.data.count;
                }
                else if(ret.code ==40003){
                    self.login();
                }
            }

        },{
            id : id
        });
    };
    this.fav = function(){
        request.get('/social/favorite',function(ret){
            if(ret.hasOwnProperty('code')){
                if(ret.code ==0){
                    self.vue.favorite = (ret.data.valid == 'FAV');
                    self.vue.favorites = ret.data.count;
                }
                else if(ret.code ==40003){
                    self.login();
                }
            }

        },{
            id : id
        });
    };
    this.login = function(){
        pop.confirm('操作首先需登录','前往登录', function(){
            location.href = redirect;
        },'返回');
    };
    this.qr = function () {
        var dom = $('#qr-container'),
        url=location.href.split('?')[0];
        dom.qrcode({
            render:'image',
            width: 200,
            height: 200,
            color: "#3a3",
            text: url,
            showCloseButton: false
        });
        qrcreated = true;
    }
}).call(define('c_handle'));