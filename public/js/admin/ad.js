/**
 * Created by Benson on 16/2/25.
 */
(function(){
    var self = this;

    this.model = {
        el : '#admin-content',
        data : {
            background : false,
            publish : data.publish,
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
            _publish : function(type){
                this.publish = type;
                self.get_data();
            },
            _add : function () {
                controller_pop.display.add.show();
            },
            _edit : function(id){
                !!id && controller_pop.display.edit.show(id);
            },
            _del : function(id){
                pop.confirm('确认删除 ?','确定',function(){
                    self.del(id);
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
        request.get('/admin/ad/ads',function(ret){
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
                publish : d.publish
            });

    };
    //删除文章
    this.del = function(id){
        request.get('/admin/ad/del', function(ret){
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

(function () {
    var self = this;
    this.vue = new Vue({
        el : '#pop-container',
        data : {
            display : '',
            ad : {
                id : '',
                title : '',
                weight: 1,
                type : 1,
                start : '',
                end : '',
                imageone : '',
                imagetwo : '',
                text : '',
                link : ''
            }
        },
        methods : {
            _close:function () {
                self.display.hide();
            },
            _type : function (type) {
                this.ad.type = type;
            },
            _weight_add : function () {
                this.ad.weight < 10 && this.ad.weight++;
            },
            _weight_sub : function () {
                this.ad.weight > 1 && this.ad.weight--;
            },
            _confirm : function () {
                return this.ad.id == '' ? self.add() : self.update();
            },
            _upload_image_one:function () {
                var _this = this.$els.imageone;
                if(!_this.files)return pop.error( '浏览器兼容性错误','确定' ).one();
                imageCrop(_this.files,{
                    aspectRatio :  4/3,
                    croppedable : true,
                    finish : function (url) {
                        self.vue.ad.imageone = url;
                    },
                    error : function (text) {
                        pop.error( text || '上传失败','确定').one();
                    }
                });
                _this.value = '';
            },
            _upload_image_two:function () {
                var _this = this.$els.imagetwo;
                if(!_this.files)return pop.error( '浏览器兼容性错误','确定' ).one();
                imageCrop(_this.files,{
                    aspectRatio :  8,
                    croppedable : true,
                    finish : function (url) {
                        self.vue.ad.imagetwo = url;
                    },
                    error : function (text) {
                        pop.error( text || '上传失败','确定').one();
                    }
                });
                _this.value = '';
            },
            _del_image_one : function () {
                this.ad.imageone = '';
            },
            _del_image_two : function () {
                this.ad.imagetwo = '';
            }
        }
    });
    this.display = {
        add : {
            show:function(){
                controller_admin.model.data.background = true;
                self.vue.display = 'add';
                self.vue.ad.id = '';
                self.vue.ad.type = 1;
                self.vue.ad.weight = 1;
                self.vue.ad.start = moment().format('YYYY-MM-DD HH:mm');
                self.vue.ad.end = moment( (moment().unix()+60*60*24*7)*1000).format('YYYY-MM-DD HH:mm');
            }
        },
        edit : {
            show: function (id) {
                request.get('/admin/ad/info',function (ret) {
                    if(ret.hasOwnProperty('code') && ret.code ==0 ){
                        controller_admin.model.data.background = true;
                        self.vue.display = 'edit';
                        self.vue.ad = $.extend({imageone : '',imagetwo:''},ret.data);
                        self.vue.ad.imageone = ret.data.type == '1' ? ret.data.image : '';
                        self.vue.ad.imagetwo = ret.data.type == '3' ? ret.data.image : '';
                        self.vue.ad.id = id;
                    }
                    else{
                        self.vue.display = '';
                        pop.error( ret.msg || '打开失败','确定').one();
                    }
                },{
                    id:id
                });
            }
        },
        hide : function(){
            var p = self.vue.ad;
            controller_admin.model.data.background = false;
            self.vue.display = '';
            p.id='';
            p.type = 1;
            p.weight = 1;
            p.link = '';
            p.imageone = '';
            p.imagetwo = '';
            p.text = '';
            p.title = '';
        }
    };
    //获取表单
    this.get_form  =function () {
        var d = self.vue.ad;
        return {
            id : d.id,
            title : d.title,
            image : d.type == '2' ? '' : (d.type == '1' ? d.imageone : d.imagetwo),
            link : d.link,
            text : d.text,
            start : d.start,
            end : d.end,
            type : d.type,
            weight : d.weight
        }
    };
    //检查表单
    this.check_form = function (update) {
      var t = '',f = self.get_form();
        if(f.title == '')t = '请填写标题';
        if(f.type != '2' && f.image == '')t = '请上传广告图片';
        if(f.type == '2' && f.text == '')t = '请填写广告文案';
        if(f.link == '')t = '请填写广告链接';
        if(moment(f.start).unix() < moment().unix()-60*20 && !update)t = '起始时间小于当前时间';
        if(moment(f.end).unix() < moment(f.start).unix() && !update)t = '终止时间小于起始时间';
        if(t != ''){
            pop.error(t,'确定').one();
            return false;
        }
        return true;
    };
    //添加广告
    this.add = function () {
        if(!self.check_form(false))return false;
        request.post('/admin/ad/add',function (ret) {
            if(ret.hasOwnProperty('code') && ret.code ==0){
                self.update_list();
                self.display.hide();
            }
            else{
                pop.error('添加失败','确定').one();
            }
        },self.get_form());
    };
    //更新广告
    this.update = function () {
        if(!self.check_form(true))return false;
        request.post('/admin/ad/update',function (ret) {
            if(ret.hasOwnProperty('code') && ret.code ==0){
                self.update_list();
                self.display.hide();
            }
            else{
                pop.error('更新失败','确定').one();
            }
        },self.get_form());
    };
    this.update_list = function(){
        controller_admin.get_data();
    };
}).call(define('controller_pop'));


(function(){
    $('#picker-start').datetimepicker({
        inline: true,
        sideBySide: true
    }).on('changeDate', function(){
        controller_pop.vue.ad.start = $(this).data('date');
    }).datetimepicker('setStartDate',moment( (moment().unix()-60*60*10)*1000).format('YYYY-MM-DD'));

    $('#picker-end').datetimepicker({
        inline: true,
        sideBySide: true
    }).on('changeDate', function(){
        controller_pop.vue.ad.end = $(this).data('date');
    }).datetimepicker('setEndDate',moment( (moment().unix()+60*60*24*365)*1000).format('YYYY-MM-DD'));;
}).call(define('controller_timerpicker'));
