@extends('admin.site.layout')

@section('container')
    <div id="admin-site-logo" class="site-logo">
        <div class="item">
            <div class="name">
                <h3>站点 Logo</h3>
            </div>
            <div class="preview">
                <div class="img">
                    <div class="img-wrap">
                        <img v-bind:src="!logo ? 'http://qiniu.cdn-chuang.com//default-upload-image-square.png' : logo">
                    </div>
                    <input type="file" accept="image/*" v-on:change="_upload('logo')" v-el:logo>
                    <a class="close" v-on:click="_clear('logo')">×</a>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="name">
                <h3>站点 icon</h3><a>[ 示例 ]<img src="http://static.chuang.pro/site-favicon-example.png"></a>
            </div>
            <div class="preview">
                <div class="img">
                    <div class="img-wrap">
                        <img v-bind:src="!favicon ? 'http://qiniu.cdn-chuang.com//default-upload-image-square.png' : favicon">
                    </div>
                    <input type="file" accept="image/*" v-on:change="_upload('favicon')" v-el:favicon>
                    <a class="close" v-on:click="_clear('favicon')">×</a>
                </div>
            </div>
        </div>
        <div class="item full">
            <div class="name">
                <h3>M站 Logo</h3><a>[ 示例 ]<img src="http://static.chuang.pro/m-logo-ep.png"></a>
            </div>
            <div class="preview">
                <div class="img">
                    <div class="img-wrap">
                        <img v-bind:src="!mobile_logo ? 'http://qiniu.cdn-chuang.com//default-upload-image-square.png' : mobile_logo">
                    </div>
                    <input type="file" accept="image/*" v-on:change="_upload('mobile_logo')" v-el:mobile_logo>
                    <a class="close" v-on:click="_clear('mobile_logo')">×</a>
                </div>
            </div>
        </div>
        <div class="item full">
            <div class="name">
                <h3>渠道页面 Logo</h3><a>[ 示例 ]<img src="http://static.chuang.pro/feed-logo-example.png"></a>
            </div>
            <div class="preview">
                <div class="img">
                    <div class="img-wrap">
                        <img v-bind:src="!thirdparty_logo ? 'http://qiniu.cdn-chuang.com//default-upload-image-square.png' : thirdparty_logo">
                    </div>
                    <input type="file" accept="image/*" v-on:change="_upload('thirdparty_logo')" v-el:thirdparty_logo>
                    <a class="close" v-on:click="_clear('thirdparty_logo')">×</a>
                </div>
            </div>
        </div>
        <div class="btn-group">
            <a class="save pub-background-transition" v-bind:class="save" v-on:click="_save"><em></em><span>保存</span></a>
        </div>
    </div>
@stop
@section('script-site')@parent<script>
    (function () {
        this.logo = {
            logo : '{{$info->logo}}',
            mobile_logo : '{{$info->mobile_logo}}',
            thirdparty_logo : '{{$info->thirdparty_logo}}',
            favicon : '{{$info->favicon}}'
        }
    }).call(define('data'));
</script>
@stop