@extends('mobile.platform.account.layout')

@section('body')@parent
<div class="content">
    <div class="container">
        <div id="find-form" class="wrap">
            <div class="account input-phone">
                <em class="username"></em>
                <div class="input">
                    <span></span>
                    <input type="text" v-model="username.val" placeholder="用户名/手机号">
                </div>
                <div class="send">
                    <a href="#" class="pub-background-transition" v-on:click="_get_captcha"  v-bind:class="captcha.disable ? 'disable' : ''" v-text="captcha.text"></a>
                </div>
                <div class="error" v-bind:class="username.error ? 'active' : ''">
                    <p v-text="username.error_tip"></p>
                </div>
                <div class="lock pub-fade-transition short" v-bind:class="username.lock ? 'active' : ''"></div>
            </div>
            <div class="account">
                <em class="captcha"></em>
                <div class="input">
                    <span></span>
                    <input type="number" v-model="captcha.val" placeholder="验证码">
                </div>
                <div class="error" v-bind:class="captcha.error ? 'active' : ''">
                    <p v-text="captcha.error_tip"></p>
                </div>
                <div class="lock pub-fade-transition"  v-bind:class="captcha.lock ? 'active' : ''"></div>
            </div>
            <div class="account">
                <em class="password"></em>
                <div class="input">
                    <span></span>
                    <input type="password" v-model="password.val" placeholder="新密码">
                </div>
                <div class="error" v-bind:class="password.error ? 'active' : ''">
                    <p v-text="password.error_tip"></p>
                </div>
                <div class="lock pub-fade-transition"  v-bind:class="password.lock ? 'active' : ''"></div>
            </div>
            <div class="account">
                <em class="password"></em>
                <div class="input">
                    <span></span>
                    <input type="password" v-model="password_re.val" placeholder="重复新密码">
                </div>
                <div class="error" v-bind:class="password_re.error ? 'active' : ''">
                    <p v-text="password_re.error_tip"></p>
                </div>
                <div class="lock pub-fade-transition"  v-bind:class="password_re.lock ? 'active' : ''"></div>
            </div>
            <div class="confirm">
                <a v-on:click="_submit" class="pub-background-transition">找回密码</a>
            </div>
            <div class="entry">
                <div class="right">
                    <a href="/account/login{{$redirect}}">直接登录</a>
                    <em class="arrow"></em>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')@parent<script src="{{ $_ENV['platform']['cdn'].elixir("mobile/js/account.find.js")}}"></script>
@stop