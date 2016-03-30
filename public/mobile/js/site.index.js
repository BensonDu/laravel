(function(){
    var self = this,
        dom  = document.getElementById('star'),
        $item = $('.star-wrap').children('a'),
        $first = $item.eq(0),
        $test = $('.test').children('p'),
        left=0,
        width = $(window).width(),
        index = 1,
        playing = true,
        direction = true;
    this.vue = new Vue({
        el : '#star',
        data : {
            current:1,
            total : $item.length
        },
        methods : {}
    });
    this.margin = function(left){
        return $first.css('margin-left',left);
    };
    this.start = function(){
        playing = false;
        left = parseInt($first.css('margin-left'));
    };
    this.move = function(mX){
        return self.margin(left+mX);
    };
    this.end = function(eX){
        var r = eX, a = Math.abs(r), rw = 50;
        if(a>rw){
            if(r>0){
                if(index>1){
                    index--;
                }
            }
            else{
                if(index<self.vue.total){
                    index++;
                }
            }
        }
        self.vue.current = index;
        self.animate(index);
    };
    this.animate=function(index){
        var c = 'transition',marginleft= 102-104*index;
        $first.addClass(c).css('margin-left',marginleft+'%');
        playing = false;
        setTimeout(function(){
            playing = true;
            $first.removeClass(c);
            left = parseInt($first.css('margin-left'));
        },400);
    };
    this.play = setInterval(function(){
        var t = self.vue.total;
        if(!playing)return false;
        if(direction){
            if(index<t){
                index++;
            }
            if(index == t){
                direction = false;
            }

        }
        else{
            if(index>1){
                index--;
            }
            if(index == 1){
                direction = true;
            }

        }
        self.vue.current = index;
        self.animate(index);
    },10000);
    this.touch = (function(el){
        var startX,
            startY,
            startT,
            scrollY,
            start = function(event){
                var  e = event || window.event;
                startX = e.touches[0].pageX;
                startY = e.touches[0].pageY;
                startT = new Date().getTime();
                scrollY = parseInt(window.scrollY);
                self.start();
            },
            move = function(event){
                var  e = event || window.event;
                mX = e.touches[0].pageX - startX;
                mY = e.touches[0].pageY - startY;
                self.move(mX);
            },
            androidmove = function(){
                var  e = event || window.event;
                mX = e.touches[0].pageX - startX;
                mY = e.touches[0].pageY - startY;
                self.move(mX);
                event.preventDefault();
                if(Math.abs(mY)>10){
                    window.scrollTo(0, (-mY*0.8)+scrollY);
                }
            },
            end = function(event){
                var  e = event || window.event,
                    eX = e.changedTouches[0].pageX - startX,
                    eY = e.changedTouches[0].pageY - startY,
                    eT = new Date().getTime();
                if(eT-startT>100 && Math.abs(eX)>10){
                    self.end(eX);
                }
            };
        if(el.nodeType && el.nodeType == 1){
            el.addEventListener('touchstart',start, false);
            if(!env.isandrod){
                el.addEventListener('touchmove',move, false);
            }
            else{
                el.addEventListener('touchmove',androidmove, false);
            }
            el.addEventListener('touchend',end, false);
        }
    })(dom);
    window.onresize = function(){width=$(window).width();}
    
}).call(define('star'));

(function(){
    var self =this,
        $category = $('.category').children('a'),
        $load = $('#load-more'),
        total = article.total,
        index = 0,
        size = 15;
    this.vue = new Vue({
        el : '#list-container',
        data : {
            category : 0,
            visible:'visible',
            none : 1,
            list : [],
            load : 'more',
            categoryload : false
        },
        methods : {}
    });
    //当前分类
    this.category = 0;
    //列表缓存
    this.list = [];
    //处于筛选模式下
    this.filter_mode = function(){
        var $dom = $('.default-list');
        return !!$dom.length && $dom.remove();
    };
    //新增条目
    this.insert_list = function(list){
        self.list = self.list.concat(list);
        self.vue.list = $.extend([],self.list);
    };
    //切换分类
    this.swith_list  = function (list) {
        self.filter_mode();
        self.list = self.vue.list = list;
    };
    //是否有更多条目
    this.has_more = function(){
        return (index+1)*size<total;
    };
    //加载按钮应有状态,以及是否显示暂无内容
    this.btn_sta = function(){
        return self.list_is_empty(!total),total==0 ? self.vue.load = '' :((self.has_more() ? self.vue.load = 'more' : self.vue.load = 'end'));
    };
    //无内容
    this.list_is_empty = function(i){
        return self.vue.none = i;
    };
    //按钮Loading态
    this.btn_loading = function(){
        return self.vue.load = 'loading';
    };
    //获取数据
    this.get_data = function(i,call,hasmore){
        if(!self.has_more() && !hasmore)return;
        self.btn_loading();
        request.get('/index/list',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    total = ret.data.total;
                    setTimeout(function(){
                        self.btn_sta();
                        call(ret.data.list);
                    },0);
                }
            },
            {
                category: self.category,
                index : i
            });
    };
    //获取更多
    this.get_list = function(){
        index++;
        self.get_data(index,function(list){
            self.insert_list(list);
        });
    };
    //获取分类
    this.get_category_list = function(category_id){
        index = 0;
        self.category = self.vue.category =category_id;
        self.vue.categoryload = true;
        self.get_data(index,function(list){
            self.vue.categoryload = false;
            self.swith_list(list);
        },1);

    };
    //分类Tap事件绑定 TODO VUE 触摸事件扩展
    $category.tap(function(){
        var id = this.data('id');
        self.get_category_list(id);
    });
    $load.tap(self.get_list);
    self.btn_sta();
}).call(define('list'));