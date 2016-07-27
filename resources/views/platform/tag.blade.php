@extends('layout.base')
@section('style')<link href="{{ $_ENV['platform']['cdn'].elixir("css/platform.tag.css")}}" rel="stylesheet">
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
    <div class="tag-container">
        <div class="tag-wrap">
            <h5>TAGGED IN</h5>
            <h3>{{$tag}}</h3>
            <p>Total: <b v-text="total"></b></p>
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
            <div class="item" v-bind:class="visible" v-for="article in list">
                <div class="item-wrap">
                    <div class="info">
                        <div class="tags">
                            <a v-for="tag in article.tags" v-bind:href="'/tag/'+tag.item" v-bind:style="'color:'+tag.color" v-text="tag.item"></a>
                        </div>
                        <a class="title" target="_blank" v-bind:href="article.domain+article.article_id" v-text="article.title"></a>
                        <h5 class="summary" v-text="article.summary"></h5>
                        <div class="social">
                            <a target="_blank" v-bind:href="'/user/'+article.user_id" class="author"><img v-bind:src="article.avatar"><span v-text="article.nickname"></span></a>
                            <p class="time" v-text="article.post_time"></p>
                            <p>发布于</p>
                            <a class="inter" target="_blank" v-bind:href="article.domain"><span v-text="article.name"></span></a>
                        </div>
                    </div>
                    <div class="image">
                        <img v-if="!!article.image" v-bind:src="article.image">
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
</div>
<!--主页内容end-->
@stop
@section('script')@parent<script>
    (function () {
        this.tag    = '{{$tag}}';
        this.total  = '{{$total}}';
        this.list   = JSON.parse('{!! json_encode_safe($list) !!}');
        this.sites = JSON.parse('{!! json_encode_safe($filter['sites']) !!}');
    }).call(define('data'));
</script>
<script src="{{ $_ENV['platform']['cdn'].elixir("js/platform.tag.js")}}"></script>
@stop