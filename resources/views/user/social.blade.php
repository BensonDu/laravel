@extends('user.layout')
@section('style')<link href="{{ $_ENV['platform']['cdn'].elixir("css/user.profile.css")}}" rel="stylesheet">
@stop
@section('body')
@parent
        <!--主页内容start-->
<div id="user-nav" class="user-nav">
    <div class="user-nav-container">
        <div class="left">
            <a href="/user/profile">个人资料</a>
            <a href="/user/password">修改密码</a>
            <a class="active" href="/user/social">社交资料</a>
        </div>
    </div>
</div>
<div id="user-content" class="user-content">
    <div id="social" class="container">
        <div class="item">
            <div class="wrap avatar">
                <p class="name">微信二维码:</p>
                <div class="img">
                    <div class="box">
                        <img class="qr" v-bind:src="wechat.val">
                        <input type="file" v-on:change="upload" accept="image/*" v-el:wechat >
                        <div class="progress pub-fade-transition" v-bind:class="wechat.progress.active ? 'active' : ''">
                            <p v-text="wechat.progress.percent"></p>
                        </div>
                        <div class="error pub-fade-transition" v-bind:class="wechat.error ? 'active' : ''">
                            <p v-text="wechat.error"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="wrap">
                <p class="name">微博:</p>
                <div class="input">
                    <input class="weibo-left" type="text" value="http://weibo.com/" disabled>
                    <input class="weibo-right" type="text" v-model="weibo.val">
                    <div class="error pub-fade-transition" v-bind:class="weibo.error ? 'active' : ''">
                        <p v-text="weibo.error"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="wrap">
                <p class="name">E-mail:</p>
                <div class="input">
                    <input type="email" v-model="email.val">
                </div>
            </div>
        </div>
        <div class="save">
            <div class="double-btn">
                <a class="save-btn black pub-background-transition" v-on:click="submit" v-bind:class="save"><em></em><span>保存</span></a>
                <a class="save-btn empty pub-background-transition" v-on:click="clear"><em></em><span>清空</span></a>
            </div>
        </div>
    </div>
</div>
<!--主页内容end-->
@stop
@section('script')@parent<script>
    (function () {
        this.form = JSON.parse('{!! $input !!}');
    }).call(define('data'));
</script>
<script src="{{ $_ENV['platform']['cdn'].elixir("js/user.social.js")}}"></script>
@stop