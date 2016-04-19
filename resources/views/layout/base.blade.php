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
    <meta name="format-detection" content="email=no" />
    <meta name="format-detection" content="telephone=yes" />
    @section('style')<link rel="shortcut icon" href="http://dn-css7.qbox.me/tc.ico" type="image/ico" />
    <link href="/css/public.base.css?" rel="stylesheet">
    <link href="/css/public.nav.left.css?v" rel="stylesheet">
    @show
</head>
<body>
@section('nav')
<!--左栏全局导航start-->
<div id="nav-left" class="nav-left">
<div class="login-sta{{!empty($uid)?' login':''}}">
    <a href="{{$url}}">
            <i></i>
            <img src="{{$avatar}}">
            <em>
                <span class="login-regist">登录/注册</span>
                <span class="name">{{$nickname}}</span>
            </em>
        </a>
</div>
    <div class="menu-top"></div>
    <div class="menu entry top-entry">
        <div class="item-top"></div>
        <a href="/">
            <i class="home"></i><span>站点首页</span>
        </a>
        <div class="item-top"></div>
        <a href="{{$nav['edit']}}">
            <i class="article"></i><span>文章管理</span>
        </a>
        <div class="item-top"></div>
        <a href="{{$nav['favorite']}}">
            <i class="folder"></i><span>我的收藏</span>
        </a>
        <div class="item-top"></div>
        <a href="{{$nav['profile']}}">
            <i class="setting"></i><span>个人设置</span>
        </a>
        <div class="item-top"></div>
        <a href="{{$nav['user']}}">
            <i class="people"></i><span>个人主页</span>
        </a>
    </div>
    <div class="bottom ">
        <div class="entry">
@if(isset($_ENV['admin']['role']) && $_ENV['admin']['role'] > 0)
            <a class="admin" href="/admin">
                <i class="web"></i><span>站点管理</span>
            </a>
@endif
@if(!empty($_ENV['uid']))
            <a class="logout" href="/account/logout">
                <i class="exit"></i><span>退出</span>
            </a>
@endif
        </div>
        <div id="nav-left-switch" class="switch">
            <i class="arrow"></i>
        </div>
    </div>
</div>
<!--左栏全局导航end-->
@show
@section('body')
@show
<div id="global-pop" class="global-pop">
    <div class="box">
        <div class="img">
            <em></em>
        </div>
        <div class="title">
            <h5></h5>
        </div>
        <div class="btn-group">
            <a class="vice">返回首页</a>
            <a class="main"></a>
        </div>
    </div>
</div>
</body>
@section('script')
<script src="http://dn-acac.qbox.me/jquery-2.1.4.min.js"></script>
<script src="/js/public.base.js"></script>
<script src="/js/public.nav.left.js"></script>
<script>
    /*全局变量*/
    (function(){
        this.uid = '{{$_ENV['uid']}}';
        this.platform = {
            home : '{{$_ENV['platform']['home']}}'
        }
    }).call(define('global'));

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