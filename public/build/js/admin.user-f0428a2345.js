(function(){this.w=$(window),this.d=$(document),this.filter=$("#filter"),this.content=$("#admin-content"),this.mid=$("#mid")}).call(define("dom")),function(){window.view_nav_left.fold=function(){return dom.mid.css("left",60),dom.content.css("padding-left",310),dom.filter.addClass("trans").removeClass("unfold")},window.view_nav_left.unfold=function(){return dom.mid.css("left",140),dom.content.css("padding-left",390),dom.filter.addClass("trans").addClass("unfold")}}.call(define("view_page_response")),function(){var e=this;this.model={el:"#admin-content",data:{background:!1,search:{keyword:""},orderby:data.orderby,order:"desc",pagination:{total:data.total,size:10,index:1,btns:[],all:0},list:data.list},methods:{_search:function(){e.page_search()},_size:function(t){e.page_size(t)},_turn:function(t){"number"==typeof t&&e.page_turn(t)},_next:function(){this.pagination.index+1<=this.pagination.all&&e.page_turn(this.pagination.index+1)},_prev:function(){this.pagination.index-1>=1&&e.page_turn(this.pagination.index-1)},_order:function(t){this.orderby=t,this.order="desc"==this.order?"asc":"desc",e.page_order()},_edit:function(e){controller_pop.display.edit.show(e)},_add:function(){controller_pop.display.add.show()},_del:function(t){pop.confirm("确认删除 ?","确定",function(){e.delete_user(t)})}}},this.page_search=function(){e.get_data()},this.page_order=function(){e.get_data()},this.page_turn=function(t){e.model.data.pagination.index=t,e.get_data()},this.page_size=function(t){var a=e.model.data.pagination;a.size=t,a.all=Math.ceil(a.total/a.size),a.index>a.all&&(a.index=a.all),e.get_data()},this.update_pagination_btn=function(){var t=e.model.data.pagination;return t.all=Math.ceil(t.total/t.size),t.btns=e.create_pagination_btn(t.index,t.size,t.total)},this.create_pagination_btn=function(e,t,a){var d,i,n=Math.ceil(a/t),o=[],r=function(e,t){return o.push({active:e,index:t})},s=function(){return o.push({active:!1,index:"..."})};if(n<=7)for(var l=1;l<=n;l++)r(l==e,l);else{d=e-3<1?1:e-3,i=e+3>n?n:e+3,d>1&&r(!1,1),d>2&&s();for(var l=d;l<=i;l++)r(l==e,l);i<n-1&&s(),i<n&&r(!1,n)}return o},this.get_data=function(){var t=e.model.data;request.get("/admin/user/users",function(t){t.hasOwnProperty("code")&&0==t.code&&(e.model.data.pagination.total=t.data.total,e.model.data.list=t.data.list,e.update_pagination_btn())},{index:t.pagination.index,size:t.pagination.size,order:t.order,orderby:t.orderby,keyword:t.search.keyword})},this.delete_user=function(t){request.get("/admin/user/delete",function(t){t.hasOwnProperty("code")&&0==t.code?e.get_data():pop.error("操作失败","确定")},{id:t})},this.vue=new Vue(e.model),e.update_pagination_btn()}.call(define("controller_admin")),function(){var e=this,t=[{name:"认证撰稿人",id:1},{name:"编辑",id:2},{name:"管理员",id:3}];this.model={el:"#pop-container",data:{display:"",edit:{id:"",user:{avatar:"",nickname:""},role:{active:!1,text:"",val:"0",list:t}},add:{step_second:!1,keyword:"",search_start:!1,search_result:[],user:{id:"",avatar:"",nickname:""},role:{active:!1,text:"",val:"0",list:t}}},methods:{_close:function(){this.display="",this.edit.role.active=0,controller_admin.model.data.background&&(controller_admin.model.data.background=!1)},_role_display:function(){this.edit.role.active=!this.edit.role.active},_role_select:function(e,t){this.edit.role.val=e,this.edit.role.text=t,this.edit.role.active=!1},_confirm_role:function(){e.update_auth_setting()},_search:function(){this.add.search_start=!0,e.search()},_search_start:function(){this.add.search_start&&(this.add.search_start=!1),this.add.search_result=[]},_step_back:function(){this.add.step_second=0,this.add.user.id=""},_select_search:function(e,a,d){this.add.user.id=e,this.add.user.avatar=a,this.add.user.nickname=d,this.add.role.text=t[0].name,this.add.role.val=t[0].id,this.add.step_second=!0},_add_role_display:function(){this.add.role.active=!this.add.role.active},_add_role_select:function(e,t){this.add.role.val=e,this.add.role.text=t,this.add.role.active=!1},_confirm_add:function(){e.add_user()}}},this.display={edit:{show:function(t){e.model.data.edit.id=t,e.model.data.display="edit",controller_admin.model.data.background=!0,e.get_user_info(t)},hide:function(){e.model.data.display="",controller_admin.model.data.background=!1}},add:{show:function(){e.model.data.add.step_second=!1,e.model.data.add.search_result=[],e.model.data.add.search_start=!1,e.model.data.add.user.id="",e.model.data.add.keyword="",e.model.data.display="add",controller_admin.model.data.background=!0},hide:function(){e.model.data.display="",controller_admin.model.data.background=!1}}},this.add_user=function(){return""!=e.model.data.add.user&&void request.get("/admin/user/add",function(t){t.hasOwnProperty("code")&&0==t.code?(e.display.add.hide(),e.update_list()):pop.error("添加失败","确定").one()},{id:e.model.data.add.user.id,role:e.model.data.add.role.val})},this.search=function(){var t=e.model.data.add.keyword;return""!=t&&void request.get("/admin/user/search",function(t){t.hasOwnProperty("code")&&0==t.code?e.model.data.add.search_result=t.data:pop.error("请求错误","确定").one()},{keyword:t})},this.update_auth_setting=function(){request.get("/admin/user/update",function(t){t.hasOwnProperty("code")&&0==t.code?(e.display.edit.hide(),pop.success("设置成功","确定",function(){e.update_list()}).one()):pop.error("保存失败","确定").one()},{id:e.model.data.edit.id,role:e.model.data.edit.role.val})},this.get_user_info=function(t){request.get("/admin/user/info",function(t){t.hasOwnProperty("code")&&0==t.code?e.fill_user_info(t.data):pop.error("获取数据失败","确定").one()},{id:t})},this.fill_user_info=function(t){var a=e.model.data.edit;a.role.text=e.get_role_name(t.role),a.role.val=t.role,a.user.avatar=t.avatar,a.user.nickname=t.nickname},this.get_role_name=function(e){var a="",d=t;for(var i in d)d[i].id==e&&(a=d[i].name);return a},this.update_list=function(){controller_admin.get_data()},this.vue=new Vue(e.model)}.call(define("controller_pop"));