<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{isset($base['title']) ? $base['title'] : '创之群媒体平台'}}</title>
    <meta name="keywords" content="{{isset($base['keywords']) ? $base['keywords'] : '创之群媒体平台'}}"/>
    <meta name="description" content="{{isset($base['description']) ? $base['description'] : '创之 发现垂直媒体的价值与影响力'}}"/>
    <meta name="robots" content="all"/>
    <meta name="copyright" content="创之"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="author" content="http://chuang.pro" />
    <meta name="format-detection" content="email=no" />
    <meta name="format-detection" content="telephone=yes" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
    <link rel="shortcut icon" href="{{isset($base['favicon']) ? $base['favicon'] : 'http://dn-noman.qbox.me/chuang.png'}}" type="image/png">
    @section('style')<link href="/mobile/css/public.base.css?v1" rel="stylesheet">
    @show
</head>
<body>
@section('header')
<!--全局头部start-->
<div class="head">
@if(isset($back['name']))
    <a href="{{$back['link']}}" class="back"><em></em><span>{{$back['name']}}</span></a>
@endif
    <a href="/" class="logo"><img src="{{$site->mobile_logo}}"></a>
</div>
<!--全局头部end-->
@show
@section('body')
@show
</body>
@section('script')
<script src="http://dn-acac.qbox.me/jquery-2.1.4.min.js"></script>
<script src="/mobile/js/public.base.js"></script>
<script>
    /*百度统计*/
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "//hm.baidu.com/hm.js?047eac725727fc206cb8019dc0fb9dc9";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
@show
</html>