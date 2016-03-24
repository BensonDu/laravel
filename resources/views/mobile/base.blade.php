<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{isset($base['title']) ? $base['title'] : 'TECH2IPO/创见'}}</title>
    <meta name="keywords" content="TECH2IPO/创见"/>
    <meta name="description" content="全世界在等待新的科技故事"/>
    <meta name="robots" content="all"/>
    <meta name="copyright" content="Tech2ipo"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="author" content="http://m.angelcrunch.com" />
    <meta name="revisit-after"  content="1 days" />
    <meta name="format-detection" content="email=no" />
    <meta name="format-detection" content="telephone=yes" />
    @section('style')<link rel="shortcut icon" href="http://dn-css7.qbox.me/tc.ico" type="image/ico" />
    <link href="/mobile/css/public.base.css" rel="stylesheet">
    @show
</head>
<body>
@section('header')
<!--全局头部start-->
<div class="head">
    <a href="/" class="logo"><img src="http://dn-t2ipo.qbox.me/Tech2ipo-logo-m-white.png"></a>
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