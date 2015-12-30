@extends('layout.account')
@section('body')
    @parent
    <div class="content">
        <div class="container">
            <div class="wrap">
                <h2>注册</h2>
                <div class="account">
                    <em class="username"></em>
                    <div class="input">
                        <span></span>
                        <input type="text" placeholder="用户名">
                    </div>
                    <div class="error">
                        <p>用户名不存在</p>
                    </div>
                </div>
                <div class="account input-phone">
                    <em class="phone"></em>
                    <div class="input">
                        <span></span>
                        <input type="number" placeholder="手机号码">
                    </div>
                    <div class="send">
                        <a href="#" class="pub-background-transition">发送验证码</a>
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
                        <input type="password" placeholder="密码">
                    </div>
                    <div class="error active">
                        <p>密码过长</p>
                    </div>
                </div>
                <div class="account">
                    <em class="password"></em>
                    <div class="input">
                        <span></span>
                        <input type="password" placeholder="重复密码">
                    </div>
                </div>
                <div class="confirm">
                    <a class="pub-background-transition">注册</a>
                </div>
                <div class="entry">
                    <div class="left">
                        <em class="forgot"></em>
                        <a href="/account/find">忘记密码</a>
                    </div>
                    <div class="right">
                        <a href="/account/login">直接登录</a>
                        <em class="arrow"></em>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop