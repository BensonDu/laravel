@extends('layout.site')
@section('style')@parent  <link href="/css/public.content.css?v2" rel="stylesheet">
    <link href="http://dn-t2ipo.qbox.me/v3/public/editor/medium-editor-insert-plugin.min.css" rel="stylesheet">
    <link href="/css/public.detail.css?v6" rel="stylesheet">
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

        <!--广告start-->
@if(isset($ad->type) && $ad->type == '3')
        <div class="ad-article-image">
            <a href="{{$ad->link}}" target="_blank"><img src="{{$ad->image}}"></a>
        </div>
@endif
@if(isset($ad->type) && $ad->type == '2')
        <div class="ad-article-text">
            <span>推广</span>{!! $ad->text !!}
        </div>
@endif
        <!--广告end-->

        <div id="detail-handle" class="handle">

            <div class="tag">
                <em>标签:</em>
                @foreach ($article['tags'] as $tag)
                    <a href="/tag/{{$tag}}">{{$tag}}</a>
                @endforeach
            </div>

            <div class="action">
                <div class="wrap">
                    <div class="weixin" v-on:mouseenter="_qr">
                        <a><em></em><span>分享</span><div id="qr-container"></div><b></b></a>
                    </div>
                    <div class="favorite" v-bind:class="favorite && 'active'" v-on:click="_favorite">
                        <a><em></em><span v-text="favorites"></span><i>+1</i></a>
                    </div>
                    <div class="like" v-bind:class="like && 'active'" v-on:click="_like">
                        <a><em></em><span v-text="likes"></span><i>+1</i></a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
<!--新闻内容end-->
@stop
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script>
    (function () {
        this.like       = '{{$article['like']}}' == '1';
        this.favorite   = '{{$article['favorite']}}' == '1';
        this.likes      = '{{$article['likes']}}';
        this.favorites  = '{{$article['favorites']}}';
    }).call(define('data'))
</script>
<script src="http://dn-acac.qbox.me/qrcode.js"></script>
<script src="/js/site.detail.js?v1"></script>
<!--苹果分销start-->
<script type='text/javascript'>var _merchantSettings=_merchantSettings || [];_merchantSettings.push(['AT', '1000lmBS']);(function(){var autolink=document.createElement('script');autolink.type='text/javascript';autolink.async=true; autolink.src= ('https:' == document.location.protocol) ? 'https://autolinkmaker.itunes.apple.com/js/itunes_autolinkmaker.js' : 'http://autolinkmaker.itunes.apple.com/js/itunes_autolinkmaker.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(autolink, s);})();</script>
<!--苹果分销end-->
@stop