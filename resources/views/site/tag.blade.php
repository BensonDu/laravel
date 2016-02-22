@extends('layout.site')
@section('style')@parent  <link href="/css/site.tag.css" rel="stylesheet">
@stop
@section('body')
@parent
<!--主页内容start-->
<div id="site-content" class="site-content">
    <div class="tag-container">
        <div class="tag-wrap">
            <h5>TAGGED IN</h5>
            <h3>{{$tag}}</h3>
            <p>Total: <b> {{$total}}</b></p>
        </div>
    </div>
    <div class="list-container">
        <div class="list-wrap">
            <div class="item" v-bind:class="visible" v-for="article in list">
                <div class="item-wrap">
                    <div class="info">
                        <div class="tags">
                            <a  target="_blank" v-for="tag in article.tags" v-bind:href="'/tag/'+tag.item" v-bind:style="'color:'+tag.color" v-text="tag.item"></a>
                        </div>
                        <a class="title" target="_blank" v-bind:href="'/'+article.article_id" v-text="article.title"></a>
                        <h5 class="summary" v-text="article.summary"></h5>
                        <div class="social">
                            <a target="_blank" v-bind:href="article.user_url" class="author"><img v-bind:src="article.avatar"><span v-text="article.nickname"></span></a>
                            <p class="time" v-text="'发布于 '+article.create_time"></p>
                            <a class="inter like"><span v-text="'赞 '+article.like"></span></a>
                            <a class="inter collect"><span v-text="'收藏 '+article.favorite"></span></a>
                        </div>
                    </div>
                    <div class="image">
                        <img v-if="!!article.image" v-bind:src="article.image">
                    </div>
                </div>
            </div>
        </div>

        <a class="load-more" v-bind:class="load">
            <p v-on:click="get_list">
                <span v-text="load">More</span><em></em>
            </p>
        </a>

    </div>
</div>
<!--主页内容end-->
@stop
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.js"></script>
<script>var default_data = {
            tag : '{{$tag}}',
            total : '{{$total}}',
            list : JSON.parse('{!! $list !!}')
    }
</script>
<script src="/js/site.tag.js"></script>
@stop