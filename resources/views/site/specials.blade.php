@extends('layout.site')
@section('style')@parent  <link href="/css/site.special.css?v1" rel="stylesheet">
@stop
@section('body')
    @parent
<!--专题start-->

<div id="background" class="background">
    <div class="background-wrap">
        <div class="special-background" v-bind:style="styleFirst" v-bind:class="{'disable' : !displayFirst,'active':active == 1}"></div>
        <div class="special-background" v-bind:style="styleSecond" v-bind:class="{'disable' : !displaySecond,'active':active == 2}"></div>
        <div class="loading-cover" v-bind:class="!loading && 'disable'">
            <div class="loading-wrap">
                <div class="loading-circle"></div>
                <div class="loading-text">loading</div>
            </div>
        </div>
        <div class="filter"></div>
    </div>
</div>

<div id="site-content" class="site-content" v-bind:class="display && 'active'">
    <div class="title">
        <em></em>
        <h1>{{$special}}</h1>
    </div>
    <div class="home">
        <div class="special-list">
            <a class="item" v-for="s in list" v-if="$index >= (index-1)*3 && $index < index*3" v-bind:href="'/special/'+s.id" target="_blank" v-on:mouseover="_over($index)">
                <div class="image">
                    <div class="image-wrap">
                        <img v-bind:src="s.image">
                    </div>
                </div>
                <div class="info">
                    <div class="info-wrap">
                        <h1 v-text="s.title"></h1>
                        <p v-text="s.summary"></p>
                    </div>
                    <div class="entry">
                        <p><span>查看详情</span><em></em></p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="pagination">
        <a class="prev" v-bind:class="!prev && 'disable'" v-on:click="_prev"><em></em><span>上一组</span></a>
        <a class="next" v-bind:class="!next && 'disable'" v-on:click="_next"><span>下一组</span><em></em></a>
    </div>
</div>
<!--专题end-->

@stop

@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3/public/vue.min.js"></script>
<script>
    (function () {
        var self = this;
        this.list   = JSON.parse('{!! json_encode_safe($list) !!}');
        this.total  = Math.ceil(self.list.length/3);
    }).call(define('data'));
</script>
<script src="/js/site.special.js?v1"></script>
@stop