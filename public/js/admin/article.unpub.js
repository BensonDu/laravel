/**
 * Created by Benson on 16/2/25.
 */
(function(){
    var self = this;

    this.model = {
        el : '#admin-content',
        data : {
            search : {
                keyword : ''
            },
            order : 'desc',
            pagination : {
                total : default_data.total,
                size : 10,
                index : 1,
                btns : [],
                all : 0
            },
            list : default_data.list
        },
        methods : {
            _search:function(){
                self.page_search();
            },
            _size : function(size){
                self.page_size(size)
            },
            _turn : function(index){
                if(typeof index == 'number')self.page_turn(index);
            },
            _next : function(){
                if(this.pagination.index + 1 <= this.pagination.all)self.page_turn(this.pagination.index+1);
            },
            _prev : function(){
                if(this.pagination.index - 1 >= 1)self.page_turn(this.pagination.index-1);
            },
            _order : function(){
                self.page_order(this.order);
            },
            _post : function(id){
                controller_pop.display.post(id);
            },
            _edit : function(id){
                console.log(id);
            },
            _del : function(id){
                pop.confirm('确认删除 ?','确定',function(){
                    self.delete_article(id);
                });
            }
        }
    };
    //搜索
    this.page_search = function(){
        self.get_data();
    };
    //切换排序
    this.page_order = function(order){
        self.model.data.order  = self.model.data.order == 'desc' ? 'asc' : 'desc';
        self.get_data();
    };
    //跳转
    this.page_turn = function(index){
        self.model.data.pagination.index = index;
        self.get_data();
    };
    //切换每页数量
    this.page_size = function(size){
        var d = self.model.data.pagination;
        d.size = size;
        d.all = Math.ceil(d.total/ d.size);
        if(d.index > d.all)d.index = d.all;
        self.get_data();
    };
    //更新页码表
    this.update_pagination_btn = function(){
        var d = self.model.data.pagination;
        d.all = Math.ceil(d.total/ d.size);
        return d.btns = self.create_pagination_btn(d.index, d.size, d.total);
    };
    //生成页码列表
    this.create_pagination_btn = function(index,size,total){
        var t = Math.ceil(total/size),
            a = [],
            s, f,
            n = function(act,i){
                return a.push({
                    active : act,
                    index : i
                })
            },
            e = function(){
                return a.push({
                    active : false,
                    index : '...'
                });
            };
        if(t <= 7){
            for(var i = 1; i <= t ;i++){
                n(i==index, i);
            }
        }
        else {
            s = index - 3 < 1 ? 1 : index - 3;
            f = index + 3 > t ? t : index + 3;
            if(s>1)n(false,1);
            if(s>2)e();
            for (var i = s; i <= f; i++) {
                n(i == index, i);
            }
            if(f< t-1)e();
            if(f<t)n(false, t);
        }
        return a;
    };
    //获取数据
    this.get_data = function(){
        var d = self.model.data;
        request.get('/admin/article/unpubs',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.model.data.pagination.total = ret.data.total;
                    self.model.data.list = ret.data.list;
                    self.update_pagination_btn();
                }
            },
            {
                index : d.pagination.index,
                size : d.pagination.size,
                order : d.order,
                keyword: d.search.keyword
            });

    };
    //删除文章
    this.delete_article = function(id){
        request.get('/admin/article/delete', function(ret){
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.get_data();
            }
            else{
                pop.error('操作失败','确定');
            }
        },{id : id});
    };
    this.vue = new Vue(self.model);
    //执行初始化页码
    self.update_pagination_btn();
}).call(define('controller_admin'));

(function(){
    var self = this;

    this.model = {
        el : '#pop-container',
        data : {
            display : '',
            post : {
                id : '',
                category : {
                    active : false,
                    text : '',
                    val : '',
                    list : default_data.categories
                },
                type : {
                    val : 'now',/*now | time | cancel*/
                    time : ''
                }
            }
        },
        methods : {
            _close : function(){
                this.display = '';
                this.post.category.active = 0;
            },
            _category_display : function(){
                this.post.category.active = !this.post.category.active;
            },
            _category_select : function(c,n){
                this.post.category.val = c;
                this.post.category.text = n;
            },
            _type_select : function(t){
                this.post.type.val = t;
                t = 'time' && (this.post.type.time = moment().format('YYYY-MM-DD HH:mm'));
            },
            _confirm_post : function(){
                this.display = '';
                this.post.category.active = 0;
                self.confirm_post();
            }
        }
    };
    this.display = {
       post : function(id){
           self.model.data.display = 'post';
           self.get_post_info(id);
       }
    };
    //Get article post info
    this.get_post_info = function(id){
        request.get('/admin/article/post/info',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.set_post(id,ret.data.category,ret.data.type,ret.data.time);
                }
                else{
                    pop.error('获取数据失败','确定').one();
                }
            },
            {
                id : id
            }
        );
    };
    //Get category name
    this.get_category_name = function(id){
        var r = '',l = self.model.data.post.category.list;
           for(var i in l){
               if(l[i].id == id){
                   r = l[i].name;
               }
           }
        return r;
    };
    //Default post
    this.set_post = function(id,category,type,time){
        var d = self.model.data.post;
        d.id = id;
        d.category.val = category;
        d.category.text = self.get_category_name(category);
        d.type.val = type;
        !!time && (d.type.time = time);
    };
    //Save post
    this.confirm_post = function(){
        var d = self.model.data.post,
            data = {
                id : d.id,
                category : d.category.val,
                type : d.type.val,
                time : d.type.time
            };
        request.get('/admin/article/post/save',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.update_list();
                }
                else{
                    pop.error('设置失败','确定').one();
                }
            },
            data
        );

    };
    this.update_list = function(){
        controller_admin.get_data();
    };
    this.vue = new Vue(self.model);
}).call(define('controller_pop'));

(function(){
    $('#datetimepicker').datetimepicker({
        inline: true,
        sideBySide: true
    }).on('changeDate', function(){
        controller_pop.model.data.post.type.time = $(this).data('date');
    });
}).call(define('controller_timerpicker'));