@extends('layout.site')
@section('style')@parent  <link href="/css/site.special.css?" rel="stylesheet">
@stop
@section('body')
    @parent
        <!--专题内容start-->
    <div id="background" class="background" style=" background: url('{{$info['bg_image']}}') no-repeat center; background-size: cover">
        <div class="filter"></div>
    </div>
    <div id="site-content" class="site-content">
@if(!empty($list))
        <div class="all">
            <a href="#" class="btn">
                <p>全部专题</p>
                <em></em>
            </a>
            <div class="list-container">
@foreach ($list as $special)
                <a href="/special/{{$special->id}}" target="_blank" class="list">
                    <h5>{{$special->title}}</h5>
                    <p>{{$special->time}}</p>
                </a>
@endforeach
            </div>
        </div>
@endif
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
@foreach ($article_list as $article)
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