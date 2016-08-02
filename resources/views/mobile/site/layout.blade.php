@extends('mobile.base')

@section('header')
<!--站点头部start-->
<div class="site-head">
    <div class="top">
        <a href="/" class="logo"><img src="{{$site->mobile_logo}}"></a>
        <a id="site-head-menu" class="menu">
            <ul class="hamburger">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </a>
        <div id="site-head-nav" class="nav">
            <div class="nav-container">
                <div class="back-platform">
                    <a href="{{$_ENV['platform']['home']}}"><em></em></a>
                </div>
                <div class="account">
@if(!empty($_ENV['uid']))
                    <a>{{$nickname}}</a><span></span><a href="{{$_ENV['platform']['home'].'/account/logout'}}">退出登录</a>
@else
                    <a href="{{$_ENV['platform']['home'].'/account/login'.$nav['callback']}}">登录</a><span></span><a href="{{$_ENV['platform']['home'].'/account/regist'.$nav['callback']}}">注册</a>
@endif
                </div>
                <div class="site-info">
                    <div class="big-logo">
                        <img src="{{$site['logo']}}">
                    </div>
                    <div class="description">
                        <div class="text">
                            <h3>{{$site['name']}}</h3>
                            <p>{{$site['description']}}</p>
                        </div>

                    </div>
                </div>
                <div class="nav-list">
@foreach($site['nav'] as $v)
                    <a href="{{$v['link']}}">{{$v['name']}}</a>
@endforeach
                </div>
                <div class="social">
                    <div id="site-qr" class="qr">
                        <img>
                    </div>
                    <div class="buttons">
                        <div class="wrap">
@if(!empty($site['weibo']))
                            <a href="{{$site['weibo']}}" class="weibo"><em></em></a>
@endif
@if(!empty($site['email']))
                            <a href="mailto:{{$site['email']}}" class="email"><em></em></a>
@endif
@if(!empty($site['weixin']))
                            <a id="weixin" class="weixin" data-src="{{$site['weixin']}}"><em></em></a>
@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--站点头部end-->
@stop