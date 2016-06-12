@extends('layout.site')
@section('style')@parent  <link href="/css/public.content.css?v2" rel="stylesheet">
    <link href="http://dn-t2ipo.qbox.me/v3/public/editor/medium-editor-insert-plugin.min.css" rel="stylesheet">
    <link href="/css/public.detail.css?v8" rel="stylesheet">
@stop
@section('body')
@parent
<!--新闻内容部分start-->
<div id="site-content" class="page-content">
    <div class="container">
        <div class="summary {{empty($article['image']) ? 'no-image' : ''}}">
            <div class="image">
                <img src="{{$article['image']}}">
            </div>
            <div class="info">
                <div class="wrap">
                    <a class="author" href="{{$_ENV['platform']['home']}}/user/{{$user['id']}}">
                        <img src="{{$user['avatar']}}">
                        <p class="name">{{$user['name']}}</p>
                    </a>
                    <div class="desc">
                        <div class="top">
                            <a>{{!empty($article['category']) ? $article['category'].' | ':''}}</a>
                            <a>发布于 {{$article['time']}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="title">
            <h1>{{$article['title']}}</h1>
            <h6>{{$article['summary']}}</h6>
        </div>
        <div class="content medium">
            {!! $article['content'] !!}
        </div>

        <!--广告start-->
@if(isset($ad->type) && $ad->type == '3')
        <div class="ad-article-image">
            <a href="{{$ad->link}}" target="_blank"><img src="{{$ad->image}}"></a>
        </div>
@endif
@if(isset($ad->type) && $ad->type == '2')
        <div class="ad-article-text">
            <span>推广</span>{!! $ad->text !!}
        </div>
@endif
        <!--广告end-->

        <div id="detail-handle" class="handle">
            <div class="handle-wrap">
                <div class="tag">
                    <em>标签:</em>
                    @foreach ($article['tags'] as $tag)
                        <a href="/tag/{{$tag}}">{{$tag}}</a>
                    @endforeach
                </div>

                <div class="action">
                    <div class="wrap">
                        <div class="weixin" v-on:mouseenter="_qr">
                            <a><em></em><span>分享</span><div id="qr-container"></div><b></b></a>
                        </div>
                        <div class="favorite" v-bind:class="favorite && 'active'" v-on:click="_favorite">
                            <a><em></em><span v-text="favorites"></span><i>+1</i></a>
                        </div>
                        <div class="like" v-bind:class="like && 'active'" v-on:click="_like">
                            <a><em></em><span v-text="likes"></span><i>+1</i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@if($comment)
        <!--评论start-->
        <div id="comment-container" class="comment-container">
            <div class="comment">
                <div class="comment-sort">
                    <a v-bind:class="orderby == 'hot' && 'active'" v-on:click="_orderby('hot')">热门评论</a>
                    <a v-bind:class="orderby == 'new' && 'active'" v-on:click="_orderby('new')">最新评论</a>
                </div>
                <div class="comment-user">
                    <div class="comment-input">
                        <div class="avatar" >
                            <img v-bind:src="user.avatar">
                        </div>
                        <div class="textarea">
                            <textarea placeholder="请输入评论..." v-model="input" v-on:keydown="_multi_key($event)"></textarea>
                            <div class="not-login" v-bind:class="user.id =='' && 'active'">
                                <h5>请<a v-on:click="_login">登录</a>后参与评论</h5>
                            </div>
                        </div>
                        <div class="submit active">
                            <p v-text="shortcut"></p>
                            <a class="pub-background-transition" v-on:click="_submit">发表</a>
                        </div>
                    </div>
                </div>
                <div class="comment-list">
                    <div class="comment-item" v-for="c in list">
                        <div class="avatar">
                            <img v-bind:src="c.avatar">
                        </div>
                        <div class="comment-body">
                            <div class="head">
                                <a class="left link" v-bind:href="c.user_home" v-text="c.nickname" target="_blank"></a>
                            </div>
                            <div class="text">
                                <p v-text="c.content"></p>
                                <span class="like" v-bind:class="c.like && 'active'" v-on:click="_like(c.id,$index,!c.like)">
                                    <em></em>
                                </span>
                            </div>
                            <div class="foot">
                                <div class="foot-origin">
                                    <span class="left normal right-space" v-text="c.time"></span>
                                    <span class="left normal">评论于站点</span>
                                    <a class="left handle-btn" target="_blank" v-bind:href="c.site_home" v-text="c.site_name"></a>
                                    <span class="right handle-btn" v-if="c.hide != 1" v-on:click="_reply_fold($index)">回复</span>
                                    <span class="right handle-btn" v-on:click="_comment_fold($index)">评论<span class="normal left-space" v-text="c.reply_count"></span></span>
                                    <span class="right handle-btn" v-if="c.hide !=1 && user.role > 1 && site.id == c.site_id" v-on:click="_del(c.id)">删除</span>
                                    <span class="right handle-btn" v-if="c.hide !=1 && user.role > 1 && site.id != c.site_id" v-on:click="_hide(c.id)">隐藏</span>
                                </div>
                                <div class="reply" v-bind:class="c.reply_fold && 'active'">
                                    <div class="reply-wrap">
                                        <div class="avatar">
                                            <img v-bind:src="user.avatar">
                                        </div>
                                        <div class="textarea">
                                            <textarea v-bind:placeholder="'@'+c.nickname" v-model="c.reply_input" v-on:keydown="_multi_key($event,$index)"></textarea>
                                        </div>
                                        <div class="submit active">
                                            <p v-text="shortcut"></p>
                                            <a class="pub-background-transition" v-on:click="_reply_submit($index)">回复</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="sub-comment-container" v-bind:class="{'active':c.comment_fold,'alone':c.hide == 1}">
                                    <div class="comment-item" v-for="r in c.replies">
                                        <div class="avatar">
                                            <img v-bind:src="r.avatar">
                                        </div>
                                        <div class="comment-body">
                                            <div class="head">
                                                <a class="left link" v-bind:href="r.user_home" v-text="r.nickname" target="_blank"></a>
                                                <a class="left divider">回复</a>
                                                <a class="left link" v-bind:href="r.replied_home" v-text="r.replied_nickname" target="_blank"></a>
                                            </div>
                                            <div class="text">
                                                <p v-text="r.content"></p>
                                                <span class="like" v-bind:class="r.like && 'active'" v-on:click="_like(r.id,$parent.$index,!r.like,$index)">
                                                    <em></em>
                                                </span>
                                            </div>
                                            <div class="foot">
                                                <div class="foot-origin">
                                                    <span class="left normal right-space" v-text="r.time"></span>
                                                    <span class="left normal">评论于站点</span>
                                                    <a class="left handle-btn" target="_blank" v-bind:href="r.site_home" v-text="r.site_name"></a>
                                                    <span class="right handle-btn" v-if="r.hide != 1" v-on:click="_reply_fold($parent.$index,$index)">回复</span>
                                                    <span class="right handle-btn" v-if="r.hide !=1 && user.role > 1 && site.id == r.site_id" v-on:click="_del(r.id,$parent.$index)">删除</span>
                                                    <span class="right handle-btn" v-if="r.hide !=1 && user.role > 1 && site.id != r.site_id" v-on:click="_hide(r.id,$parent.$index)">隐藏</span>
                                                </div>
                                                <div class="reply" v-bind:class="r.reply_fold && 'active'">
                                                    <div class="reply-wrap">
                                                        <div class="avatar">
                                                            <img v-bind:src="user.avatar">
                                                        </div>
                                                        <div class="textarea">
                                                            <textarea v-bind:placeholder="'@'+r.nickname" v-model="r.reply_input" v-on:keydown="_multi_key($event,$parent.$index,$index)"></textarea>
                                                        </div>
                                                        <div class="submit active">
                                                            <p v-text="shortcut"></p>
                                                            <a class="pub-background-transition" v-on:click="_reply_submit($parent.$index,$index)">回复</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--评论end-->
@endif
    </div>
</div>
<!--新闻内容end-->
@stop
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script>
    (function () {
        this.like       = '{{$article['like']}}' == '1';
        this.favorite   = '{{$article['favorite']}}' == '1';
        this.likes      = '{{$article['likes']}}';
        this.favorites  = '{{$article['favorites']}}';
        this.article = {
            id : '{{$article['id']}}',
            source : '{{$article['source']}}'
        };
        this.site = {
            id : '{{$site->id}}',
            name : '{{$site->name}}'
        };
        this.comment = '{{$comment ? '1' : '0'}}';
    }).call(define('data'))
</script>
<script src="http://dn-acac.qbox.me/qrcode.js"></script>
<script src="/js/site.detail.js?v6"></script>
<!--苹果分销start-->
<script type='text/javascript'>var _merchantSettings=_merchantSettings || [];_merchantSettings.push(['AT', '1000lmBS']);(function(){var autolink=document.createElement('script');autolink.type='text/javascript';autolink.async=true; autolink.src= ('https:' == document.location.protocol) ? 'https://autolinkmaker.itunes.apple.com/js/itunes_autolinkmaker.js' : 'http://autolinkmaker.itunes.apple.com/js/itunes_autolinkmaker.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(autolink, s);})();</script>
<!--苹果分销end-->
@stop