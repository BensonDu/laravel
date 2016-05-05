@extends('layout.base')
@section('style')
@parent<link href="/css/site.mid.css?" rel="stylesheet">
@stop
@section('nav')
    @parent
@stop
@section('body')
<!--站点中栏start-->
<div id="mid" class="site-mid">
    <div class="top">
        <div class="logo"><a href="/"><img src="{{$site['logo']}}"></a></div>
        <h2>{{$site['name']}}</h2>
        <h3>{{$site['slogan']}}</h3>
        <div class="social">
@if(!empty($site['weibo']))
            <a class="weibo" href="{{$site['weibo']}}" target="_blank" title="微博"></a>
@endif
@if(!empty($site['email']))
            <a class="email" href="mailto:{{$site['email']}}" title="E-mail"></a>
@endif
@if(!empty($site['twitter']))
            <a class="twitter" href="{{$site['twitter']}}"  target="_blank" title="twitter"></a>
@endif
@if(!empty($site['weixin']))
            <a class="weixin">
                <img src="{{$site['weixin']}}">
            </a>
@endif
        </div>
        <div class="post">
            <a href="{{$_ENV['platform']['home']}}/user/edit">投稿</a>
        </div>
    </div>
    <div class="mid">
        <div class="container">
            <a href="/" class="{{isset($active) && $active =='home' ? 'active' : ''}}"><span>最近更新</span><em>19</em></a>
@if($site['special'] > 0)
            <a href="/special" class="{{isset($active) && $active =='special' ? 'active' : ''}}"><span>专题聚光</span></a>
@endif
@if($site['site_id'] === '1')
            <a href="http://tech2ipo.com/10028495" class="{{isset($active) && $active =='joinus' ? 'active' : ''}}"><span>关于我们</span></a>
@endif
        </div>
    </div>
    <div class="bottom">
        <a href="http://www.miitbeian.gov.cn/" target="_blank">{{$site['icp']}}</a>
    </div>
</div>
<!--站点中栏end-->
@stop

@section('script')@parent<script src="/js/site.base.js?"></script>
@stop