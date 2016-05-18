@extends('layout.admin')
@section('style')@parent <link href="/css/admin.site.css" rel="stylesheet">
<link href="/lib/cropper/cropper.min.css" rel="stylesheet">
@stop
@section('area')
    <div class="list-header">
        <div class="nav">
            <a class="{{$sub_act =='base' ? 'active' : ''}}" href="/admin/site/index">基本资料</a>
            <a class="{{$sub_act =='logo' ? 'active' : ''}}" href="/admin/site/logo">Logo</a>
            <a class="{{$sub_act =='social' ? 'active' : ''}}" href="/admin/site/social">社交资料</a>
            <a class="{{$sub_act =='nav' ? 'active' : ''}}" href="/admin/site/nav">站点导航</a>
            <a class="{{$sub_act =='contribution' ? 'active' : ''}}" href="/admin/site/contribution">稿件相关</a>
        </div>
    </div>
@section('container')
@show
@stop
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
@section('script-site')
@show
<script src="/js/admin/site.js"></script>
@stop