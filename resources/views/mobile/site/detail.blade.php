@extends('mobile.base')
@section('style')@parent  <link href="/mobile/css/public.detail.css?v" rel="stylesheet">
@stop
@section('body')
<div class="article-image">
@if(!empty($article['image']))
    <div class="img" style="background-image: url({{$article['image']}})"></div>
@endif
    <div class="summary {{!empty($article['image']) ? 'image' : ''}}">
        <p class="info"><span>{{$article['category']}}</span><span> | </span><span>{{$article['time']}}</span></p>
    </div>
</div>
<div class="author">
    <div class="avatar"><img src="{{$user['avatar']}}"></div>
    <div class="name"><p>{{$user['name']}}</p></div>
</div>
<div class="article">
    <h3>{{$article['title']}}</h3>
    <h5>{{$article['summary']}}</h5>
    <div class="content medium">
        {!! $article['content'] !!}
    </div>
    <p class="end">-- End --</p>
</div>
@stop
@section('script')@parent
@stop