@extends('layout.base')
@section('style')
@parent<link href="/css/admin.base.css" rel="stylesheet">
@stop
@section('nav')
    @parent
@stop
@section('body')
<!--管理中栏start-->
<div id="mid" class="admin-mid">
    <div class="top">
        <div class="logo"><img src="{{$site['logo']}}"></div>
        <h2>{{$site['name']}}</h2>
        <h3>{{$site['slogan']}}</h3>
    </div>
    <div class="mid">
        <div class="container">
@if(isset($_ENV['admin']['role']))
            <a href="/admin/" ><span>文章管理</span><em>{{$uncontribute_article_num}}</em></a>
@endif
@if(isset($_ENV['admin']['role']) && ($_ENV['admin']['role'] > 1))
            <a href="/admin/special"><span>专题管理</span></a>
            <a href="/admin/star" ><span>精选管理</span></a>
            <a href="/admin/category" ><span>分类管理</span></a>
@endif
@if(isset($_ENV['admin']['role']) && ($_ENV['admin']['role'] > 2))
            <a href="/admin/user" ><span>用户管理</span></a>
@endif
        </div>
    </div>
    <div class="bottom">
        <a href="http://www.miitbeian.gov.cn/" target="_blank">{{$site['icp']}}</a>
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
@section('script')@parent<script src="/js/admin/base.js"></script>
@stop