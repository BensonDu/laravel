@extends('admin.user.layout')

@section('table')
    <table>
        <thead>
        <tr>
            <th><span>用户昵称</span></th>
            <th class="orderable" v-bind:class="orderby == 'role' ? order : ''" v-on:click="_order('role')"><span>身份</span><em></em></th>
            <th class="orderable" v-bind:class="orderby == 'create_time' ? order : ''" v-on:click="_order('create_time')"><span>添加时间</span><em></em></th>
            <th><span>操作</span></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="a in list">
            <td class="clickable active" v-on:click="_edit(a.user_id)"><a v-text="a.nickname"></a></td>
            <td v-text="a.role"></td>
            <td v-text="a.create_time"></td>
            <td class="handle">
                <div>
                    <a class="edit" v-on:click="_edit(a.user_id)"><em></em><span>修改</span></a>
                    <a class="del" v-on:click="_del(a.user_id)"><em></em><span>删除</span></a>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
@stop
@section('pop')
    <div id="pop-container" class="pop-container" v-bind:class="display">
        <div class="thin-box edit">
            <div class="header">
                <h3><em></em><span>修改</span></h3>
                <a v-on:click="_close">×</a>
            </div>

            <div class="profile">
                <div class="avatar">
                    <img v-bind:src="edit.user.avatar">
                </div>
                <div class="intro">
                    <p v-text="edit.user.nickname"></p>
                </div>
            </div>

            <div class="role" v-bind:class="{ 'active': edit.role.active }">
                <h5>身份权限</h5>
                <p v-on:click="_role_display"><span v-text="edit.role.text"></span><em></em></p>
                <ul>
                    <li v-for="r in edit.role.list" v-text="r.name" v-on:click="_role_select(r.id,r.name)"></li>
                </ul>
            </div>

            <div class="confirm">
                <div><a v-on:click="_confirm_role"><em class="yes"></em><span>确定</span></a></div>
                <div><a v-on:click="_close"><em class="no"></em><span>取消</span></a></div>
            </div>
        </div>
        <div class="thin-box add" v-bind:class="add.step_second ? 'second' : ''">
            <div class="header">
                <h3 v-on:click="_step_back"><em></em></h3>
                <a v-on:click="_close">×</a>
            </div>

            <div class="search">
                <div class="wrap">
                    <div class="input">
                        <input type="text" placeholder="用户名、用户 ID" v-on:focus="_search_start" v-on:keydown="_search_start" v-on:keyup.enter="_search" v-model="add.keyword">
                        <a v-on:click="_search">搜索</a>
                        <ul v-if="add.search_start">
                            <li v-for="r in add.search_result"><img v-bind:src="r.avatar"><span v-text="r.nickname"></span><em v-on:click="_select_search(r.id,r.avatar,r.nickname)">+</em></li>
                        </ul>
                    </div>
                    <div class="role">
                        <div class="profile">
                            <div class="avatar">
                                <img v-bind:src="add.user.avatar">
                            </div>
                            <div class="intro">
                                <p v-text="add.user.nickname"></p>
                            </div>
                        </div>
                        <div class="set" v-bind:class="{ 'active': add.role.active }">
                            <h5>身份权限</h5>
                            <p v-on:click="_add_role_display"><span v-text="add.role.text"></span><em></em></p>
                            <ul>
                                <li v-for="r in add.role.list" v-text="r.name" v-on:click="_add_role_select(r.id,r.name)"></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="confirm">
                <div><a v-on:click="_confirm_add"><em class="yes"></em><span>确定</span></a></div>
                <div><a v-on:click="_close"><em class="no"></em><span>取消</span></a></div>
            </div>
        </div>
    </div>
@stop
@section('script')@parent<script>
    (function () {
        this.total  = '{{$users['total']}}';
        this.list   = JSON.parse('{!! json_encode_safe($users['list']) !!}');
        this.orderby= 'create_time';
    }).call(define('data'));
</script>
<script src="{{ $_ENV['platform']['cdn'].elixir("js/admin.user.js")}}"></script>
@stop