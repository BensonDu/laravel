@extends('layout.user')
@section('style')@parent  <link href="http://static.chuang.pro/public-medium-editor.min.css" rel="stylesheet">
    <link href="http://static.chuang.pro/public-default.min.css" rel="stylesheet">
    <link href="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Ffont-awesome.css?" rel="stylesheet">
    <link href="http://dn-t2ipo.qbox.me/v3/public/editor/medium-editor-insert-plugin.min.css" rel="stylesheet">
    <link href="/css/user.edit.css" rel="stylesheet">
    <link href="/css/public.content.css?v1" rel="stylesheet">
@stop
@section('body')
<!--文章列表start-->
<div id="mid" class="mid">
    <div class="top">
        <h3>全部文章</h3>
        <a class="add" v-on:click="create"><em></em><span>撰写文章</span></a>
    </div>
    <div class="list">
        <div class="wrap" v-for="(key,val) in list">
            <p class="range" v-text="key"></p>
            <div class="item article-list" v-for="article in val" v-bind:class="!!article.active ? 'active' : ''"  v-bind:data-id="article.id">
                <div class="title" v-on:click="open(article.id)">
                    <a v-text="article.title"></a>
                </div>
                <div class="handle">
                    <p><span class="last" v-text="article.update_time"></span> <span class="sta" v-bind:class="article.post_status==2 ? 'publish' : ''"><em class="unpub">未发布</em><em class="pub">已发布</em></span></p>
                    <a v-on:click="del(article.id)">删除</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!--文章列表end-->

<!--主体部分start-->
<div id="content" class="content">

    <div id="editor-sta" class="editor-sta" v-bind:class="sta">
        <div class="btn-group">
            <a class="create" v-on:click="create"><em></em><span>新建文章</span></a>
            <a class="loading"><img src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fpreload-writing.gif"></a>
        </div>
    </div>

    <!--文章操作start-->
    <div id="article-handle" class="article-handle">
        <div class="left">
            <p><span>最后保存时间:</span> <span v-text="lastmodify"></span></p>
        </div>
        <div class="right">
            <a class="save" v-bind:class="handle_sta.save" v-on:click="save"><em></em><p>保存</p></a>
            <a class="personal" v-bind:class="handle_sta.post" v-on:click="post"><em></em><p v-text="ispost ? '取消发布到主页' :'发布到个人主页'"></p></a>
            <a class="contribute" v-bind:class="handle_sta.contribute" v-on:click="contribute"><em></em><p>投稿</p></a>
        </div>
        <div class="site-list" v-bind:class="site_list.display ? 'active' : ''">
            <h3>投稿</h3>
            <div class="list">
                <a v-for="site in site_list.items" v-bind:class="site.active ? 'active' : ''" v-on:click="select($index)" track-by="$index"><em></em><span v-text="site.name"></span></a>
                <a></a>
                <a></a>
            </div>
            <a class="confirm pub-background-transition" v-on:click="confirm_contribute" v-bind:class="site_list.confirm ? 'active' : ''">确定投稿</a>
            <a class="cancle" v-on:click="select_cancle">取消</a>
        </div>
    </div>
    <!--文章操作end-->

    <!--内容部分start-->
    <div id="article-content" class="container">

        <div id="summary-switch" class="summary-container">
            <div  class="switch">
                <a class="summary active"><em></em></a>
                <a class="image"><em></em></a>
                <a class="tag"><em></em></a>
            </div>
            <div class="modules">
                <div class="summary">
                    <textarea v-model="summary" v-on:keydown.tab.prevent="default_keydown" maxlength="100" placeholder="摘要"></textarea>
                </div>
                <div class="image">
                    <div class="preview">
                        <div class="img-container" v-bind:class="image.progress.active ? 'loading' : ''">
                            <img v-bind:src="!image.val ? 'http://dn-t2ipo.qbox.me/v3/public/img-click-upload-dark.png' : image.val">
                            <span><p v-text="image.progress.percent"></p></span>
                            <input type="file" v-on:change="upload" accept="image/*" v-el:image>
                        </div>
                    </div>
                    <div class="desc">
                        <p>文章配图需上传大于500 x 200像素且宽高比为 5:2 的图片,最大尺寸2M.</p>
                    </div>
                </div>
                <div class="tag">
                    <span v-for="tag in tag.items" track-by="$index"><p v-text="tag"></p><em v-on:click = tag_del>×</em></span>
                    <input v-model = "tag.input" v-on:keydown="tag_keydown($event)"  type="text" placeholder="输入标签(Enter键选中)">
                </div>
            </div>
        </div>

        <div class="title">
            <input type="text" v-model="title" maxlength="40" placeholder="标题" v-el:title>
        </div>

        <div id="content-editor" class="editor medium">
        </div>

    </div>
    <!--内容部分end-->
</div>
<!--主体部分end-->
@stop
@section('script')@parent<script src="http://static.chuang.pro/imageuploader.min.js?"></script>
<script src="http://static.chuang.pro/public-medium-editor.min.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fhandlebars.runtime.min.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fjquery-sortable-min.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fjquery.cycle2.min.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fjquery.cycle2.center.min.js"></script>
<script src="http://static.chuang.pro/medium-plugin.min.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script>var default_data = {list : JSON.parse('{!! $list !!}'),route : '{{isset($route)?$route:null}}'}</script>
<script src="/js/user.edit.js?v5"></script>
@stop