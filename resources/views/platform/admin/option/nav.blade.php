@extends('platform.admin.option.layout')

@section('container')
    <div class="admin-nav">
        <div class="wrap">
            <ul class="header">
                <li class="site"><p>站点</p></li>
                <li class="handle"><p>操作</p></li>
            </ul>
            <div id="sort-list" class="site-list">
                <div class="item" v-for="a in list" v-bind:data-id = "a.id">
                    <div class="info">
                        <div class="logo"><img v-bind:src="a.logo"></div>
                        <div class="name"><h3 v-text="a.name"></h3></div>
                    </div>
                    <div class="handle">
                        <a v-on:click="_del(a.id)">×</a>
                    </div>
                </div>
            </div>
            <div class="add" v-if="list.length < 7">
                <p v-on:click="_add">+</p>
            </div>
        </div>
    </div>
@stop

@section('pop')
    <div id="pop-container" class="nav-pop-container" v-bind:class="display">
        <div class="add">

            <div class="header">
                <h3><em>+</em><span>添加</span></h3>
                <a v-on:click="_close">×</a>
            </div>

            <div class="keyword">
                <input type="text" placeholder="输入关键词" v-model="keyword" v-on:keyup.enter="_search">
                <a v-on:click="_search">搜索</a>
            </div>

            <ul class="item">
                <li v-for="s in list">
                    <a v-text="s.name"></a>
                    <em v-on:click="_add(s.id)">+</em>
                </li>
            </ul>

            <div class="result">
                <span>共: </span>
                <b v-text="list.length"></b>
                <span> 条</span>
            </div>

        </div>
    </div>
@stop
@section('script')@parent<script>
    (function () {
        this.list = JSON.parse('{!! json_encode_safe($sites) !!}')
    }).call(define('data'));
</script>
<script src="{{ $_ENV['platform']['cdn'].elixir("js/platform/admin.nav.js")}}"></script>
@stop