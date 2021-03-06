@extends('admin.layout')
@section('style')<link href="{{ $_ENV['platform']['cdn'].elixir("css/admin.article.css") }}" rel="stylesheet">
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
@section('script')@parent
@section('script-article')
@show
<script src="{{ $_ENV['platform']['cdn'].elixir("js/admin.article.js")}}"></script>
@stop