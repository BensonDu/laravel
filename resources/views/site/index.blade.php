@extends('layout.site')
@section('style')@parent  <link href="css/site.index.css?v" rel="stylesheet">
@stop
@section('body')
@parent
<!--站点内容start-->
<div id="site-content" class="site-content">
@if(!empty($stars))
    <div class="star">
        <div id="star-album" class="album">
            <div class="nav prev"><em></em></div>
            <div class="item-container">
@foreach ($stars as $star)
                <a href="{{$star['jump']}}">
                    <div class="image">
                        <img src="{{$star['image']}}">
                    </div>
                    <div class="summary">
                        <h5>{{$star['category']}}</h5>
                        <h2>{{$star['title']}}</h2>
                        <h6>{{$star['time']}}</h6>
                    </div>
                </a>
@endforeach
            </div>
            <div class="nav next"><em></em></div>
        </div>
    </div>
@endif
    <div class="filter">
        <div id="filter" class="filter-container">
            <div class="parent">
                <div class="cate">
@foreach ($categories as $category)
                    <a v-bind:class="category.active == {{$category['id']}} ? 'active' : ''" v-on:click="filter({{$category['id']}})"><span>{{$category['name']}}</span></a>
@endforeach
                </div>
                <div class="search">
                    <div><i></i><input v-model="keyword" type="text" v-on:keyup.enter="search" placeholder="搜索关键词"><em></em></div>
                </div>
            </div>
        </div>
    </div>
    <!--新闻列表start-->
    <div class="news">
        <div id="list-container" class="list-container">
@foreach ($articles['list'] as $article)
            <div class="list default-list visible">
                <div class="wrap">
                    <div class="info">
                        <div class="tags">
@foreach ($article->tags as $tag)
                            <a href="/tag/{{$tag['item']}}" style="color: {{$tag['color']}}">{{$tag['item']}}</a>
@endforeach
                        </div>
                        <a class="title" href="/{{$article->article_id}}">{{$article->title}}</a>
                        <h5 class="summary">{{$article->summary}}</h5>
                        <div class="social">
                            <a href="{{$article->user_url}}" class="author"><img src="{{$article->avatar}}"><span>{{$article->nickname}}</span></a>
                            <p class="time">发布于 {{$article->time}}</p>
                            <a class="inter like"><span>赞 {{$article->likes}}</span></a>
                            <a class="inter collect"><span>收藏 {{$article->favorites}}</span></a>
                        </div>
                    </div>
                    <div class="image">
@if(!empty($article->image))
                        <img src="{{$article->image}}">
@endif
                    </div>
                </div>
            </div>
@endforeach

            <div class="list" v-bind:class="visible" v-for="article in list">
                <div class="wrap">
                    <div class="info">
                        <div class="tags">
                            <a  target="_blank" v-for="tag in article.tags" v-bind:href="'tag/'+tag.item" v-bind:style="'color:'+tag.color" v-text="tag.item"></a>
                        </div>
                        <a class="title" target="_blank" v-bind:href="'/'+article.article_id" v-text="article.title"></a>
                        <h5 class="summary" v-text="article.summary"></h5>
                        <div class="social">
                            <a target="_blank" v-bind:href="article.user_url" class="author"><img v-bind:src="article.avatar"><span v-text="article.nickname"></span></a>
                            <p class="time" v-text="'发布于 '+article.time"></p>
                            <a class="inter like"><span v-text="'赞 '+article.likes"></span></a>
                            <a class="inter collect"><span v-text="'收藏 '+article.favorites"></span></a>
                        </div>
                    </div>
                    <div class="image">
                        <img v-if="!!article.image" v-bind:src="article.image">
                    </div>
                </div>
            </div>

            <div class="none" v-bind:class="none ? 'active' : ''">
                <h5>暂无内容</h5>
            </div>

            <a class="load-more" v-bind:class="load">
                <p v-on:click="get_list">
                    <span v-text="load"></span><em></em>
                </p>
            </a>

        </div>
        <!--新闻列表end-->
        <div class="right-side">
@if(isset($ad->image) && $ad->type == 1)
            <!--广告start-->
            <div class="ad-sitehome-right">
                <a href="{{$ad->link}}" target="_blank">
                    <img src="{{$ad->image}}">
                </a>
            </div>
            <!--广告end-->
@endif
@if(!empty($hot))
            <!--热榜start-->
            <div class="rank-container">
                <div class="parent">
                    <div class="title">
                        <h3>热榜</h3>
                        <span></span>
                    </div>
@foreach ($hot as $k => $article)
                    <div class="item">
                        <a href="#" class="rank"><b>N</b>o.0{{$k+1}}</a>
                        <h6><a href="{{$_ENV['platform']['home']}}/user/{{$article['user_id']}}"class="author">{{$article['author']}}</a><span class="time">{{$article['time']}}</span></h6>
                        <a href="/{{$article['article_id']}}" class="summary">{{$article['title']}}</a>
                    </div>
@endforeach
                </div>
            </div>
            <!--热榜end-->
@endif
        </div>
    </div>

</div>
<!--站点内容end-->
@stop
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script>var default_data = {article : {total : '{{$articles['total']}}'}}</script>
<script src="/js/site.index.js?"></script>
@stop