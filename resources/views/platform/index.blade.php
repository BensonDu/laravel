@extends('layout.base')

@section('nav')
    @parent
@stop

@section('style')<link href="{{ $_ENV['platform']['cdn'].elixir("css/platform.index.css") }}" rel="stylesheet">
@stop

@section('body')
<!--顶栏start-->
<div id="nav-container" class="index-header">
    <div class="wrap">
        <div class="logo">
            <a href="/"><img src="http://qiniu.cdn-chuang.com/platform-logo.png?"></a>
            <p>[ 用心创作快乐 ]</p>
        </div>
        <div class="search">
            <div><i></i><input v-model="keyword" type="text" v-on:keyup.enter="_search" placeholder="搜索关键词"><em></em></div>
        </div>
        <a class="about" href="http://help.chuang.pro" target="_blank">
            <i></i>
            <p>点这里,了解创之</p>
        </a>
    </div>
</div>
<!--顶栏end-->
<!--主体start-->
<div id="content-container" class="content-container">
    <div class="wrap">

        <div class="slide">
            <div class="filter">
                <div class="item-container" v-bind:class="orderby">
                    <a class="item hot">
                        <div class="btn" v-on:click="_orderby('hot')">
                            <i></i><p>热门</p>
                        </div>
                    </a>
                    <a class="item new">
                        <div class="btn" v-on:click="_orderby('new')">
                            <i></i><p>最新</p>
                        </div>
                    </a>
                    <a class="selector">
                        <em></em>
                    </a>
                </div>
            </div>
            <div class="site-nav">
                <div class="head"><p>导航</p></div>
                <div class="site-list">
@foreach($sites as $v)
                    <a href="{{$v['link']}}" class="site">
                        <div class="logo"><img src="{{$v['logo']}}"></div>
                        <div class="name"><p>{{$v['name']}}</p></div>
                    </a>
@endforeach
                </div>
            </div>
        </div>

        <div class="site-body">
            <div class="site-wrap">
                <div class="list-wrap">
                    <div class="list-container">
                        <div class="syn-wrap" v-bind:class="!syn && 'hide'">
@foreach($articles as $v)
                                <div class="article">
                                    <div class="image">
                                        <img data-lazy-src="{{$v['image']}}" src="http://qiniu.cdn-chuang.com/Occupy.png">
                                    </div>
                                    <div class="info">
                                        <div class="top">
                                            <a href="{{$v['site']['link'].$v['id']}}" target="_blank">{{$v['title']}}</a>
                                            <p>{{$v['summary']}}</p>
                                        </div>
                                        <div class="bottom">
                                            <a href="{{$v['user']['link']}}" target="_blank" class="author"><img src="{{$v['user']['avatar']}}"><span>{{$v['user']['name']}}</span></a>
                                            <p class="time">{{$v['time']}}</p>
                                            <p class="site"><span>首发于站点:</span><a href="{{$v['site']['link']}}" target="_blank">{{$v['site']['name']}}</a></p>
                                            <div class="hot">
                                                <span>热度:</span>
                                                <em class="heat-container">
                                                    <ul class="heat {{$v['rank']}}"><li></li><li></li><li></li><li></li></ul>
                                                </em>
                                            </div>
                                        </div>
                                    </div>
                                </div>
@endforeach
                        </div>
                        <div class="syn-wrap" v-bind:class="!rsyn && 'hide'">
                           <div class="article" v-for="v in list">
                                <div class="image">
                                    <img v-bind:data-lazy-src="v.image" src="http://qiniu.cdn-chuang.com/Occupy.png">
                                </div>
                                <div class="info">
                                    <div class="top">
                                        <a v-bind:href="v.site.link+v.id" v-text="v.title" target="_blank"></a>
                                        <p v-text="v.summary"></p>
                                    </div>
                                    <div class="bottom">
                                        <a v-bind:href="v.user.link" target="_blank" class="author"><img v-bind:src="v.user.avatar"><span v-text="v.user.name"></span></a>
                                        <p class="time" v-text="v.time"></p>
                                        <p class="site"><span>首发于站点:</span><a v-bind:href="v.site.link" v-text="v.site.name" target="_blank"></a></p>
                                        <div class="hot">
                                            <span>热度:</span>
                                            <em class="heat-container">
                                                <ul class="heat" v-bind:class="v.rank"><li></li><li></li><li></li><li></li></ul>
                                            </em>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="load-more" v-bind:class="load">
                            <p v-on:click="_more">
                                <span v-text="load"></span><em></em>
                            </p>
                        </a>
                    </div>
                </div>
                <div class="right-slide">
                    <div class="ad">
                        <a href="http://energy.chuang.pro" target="_blank">
                            <img src="http://dn-noman.qbox.me/FvHpLxbj5kb-n9fGwwk5CtDEAifT">
                        </a>
                    </div>
                    <div class="partner py">
                        <div class="title">
                            <h3>合作伙伴</h3>
                        </div>
                        <div class="partner-list">
                            <a href="http://angelcrunch.com" target="_blank">
                                <div class="logo">
                                    <img src="http://qiniu.cdn-chuang.com/partner_ac.png">
                                </div>
                                <div class="info">
                                    <h3>天使汇</h3>
                                    <p>让靠谱的项目找到靠谱的钱</p>
                                </div>
                            </a>
                            <a href="http://www.1wangtong.com/" target="_blank">
                                <div class="logo">
                                    <img src="http://qiniu.cdn-chuang.com/partner_1wt.png">
                                </div>
                                <div class="info">
                                    <h3>壹网通</h3>
                                    <p>足不出户,注册公司</p>
                                </div>
                            </a>
                            <a href="http://laravelacademy.org/" target="_blank">
                                <div class="logo">
                                    <img src="http://qiniu.cdn-chuang.com/partner_laravel.png">
                                </div>
                                <div class="info">
                                    <h3>Laravel</h3>
                                    <p>本站点由 Laravel 驱动</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="aboutus">
                        <div class="title">
                            <h3>关于我们</h3>
                        </div>
                        <div class="item-list">
                            <div class="item">
                                <h3><span>市场合作:</span></h3>
                                <a href="mailto:marketing@tech2ipo.com">marketing@tech2ipo.com</a>
                            </div>
                            <div class="item">
                                <h3><span>内容合作:</span></h3>
                                <a href="mailto:hezuo@tech2ipo.com">hezuo@tech2ipo.com</a>
                            </div>
                            <div class="item">
                                <h3><span>联系电话:</span></h3>
                                <a href="tel:010-50980654">010-50980654</a>
                            </div>
                            <div class="item">
                                <h3><span>域名备案:</span></h3>
                                <a href="http://www.miitbeian.gov.cn/" target="_blank">津ICP备14006995号-4</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!--主体end-->
@stop

@section('script')@parent<script src="{{ $_ENV['platform']['cdn'].elixir("js/platform.index.js")}}"></script>
@stop