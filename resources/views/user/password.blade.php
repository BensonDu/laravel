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
            <a href="/user/social">社交资料</a>
        </div>
    </div>
</div>
<div id="user-content" class="user-content">
    <div id="password" class="container">
        <div class="item">
            <div class="wrap">
                <p class="name">原密码:</p>
                <div class="input">
                    <input type="password" v-model="password.val">
                    <div class="error" v-bind:class="password.error ? 'active' : ''">
                        <p v-text="password.error"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="wrap">
                <p class="name">新密码:</p>
                <div class="input">
                    <input type="password" v-model="newpassword.val">
                    <div class="error" v-bind:class="newpassword.error ? 'active' : ''">
                        <p v-text="newpassword.error"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="wrap">
                <p class="name">重复新密码:</p>
                <div class="input">
                    <input type="password" v-model="newpassword_re.val">
                    <div class="error" v-bind:class="newpassword_re.error ? 'active' : ''">
                        <p v-text="newpassword_re.error"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="save">
            <div class="btn-wrap">
                <a class="save-btn black pub-background-transition" v-on:click="submit" v-bind:class="save"><em></em><span>保存</span></a>
            </div>
        </div>
    </div>
</div>
<!--主页内容end-->
@stop
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.js"></script>
<script src="/js/user/base.js"></script>
<script src="/js/user/password.js"></script>
@stop