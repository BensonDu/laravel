@extends('layout.user')
@section('style')@parent  <link href="/css/user.profile.css" rel="stylesheet">
@stop
@section('body')
@parent
<!--主页内容start-->
<div id="user-nav" class="user-nav">
    <div class="user-nav-container">
        <div class="left">
            <a href="/user/profile">个人资料</a>
            <a class="active" href="/user/password">修改密码</a>
        </div>
    </div>
</div>
<div id="user-content" class="user-content">
    <div class="container">
        <div class="item-normal">
            <p>旧密码:</p>
            <input type="password">
        </div>
        <div class="item-normal">
            <p>新密码:</p>
            <input type="password">
        </div>
        <div class="item-normal">
            <p>重复新密码:</p>
            <input type="password">
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