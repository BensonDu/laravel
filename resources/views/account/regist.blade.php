@extends('layout.account')
@section('body')
@parent
<div class="content">
    <div class="container">
        <div id="regist-form" class="wrap">
            <h2>注册</h2>
            <div class="account">
                <em class="username"></em>
                <div class="input">
                    <span></span>
                    <input type="text" v-on:blur="check_username" v-model="username.val" placeholder="用户名">
                </div>
                <div class="error" v-bind:class="username.error ? 'active' : ''">
                    <p v-text="username.error_tip"></p>
                </div>
            </div>
            <div class="account input-phone">
                <em class="phone"></em>
                <div class="input">
                    <span></span>
                    <input type="number" v-model="phone.val" placeholder="手机号码">
                </div>
                <div class="send">
                    <a href="#" v-on:click="get_captcha"  v-bind:class="captcha.disable ? 'disable' : ''" v-text="captcha.text" class="pub-background-transition"></a>
                </div>
                <div class="error" v-bind:class="phone.error ? 'active' : ''">
                    <p v-text="phone.error_tip"></p>
                </div>
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
            </div>
            <div class="account">
                <em class="password"></em>
                <div class="input">
                    <span></span>
                    <input type="password" v-model="password.val" placeholder="密码">
                </div>
                <div class="error" v-bind:class="password.error ? 'active' : ''">
                    <p v-text="password.error_tip"></p>
                </div>
            </div>
            <div class="account">
                <em class="password"></em>
                <div class="input">
                    <span></span>
                    <input type="password" v-model="password_re.val" placeholder="重复密码">
                </div>
                <div class="error" v-bind:class="password_re.error ? 'active' : ''">
                    <p v-text="password_re.error_tip"></p>
                </div>
            </div>
            <div class="confirm">
                <a v-on:click="submit" class="pub-background-transition">注册</a>
            </div>
            <div class="entry">
                <div class="left">
                    <em class="forgot"></em>
                    <a href="/account/find{{$redirect}}">忘记密码</a>
                </div>
                <div class="right">
                    <a href="/account/login{{$redirect}}">直接登录</a>
                    <em class="arrow"></em>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="regist-success" class="pop-window">
    <div class="box">
        <div class="img">
            <em></em>
        </div>
        <div class="title">
            <h5>注册成功</h5>
        </div>
        <div class="btn-group">
            <a href="/">返回首页</a>
            <a href="/user/profile">完善个人资料</a>
        </div>
    </div>
</div>
@stop
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script src="/js/account/regist.js?v"></script>
@stop