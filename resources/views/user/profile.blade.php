@extends('layout.user')
@section('style')@parent  <link href="/css/user.profile.css" rel="stylesheet">
@stop
@section('body')
@parent
        <!--主页内容start-->
<div id="user-nav" class="user-nav">
    <div class="user-nav-container">
        <div class="left">
            <a class="active" href="/user/profile">个人资料</a>
            <a href="/user/password">修改密码</a>
            <a href="/user/social">社交资料</a>
        </div>
    </div>
</div>
<div id="user-content" class="user-content">
    <div id="profile" class="container">
        <div class="item">
            <div class="wrap one-line">
                <p class="name">用户名:</p><span class="value unchangeable">{{$input['username']}}</span>
            </div>
        </div>
        <div class="item">
            <div class="wrap avatar circle">
                <p class="name">头像:</p>
                <div class="img">
                    <div class="box">
                        <img v-bind:src="avatar.val">
                        <input type="file" v-on:change="upload" accept="image/*" v-el:avatar >
                        <div class="progress pub-fade-transition" v-bind:class="avatar.progress.active ? 'active' : ''">
                            <p v-text="avatar.progress.percent"></p>
                        </div>
                        <div class="error pub-fade-transition" v-bind:class="avatar.error ? 'active' : ''">
                            <p v-text="avatar.error"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="wrap">
                <p class="name">昵称:</p>
                <div class="input">
                    <input type="text" v-model="nickname.val">
                    <div class="error" v-bind:class="nickname.error ? 'active' : ''">
                        <p v-text="nickname.error"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="wrap">
                <p class="name">Slogan:</p>
                <div class="input">
                    <input type="text" v-model="slogan.val">
                </div>
            </div>
        </div>
        <div class="item">
            <div class="wrap">
                <p class="name">简介:</p>
                <textarea maxlength="100" v-model="introduce.val"></textarea>
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
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script src="http://dn-noman.qbox.me/imageuploader.min.js"></script>
<script>var default_data = JSON.parse('{!! json_encode_safe($input) !!}');</script>
<script src="/js/user/base.js"></script>
<script src="/js/user/profile.js"></script>
@stop