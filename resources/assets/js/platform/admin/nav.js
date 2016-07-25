/**
 * Created by Benson on 16/7/22.
 */
(function () {
    var self = this,dom = document.getElementById('sort-list');
    Sortable.create(dom, {
        draggable: '.item',
        animation: 150,
        ghostClass: "drag-ghost",
        onUpdate: function () {
            self.order();
        }
    });
    this.order = function () {
        var order = [],id;
        $(dom).children().each(function () {
            id = $(this).data('id');
            !!id && order.push(id);
        });
        c_nav.save(order);
    }
}).call(define('c_order'));

(function () {
    var self = this;

    //当前站点ID数组
    this.selected = [];

    //格式化数组筛选出站点ID
    this.format = function (list) {
        var l = list.length;
        self.selected = [];
        for(var i = 0; i < l; i++){
            self.selected.push(list[i].id);
        }
        return list;
    };

    this.vue = new Vue({
        el : '#admin-content',
        data : {
            list : self.format(data.list)
        },
        methods : {
            _add : function () {
                c_pop.display.add.show();
            },
            _del : function (id) {
                var l = this.list.length,order = [],list = [];
                //过滤出不为待删除ID的站点列表
                for(var i = 0; i < l; i++){
                    if(this.list[i].id != id){
                        order.push(this.list[i].id);
                        list.push(this.list[i]);
                    }
                }
                //保存
                self.save(order,function () {
                    self.vue.list = list;
                });
            }
        }
    });
    //保存列表
    this.save = function (order,call) {
        //缓存当前站点ID数组
        self.selected = order;
        //同步待添加站点列表
        c_pop.syn();
        //数据保存
        request.post(
            '/admin/option/nav/save',
            function (ret) {
                if(ret.hasOwnProperty('code') && ret.code == '0'){
                    if(!!call)call();
                }
            },
            {
                order : order
            }
        );
    };
    //更新列表
    this.update_list = function(){
        request.get('/admin/option/nav/list',function(ret){
            if(ret.hasOwnProperty('code') && ret.code == 0){
                self.vue.list = self.format(ret.data);
            }
        });
    };

}).call(define('c_nav'));

(function () {
    var self = this;
    this.vue = new Vue({
        el : '#pop-container',
        data : {
            display : '',
            keyword : '',
            list : []
        },
        methods : {
            _close : function () {
                self.display.add.hide();
            },
            _add : function (id) {
                if($.inArray(id,c_nav.selected) < 0 ){
                    c_nav.selected.push(id);
                    c_nav.save(c_nav.selected,function () {
                        c_nav.update_list();
                    });
                }
            },
            _search : function () {
                self.get_site_list();
            }
        }
    });
    //站点列表
    this.sites = [];
    //最新选中状态同步列表
    this.syn   = function () {
        var selected = c_nav.selected,ret = [],l = self.sites.length;
        for(var i = 0; i < l; i ++){
            if($.inArray(self.sites[i].id,selected) < 0){
                ret.push(self.sites[i]);
            }
        }
        self.vue.list = ret;
    };
    //显示组
    this.display = {
        add : {
            show : function () {
                self.vue.display    = 'add';
                self.get_site_list();
            },
            hide : function () {
                self.vue.display    = '';
                self.vue.keyword    = '';
            }
        }
    };
    //获得站点列表
    this.get_site_list = function () {
        request.get('/site/list',function (ret) {
                if(ret.hasOwnProperty('code') && ret.code == '0'){
                    self.sites = ret.data;
                    self.syn();
                }
            },
            {
                keyword : self.vue.keyword
            }
        );
    }
}).call(define('c_pop'));