@extends('mobile.site.layout')
@section('style')@parent<link href="/mobile/css/public.detail.css?v3" rel="stylesheet">
@stop
@section('body')
<div class="article-image">
@if(!empty($article['image']))
    <div class="img" style="background-image: url({{$article['image']}})">
        <img src="{{$article['weixin']}}">
    </div>
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
</div>
@if($comment)
<div id="comment-list" class="comment-list">
    <h2>热门评论 :</h2>
    <div class="comment-empty" v-if="list.length == 0">
        <p>暂无评论,快来<span v-on:click="_comment">点击</span>抢占沙发</p>
    </div>
    <div class="comment-wrap">
        <div class="comment-item" v-for="c in list" v-if="$index < 3 || all">
            <div class="avatar">
                <img v-bind:src="c.avatar">
            </div>
            <div class="comment-body">
                <div class="head">
                    <a class="left link" v-bind:href="c.user_home" v-text="c.nickname" target="_blank"></a>
                    <span class="right normal right-space" v-text="c.time"></span>
                </div>
                <div class="text">
                    <p v-text="c.content"></p>
                    <span class="like" v-bind:class="c.like && 'active'" v-on:click="_like(c.id,$index,!c.like)">
                        <em></em>
                    </span>
                </div>
                <div class="foot">
                    <div class="foot-origin">
                        <span class="left normal">评论于</span>
                        <a class="left handle-btn" target="_blank" v-bind:href="c.site_home" v-text="c.site_name"></a>
                        <span class="right handle-btn" v-if="c.hide != 1" v-on:click="_reply($event,c.nickname,$index)">回复</span>
                        <span class="right handle-btn" v-on:click="_comment_fold($index)">评论<span class="normal left-space" v-text="c.reply_count"></span></span>
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
                                    <span class="right normal right-space" v-text="r.time"></span>
                                </div>
                                <div class="text">
                                    <p v-text="r.content"></p>
                                    <span class="like" v-bind:class="r.like && 'active'" v-on:click="_like(r.id,$parent.$index,!r.like,$index)">
                                        <em></em>
                                    </span>
                                </div>
                                <div class="foot">
                                    <div class="foot-origin">
                                        <span class="left normal">评论于</span>
                                        <a class="left handle-btn" target="_blank" v-bind:href="r.site_home" v-text="r.site_name"></a>
                                        <span class="right handle-btn" v-if="r.hide != 1" v-on:click="_reply($event,r.nickname,$parent.$index,$index)">回复</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="comment-all" v-if="list.length>3 && !all">
        <p v-on:click="_all">查看全部<span v-text="list.length"></span>条评论</p>
    </div>
</div>
@endif
<div class="related-reading">
    <h2>相关阅读 :</h2>
    <div id="list-container" class="list-container">
        <a v-for="a in list" v-bind:href="a.url" v-bind:data-id="a.id" v-bind:class="a.type == 1 && 'sta'">
            <div class="image" v-bind:style="{ 'background': 'url('+a.image+') no-repeat center','background-size':'100%'}" ></div>
            <div class="wrap">
                <div class="info">
                    <p class="time" v-text="a.date"></p>
                    <h3 v-text="a.title"></h3>
                </div>
                <div class="sta">
                    <p class="type" v-if="a.type == '1'">打开头条阅读</p>
                </div>
            </div>
        </a>
    </div>
</div>
<div id="social-bar" class="social-bar">
    <div class="wrap">
        <div class="input">
            <p v-on:click="_input"><span>请输入评论</span></p>
        </div>
        <div class="social">
            <div class="wrap">
                <div class="favorite" v-bind:class="favorite && 'active'">
                    <a v-on:click="_favorite"><em></em><span v-text="favorites"></span><i>+1</i></a>
                </div>
                <div class="like" v-bind:class="like && 'active'">
                    <a v-on:click="_like"><em></em><span v-text="likes"></span><i>+1</i></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="comment-textarea" class="comment-textarea" v-bind:class="display && 'active'">
    <div class="wrap">
        <textarea v-bind:placeholder="placeholder" v-model="input" v-on:blur="_blur"></textarea>
        <div class="btn">
            <a class="cancel" v-on:click="_cancel">取消</a>
            <a class="submit" v-if="login" v-on:click="_submit">提交</a>
        </div>
    </div>
    <div class="login" v-if="!login">
        <p>请<span v-on:click="_login">登录</span>后参与评论</p>
    </div>
</div>
@stop
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<!--头条推荐阅读start-->
<script src="http://dn-noman.qbox.me/tech2ipo.custom.js"></script>
<script>(readsByToutiao = window.readsByToutiao ||[]).push({  id:'list-container',num:8,openAd:true,theme:false,plugins: { "render": function(data) {controller.render(data);}}});</script>
<!--头条推荐阅读end-->
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
<script src="/mobile/js/site.detail.js"></script>
<script src="http://static.geetest.com/static/tools/gt.js"></script>
<script src="/lib/geetest/geetest.js"></script>
@stop