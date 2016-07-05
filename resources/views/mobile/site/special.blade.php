@extends('mobile.site.layout')
@section('style')<link href="{{$_ENV['platform']['cdn']}}/dist/mobile/css/site.special.css" rel="stylesheet">
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
        <div class="container">
            <div class="image">
                <img src="{{$info['image']}}">
            </div>
            <div class="title">
                <h1>{{$info['title']}}</h1>
            </div>
            <div class="summary">
                <p>{{$info['summary']}}</p>
            </div>
            <div class="list">
                <div class="list-container">
                    @foreach ($list as $article)
                        <a href="/{{$article->id}}">
                            <div class="text">
                                <h5>{{$article->title}}</h5>
                                <p>{{$article->summary}}</p>
                            </div>
                            <em></em>
                        </a>
                    @endforeach
                </div>
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

        this.background = '{{$info['bg_image']}}';

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