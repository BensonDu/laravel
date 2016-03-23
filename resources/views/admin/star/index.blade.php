@extends('layout.admin')
@section('style')@parent  <link href="/css/admin.star.css" rel="stylesheet">
@stop
@section('area')
<div id="box-container" class="box-container">
    <div class="box" v-for="s in list" v-bind:data-index="s.id">
        <div class="star">
            <div class="head"><p v-text="s.category"></p><a v-on:click="_del(s.id)">×</a></div>
            <div class="img" v-on:click="_open(s.id)">
                <img v-bind:src="s.image">
            </div>
            <div class="desc">
                <p v-text="s.title"></p>
                <span v-text="s.time"></span>
            </div>
        </div>
    </div>
    <div class="add" v-if="total < 8">
        <div class="star" v-on:click="_add">
            <p class="add">+</p>
        </div>
    </div>
    <div class="occupy"></div>
</div>
@stop
@section('pop')
    <div id="pop-container" class="pop-container" v-bind:class="display">
        <div class="common">
            <div class="header">
                <h3 class="add"><em>+</em><span>添加</span></h3>
                <h3 class="search" v-on:click="_search_back"><em><</em><span>返回</span></h3>
                <h3 class="edit"><em></em><span>修改</span></h3>
                <a v-on:click="_close">×</a>
            </div>
            <div class="content">
                <div class="wrap">
                    <div class="left">
                        <div class="type" v-bind:class="type.name">
                            <ul>
                                <li class="link" v-on:click="_select_type('link')">
                                    <p><em></em></p>
                                    <span>链接</span>
                                </li>
                                <li class="article" v-on:click="_select_type('article')">
                                    <p><em></em></p>
                                    <span>文章</span>
                                </li>
                                <li class="special" v-on:click="_select_type('special')">
                                    <p><em></em></p>
                                    <span>专题</span>
                                </li>
                            </ul>
                            <div class="result">
                                <div class="link">
                                    <input type="text" placeholder="输入跳转链接" v-model="type.link">
                                </div>
                                <div class="article">
                                    <div v-if="type.article.title != ''" class="normal">
                                        <div class="brief">
                                            <p v-text="type.article.title"></p>
                                            <span v-text="type.article.time"></span>
                                        </div>
                                        <div class="change">
                                            <a v-on:click="_search_article">更改</a>
                                        </div>
                                    </div>
                                    <div v-if="type.article.title == ''" class="empty">
                                        <a v-on:click="_search_article">+</a>
                                    </div>
                                </div>
                                <div class="special">
                                    <div v-if="type.special.title != ''" class="normal">
                                        <div class="brief">
                                            <p v-text="type.special.title"></p>
                                            <span v-text="type.special.time"></span>
                                        </div>
                                        <div class="change">
                                            <a v-on:click="_search_special">更改</a>
                                        </div>
                                    </div>
                                    <div v-if="type.special.title == ''" class="empty">
                                        <a v-on:click="_search_special">+</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="image">
                            <div class="name"><p>精选配图</p></div>
                            <div class="preview">
                                <div class="img" v-bind:class="image.progress.active ? 'loading' : ''">
                                    <img v-bind:src="!image.val ? 'http://dn-t2ipo.qbox.me/v3/public/img-click-upload-dark.png' : image.val">
                                    <input type="file" accept="image/*" v-on:change="_upload_image" v-el:image>
                                    <div class="process">
                                        <p v-text="image.progress.percent"></p>
                                    </div>
                                    <a class="close" v-on:click="_del_image">×</a>
                                </div>
                                <div class="remark">
                                    <p>精选配图需上传大于640 x 360像素且宽高比为 16:9 的图片,最大尺寸2M.</p>
                                </div>
                            </div>
                        </div>
                        <div class="title">
                            <div class="name"><p>标题</p></div>
                            <div class="textarea">
                                <textarea maxlength="50" placeholder="输入标题" v-model="title"></textarea>
                            </div>
                        </div>
                        <div class="category">
                            <div class="name"><p>类型</p></div>
                            <div class="input">
                                <input type="text" maxlength="30" placeholder="输入类型" v-model="category" v-on:keydown.tab="_stop_tab">
                            </div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="keyword">
                            <input type="text" placeholder="输入关键词" v-model="search.keyword" v-on:keyup.enter="_search_action">
                            <a v-on:click="_search_action">搜索</a>
                        </div>
                        <div class="list">
                            <a v-for="s in search.list">
                                <p v-text="s.title"></p>
                                <span v-text="s.time"></span>
                                <em v-on:click="_search_select(s.id,s.title,s.time,s.category,s.image)">+</em>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="confirm">
                <div><a v-on:click="_confirm"><em class="yes"></em><span>确定</span></a></div>
                <div><a v-on:click="_close"><em class="no"></em><span>取消</span></a></div>
            </div>
        </div>
    </div>
@stop
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script src="http://dn-noman.qbox.me/imageuploader.min.js?"></script>
<script src="/lib/sortable/js/Sortable.min.js"></script>
<script>
    var default_data = {
        list : JSON.parse('{!! json_encode_safe($list) !!}')
    }
</script>
<script src="/js/admin/star.js"></script>
@stop