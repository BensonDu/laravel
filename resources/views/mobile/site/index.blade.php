@extends('mobile.site.layout')
@section('style')<link href="{{ $_ENV['platform']['cdn'].elixir("mobile/css/site.index.css") }}" rel="stylesheet">
@stop
@section('body')
@if(!empty($stars))
    <div id="star" class="star">
        <div class="container star-wrap">
@foreach ($stars as $star)
            <a class="item" href="{{$star['jump']}}">
                <div class="img" style="background-image: url({{$star['image']}})"></div>
                <div class="summary">
                    <p><span>{{$star['category']}}</span> | <span>{{$star['time']}}</span></p>
                    <h3>{{$star['title']}}</h3>
                </div>
            </a>
@endforeach
        </div>
        <div class="position">
            <p><span v-text="current"></span>/<span v-text="total"></span></p>
        </div>
    </div>
@endif

    <div id="list-container" class="list-container">
        <div class="category">
@foreach ($categories as $category)
            <a v-bind:class="category == {{$category['id']}} ? 'active' : ''" data-id="{{$category['id']}}"><span>{{$category['name']}}</span></a>
@endforeach
        </div>

        <div class="list">
            <div class="category-loading" v-bind:class="categoryload ? 'active' : ''">
                <em></em>
            </div>
@foreach ($articles['list'] as $article)
            <a class="item default-list visible" href="/{{$article->article_id}}">
                <div class="title">
                    <h3>{{$article->title}}</h3>
                </div>
                <div class="image">
                    <img data-lazy-src="{{$article->image}}" src="http://dn-noman.qbox.me/Occupy.png">
                    <div class="info">
                        <div class="time">
                            <span>{{$article->category_name}}</span><span> | </span><span>{{$article->time}}</span>
                        </div>
                    </div>
                </div>
                <div class="summary">
                    <p>{{$article->summary}}</p>
                    <span>查看全文>></span>
                </div>
            </a>
@endforeach
            <a class="item" v-bind:class="visible" v-for="article in list" v-bind:href="'/'+article.article_id">
                <div class="title">
                    <h3 v-text="article.title"></h3>
                </div>
                <div class="image">
                    <img v-bind:data-lazy-src="article.image" src="http://dn-noman.qbox.me/Occupy.png">
                    <div class="info">
                        <div class="time">
                            <span v-text="article.category_name"></span><span> | </span><span v-text="'发布于 '+article.time"></span>
                        </div>
                    </div>
                </div>
                <div class="summary">
                    <p v-text="article.summary"></p>
                    <span>查看全文</span>
                </div>
            </a>
        </div>
        <div class="load-more" v-bind:class="load">
            <p id="load-more">
                <span></span><em></em>
            </p>
        </div>
    </div>
@stop
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script>var article = {total : '{{$articles['total']}}'}</script>
<script src="/mobile/js/site.index.js?"></script>
<script src="/lib/imagelazyload/imagelazyload.js"></script>
@stop