@extends('mobile.base')
@section('style')@parent  <link href="/mobile/css/public.detail.css?v" rel="stylesheet">
@stop
@section('body')
<div class="article-image">
@if(!empty($article['image']))
    <div class="img" style="background-image: url({{$article['image']}})"></div>
@endif
    <div class="summary {{!empty($article['image']) ? 'image' : ''}}">
        <p class="info"><span>{{$article['category']}}</span><span> | </span><span>{{$article['time']}}</span></p>
    </div>
</div>
<div class="author">
    <div class="avatar"><img src="{{$user['avatar']}}"></div>
    <div class="name"><p>{{$user['name']}}</p></div>
</div>
<div class="article">
    <h3>{{$article['title']}}</h3>
    <h5>{{$article['summary']}}</h5>
    <div class="content medium">
        {!! $article['content'] !!}
    </div>
    <p class="end">-- End --</p>
</div>
<div id="toutiao-container" style="display: none"></div>
@stop
@section('script')@parent
<!--头条推荐阅读start-->
<script src="http://dn-noman.qbox.me/toutiao.tech2ipo.min.js"></script>
<script>(readsByToutiao = window.readsByToutiao ||[]).push({  id:'toutiao-container',plugins: { "done": function() {console.log("im done");}}});</script>
<!--头条推荐阅读end-->
<!--苹果分销start-->
<script type='text/javascript'>var _merchantSettings=_merchantSettings || [];_merchantSettings.push(['AT', '1000lmBS']);(function(){var autolink=document.createElement('script');autolink.type='text/javascript';autolink.async=true; autolink.src= ('https:' == document.location.protocol) ? 'https://autolinkmaker.itunes.apple.com/js/itunes_autolinkmaker.js' : 'http://autolinkmaker.itunes.apple.com/js/itunes_autolinkmaker.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(autolink, s);})();</script>
<!--苹果分销end-->
@stop