@extends('layout.admin')
@section('style')<link href="{{$_ENV['platform']['cdn']}}/dist/css/admin.ad.css" rel="stylesheet">
@stop
@section('area')
<div class="list-header">
    <div class="nav">
        <a v-bind:class="{ 'active' : publish == 'unpub'}" v-on:click="_publish('unpub')">未投放</a>
        <a v-bind:class="{ 'active' : publish == 'pub'}" v-on:click="_publish('pub')">已投放</a>
        <a v-bind:class="{ 'active' : publish == 'end'}" v-on:click="_publish('end')">已结束</a>
    </div>
    <div class="add">
        <a v-on:click="_add"><em>+</em> <span>添加</span></a>
    </div>
</div>
<div class="list-body">
    <table>
        <thead>
        <tr>
            <th><span>广告标题</span></th>
            <th><span>广告类型</span></th>
            <th class="orderable" v-bind:class="orderby == 'start' ? order : ''" v-on:click="_order('start')"><span>起始时间</span><em></em></th>
            <th class="orderable" v-bind:class="orderby == 'end' ? order : ''" v-on:click="_order('end')"><span>结束时间</span><em></em></th>
            <th class="orderable" v-bind:class="orderby == 'create_time' ? order : ''" v-on:click="_order('create_time')"><span>创建时间</span><em></em></th>
            <th><span>操作</span></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="a in list">
            <td class="title active" v-on:click="_edit(a.id)"><a v-text="a.title"></a></td>
            <td v-text="a.type"></td>
            <td v-text="a.start"></td>
            <td v-text="a.end"></td>
            <td v-text="a.create_time"></td>
            <td class="handle">
                <div>
                    <a class="edit" v-on:click="_edit(a.id)"><em></em><span>修改</span></a>
                    <a class="del" v-on:click="_del(a.id)"><em></em><span>删除</span></a>
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

@section('pop')
    <div id="pop-container" class="pop-container" v-bind:class="display">
        <div class="content">
            <div class="header">
                <h3><em></em><span></span></h3>
                <a v-on:click="_close">×</a>
            </div>
            <div class="section">
                <div class="title">
                    <div class="name"><p>标题</p></div>
                    <div class="textarea">
                        <input type="text" placeholder="输入标题" v-model="ad.title">
                    </div>
                </div>
                <div class="weight">
                    <div class="name"><p>权重</p><div class="number"><em class="sub" v-on:click="_weight_sub"></em><span v-text="ad.weight"></span><em class="add" v-on:click="_weight_add"></em></div></div>
                </div>
                <div class="date">
                    <div class="name"><p>投放时间</p></div>
                    <div class="range">
                        <div class="left">
                            <div class="picked"><span>起始:</span><p v-text="ad.start"></p></div>
                            <div id="picker-start" class="picker"></div>
                        </div>
                        <div class="right">
                            <div class="picked"><span>终止:</span><p v-text="ad.end"></p></div>
                            <div id="picker-end" class="picker"></div>
                        </div>
                    </div>
                </div>
                <div class="type">
                    <div class="name"><p>广告类型</p></div>
                    <div class="select">
                        <div class="item active" v-bind:class="ad.type == 1 && 'active'" v-on:click="_type(1)">
                            <img src="http://static.chuang.pro/home-right-image.png">
                            <p><span>首页侧栏</span></p>
                        </div>
                        <div class="item" v-bind:class="ad.type == 2 && 'active'" v-on:click="_type(2)">
                            <img src="http://static.chuang.pro/article-bottom-text.png">
                            <p><span>文章底部文字</span></p>
                        </div>
                        <div class="item" v-bind:class="ad.type == 3 && 'active'" v-on:click="_type(3)">
                            <img src="http://static.chuang.pro/article-bottom-image.png">
                            <p><span>文章底部图片</span></p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="name"><p>链接</p></div>
                    <div class="textarea">
                        <input type="text" placeholder="输入链接" v-model="ad.link">
                    </div>
                </div>
                <div class="image" v-if="ad.type == 1">
                    <div class="name"><p>图片</p></div>
                    <div class="preview">
                        <div class="img">
                            <div class="img-wrap">
                                <img v-bind:src="!ad.imageone ? 'http://7xtcxr.com2.z0.glb.qiniucdn.com/default-upload-image.png' : ad.imageone">
                            </div>
                            <input type="file" accept="image/*" v-on:change="_upload_image_one" v-el:imageone>
                            <a class="close" v-on:click="_del_image_one">×</a>
                        </div>
                    </div>
                </div>
                <div class="image" v-if="ad.type == 3">
                    <div class="name"><p>图片</p></div>
                    <div class="preview">
                        <div class="img">
                            <div class="img-wrap">
                                <img v-bind:src="!ad.imagetwo ? 'http://7xtcxr.com2.z0.glb.qiniucdn.com/default-upload-image.png' : ad.imagetwo">
                            </div>
                            <input type="file" accept="image/*" v-on:change="_upload_image_two" v-el:imagetwo>
                            <a class="close" v-on:click="_del_image_two">×</a>
                        </div>
                    </div>
                </div>
                <div class="text" v-if="ad.type == 2">
                    <div class="name"><p>文案 &nbsp;&nbsp;注：使用##来标记超链接选择文字</p></div>
                    <div class="textarea">
                        <textarea maxlength="100" placeholder="输入广告文案" v-model="ad.text"></textarea>
                    </div>
                </div>
            </div>
            <div class="confirm">
                <div><a><em class="yes" v-on:click="_confirm"></em><span>提交</span></a></div>
                <div><a><em class="no" v-on:click="_close"></em><span>取消</span></a></div>
            </div>
        </div>
    </div>
@stop

@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script src="/lib/datetimepicker/js/moment.min.js"></script>
<script src="/lib/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="http://static.chuang.pro/imageuploader.min.js"></script>
<script src="/lib/cropper/cropper.min.js"></script>
<script>
    (function () {
        this.orderby    = 'create_time';
        this.total      = '{{$total}}';
        this.publish    = 'unpub';
        this.list       = JSON.parse('{!! json_encode_safe($list) !!}');
    }).call(define('data'));
</script>
<script src="/js/admin/ad.js"></script>
@stop