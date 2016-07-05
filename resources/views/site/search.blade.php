@extends('layout.site')
@section('style')<link href="{{$_ENV['platform']['cdn']}}/dist/css/site.search.css" rel="stylesheet">
@stop
@section('body')
@parent
<!--主页内容start-->
<div id="site-content" class="site-content">
    <div class="search-container">
        <div class="input-wrap">
            <div class="input">
                <input type="text" v-model="keyword" v-on:keypress.enter="search" placeholder="输入关键词">
                <a class="pub-background-transition" v-on:click="search">搜索</a>
            </div>
        </div>
        <div class="result-wrap">
            <p>找到约 <span v-text="total"></span>  条结果 </p>
        </div>
    </div>
    <div class="list-container">
        <div class="list-wrap">
            <div class="item" v-for="ret in list">
                <a v-bind:href="'/'+ret.article_id" class="title" v-html="ret.title"></a>
                <p class="summary" v-html="ret.summary"></p>
                <div class="info">
                    <a class="author" target="_blank" v-bind:href="ret.user_url" v-html="ret.nickname"></a>
                    <p class="time" v-text="ret.create_time"></p>
                </div>
            </div>
        </div>
    </div>
    <a class="load-more" v-bind:class="load">
        <p v-on:click="get_list">
            <span v-text="load">More</span><em></em>
        </p>
    </a>
</div>
<!--主页内容end-->
@stop
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script>var default_data = {
        search : {
            keyword : '{{$search['keyword']}}',
            total : '{{$search['total']}}',
            list : JSON.parse('{!! $search['list'] !!}')
        }
    }
</script>
<script src="/js/site.search.js?v1"></script>
@stop