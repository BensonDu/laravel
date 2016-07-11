@extends('layout.admin')
@section('style')<link href="{{ $_ENV['platform']['cdn'].elixir("css/admin.site.css") }}" rel="stylesheet">
@stop
@section('area')
    <div class="list-header">
        <div class="nav">
            <a class="{{$sub_act =='base' ? 'active' : ''}}" href="/admin/site/index">基本资料</a>
            <a class="{{$sub_act =='logo' ? 'active' : ''}}" href="/admin/site/logo">Logo</a>
            <a class="{{$sub_act =='social' ? 'active' : ''}}" href="/admin/site/social">社交资料</a>
            <a class="{{$sub_act =='nav' ? 'active' : ''}}" href="/admin/site/nav">站点导航</a>
            <a class="{{$sub_act =='others' ? 'active' : ''}}" href="/admin/site/others">其它</a>
        </div>
    </div>
@section('container')
@show
@stop
@section('script')@parent
@section('script-site')
@show
<script src="{{ $_ENV['platform']['cdn'].elixir("js/admin.site.js")}}"></script>
@stop