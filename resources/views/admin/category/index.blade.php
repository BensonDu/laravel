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
            <div class="close"><p v-if="c.count == 0" v-on:click="_del(c.id)">×</p></div>
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
        <p v-text="'待选分类 '+hide.total+'/5'"></p>
    </div>
    <ul id="sort-list-2">
        <li v-for="c in hide.list" track-by="$index" v-bind:class="!!c.edit ? 'edit' : ''" v-bind:data-id="c.id">
            <div class="name"><p v-text="c.name"></p><em v-on:click="_edit_hide($index)"></em></div>
            <div class="edit"><input type="text" v-on:keyup.enter="_edit_hide_confirm($index)" v-model="c.name"><a class="confirm" v-on:click="_edit_hide_confirm($index)"></a><a class="cancel" v-on:click="_edit_hide_cancel($index)"></a></div>
            <div class="count"><p>相关文章</p><span v-text="c.count"></span><p>篇</p></div>
            <div class="close"><p v-if="c.count == 0" v-on:click="_del(c.id)">×</p></div>
        </li>
    </ul>
    <div class="add" v-if="hide.total < 5">
        <div class="input">
            <input type="text" v-on:keyup.enter="_add" v-model="input"><em></em>
        </div>
        <a v-on:click="_add_display">+</a>
    </div>
    <div class="cover"></div>
</div>
@stop
@section('pop')
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