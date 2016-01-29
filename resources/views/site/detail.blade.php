@extends('layout.site')
@section('style')@parent  <link href="/css/public.content.css" rel="stylesheet">
<link href="/css/public.detail.css" rel="stylesheet">
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
            <a href="/user/{{$user['id']}}" class="author"><img src="{{$user['avatar']}}"><span>{{$user['name']}}</span></a>
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
    </div>
</div>
<!--新闻内容end-->
@stop
@section('script')@parent<script src="/js/public.detail.js"></script>
@stop