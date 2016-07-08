@extends('layout.admin')
@section('style')<link href="{{ $_ENV['platform']['cdn'].elixir("css/admin.comment.css") }}" rel="stylesheet">
@stop
@section('area')
<div class="list-header">
    <div class="nav">
        <a v-bind:class="{ 'active' : site == 'insite'}" v-on:click="_site('insite')">站内评论</a>
        <a v-bind:class="{ 'active' : site == 'outsite'}" v-on:click="_site('outsite')">站外评论</a>
    </div>
</div>
<div class="list-body">
    <table>
        <thead>
        <tr>
            <th width="15%"><span>评论作者</span></th>
            <th width="45%"><span>评论内容</span></th>
            <th class="orderable" v-bind:class="order" v-on:click="_order('start')" width="20%"><span>评论时间</span><em></em></th>
            <th width="20%"><span>操作</span></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="a in list">
            <td class="title active"><a v-text="a.nickname"></a></td>
            <td v-text="a.content" v-bind:title="a.content"></td>
            <td v-text="a.time"></td>
            <td class="handle">
                <div>
                    <a class="preview" v-bind:href="a.link" target="_blank"><em></em><span>查看</span></a>
                    <a class="hide" v-if="site != 'insite'" v-on:click="_hide(a.id)"><em></em><span>隐藏</span></a>
                    <a class="del" v-if="site == 'insite'" v-on:click="_del(a.id)"><em></em><span>删除</span></a>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
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

@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script>
    (function () {
        this.site       = 'insite';
        this.total      = '{{$total}}';
        this.list       = JSON.parse('{!! json_encode_safe($list) !!}');
    }).call(define('data'));
</script>
<script src="/js/admin/comment.js"></script>
@stop