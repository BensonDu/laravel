@extends('mobile.base')
@section('style')@parent  <link href="/mobile/css/public.detail.css?v1" rel="stylesheet">
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
</div>
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
@stop
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<!--头条推荐阅读start-->
<script src="http://dn-noman.qbox.me/tech2ipo.custom.js"></script>
<script>(readsByToutiao = window.readsByToutiao ||[]).push({  id:'toutiao-container',num:8,openAd:true,theme:false,plugins: { "render": function(data) {controller.render(data);}}});</script>
<script>
    (function () {
        var self = this;
        this.vue = new Vue({
            el : '#list-container',
            data : {
                list : []
            },
            methods : {}
        });
        this.render = function (data) {
            var l = data.length,ret = [];
            for(var i = 0; i < l ; i++){
                ret[i] = {
                    id : data[i].id,
                    type : data[i].type,
                    title : data[i].title,
                    date : data[i].date,
                    url : data[i].url,
                    image : !!data[i].imgs && data[i].imgs.length > 0 ? data[i].imgs[0] : ''
                };
            }
            self.vue.list = ret;
        };
    }).call(define('controller'));
</script>
<!--头条推荐阅读end-->
<!--苹果分销start-->
<script type='text/javascript'>var _merchantSettings=_merchantSettings || [];_merchantSettings.push(['AT', '1000lmBS']);(function(){var autolink=document.createElement('script');autolink.type='text/javascript';autolink.async=true; autolink.src= ('https:' == document.location.protocol) ? 'https://autolinkmaker.itunes.apple.com/js/itunes_autolinkmaker.js' : 'http://autolinkmaker.itunes.apple.com/js/itunes_autolinkmaker.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(autolink, s);})();</script>
<!--苹果分销end-->
@stop