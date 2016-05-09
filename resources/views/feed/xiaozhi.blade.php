<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta id="viewport" name="viewport" content="initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,minimal-ui">
    <meta name="format-detection" content="telephone=no">
    <title>{{$article->title}}</title>
    <link href="/css/feed.xiaozhi.css" rel="stylesheet" type="text/css">
    <style>figure{margin: 0;padding: 0}</style>
</head>

<body>
<!--背景色,背景图片,logo图片-->
<div class="headbar" style="background:#eee;" >
    <a href="#" class="logo">
        <img src="{{$site->thirdparty_logo}}">
    </a>
</div>

<!--header-->
<header>
    <h1>{{$article->title}}</h1>
    <div class="subtitle">
        <a id="source">{{$article->nickname}}</a>
        <time>{{substr($article->post_time,5,11)}}</time>
    </div>
</header>

<!--正文内容-->
<article>
@if(!empty($article->image))
    <figure class="image">
        <img src='{{$article->image}}'>
    </figure>
@endif
    {!! $article->content !!}
</article>

</body>
<script src="http://download.inveno.com/SDK/malacca_js/1.1/src/inveno_default.min.1.0.js"></script>
<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "//hm.baidu.com/hm.js?047eac725727fc206cb8019dc0fb9dc9";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
</html>
