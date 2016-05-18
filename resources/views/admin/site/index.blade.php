@extends('admin.site.layout')

@section('container')
    <div id="admin-site-base" class="site-form base">
        <div class="item">
            <div class="name">
                <h3>站点域名</h3>
            </div>
            <div class="input disable">
                <input type="text" v-model="domain">
                <div class="disable"></div>
            </div>
        </div>
        <div class="item">
            <div class="name">
                <h3>站点名称</h3>
            </div>
            <div class="input">
                <input type="text" v-model="name">
                <div class="disable"></div>
            </div>
        </div>
        <div class="item">
            <div class="name">
                <h3>站点Slogan</h3>
            </div>
            <div class="input">
                <input type="text" v-model="slogan">
                <div class="disable"></div>
            </div>
        </div>
        <div class="item">
            <div class="name">
                <h3>站点关键词</h3><span>( 用于SE0 )</span>
            </div>
            <div class="input">
                <textarea maxlength="100" v-model="keywords"></textarea>
                <div class="disable"></div>
            </div>
        </div>
        <div class="item">
            <div class="name">
                <h3>站点描述</h3><span>( 用于SE0 )</span>
            </div>
            <div class="input">
                <textarea maxlength="100" v-model="description"></textarea>
                <div class="disable"></div>
            </div>
        </div>
        <div class="btn-group">
            <a class="save pub-background-transition" v-bind:class="save" v-on:click="_save"><em></em><span>保存</span></a>
            <a class="clear pub-background-transition" v-on:click="_clear"><span>清空</span></a>
        </div>
    </div>
@stop

@section('script-site')@parent<script>
    (function () {
        this.base = {
            domain : '{{$info->domain}}',
            name : '{{$info->name}}',
            slogan : '{{$info->slogan}}',
            keywords : '{{$info->keywords}}',
            description : '{{$info->description}}'
        }
    }).call(define('data'));
</script>
@stop