@extends('layout.base')
@section('style')<link href="{{ $_ENV['platform']['cdn'].elixir("css/public.account.css") }}" rel="stylesheet">
@stop
@section('body')
@parent
<!--背景start-->
<div id="background" class="background">
    <div class="filter"></div>
</div>
<!--背景end-->
@stop