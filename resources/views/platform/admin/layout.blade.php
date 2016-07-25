@extends('layout.base')

@section('nav')
    @parent
@stop

@section('body')
<!--管理中栏start-->
<div id="mid" class="admin-mid">
    <div class="top">
        <a class="logo" href="/"><img src="http://qiniu.cdn-chuang.com/chuang-logo-circle.png"></a>
        <h2>创之群媒体平台</h2>
        <h3>[ 用心创作快乐 ]</h3>
    </div>
    <div class="mid">
        <div class="container">
            <div class="margin-top"></div>
            <a href="/admin/site" ><span>站点管理</span></a>
            <div class="margin-top"></div>
            <a href="/admin/user" ><span>权限管理</span></a>
            <div class="margin-top"></div>
            <a href="/admin/option" ><span>平台设置</span></a>
        </div>
    </div>
    <div class="bottom">
        <a href="http://www.miitbeian.gov.cn/" target="_blank">津ICP备14006995号-4</a>
    </div>
</div>
<!--管理中栏end-->

<!--管理内容start-->
<div id="admin-content" class="admin-content">
    <div class="top">
        <div class="name {{$admin_nav_top['class']}}">
            <em></em>
            <h3>{{$admin_nav_top['name']}}</h3>
        </div>
    </div>
    <div class="admin-area">
        <div class="background" v-bind:class="background ? 'show' :''"></div>
@section('area')
@show
    </div>
</div>
<!--管理内容end-->

<!--弹出层start-->
@section('pop')
@show
<!--弹出层end-->

@stop