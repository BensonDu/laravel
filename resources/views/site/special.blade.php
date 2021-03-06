@extends('site.layout')
@section('style')<link href="{{ $_ENV['platform']['cdn'].elixir("css/site.special.css") }}" rel="stylesheet">
@stop
@section('body')
    @parent
        <!--专题内容start-->
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
                    <a href="/{{$article->id}}" target="_blank">
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
    </div>
    <!--专题内容end-->
@stop

@section('script')@parent<script>
    (function () {
        this.image = '{{$info['bg_image']}}';
    }).call(define('data'));
</script>
<script src="{{ $_ENV['platform']['cdn'].elixir("js/site.special.detail.js")}}"></script>
@stop