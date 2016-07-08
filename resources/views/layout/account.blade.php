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
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script src="/js/account/base.js"></script>
@stop