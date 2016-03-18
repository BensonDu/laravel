@extends('layout.admin')
@section('style')@parent  <link href="/css/admin.category.css" rel="stylesheet">
@stop
@section('area')
        <div class="box-container" v-bind:class="add?'add':''">
            <div class="header">
                <p v-text="'首页显示 '+show.total+'/5'"></p>
            </div>
            <ul id="sort-list-1">
                <li v-for="c in show.list" track-by="$index" v-bind:class="!!c.edit ? 'edit' : ''" v-bind:data-id="c.id">
                    <div class="name"><p v-text="c.name"></p><em v-on:click="_edit_show($index)"></em></div>
                    <div class="edit"><input type="text" v-on:keyup.enter="_edit_show_confirm($index)" v-model="c.name"><a class="confirm" v-on:click="_edit_show_confirm($index)"></a><a class="cancel" v-on:click="_edit_show_cancel($index)"></a></div>
                    <div class="count"><p>相关文章</p><span v-text="c.count"></span><p>篇</p></div>
                    <div class="close"><p v-on:click="_del(c.id)">×</p></div>
                </li>
            </ul>
            <div class="cover"></div>
        </div>
        <div class="box-mid">
            <div class="img">
                <img src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fexchange.png">
            </div>
        </div>
        <div class="box-container" v-bind:class="add?'add':''">
            <div class="header">
                <p>首页隐藏</p>
            </div>
            <ul id="sort-list-2">
                <li v-for="c in hide.list" track-by="$index" v-bind:class="!!c.edit ? 'edit' : ''" v-bind:data-id="c.id">
                    <div class="name"><p v-text="c.name"></p><em v-on:click="_edit_hide($index)"></em></div>
                    <div class="edit"><input type="text" v-on:keyup.enter="_edit_hide_confirm($index)" v-model="c.name"><a class="confirm" v-on:click="_edit_hide_confirm($index)"></a><a class="cancel" v-on:click="_edit_hide_cancel($index)"></a></div>
                    <div class="count"><p>相关文章</p><span v-text="c.count"></span><p>篇</p></div>
                    <div class="close"><p v-on:click="_del(c.id)">×</p></div>
                </li>
            </ul>
            <div class="add" v-if="(hide.total + show.total) < 5">
                <div class="input">
                    <input type="text" v-on:keyup.enter="_add" v-model="input"><em></em>
                </div>
                <a v-on:click="_add_display">+</a>
            </div>
            <div class="cover"></div>
        </div>
        <div class="delete-container" v-bind:class="del.display ? 'active' : ''">
            <div class="wrap">
                <div class="delete">
                    <h3>确定删除 ?</h3>
                    <p>当前分类 <span v-text="del.count"></span> 篇文章,全部移入下面所选分类:</p>
                    <div class="list">
                        <div class="content">
                            <a v-for="c in del.list" v-on:click="_del_select(c.id)" track-by="$index" v-bind:class="del.active == c.id ? 'active' :''"><span v-text="c.name"></span><em></em></a>
                        </div>
                    </div>
                    <div class="btn">
                        <a class="cancel" v-on:click="_del_cancel">取消</a>
                        <a class="confirm" v-on:click="_del_confirm" v-bind:class="del.active ? 'active' : ''" v-text="del.loading ? '删除中...':'确定'"></a>
                    </div>
                </div>
            </div>
        </div>
@stop
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.js"></script>
<script src="http://dn-acac.qbox.me/mobile/public/image_upload.min.js"></script>
<script src="/lib/sortable/js/Sortable.min.js"></script>
<script>
    var default_data = {
        list : JSON.parse('{!! json_encode_safe($list) !!}')
    }
</script>
<script src="/js/admin/category.js"></script>
@stop