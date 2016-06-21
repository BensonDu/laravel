@extends('layout.user')
@section('style')@parent  <link href="http://static.chuang.pro/public-medium-editor.min.css" rel="stylesheet">
    <link href="http://static.chuang.pro/public-default.min.css" rel="stylesheet">
    <link href="/lib/datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <link href="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Ffont-awesome.css?" rel="stylesheet">
    <link href="http://dn-t2ipo.qbox.me/v3/public/editor/medium-editor-insert-plugin.min.css" rel="stylesheet">
    <link href="/css/user.edit.css?v9" rel="stylesheet" charset="utf-8">
    <link href="/lib/cropper/cropper.min.css" rel="stylesheet">
    <link href="/css/public.content.css?v1" rel="stylesheet">
@stop
@section('body')
<!--文章列表start-->
<div id="mid" class="mid">
    <div class="top">
        <h3>全部文章</h3>
        <a class="add" v-on:click="create"><em>+</em><span>撰写文章</span></a>
    </div>
    <div class="list">
        <div class="filter">
            <div class="search">
                <div><i></i><input placeholder="搜索" v-model="search" v-on:keyup.enter="_search"><em></em></div>
            </div>
            <div class="type">
                <a v-bind:class="{'active':type=='all'}" v-on:click="_type('all')"><span>全部</span></a>
                <a v-bind:class="{'active':type=='pub'}" v-on:click="_type('pub')"><span>已发布</span></a>
                <a v-bind:class="{'active':type=='unpub'}" v-on:click="_type('unpub')"><span>未发布</span></a>
            </div>
        </div>
        <div class="list-container">
            <div class="item" v-for="a in list">
                <p class="range" v-if="!a.id" v-text="a.title"></p>
                <div class="card article-list" v-if="!!a.id" v-bind:data-id="a.id" v-bind:class="!!a.active ? 'active' : ''">
                    <div class="title" v-on:click="open(a.id)">
                        <a v-text="a.title"></a>
                    </div>
                    <div class="handle">
                        <p><span class="last" v-text="a.create_time"></span> <span class="sta" v-bind:class="a.post_status==1 ? 'publish' : ''"><em class="unpub">未发布</em><em class="pub">已发布</em></span></p>
                        <a v-on:click="del(a.id)">删除</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="load more" v-bind:class="load">
            <p v-on:click="_load">
                <span></span><em></em>
            </p>
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
            <a class="save" v-bind:class="save" v-on:click="_save"><em></em><p>保存草稿</p></a>
            <a class="contribute" v-bind:class="{'loading':contribute,'update':post_status}"v-on:click="_contribute"><em></em><p></p></a>
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
                            <img v-bind:src="!image.val ? 'http://dn-noman.qbox.me/default-upload-image.png' : image.val">
                            <span><p v-text="image.progress.percent"></p></span>
                            <input type="file" v-on:change="upload" accept="image/*" v-el:image>
                            <a class="close" v-on:click="_del_image">×</a>
                        </div>
                    </div>
                </div>
                <div class="tag">
                    <span v-for="tag in tag.items" track-by="$index"><p v-text="tag"></p><em v-on:click = tag_del>×</em></span>
                    <input v-model = "tag.input" v-on:keydown="tag_keydown($event)"  type="text" placeholder="输入标签(Enter键选中)">
                </div>
            </div>
        </div>

        <div class="title">
            <input type="text" v-model="title" maxlength="40" placeholder="标题" v-el:title v-on:focus="_default_clear" v-on:blur="_default_fill">
        </div>

        <div id="content-editor" class="editor medium">
        </div>

    </div>
    <!--内容部分end-->
</div>
<!--遮罩部分start-->
<div id="pop-background" class="background"></div>
<!--遮罩部分end-->
<!--投稿管理部分start-->
<div id="pop-container" class="pop-container">
    <div class="header">
        <h3><em></em><span>发布管理</span></h3>
        <a v-on:click="_close">×</a>
    </div>
    <div class="post">
        <div class="table">
            <div class="table-head">
                <em class="question">使用说明<i>你可以将稿件投稿至相关站点，点击右侧【添加站点】选择或搜索相关站点，将其添加到你的站点列表中。若你拥有某站点的投稿人权限，该站点将出现【发稿站点】中；若你想将内容向某一站点投稿，该站点将出现在【投稿站点】中。具体权限请与站点管理员联系。</i></em>
                <a class="add" v-on:click="_add" v-bind:class="slider == 'add-site' ? 'unfold' : ''"></a>
            </div>
            <table>
                <thead>
                <tr>
                    <th class="name"><span></span></th>
                    <th><span></span></th>
                    <th><span></span></th>
                </tr>
                </thead>
                <tbody>
                <tr class="sub" v-if="!!auth.length">
                    <td class="name">发稿站点<a class="question"><i>你可以将稿件直接发布至以下站点中，后续的文章修改可点击【推送更新】来进行修改。</i></a></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr v-if="!!auth.length" v-for="p in auth">
                    <td><a v-bind:href="p.link" target="_blank" v-text="p.name"></a></td>
                    <td><span class="sta" v-bind:class="p.post_status"><em></em></span></td>
                    <td>
                        <a class="btn-post" v-bind:class="p.post_status" v-on:click="_post_admin(p.site_id,p.category,p.post_status,p.post_time)"><em></em></a>
                        <a class="btn-push" v-bind:class="p.update" v-on:click="_push_admin(p.site_id,p.update)"><em></em><span class="question"><i>由于该稿件在该站点后台被编辑修改或删除，为了避免你的推送覆盖编辑的修改，请在你自行前往后台对文章进行修改，后台入口位于左侧导航里底部，登出按钮上方。</i></span></a>
                    </td>
                </tr>
                <tr class="sub" v-if="!!contribute.length">
                    <td class="name">投稿站点<a class="question"><i>你可以将稿件投稿至以下站点中，相关站点管理员将审核后发布或驳回你的稿件。</i></a></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr v-if="!!contribute.length" v-for="p in contribute">
                    <td><a v-bind:href="p.link" target="_blank" v-text="p.name"></a></td>
                    <td><span class="sta" v-bind:class="p.post_status"><em></em></span></td>
                    <td>
                        <a class="btn-contribute" v-on:click="_contribute(p.site_id,p.update)" v-bind:class="p.update"><em></em></a>
                        <a class="btn-hide" v-on:click="_remove_site(p.site_id,p.name,p.link)">×</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="slider" v-bind:class="slider">
        <div class="border"></div>
        <div class="add-site">
            <div class="keyword">
                <input type="text" placeholder="输入关键词" v-model="search.keyword" v-on:keyup.enter="_search">
                <a v-on:click="_search">搜索</a>
            </div>
            <ul class="item">
                <li v-for=" s in search.list">
                    <a v-bind:href="s.link" target="_blank" v-text="s.name"></a>
                    <em v-on:click="_add_site(s.id)">+</em>
                </li>
            </ul>
            <div class="result">
                <span>共: </span>
                <b v-text="search.list.length"></b>
                <span> 条</span>
            </div>
        </div>
        <div class="post-admin">
            <div class="category" v-bind:class="{ 'active': post.category.active }" v-on:click="_category_display">
                <h5>文章分类</h5>
                <p><span v-text="post.category.text"></span><em></em></p>
                <ul>
                    <li v-for="c in post.category.list" v-text="c.name" v-on:click="_category_select(c.id,c.name)"></li>
                </ul>
            </div>
            <div class="type">
                <ul>
                    <li v-bind:class="{ 'active': post.type.val == 'now' }" v-on:click="_type_select('now')">
                        <p><em class="flash"></em></p>
                        <span>立即发布</span>
                    </li>
                    <li v-bind:class="{ 'active': post.type.val == 'time' }" v-on:click="_type_select('time')">
                        <p><em class="clock"></em></p>
                        <span>定时/延迟</span>
                    </li>
                    <li v-bind:class="{ 'active': post.type.val == 'cancel' }" v-on:click="_type_select('cancel')">
                        <p><em class="cancel"></em></p>
                        <span>暂不公开</span>
                    </li>
                </ul>
            </div>

            <div class="time" v-bind:class="{ 'active': post.type.val == 'time' }">
                <div class="picked">
                    <p v-text="post.type.time"></p>
                </div>
                <div id="datetimepicker" class="picker-container">
                </div>
            </div>

            <div class="confirm">
                <div><a v-on:click="_confirm_post_admin"><em class="yes"></em><span>确定</span></a></div>
                <div><a v-on:click="_fold"><em class="no"></em><span>取消</span></a></div>
            </div>
        </div>
    </div>
</div>
<!--投稿管理部分end-->
<!--主体部分end-->
@stop
@section('script')@parent<script src="http://static.chuang.pro/imageuploader.min.js?"></script>
<script src="http://static.chuang.pro/public-medium-editor.min.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fhandlebars.runtime.min.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fjquery-sortable-min.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fjquery.cycle2.min.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fjquery.cycle2.center.min.js"></script>
<script src="http://static.chuang.pro/moment.min.js"></script>
<script src="http://static.chuang.pro/bootstrap-datetimepicker.min.js"></script>
<script src="http://static.chuang.pro/medium-plugin.min.850.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script src="/lib/cropper/cropper.min.js"></script>
<script>
    (function () {
        this.list   = JSON.parse('{!! json_encode_safe($list) !!}');
        this.total  = '{!! $total !!}';
        this.route  = '{{isset($route)?$route:null}}';
    }).call(define('data'));
</script>
<script src="/js/user.edit.js?2B4CEB5987"></script>
@stop