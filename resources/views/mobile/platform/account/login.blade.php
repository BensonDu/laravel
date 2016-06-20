@extends('mobile.platform.layout')

@section('style')@parent<link href="/mobile/css/public.account.css" rel="stylesheet">
@stop

@section('body')
<!--背景start-->
<div id="background" class="background">
    <div class="filter"></div>
</div>
<!--背景end-->
<div id="login-container" class="content">
    <div class="container">
        <div class="wrap">
            <div class="account">
                <em class="username"></em>
                <div class="input">
                    <span></span>
                    <input type="text" placeholder="用户名/手机号" v-model="username.val" v-on:blur="_check">
                </div>
                <div class="error" v-bind:class="username.error.active && 'active'">
                    <p v-text="username.error.text"></p>
                </div>
            </div>
            <div class="account">
                <em class="password"></em>
                <div class="input">
                    <span></span>
                    <input type="password" placeholder="密码" v-model="password.val">
                </div>
            </div>
            <div class="confirm">
                <a class="pub-background-transition" v-on:click="_login">登录</a>
            </div>
            <div class="entry">
                <div class="left">
                    <em class="forgot"></em>
                    <a href="/account/find?redirect=http://dubaoxing.com/user/edit">忘记密码</a>
                </div>
                <div class="right">
                    <a href="/account/regist?redirect=http://dubaoxing.com/user/edit">立即注册</a>
                    <em class="arrow"></em>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script src="/js/account/login.js?v2"></script>
@stop