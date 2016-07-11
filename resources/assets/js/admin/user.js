/**
 * Created by Benson on 16/3/2.
 */
(function(){
    var self = this;

    this.model = {
        el : '#admin-content',
        data : {
            background : false,
            search : {
                keyword : ''
            },
            orderby : data.orderby,
            order : 'desc',
            pagination : {
                total : data.total,
                size : 10,
                index : 1,
                btns : [],
                all : 0
            },
            list : data.list
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
            _order : function(by){
                this.orderby = by;
                this.order  = this.order == 'desc' ? 'asc' : 'desc';
                self.page_order();
            },
            _edit : function(id){
                controller_pop.display.edit.show(id);
            },
            _add : function(){
                controller_pop.display.add.show();
            },
            _del : function(id){
                pop.confirm('确认删除 ?','确定',function(){
                    self.delete_user(id);
                });
            }
        }
    };
    //搜索
    this.page_search = function(){
        self.get_data();
    };
    //切换排序
    this.page_order = function(){
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
        request.get('/admin/user/users',function(ret){
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
                orderby : d.orderby,
                keyword: d.search.keyword
            });

    };
    //删除
    this.delete_user = function(id){
        request.get('/admin/user/delete', function(ret){
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
    var self = this,
        role_list = [{'name':'认证撰稿人','id':1},{'name':'编辑','id':2},{'name':'管理员','id':3}];

    this.model = {
        el : '#pop-container',
        data : {
            display : '',
            edit : {
                id : '',
                user : {
                    avatar : '',
                    nickname : ''
                },
                role : {
                    active : false,
                    text : '',
                    val : '0',
                    list : role_list
                }
            },
            add : {
                step_second :false,
                keyword : '',
                search_start : false,
                search_result : [],
                user : {
                    id : '',
                    avatar : '',
                    nickname :''
                },
                role : {
                    active : false,
                    text : '',
                    val : '0',
                    list : role_list
                }
            }
        },
        methods : {
            _close : function(){
                this.display = '';
                this.edit.role.active = 0;
                if(controller_admin.model.data.background) controller_admin.model.data.background = false;
            },
            _role_display : function(){
                this.edit.role.active = !this.edit.role.active;
            },
            _role_select : function(c,n){
                this.edit.role.val = c;
                this.edit.role.text = n;
                this.edit.role.active = false;
            },
            _confirm_role : function(){
                self.update_auth_setting();
            },
            _search : function(){
                this.add.search_start = true;
                self.search();
            },
            _search_start : function(){
                this.add.search_start && (this.add.search_start = false);
                this.add.search_result = [];
            },
            _step_back : function(){
                this.add.step_second = 0;
                this.add.user.id = '';
            },
            _select_search : function(id,avatar,nickname){
                this.add.user.id = id;
                this.add.user.avatar = avatar;
                this.add.user.nickname = nickname;
                this.add.role.text = role_list[0]['name'];
                this.add.role.val = role_list[0]['id'];
                this.add.step_second = true;
            },
            _add_role_display : function(){
                this.add.role.active = !this.add.role.active;
            },
            _add_role_select : function(c,n){
                this.add.role.val = c;
                this.add.role.text = n;
                this.add.role.active = false;
            },
            _confirm_add : function(){
                self.add_user();
            }
        }
    };
    this.display = {
        edit : {
            show : function(id) {
                self.model.data.edit.id = id;
                self.model.data.display = 'edit';
                controller_admin.model.data.background = true;
                self.get_user_info(id);
            },
            hide : function(){
                self.model.data.display = '';
                controller_admin.model.data.background = false;
            }
        },
        add : {
            show : function(){
                self.model.data.add.step_second = false;
                self.model.data.add.search_result = [];
                self.model.data.add.search_start = false;
                self.model.data.add.user.id = '';
                self.model.data.add.keyword = '';
                self.model.data.display = 'add';
                controller_admin.model.data.background = true;
            },
            hide : function(){
                self.model.data.display = '';
                controller_admin.model.data.background = false;
            }
        }
    };
    //添加用户
    this.add_user = function(){
        if(self.model.data.add.user == '')return false;
        request.get('/admin/user/add',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.display.add.hide();
                    self.update_list();
                }
                else{
                    pop.error('添加失败','确定').one();
                }
            },
            {
                id : self.model.data.add.user.id,
                role : self.model.data.add.role.val
            }
        );
    };
    //搜索用户
    this.search = function(){
        var k = self.model.data.add.keyword;
        if(k == '')return false;
        request.get('/admin/user/search',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.model.data.add.search_result = ret.data;
                }
                else{
                    pop.error('请求错误','确定').one();
                }
            },
            {
                keyword : k
            }
        );
    };
    //更新权限设置信息
    this.update_auth_setting = function(){
        request.get('/admin/user/update',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.display.edit.hide();
                    pop.success('设置成功','确定',function(){
                        self.update_list();
                    }).one();
                }
                else{
                    pop.error('保存失败','确定').one();
                }
            },
            {
                id : self.model.data.edit.id,
                role : self.model.data.edit.role.val
            }
        );
    };
    //获得用户信息
    this.get_user_info = function(id){
        request.get('/admin/user/info',function(ret){
                if(ret.hasOwnProperty('code') && ret.code ==0){
                    self.fill_user_info(ret.data);
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
    //填充用户信息
    this.fill_user_info = function(data){
      var d = self.model.data.edit;
        d.role.text = self.get_role_name(data.role);
        d.role.val = data.role;
        d.user.avatar = data.avatar;
        d.user.nickname = data.nickname;
    };
    //身份ID获取身份名称
    this.get_role_name = function(id){
        var r = '',l = role_list;
        for(var i in l){
            if(l[i].id == id){
                r = l[i].name;
            }
        }
        return r;
    };
    this.update_list = function(){
        controller_admin.get_data();
    };
    this.vue = new Vue(self.model);
}).call(define('controller_pop'));