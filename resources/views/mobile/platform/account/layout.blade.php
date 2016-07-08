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

@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script src="http://static.geetest.com/static/tools/gt.js"></script>
<script src="/lib/geetest/geetest.js"></script>
@stop