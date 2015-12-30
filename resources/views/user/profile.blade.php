@extends('layout.user')
@section('style')@parent  <link href="/css/user.profile.css" rel="stylesheet">
@stop
@section('body')
@parent
<!--主页内容start-->
<div id="user-nav" class="user-nav">
    <div class="user-nav-container">
        <div class="left">
            <a class="active" href="/user/profile">个人资料</a>
            <a href="/user/password">修改密码</a>
        </div>
    </div>
</div>
<div id="user-content" class="user-content">
    <div class="container">
        <div class="item-small">
            <p>用户名:</p><span>dubaoxing</span>
        </div>
        <div class="avatar">
            <div class="name">
                <p>头像:</p>
            </div>
            <div class="img">
                <div class="wrap">
                    <img src="http://dn-acac.qbox.me/mobile/public/New_avatar.png">
                </div>
            </div>
        </div>
        <div class="item-normal">
            <p>昵称:</p>
            <input type="text">
        </div>
        <div class="item-normal">
            <p>Slogan:</p>
            <input type="text">
        </div>
        <div class="item-textarea">
            <p>简介:</p>
            <textarea maxlength="100"></textarea>
        </div>
        <div class="save pub-background-transition">
            <a href="#">保存</a>
        </div>
    </div>
</div>
<!--主页内容end-->
@stop
@section('script')@parent<script src="/js/user.profile.js"></script>
@stop