<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tech2ipo</title>
    <meta name="keywords" content="创见"/>
    <meta name="description" content="Tech2ipo"/>
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
    <link href="/css/public.base.css" rel="stylesheet">
    <link href="/css/public.nav.left.css" rel="stylesheet">
    @show
</head>
<body>
@section('nav')<!--左栏全局导航start-->
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
    <div class="menu entry">
        <a href="/">
            <i class="home"></i><span>站点首页</span>
        </a>
        <a href="/user/edit">
            <i class="add"></i><span>新建文章</span>
        </a>
        <a href="/user/edit">
            <i class="article"></i><span>文章管理</span>
        </a>
        <a href="#">
            <i class="folder"></i><span>我的收藏</span>
        </a>
        <a href="/user/profile">
            <i class="setting"></i><span>个人设置</span>
        </a>
        <a href="/user/10001716">
            <i class="people"></i><span>个人主页</span>
        </a>
    </div>
    <div class="bottom ">
        <div class="entry">
            <a href="#">
                <i class="web"></i><span>站点管理</span>
            </a>
            <a href="/account/logout">
                <i class="exit"></i><span>退出</span>
            </a>
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
@show
</html>