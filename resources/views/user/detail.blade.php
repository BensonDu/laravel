@extends('layout.user')
@section('style')@parent  <link href="/css/public.content.css?" rel="stylesheet">
<link href="/css/public.detail.css?v" rel="stylesheet">
@stop
@section('body')
@parent
<!--新闻内容部分start-->
<div id="page-content" class="page-content">
    <div class="container">
        <div class="summary">
            @if(!empty($article->image))
                <div class="image">
                    <img src="{{$article->image}}">
                </div>
            @endif
            <div class="info {{empty($article->image) ? 'no-image' : ''}}">
                <div class="wrap">
                    <a class="author" href="{{$_ENV['platform']['home']}}/user/{{$id}}">
                        <img src="{{$profile['avatar']}}">
                        <p class="name">{{$profile['nickname']}}</p>
                    </a>
                    <div class="desc">
                        <div class="top">
                            <a>{{isset($article->tags[0]) ? $article->tags[0].' | ':''}}</a>
                            <a>发布于 {{$article->post_time}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="title">
            <h1>{{$article->title}}</h1>
            <h6>{{$article->summary}}</h6>
        </div>

        <div class="content medium">
            {!! $article->content !!}
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
@section('script')@parent<script src="/js/user.detail.js"></script>
@stop