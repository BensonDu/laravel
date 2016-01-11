@extends('layout.base')
@section('style')@parent<link href="/css/user.mid.css" rel="stylesheet">
@stop
@section('nav')
@parent
@stop
@section('body')
<!--用户中栏start-->
<div id="mid" class="user-mid">
    <div class="top">
        <div class="avatar"><img src="http://dn-acac.qbox.me/mobile/public/New_avatar.png"></div>
        <h4>{{$profile['slogan']}}</h4>
        <h2>{{$profile['nickname']}}</h2>
        <h3>{{$profile['introduce']}}</h3>
        <div class="social">
@if(!empty($profile['weibo']))
            <a class="weibo" href="{{$profile['weibo']}}" target="_blank" title="微博"></a>
@endif
@if(!empty($profile['email']))
            <a class="email" href="mailto:{{$profile['email']}}" title="E-mail"></a>
@endif
@if(!empty($profile['twitter']))
            <a class="twitter" href="#" title="twitter"></a>
@endif
@if(!empty($profile['wechat']))
            <a class="weixin" title="微信">
                <img src="{{$profile['wechat']}}">
            </a>
@endif
        </div>
    </div>
    <div class="mid">
        <div class="container">
            <a href="/user/132132" class="active"><span>个人主页</span></a>
            <a href="/user/profile"><span>资料修改</span></a>
            <a href="/user/password"><span>基本设置</span></a>
        </div>
    </div>
</div>
<!--用户中栏end-->
@stop