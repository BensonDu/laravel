@extends('layout.admin')
@section('style')<link href="{{ $_ENV['platform']['cdn'].elixir("css/admin.category.css") }}" rel="stylesheet">
@stop
@section('area')
        <div class="box-container" v-bind:class="add && 'add'">
            <div class="table">
                <div class="header">
                    <div class="name">
                        <p>分类</p>
                    </div>
                    <div class="count">
                        <p>相关文章</p>
                    </div>
                    <div class="display">
                        <p>启用</p>
                    </div>
                    <div class="del">
                        <p>操作</p>
                    </div>
                </div>
                <div id="sort-list" class="list-body">
                    <div class="item default">
                        <div class="name">
                            <p class="normal">
                                <span>默认分类</span>
                            </p>
                        </div>
                        <div class="count">
                            <p>共<span v-text="list.default.count"></span>篇</p>
                        </div>
                        <div class="display">

                        </div>
                        <div class="del" v-bind:class="(!list.custom.length || list.default.count == '0') && 'disable'">
                            <p v-on:click="_transfer">转移</p>
                        </div>
                    </div>
                    <div class="item drag" v-for="a in list.custom" v-bind:data-id="a.id" track-by="$index" >
                        <div class="name" v-bind:class="!!a.edit && 'edit'">
                            <p class="normal">
                                <span v-text="a.name"></span>
                                <em v-on:click="_edit_show($index)"></em>
                            </p>
                            <p class="edit">
                                <input v-model="a.name" type="text" v-on:keyup.enter="_edit_show_confirm($index)" v-model="a.name"><a class="confirm" v-on:click="_edit_show_confirm($index)"></a><a class="cancel" v-on:click="_edit_show_cancel($index)"></a>
                            </p>
                        </div>
                        <div class="count">
                            <p>共<span v-text="a.count"></span>篇</p>
                        </div>
                        <div class="display"v-on:click="_display(a.id,a.deleted,$index)">
                            <p class="slider" v-bind:class="a.deleted == '0' && 'true'"><span><em></em></span></p>
                        </div>
                        <div class="del">
                            <p v-on:click="_del(a.id)">删除</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="add" v-if="list.custom.length < 5">
                <div class="input">
                    <input type="text" v-on:keyup.enter="_add" v-model="input"><em></em>
                </div>
                <a v-on:click="_add_display">+</a>
            </div>
            <div class="cover"></div>
       </div>
        <div class="delete-container" v-bind:class="del.display && 'active'">
            <div class="wrap">
                <div class="delete">
                    <h3 v-text="!del.transfer ? '确定删除 ?' : '确认转移 ?'">确定删除 ?</h3>
                    <p>当前分类 <span v-text="del.count"></span> 篇文章,全部移入下面所选分类:</p>
                    <div class="list">
                        <div class="content">
                            <a v-for="a in del.list" v-on:click="_del_select(a.id)" track-by="$index" v-bind:class="del.active == a.id ? 'active' :''"><span v-text="a.name"></span><em></em></a>
                        </div>
                    </div>
                    <div class="btn">
                        <a class="cancel" v-on:click="_del_cancel">取消</a>
                        <a class="confirm active" v-on:click="_del_confirm" v-text="del.loading ? '删除中...':'确定'"></a>
                    </div>
                </div>
            </div>
        </div>
@stop
@section('script')@parent<script>
    (function () {
        this.list = JSON.parse('{!! json_encode_safe($list) !!}');
    }).call(define('data'));
</script>
<script src="{{ $_ENV['platform']['cdn'].elixir("js/admin.category.js")}}"></script>
@stop