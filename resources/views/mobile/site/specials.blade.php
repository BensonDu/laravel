@extends('mobile.site.layout')
@section('style')<link href="{{ $_ENV['platform']['cdn'].elixir("mobile/css/site.special.css") }}" rel="stylesheet">
@stop
@section('body')
    <!--专题列表start-->
    <div id="background" class="background">
        <div class="background-wrap">
            <div class="special-background"></div>
            <div class="loading-cover">
                <div class="loading-wrap">
                    <div class="loading-circle"></div>
                    <div class="loading-text">loading</div>
                </div>
            </div>
            <div class="filter"></div>
        </div>
    </div>
    <div id="site-content" class="site-content">
        <div class="title">
            <em></em>
            <h1>{{$special}}</h1>
        </div>
        <div class="home">
            <div class="special-list">
@foreach($list as $v)
                <a class="item" href="/special/{{$v->id}}">
                    <div class="image">
                        <div class="image-wrap">
                            <img src="{{$v->image}}">
                        </div>
                    </div>
                    <div class="info">
                        <div class="info-wrap">
                            <h1>{{$v->title}}</h1>
                            <p>{{$v->summary}}</p>
                        </div>
                        <div class="entry">
                            <p><span>查看详情</span><em></em></p>
                        </div>
                    </div>
                </a>
                <div class="line">
                    <div>
                        <em class="top"></em>
                        <em class="mid"></em>
                        <em class="bottom"></em>
                    </div>
                </div>
@endforeach
            </div>
        </div>
        <div class="end">
            <p>End</p>
        </div>
    </div>
    <!--专题列表end-->
@stop

@section('script')@parent
<script>
    (function () {
        var self = this,
                content = jQuery('#site-content'),
                loading = jQuery('.loading-cover'),
                background = jQuery('.special-background');

        //背景图加载
        this.imgLoader = function (url,call) {
            var img = new Image;
            img.onload = call;
            img.onerror = call;
            img.src = url;
        };

        this.background = 'http://qiniu.cdn-chuang.com//FuycdN305ZoUtO7lX9IiAjfBT2Y_?imageMogr2/';

        this.timer = setTimeout(function () {
            self.imgLoader(self.background,function () {
                background.css('background-image','url('+self.background+')');
                setTimeout(function () {
                    content.addClass('active');
                    loading.addClass('disable');
                },800);
            })
        },0);

    }).call(define('data'));
</script>
@stop