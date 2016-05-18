@extends('admin.site.layout')

@section('container')
    <div id="admin-site-social" class="site-social">
        <div class="item">
            <div class="name">
                <h3>微信</h3><span>[二维码]</span>
            </div>
            <div class="preview">
                <div class="img">
                    <div class="img-wrap">
                        <img v-bind:src="!weixin ? 'http://dn-noman.qbox.me/default-upload-image-square.png' : weixin">
                    </div>
                    <input type="file" accept="image/*" v-on:change="_upload('weixin')" v-el:weixin>
                    <a class="close" v-on:click="_clear('weixin')">×</a>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="name">
                <h3>微博</h3>
            </div>
            <div class="weibo">
                <input class="prefix" type="text" value="http://weibo.com/" disabled>
                <input class="suffix" type="text" v-model="weibo">
            </div>
        </div>
        <div class="item">
            <div class="name">
                <h3>邮箱</h3>
            </div>
            <div class="input">
                <input type="email" v-model="email">
            </div>
        </div>
        <div class="btn-group">
            <a class="save pub-background-transition" v-bind:class="save" v-on:click="_save"><em></em><span>保存</span></a>
        </div>
    </div>
@stop

@section('script-site')@parent<script src="http://static.chuang.pro/imageuploader.min.js"></script>
<script src="/lib/cropper/cropper.min.js"></script>
<script>
    (function () {
        this.social = {
            weibo : '{{$info->weibo}}',
            weixin : '{{$info->weixin}}',
            email : '{{$info->email}}'
        }
    }).call(define('data'));
</script>
@stop