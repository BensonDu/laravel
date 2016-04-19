//VUE略奢侈
(function(){
    var self = this,
        id = location.pathname.match(/\d{3,10}/)[0],
        $like = $('#like'),
        $favorite = $('#favorite'),
        login = global.platform.home+'/account/login?redirect='+encodeURIComponent(location.href);

    this.like = function(){
        request.get('/social/like',function(ret){
            if(ret.hasOwnProperty('code')){
                if(ret.code ==0){
                    if(ret.msg=='LIKE'){
                        $like.addClass('active');
                    }
                    else{
                        $like.removeClass('active');
                    }
                }
                else if(ret.code ==40003){
                    self._login();
                }
            }

        },{
            type : 1,
            id : id
        });
    };
    this.fav = function(){
        request.get('/social/favorite',function(ret){
            if(ret.hasOwnProperty('code')){
                if(ret.code ==0){
                    if(ret.msg == 'FAV'){
                        $favorite.addClass('active');
                    }
                    else{
                        $favorite.removeClass('active')
                    }
                }
                else if(ret.code ==40003){
                    self._login();
                }
            }

        },{
            type : 1,
            id : id
        });
    };
    this._login = function(){
        pop.confirm('操作首先需登录','前往登录', function(){
            location.href = login;
        },'返回');
    };
    $like.children('a').click(function(){
        self.like();
    });
    $favorite.children('a').click(function(){
        self.fav();
    })
}).call(define('controller_detail'));