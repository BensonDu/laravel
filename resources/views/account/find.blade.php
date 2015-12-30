@extends('layout.account')
@section('body')
@parent
<div class="content">
    <div class="container">
        <div class="wrap">
            <h2>找回密码</h2>
            <div class="account input-phone">
                <em class="username"></em>
                <div class="input">
                    <span></span>
                    <input type="text" placeholder="用户名/手机号">
                </div>
                <div class="send">
                    <a href="#" class="pub-background-transition">验证</a>
                </div>
            </div>
            <div class="account">
                <em class="captcha"></em>
                <div class="input">
                    <span></span>
                    <input type="number" placeholder="验证码">
                </div>
            </div>
            <div class="account">
                <em class="password"></em>
                <div class="input">
                    <span></span>
                    <input type="password" placeholder="新密码">
                </div>
            </div>
            <div class="account">
                <em class="password"></em>
                <div class="input">
                    <span></span>
                    <input type="password" placeholder="重复新密码">
                </div>
            </div>
            <div class="confirm">
                <a class="pub-background-transition">确认</a>
            </div>
            <div class="entry">
                <div class="right">
                    <a href="/account/login">直接登录</a>
                    <em class="arrow"></em>
                </div>
            </div>
        </div>
    </div>
</div>
@stop