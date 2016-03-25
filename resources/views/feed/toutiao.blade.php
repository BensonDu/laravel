<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta id="viewport" name="viewport" content="initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,minimal-ui">
    <meta name="format-detection" content="telephone=no">
    <title>{{$article->title}} | TECH2IPO/创见</title>
    <!--头条css-->
    <link href="http://s2.pstatp.com/inapp/TTDefaultCSS.css" rel="stylesheet" type="text/css">
</head>

<body>
<!--背景色,背景图片,logo图片-->
<div id="TouTiaoBar" style="background:#eee;">
    <a href="#" id="logo" class="logo">
        <img src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2FLogo-mobile-tech2ipo.png?v" onerror="TouTiao.hideBar()" alt="">
    </a>
</div>

<!--header-->
<header>
    <h1>{{$article->title}}</h1>
    <div class="subtitle">
        <a id="source">{{$article->nickname}}</a>
        <time>{{substr($article->post_time,5,11)}}</time>
        <a id="toggle_img" onClick="TouTiao.showImage(); return false" href="#">显示图片</a>
    </div>
</header>

<!--正文内容-->
<article>
    {!! $article->content !!}
</article>

<!--头条JS-->
<script src="http://s2.pstatp.com/inapp/TTDefaultJS.js"></script>
<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "//hm.baidu.com/hm.js?047eac725727fc206cb8019dc0fb9dc9";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
</body>
</html>
