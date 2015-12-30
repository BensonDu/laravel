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
        <h4>GOOD MORNING</h4>
        <h2>凉凉钳</h2>
        <h3>一句话介绍一下自己吧,让别人了解你有多撒逼</h3>
        <div class="social">
            <a class="weibo" href="#" title="微博"></a>
            <a class="email" href="#" title="E-mail"></a>
            <a class="twitter" href="#" title="twitter"></a>
            <a class="weixin" href="#" title="微信">
                <img src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2FFullSizeRender.jpg">
            </a>
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