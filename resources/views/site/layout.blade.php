@extends('layout.base')

@section('nav')
    @parent
@stop

@section('body')
<!--站点中栏start-->
<div id="mid" class="site-mid">
    <div class="top">
        <div class="logo"><a href="/"><img src="{{$site['logo']}}"></a></div>
        <h2>{{$site['name']}}</h2>
        <h3>{{$site['slogan']}}</h3>
        <div class="social">
@if(!empty($site['weibo']))
            <a class="weibo" href="{{$site['weibo']}}" target="_blank" title="微博"></a>
@endif
@if(!empty($site['email']))
            <a class="email" href="mailto:{{$site['email']}}" title="E-mail"></a>
@endif
@if(!empty($site['twitter']))
            <a class="twitter" href="{{$site['twitter']}}"  target="_blank" title="twitter"></a>
@endif
@if(!empty($site['weixin']))
            <a class="weixin">
                <img src="{{$site['weixin']}}">
            </a>
@endif
        </div>
@if($site['contribute'])
        <div class="post">
            <a href="{{$_ENV['platform']['home']}}/user/edit">投稿</a>
        </div>
@endif
    </div>
    <div class="mid">
        <div class="container">
@foreach($site['nav'] as $v)
            <a href="{{$v['link']}}" target="{{!empty($v['id']) ? '_self' : '_blank'}}" class="{{isset($active) && $active == $v['id'] ? 'active' : ''}}"><span>{{$v['name']}}</span></a>
@endforeach
        </div>
    </div>
    <div class="bottom">
        <a href="http://www.miitbeian.gov.cn/" target="_blank">{{$site['icp']}}</a>
    </div>
</div>
<!--站点中栏end-->
@stop