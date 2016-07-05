@extends('layout.base')

@section('nav')
@parent
@stop

@section('body')
<!--用户中栏start-->
<div id="mid" class="user-mid">
    <div class="top">
        <div class="avatar"><a href="/user/{{$id}}"><img src="{{avatar($profile['avatar'])}}"></a></div>
        <h4>{{$profile['slogan']}}</h4>
        <h2>{{$profile['nickname']}}</h2>
        <h3>{{$profile['introduce']}}</h3>
        <div class="social">
@if(!empty($profile['weibo']))
            <a class="weibo" href="http://weibo.com/{{$profile['weibo']}}" target="_blank" title="微博"></a>
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
@if(!empty($self))
    <div class="mid">
        <div class="container">
            <a href="/user/{{$id}}" class="{{isset($active) && $active =='home' ? 'active' : ''}}"><span>个人主页</span></a>
            <a href="/user/profile" class="{{isset($active) && $active =='profile' ? 'active' : ''}}"><span>资料修改</span></a>
            <a href="/user/password" class="{{isset($active) && $active =='password' ? 'active' : ''}}"><span>密码修改</span></a>
            <a href="/user/social" class="{{isset($active) && $active =='social' ? 'active' : ''}}"><span>社交资料</span></a>
        </div>
    </div>
@endif
</div>
<!--用户中栏end-->
@stop