@extends('layout.site')
@section('style')@parent  <link href="/css/public.content.css" rel="stylesheet">
<link href="/css/public.detail.css?" rel="stylesheet">
@stop
@section('body')
@parent
<!--新闻内容部分start-->
<div id="page-content" class="page-content">
    <div class="container">
        <div class="summary">
            <h1>{{$article['title']}}</h1>
            <h6>{{$article['summary']}}</h6>
        </div>
        <div class="social">
            <a href="{{$_ENV['platform']['home']}}/user/{{$user['id']}}" class="author"><img src="{{$user['avatar']}}"><span>{{$user['name']}}</span></a>
            <p class="time">发布于 {{$article['time']}}</p>
            <div class="tag">
                <em></em>
                @foreach ($article['tags'] as $tag)
                    <a href="/tag/{{$tag}}">{{$tag}}</a>
                @endforeach
            </div>
        </div>
        <div class="content medium">
            {!! $article['content'] !!}
        </div>
        <div class="action">
            <div class="wrap">
                <div id="favorite" class="favorite {{$article['favorite'] ? 'active' : ''}}">
                    <a><em></em><span></span><i>+1</i></a>
                </div>
                <div class="tip"></div>
                <div id="like" class="like {{$article['like'] ? 'active' : ''}}">
                    <a><em></em><span></span><i>+1</i></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!--新闻内容end-->
@stop
@section('script')@parent<script src="/js/site.detail.js"></script>
@stop