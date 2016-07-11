@extends('layout.user')
@section('style')<link href="{{ $_ENV['platform']['cdn'].elixir("css/user.index.css")}}" rel="stylesheet">
@stop
@section('body')
@parent
<!--主页内容start-->
<div id="user-nav" class="user-nav">
    <div class="user-nav-container">
        <div class="left">
            <a class="active">文章归档</a>
@if(!empty($self))
            <a href="/user/favorite">我的收藏</a>
@endif
        </div>
    </div>
</div>
<div id="user-content" class="user-content">
    <div class="container">
        <div id="article-list" class="list-container">
@foreach ($list as $article)
            <div class="list visible">
                <div class="info">
                    <div class="tags">
@foreach ($article->tags as $tag)
                        <a href="{{$article->domain}}/tag/{{$tag['item']}}" target="_blank" style="color: {{$tag['color']}}">{{$tag['item']}}</a>
@endforeach
                    </div>
                    <div class="title">
                        <a target="_blank" href="{{$article->domain}}/{{$article->id}}">{{$article->title}}</a>
                    </div>
                    <div class="summary">
                        <h5>{{$article->summary}}</h5>
                    </div>
                    <div class="social">
                        <p class="time">发布于 {{$article->post_time}}</p>
                        <a class="inter like"><span>赞 {{$article->likes}}</span></a>
                        <a class="inter collect"><span>收藏 {{$article->favorites}}</span></a>
                    </div>
                </div>
@if(!empty($article->image))
                <div class="image">
                    <img data-lazy-src="{{$article->image}}" src="http://dn-noman.qbox.me/Occupy.png">
                </div>
@endif
            </div>
@endforeach
@if(empty($total))
           <div class="none">
               <h5>暂无内容</h5>
           </div>
@endif
            <div class="list" v-bind:class="visible" v-for="article in list">
                <div class="info">
                    <div class="tags">
                        <a target="_blank"  v-for="tag in article.tags" v-bind:href="article.domain+'/tag/'+tag.item" v-bind:style="'color:'+tag.color" v-text="tag.item"></a>
                    </div>
                    <div class="title">
                        <a target="_blank" v-bind:href="article.domain+'/'+article.id" v-text="article.title"></a>
                    </div>
                    <div class="summary">
                        <h5 v-text="article.summary"></h5>
                    </div>
                    <div class="social">
                        <p class="time" v-text="'发布于 '+article.post_time"></p>
                        <a class="inter like"><span v-text="'赞 '+article.likes"></span></a>
                        <a class="inter collect"><span v-text="'收藏 '+article.favorites"></span></a>
                    </div>
                </div>
                <div class="image" v-if="!!article.image">
                    <img v-bind:data-lazy-src="article.image" src="http://dn-noman.qbox.me/Occupy.png">
                </div>
            </div>
            <a class="load-more" v-bind:class="load">
                <p v-on:click="get_list">
                    <span v-text="load"></span><em></em>
                </p>
            </a>
        </div>
    </div>
</div>
<!--主页内容end-->
@stop
@section('script')@parent<script>
    (function () {
        this.id =  '{{$id}}';
        this.article = {
            total : '{{$total}}'
        };
        this.api = '/user/index/list';
    }).call(define('data'));
</script>
<script src="{{ $_ENV['platform']['cdn'].elixir("js/user.index.js")}}"></script>
@stop