<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta id="viewport" name="viewport" content="initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,minimal-ui">
    <meta name="format-detection" content="telephone=no">
    <title>{{$article->title}}</title>
    <link href="/css/feed.xiaozhi.css" rel="stylesheet" type="text/css">
</head>

<body>
<!--背景色,背景图片,logo图片-->
<div class="headbar" style="background:#eee;" >
    <a href="#" class="logo">
        <img src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2FLogo-mobile-tech2ipo.png?v">
    </a>
</div>

<!--header-->
<header>
    <h1>{{$article->title}}</h1>
    <div class="subtitle">
        <a id="source">{{$article->nickname}}</a>
        <time>{{substr($article->create_time,5,11)}}</time>
    </div>
</header>

<!--正文内容-->
<article>
    {!! $article->content !!}
</article>

</body>
</html>
