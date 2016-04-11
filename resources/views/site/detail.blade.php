@extends('layout.site')
@section('style')@parent  <link href="/css/public.content.css?v2" rel="stylesheet">
    <link href="http://dn-t2ipo.qbox.me/v3/public/editor/medium-editor-insert-plugin.min.css" rel="stylesheet">
    <link href="/css/public.detail.css?v3" rel="stylesheet">
@stop
@section('body')
@parent
<!--新闻内容部分start-->
<div id="site-content" class="page-content">
    <div class="container">
        <div class="summary {{empty($article['image']) ? 'no-image' : ''}}">
            <div class="image">
                <img src="{{$article['image']}}">
            </div>
            <div class="info">
                <div class="wrap">
                    <a class="author" href="{{$_ENV['platform']['home']}}/user/{{$user['id']}}">
                        <img src="{{$user['avatar']}}">
                        <p class="name">{{$user['name']}}</p>
                    </a>
                    <div class="desc">
                        <div class="top">
                            <a>{{!empty($article['category']) ? $article['category'].' | ':''}}</a>
                            <a>发布于 {{$article['time']}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="title">
            <h1>{{$article['title']}}</h1>
            <h6>{{$article['summary']}}</h6>
        </div>
        <div class="content medium">
            {!! $article['content'] !!}
        </div>
        <div class="tag">
            <em>标签:</em>
@foreach ($article['tags'] as $tag)
                <a href="/tag/{{$tag}}">{{$tag}}</a>
@endforeach
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
@section('script')@parent<script src="/js/site.detail.js?"></script>
@stop