@extends('layout.base')
@section('style')<link href="{{ $_ENV['platform']['cdn'].elixir("css/platform.search.css") }}" rel="stylesheet">
@stop
@section('body')
@parent
<!--顶栏start-->
<div id="nav-container" class="index-header">
    <div class="wrap">
        <div class="logo">
            <a href="/"><img src="http://qiniu.cdn-chuang.com/platform-logo.png?"></a>
            <p>[ 用心创作快乐 ]</p>
        </div>
        <a class="about" href="http://help.chuang.pro" target="_blank">
            <i></i>
            <p>点这里,了解创之</p>
        </a>
    </div>
</div>
<!--顶栏end-->
<!--主页内容start-->
<div id="content-container" class="content-container">
    <div class="search-container">
        <div class="input-wrap">
            <div class="input">
                <input type="text" v-model="keyword" v-on:keypress.enter="_search" placeholder="输入关键词">
                <a class="pub-background-transition" v-on:click="_search">搜索</a>
            </div>
        </div>
        <div class="result-wrap">
            <p>找到约 <span v-text="total"></span>  条结果 </p>
        </div>
        <div class="filter-wrap">
            <div class="filter">
                <h3>全部结果 :</h3>
                <ul>
                   <li v-for="s in filter.selected"><span v-text="s.name"></span><em v-on:click="_unselected(s.id)">×</em></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="list-container">
        <div class="filter-wrap">
            <div class="condition-item" v-bind:class="!filter.fold && 'all'">
                <h3>站点 :</h3>
                <ul>
                    <li v-bind:class="filter.selected.length == 0 && 'active'" v-on:click="_clear"><span>不限</span></li>
                    <li v-for="s in sites" v-on:click="_selected(s.id)"><span v-text="s.name"></span></li>
                </ul>
                <a v-on:click="_fold"><span></span><em></em></a>
            </div>
        </div>
        <div class="list-wrap">
            <div class="item" v-for="ret in list">
                <a v-bind:href="ret.domain+ret.id" class="title" v-html="ret.title"></a>
                <p class="summary" v-html="ret.summary"></p>
                <div class="info">
                    <a class="author" target="_blank" v-bind:href="'/user/'+ret.user_id" v-html="ret.nickname"></a>
                    <p class="time" v-text="ret.post_time"></p>
                    <p>发布于:</p>
                    <a target="_blank" v-bind:href="ret.domain" v-text="ret.name"></a>
                </div>
            </div>
        </div>
    </div>
    <a class="load-more" v-bind:class="load">
        <p v-on:click="_more">
            <span v-text="load">More</span><em></em>
        </p>
    </a>
</div>
<!--主页内容end-->
@stop
@section('script')@parent<script>
    (function () {
        this.search = {
            keyword : '{{$search['keyword']}}',
            total : '{{$search['total']}}',
            list : JSON.parse('{!! json_encode_safe($search['list']) !!}')
        };
        this.sites = JSON.parse('{!! json_encode_safe($filter['sites']) !!}')
    }).call(define('data'));
</script>
<script src="{{ $_ENV['platform']['cdn'].elixir("js/platform.search.js")}}"></script>
@stop