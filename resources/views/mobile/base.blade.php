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
    @section('style')
    @show
</head>
<body>

@section('header')
@show

@section('body')
@show

</body>
@section('script')
<script src="{{ $_ENV['platform']['cdn'].elixir("mobile/js/base.js")}}"></script>
<script>
    /*全局变量*/
    (function () {
        this.uid = '{{$_ENV['uid']}}';
        this.platform = {
            home : '{{$_ENV['platform']['home']}}'
        };
        this.user = {
            id : '{{$_ENV['uid']}}',
            name : '{{$nickname}}',
            avatar : '{{$avatar}}',
            role : '{{isset($_ENV['admin']['role']) ? $_ENV['admin']['role'] : 0}}'
        };
    }).call(define('global'));
</script>
@show
</html>