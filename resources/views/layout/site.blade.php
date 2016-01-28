@extends('layout.base')
@section('style')
@parent<link href="/css/site.mid.css" rel="stylesheet">
@stop
@section('nav')
    @parent
@stop
@section('body')
<!--站点中栏start-->
<div id="mid" class="site-mid">
    <div class="top">
        <div class="logo"><img src="http://dn-t2ipo.qbox.me/v3/public/Tech2ipo-logo.png"></div>
        <h2>TECH2IPO</h2>
        <h3>「 等待新的科技故事 」</h3>
        <div class="social">
            <a class="weibo" href="#" title="微博"></a>
            <a class="email" href="#" title="E-mail"></a>
            <a class="twitter" href="#" title="twitter"></a>
            <a class="weixin" href="#" title="微信">
                <img src="http://dn-acac.qbox.me/tech2ipoqrcode.jpg">
            </a>
        </div>
        <div class="post">
            <a href="/user/edit">投稿</a>
        </div>
    </div>
    <div class="mid">
        <div class="container">
            <a href="/"><span>最近更新</span><em>15</em></a>
            <a href="/special"><span>专题聚光</span></a>
            <a href="#"><span>精选热点</span></a>
        </div>
    </div>
    <div class="bottom">
        <a href="http://www.miitbeian.gov.cn/" target="_blank">京ICP备1404667</a>
    </div>
</div>
<!--站点中栏end-->
@stop