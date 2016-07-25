@extends('platform.admin.layout')
@section('style')<link href="{{ $_ENV['platform']['cdn'].elixir("css/platform.admin.option.css") }}" rel="stylesheet">
@stop
@section('area')
    <div class="list-header">
        <div class="nav">
            <a class="{{$sub_act == 'nav' ? 'active' : ''}}" href="/admin/site/nav">站点导航</a>
        </div>
    </div>
@section('container')
@show
@stop