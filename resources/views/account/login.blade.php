@extends('account.layout')
@section('body')
    @parent
<div id="login-container" class="content">
    <div class="container">
        <div class="wrap">
            <h2>登录</h2>
            <div class="account">
                <em class="username"></em>
                <div class="input">
                    <span></span>
                    <input type="text"  autocomplete="on" placeholder="用户名/手机号" v-model="username.val" v-on:blur="_check">
                </div>
                <div class="error" v-bind:class="username.error.active && 'active'">
                    <p v-text="username.error.text"></p>
                </div>
            </div>
            <div class="account">
                <em class="password"></em>
                <div class="input">
                    <span></span>
                    <input type="password"  autocomplete="on" placeholder="密码" v-model="password.val" v-on:keyup.enter="_login">
                </div>
            </div>
            <div class="confirm">
                <a class="pub-background-transition" v-on:click="_login">登录</a>
            </div>
            <div class="entry">
                <div class="left">
                    <em class="forgot"></em>
                    <a href="/account/find{{$redirect}}">忘记密码</a>
                </div>
                <div class="right">
                    <a href="/account/regist{{$redirect}}">立即注册</a>
                    <em class="arrow"></em>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('script')@parent<script src="{{ $_ENV['platform']['cdn'].elixir("js/account.login.js")}}"></script>
@stop