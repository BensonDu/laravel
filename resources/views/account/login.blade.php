@extends('layout.account')
@section('body')
    @parent
    <div class="content">
        <div class="container">
            <div class="wrap">
                <h2>登录</h2>
                <div id="login-username" class="account">
                    <em class="username"></em>
                    <div class="input">
                        <span></span>
                        <input type="text" placeholder="用户名/手机号">
                    </div>
                    <div class="error">
                        <p>用户名不存在</p>
                    </div>
                </div>
                <div id="login-password" class="account">
                    <em class="password"></em>
                    <div class="input">
                        <span></span>
                        <input type="password" placeholder="密码">
                    </div>
                    <div class="error">
                        <p>用户名不存在</p>
                    </div>
                </div>
                <div id="login-confirm" class="confirm">
                    <a class="pub-background-transition">登录</a>
                </div>
                <div class="entry">
                    <div class="left">
                        <em class="forgot"></em>
                        <a href="/account/find">忘记密码</a>
                    </div>
                    <div class="right">
                        <a href="/account/regist">立即注册</a>
                        <em class="arrow"></em>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('script')@parent<script src="/js/account/login.js"></script>
@stop