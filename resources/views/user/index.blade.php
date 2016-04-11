@extends('layout.user')
@section('style')@parent  <link href="/css/user.index.css" rel="stylesheet">
@stop
@section('body')
@parent
<!--主页内容start-->
<div id="user-nav" class="user-nav">
    <div class="user-nav-container">
        <div class="left">
            <a class="active" href="#">最新文章</a>
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
                        <a style="color: {{$tag['color']}}">{{$tag['item']}}</a>
@endforeach
                    </div>
                    <div class="title">
                        <a href="/user/{{$id}}/{{$article->id}}">{{$article->title}}</a>
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
                    <img src="{{$article->image}}">
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
                        <a v-for="tag in article.tags" v-bind:style="'color:'+tag.color" v-text="tag.item"></a>
                    </div>
                    <div class="title">
                        <a v-bind:href="'/user/{{$id}}/'+article.id" v-text="article.title"></a>
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
                    <img v-bind:src="article.image">
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
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script>var default_data = {id : '{{$id}}', article : {total : '{{$total}}'},api : '/user/index/list'}</script>
<script src="/js/user.index.js"></script>
@stop