@extends('mobile.base')

@section('header')
<!--站点头部start-->
<div class="site-head">
    @if(isset($back['name']))
        <a href="{{$back['link']}}" class="back"><em></em><span>{{$back['name']}}</span></a>
    @endif
    <a href="/" class="logo"><img src="{{$site->mobile_logo}}"></a>
</div>
<!--站点头部end-->
@stop