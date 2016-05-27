@extends('layout.admin')
@section('style')@parent  <link href="/css/admin.article.css?v2" rel="stylesheet">
    <link href="http://static.chuang.pro/public-medium-editor.min.css" rel="stylesheet">
    <link href="/lib/datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <link href="http://static.chuang.pro/public-default.min.css"rel="stylesheet" >
    <link href="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Ffont-awesome.css?" rel="stylesheet">
    <link href="http://dn-t2ipo.qbox.me/v3/public/editor/medium-editor-insert-plugin.min.css" rel="stylesheet">
    <link href="/css/public.content.css?v1" rel="stylesheet">
    <link href="/lib/cropper/cropper.min.css" rel="stylesheet">
@stop
@section('area')
<div class="list-header">
    <div class="nav">
        <a class="{{$sub_active == 'unpub' ? 'active' : ''}}" href="/admin/article/unpub">未发表</a>
        <a class="{{$sub_active == 'pub' ? 'active' : ''}}" href="/admin/article/pub">已发表</a>
        <a class="{{$sub_active == 'mine' ? 'active' : ''}}" href="/admin/article/mine">我的文章</a>
        <a class="{{$sub_active == 'recycle' ? 'active' : ''}}" href="/admin/article/recycle">回收站</a>
    </div>
    <div class="search">
        <div class="input"><em></em><input v-model="search.keyword" v-on:keyup.enter="_search" type="text" placeholder="搜索标题、作者"><i></i></div>
    </div>
</div>
<div class="list-body">
@section('table')
@show
</div>
<div class="list-footer">
    <div class="page-size">
        <p>每页显示</p>
        <span>
            <a v-on:click="_size(10)" v-bind:class="pagination.size == 10 ? 'active' : ''">10</a>
            <a v-on:click="_size(20)" v-bind:class="pagination.size == 20 ? 'active' : ''">20</a>
            <a v-on:click="_size(50)" v-bind:class="pagination.size == 50 ? 'active' : ''">50</a>
        </span>
        <p>条</p>
    </div>
    <div class="page-info">
        <p>共 <span v-text="pagination.total"></span> 条</p>
    </div>
    <div class="pagination">
        <a class="btn" v-on:click="_prev()"v-bind:class="pagination.index != 1 ? 'active' : ''" ><em class="prev"></em></a>
        <span>
            <a v-for="b in pagination.btns" v-bind:class="{ 'disable': b.index == '...', 'active': b.active }" v-text="b.index" v-on:click="_turn(b.index)"></a>
        </span>
        <a class="btn" v-on:click="_next()" v-bind:class="pagination.index+1<=pagination.all ? 'active' : ''"><em class="next"></em></a>
    </div>
</div>
@stop
@section('script')@parent <script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script src="/lib/datetimepicker/js/moment.min.js"></script>
<script src="/lib/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="http://static.chuang.pro/imageuploader.min.js"></script>
<script src="http://static.chuang.pro/public-medium-editor.min.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fhandlebars.runtime.min.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fjquery-sortable-min.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fjquery.cycle2.min.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fjquery.cycle2.center.min.js"></script>
<script src="http://static.chuang.pro/medium-plugin.min.850.js"></script>
<script src="/lib/cropper/cropper.min.js"></script>
@section('script-article')
@show
<script src="/js/admin/article.js?v6"></script>
@stop