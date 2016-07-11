@extends('mobile.platform.layout')

@section('style')<link href="{{ $_ENV['platform']['cdn'].elixir("mobile/css/platform.account.css") }}" rel="stylesheet">
@stop

@section('body')
<!--背景start-->
<div id="background" class="background">
    <div class="filter"></div>
</div>
<!--背景end-->
@stop