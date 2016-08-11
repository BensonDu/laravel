@extends('admin.user.layout')

@section('table')
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
@section('script')@parent<script>
    (function () {
        this.total  = '{{$users['total']}}';
        this.list   = JSON.parse('{!! json_encode_safe($users['list']) !!}');
        this.orderby= 'time';
    }).call(define('data'));
</script>
<script src="{{ $_ENV['platform']['cdn'].elixir("js/admin.blacklist.js")}}"></script>
@stop