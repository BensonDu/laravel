<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <!-- UC提供样式 -->
    <link type="text/css" rel="stylesheet" href="http://zzd.sm.cn/third_party/css/SMDefaultCSS.css"/>
    <!-- 文章标题 -->
    <title>{{$article->title}}</title>
</head>

<body>

<!-- 标题 -->
<header class="article-header">
    <!-- 文章标题 -->
    <h1>{{$article->title}}</h1>
    <div class="subtitle">
        <!-- 文章来源 -->
        <a id="source">{{$site->name}}</a>
        <time>{{substr($article->post_time,5,11)}}</time>
    </div>
</header>

<!-- 合作方正文 -->
<article class="article-content">
    @if(!empty($article->image))
        <figure class="image">
            <img src='{{$article->image}}'>
        </figure>
    @endif
    {!! $article->content !!}
</article>

<!-- UC提供专用脚本 -->
<script type="text/javascript" src="http://zzd.sm.cn/third_party/js/jquery.min.js"></script>
<script type="text/javascript" src="http://zzd.sm.cn/third_party/js/SMDefaultJS.js"></script>
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