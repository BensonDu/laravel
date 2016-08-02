@extends('mobile.base')

@section('style')@parent<link href="{{ $_ENV['platform']['cdn'].elixir("mobile/css/platform.index.css") }}" rel="stylesheet">
@stop

@section('body')
    <div class="platform-header">
        <div class="top">
            <a class="logo" href="/"><img src="http://qiniu.cdn-chuang.com/platform-logo.png?"></a>
            <div class="login">
@if(!empty($_ENV['uid']))
                    <div>
                        <a>{{$nickname}}</a>
                        <span> | </span>
                        <a href="{{$_ENV['platform']['home'].'/account/logout'}}">退出登录</a>
                    </div>
@else
                    <div>
                        <a href="{{$_ENV['platform']['home'].'/account/login'.$nav['callback']}}">登录</a>
                        <span> | </span>
                        <a href="{{$_ENV['platform']['home'].'/account/regist'.$nav['callback']}}">注册</a>
                    </div>
@endif
            </div>
        </div>
        <div class="nav">
@foreach($sites as $v)
            <a href="{{$v['link']}}">
                <div class="image">
                    <img src="{{$v['logo']}}">
                </div>

                <h3>{{$v['name']}}</h3>
            </a>
@endforeach
            <a href="http://help.chuang.pro/10031298">
                <div class="image">
                    <img src="http://qiniu.cdn-chuang.com/chuang-about.png">
                </div>
                <h3>关于创之</h3>
            </a>
        </div>
    </div>
    <div id="list-container" class="list-container">
        <div class="category" v-bind:class="orderby">
            <a class="hot" v-on:click="_orderby('hot')"><span>热门</span></a>
            <a class="new" v-on:click="_orderby('new')"><span>最新</span></a>
        </div>

        <div class="list" v-bind:class="!syn && 'hide-syn'">
@foreach($articles as $v)
            <a class="item syn" href="{{$v['site']['link'].$v['id']}}">
                <div class="title">
                    <h3>{{$v['title']}}</h3>
                </div>
                <div class="image">
                    <img data-lazy-src="{{$v['image']}}" src="http://qiniu.cdn-chuang.com/Occupy.png">
                    <div class="info">
                        <div class="time">
                            <span>{{$v['time']}}</span>
                            <span> | </span>
                            <span>热度:</span>
                            <span class="heat-container">
                                <ul class="heat {{$v['rank']}}"><li></li><li></li><li></li><li></li></ul>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="summary">
                    <p>{{$v['summary']}}</p>
                    <span>查看全文&gt;&gt;</span>
                </div>
            </a>
@endforeach
            <a v-for="a in list" class="item" v-bind:href="a.site.link+a.id">
                <div class="title">
                    <h3 v-text="a.title"></h3>
                </div>
                <div class="image">
                    <img v-bind:data-lazy-src="a.image" src="http://qiniu.cdn-chuang.com/Occupy.png">
                    <div class="info">
                        <div class="time">
                            <span v-text="a.time"></span>
                            <span> | </span>
                            <span>热度:</span>
                            <span class="heat-container">
                                <ul class="heat" v-bind:class="a.rank"><li></li><li></li><li></li><li></li></ul>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="summary">
                    <p v-text="a.summary"></p>
                    <span>查看全文&gt;&gt;</span>
                </div>
            </a>
        </div>
        <div class="load-more" v-bind:class="load">
            <p id="load-more" v-on:click="_more">
                <span v-text="load"></span><em></em>
            </p>
        </div>
    </div>
@stop

@section('script')@parent<script src="{{ $_ENV['platform']['cdn'].elixir("mobile/js/platform.index.js")}}"></script>
@stop