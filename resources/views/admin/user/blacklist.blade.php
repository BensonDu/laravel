@extends('layout.admin')
@section('style')@parent  <link href="/css/admin.user.css?" rel="stylesheet">
@stop
@section('area')
    <div class="list-header">
        <div class="nav">
            <a href="/admin/user">成员管理</a>
            <a class="active" href="/admin/user/blacklist">黑名单</a>
        </div>
        <div class="nav-right">
            <a v-on:click="_add"><em>+</em> <span>添加</span></a>
        </div>
        <div class="divider">
            <em></em>
        </div>
        <div class="search">
            <div class="input"><em></em><input v-model="search.keyword" v-on:keyup.enter="_search" type="text" placeholder="搜索用户"><i></i></div>
        </div>
    </div>
    <div class="list-body">
        <table>
            <thead>
            <tr>
                <th><span>用户昵称</span></th>
                <th class="orderable" v-bind:class="orderby == 'time' ? order : ''" v-on:click="_order('time')"><span>添加时间</span><em></em></th>
                <th><span>操作</span></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="a in list">
                <td class="clickable"><a v-text="a.nickname"></a></td>
                <td v-text="a.time"></td>
                <td class="handle">
                    <div>
                        <a class="unlink" v-on:click="_del(a.user_id)"><em></em><span>解除黑名单</span></a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="list-footer">
        <div class="page-size">
            <p>每页显示</p>
        <span>
            <a v-on:click="_size(10)" v-bind:class="pagination.size == 10 ? 'active' : ''">10</a>
            <a v-on:click="_size(20)" v-bind:class="pagination.size == 20 ? 'active' : ''">20</a>
            <a v-on:click="_size(50)" v-bind:class="pagination.size == 50 ? 'active' : ''">50</a>
        </span>
            <p>条</p>
        </div>
        <div class="page-info">
            <p>共 <span v-text="pagination.total"></span> 条</p>
        </div>
        <div class="pagination">
            <a class="btn" v-on:click="_prev()"v-bind:class="pagination.index != 1 ? 'active' : ''" ><em class="prev"></em></a>
        <span>
            <a v-for="b in pagination.btns" v-bind:class="{ 'disable': b.index == '...', 'active': b.active }" v-text="b.index" v-on:click="_turn(b.index)"></a>
        </span>
            <a class="btn" v-on:click="_next()" v-bind:class="pagination.index+1<=pagination.all ? 'active' : ''"><em class="next"></em></a>
        </div>
    </div>
@stop
@section('pop')
    <div id="pop-container" class="pop-container" v-bind:class="display">
        <div class="blacklist-add" >
            <div class="header">
                <h3><em></em></h3>
                <a v-on:click="_close">×</a>
            </div>
            <div class="selector">
                <div class="keyword">
                    <input type="text" placeholder="输入用户名或用户 ID" v-model="search.keyword" v-on:keyup.enter="_search">
                    <a v-on:click="_search">搜索</a>
                </div>
                <div class="item">
                    <a v-for="a in search.list">
                        <img v-bind:src="a.avatar">
                        <p v-text="a.nickname"></p>
                        <em v-on:click="_add(a.id)">+</em>
                    </a>
                </div>
                <div class="result">
                    <span>共: </span>
                    <b v-text="search.list.length"></b>
                    <span> 条</span>
                </div>
            </div>
        </div>
    </div>
@stop
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script>
    (function () {
        this.total  = '{{$users['total']}}';
        this.list   = JSON.parse('{!! json_encode_safe($users['list']) !!}');
        this.orderby= 'time';
    }).call(define('data'));
</script>
<script src="/js/admin/blacklist.js"></script>
@stop