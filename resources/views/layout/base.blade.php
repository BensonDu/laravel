<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{isset($base['title']) ? $base['title'] : '创之群媒体平台'}}</title>
    <meta name="keywords" content="{{isset($base['keywords']) ? $base['keywords'] : '创之群媒体平台'}}"/>
    <meta name="description" content="{{isset($base['description']) ? $base['description'] : '创之 发现垂直媒体的价值与影响力'}}"/>
    <meta name="robots" content="all"/>
    <meta name="copyright" content="创之"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="author" content="http://chuang.pro" />
    <meta name="format-detection" content="email=no" />
    <meta name="format-detection" content="telephone=yes" />
    <link rel="shortcut icon" href="{{isset($base['favicon']) ? $base['favicon'] : 'http://dn-noman.qbox.me/chuang.png'}}" type="image/png">
    @section('style')<link href="/css/public.base.css?v1" rel="stylesheet">
    <link href="/css/public.nav.left.css?v1" rel="stylesheet">
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
        <a href="/" title="首页">
            <i class="home"></i><span>站点首页</span>
        </a>
        <div class="item-top"></div>
        <a href="{{$nav['edit']}}" title="文章管理">
            <i class="article"></i><span>文章管理</span>
        </a>
        <div class="item-top"></div>
        <a href="{{$nav['favorite']}}" title="我的收藏">
            <i class="folder"></i><span>我的收藏</span>
        </a>
        <div class="item-top"></div>
        <a href="{{$nav['profile']}}" title="个人设置">
            <i class="setting"></i><span>个人设置</span>
        </a>
        <div class="item-top"></div>
        <a href="{{$nav['user']}}"  title="个人主页">
            <i class="people"></i><span>个人主页</span>
        </a>
    </div>
    <div class="bottom ">
        <div class="entry">
@if(isset($_ENV['admin']['role']) && $_ENV['admin']['role'] > 0)
            <a class="admin" href="/admin" title="站点管理">
                <i class="web"></i><span>站点管理</span>
            </a>
@endif
@if(!empty($_ENV['uid']))
            <a class="logout" href="/account/logout" title="退出登录">
                <i class="exit"></i><span>退出登录</span>
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
        };
        this.user = {
            id : '{{$_ENV['uid']}}',
            name : '{{$nickname}}',
            avatar : '{{$avatar}}',
            role : '{{isset($_ENV['admin']['role']) ? $_ENV['admin']['role'] : 0}}'
        };
    }).call(define('global'));
    /*GrowingIO*/
    var _vds = _vds || [];
    window._vds = _vds;
    (function(){
        _vds.push(['setAccountId', 'a0c6db62ca24ce9c']);
        (function() {
            var vds = document.createElement('script');
            vds.type='text/javascript';
            vds.async = true;
            vds.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'dn-growing.qbox.me/vds.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(vds, s);
        })();
    })();
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