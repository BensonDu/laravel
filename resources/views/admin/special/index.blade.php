@extends('layout.admin')
@section('style')@parent  <link href="/css/admin.special.css?v1" rel="stylesheet">
    <link href="/lib/cropper/cropper.min.css" rel="stylesheet">
@stop
@section('area')
    <div class="list-header">
        <div class="search">
            <div class="input"><em></em><input v-model="search.keyword" v-on:keyup.enter="_search" type="text" placeholder="专题名称"><i></i></div>
        </div>
        <div class="nav">
            <a v-on:click="_add"><em>+</em> <span>添加</span></a>
        </div>
    </div>
    <div class="list-body">
        <table>
            <thead>
            <tr>
                <th><span>标题</span></th>
                <th class="orderable" v-bind:class="orderby == 'post_status' ? order : ''" v-on:click="_order('post_status')"><span>发布状态</span><em></em></th>
                <th class="orderable" v-bind:class="orderby == 'update_time' ? order : ''" v-on:click="_order('update_time')"><span>发布时间</span><em></em></th>
                <th><span>操作</span></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="a in list">
                <td class="clickable active" v-on:click="_edit(a.id)"><a v-text="a.title"></a></td>
                <td class="publish" v-bind:class="a.post_status"><em></em></td>
                <td v-text="a.update_time"></td>
                <td class="handle">
                    <div>
                        <a class="post" v-on:click="_post(a.id,a.post_status)"><em></em><span>发布状态</span></a>
                        <a class="preview" v-on:click="_preview(a.id)"><em></em><span>预览</span></a>
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

        <div class="post">
            <div class="header">
                <h3><em></em><span>发布</span></h3>
                <a v-on:click="_close">×</a>
            </div>
            <div class="type">
                <ul>
                    <li v-bind:class="{ 'active': special.post == 'now' }" v-on:click="_post_type('now')">
                        <p><em class="flash"></em></p>
                        <span>发布</span>
                    </li>
                    <li v-bind:class="{ 'active': special.post == 'cancel' }" v-on:click="_post_type('cancel')">
                        <p><em class="cancel"></em></p>
                        <span>撤回</span>
                    </li>
                </ul>
            </div>

            <div class="confirm">
                <div><a v-on:click="_confirm_post"><em class="yes"></em><span>确定</span></a></div>
                <div><a v-on:click="_close"><em class="no"></em><span>取消</span></a></div>
            </div>
        </div>

        <div class="preview">
            <div class="header">
                <h3><em></em><span>预览</span></h3>
                <a v-on:click="_close">×</a>
            </div>
            <div class="preview-container">
                <div class="preview-background" v-bind:style="'background-image: url('+preview.bg_image+')'"></div>
                <div class="preview-cover"></div>
                <div class="preview-content">
                    <div class="wrap">
                        <div class="image">
                            <img v-bind:src="preview.image">
                        </div>
                        <div class="title">
                            <h1 v-text="preview.title"></h1>
                        </div>
                        <div class="summary">
                            <p v-text=preview.summary></p>
                        </div>
                        <div class="list">
                            <div class="list-container">
                                <a v-for="a in preview.list" v-bind:href="'/'+a.id" target="_blank">
                                    <div class="text">
                                        <h5 v-text="a.title"></h5>
                                        <p v-text="a.summary"></p>
                                    </div>
                                    <em></em>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="special">
            <div class="header">
                <h3><em></em><span></span></h3>
                <a v-on:click="_close">×</a>
            </div>
            <div class="left">
                <div class="title">
                    <div class="name"><p>标题</p></div>
                    <div class="textarea">
                        <textarea maxlength="50" placeholder="输入标题" v-model="special.title"></textarea>
                    </div>
                </div>
                <div class="image">
                    <div class="name"><p>封面图</p></div>
                    <div class="preview">
                        <div class="img" v-bind:class="special.cover.progress.active ? 'loading' : ''">
                            <div class="img-wrap">
                                <img v-bind:src="!special.cover.val ? 'http://dn-noman.qbox.me/default-upload-image.png' : special.cover.val">
                            </div>
                            <input type="file" accept="image/*" v-on:change="_upload_cover_image" v-el:cover>
                            <div class="process">
                                <p v-text="special.cover.progress.percent"></p>
                            </div>
                            <a class="close" v-on:click="_del_cover_image">×</a>
                        </div>
                    </div>
                </div>
                <div class="summary">
                    <div class="name"><p>描述</p></div>
                    <div class="textarea">
                        <textarea maxlength="100" placeholder="输入描述" v-model="special.summary"></textarea>
                    </div>
                </div>
                <div class="image">
                    <div class="name"><p>背景图</p></div>
                    <div class="preview">
                        <div class="img" v-bind:class="special.bk.progress.active ? 'loading' : ''">
                            <div class="img-wrap">
                                <img v-bind:src="!special.bk.val ? 'http://dn-noman.qbox.me/default-upload-image.png' : special.bk.val">
                            </div>
                            <input type="file" accept="image/*" v-on:change="_upload_bk_image" v-el:bk>
                            <div class="process">
                                <p v-text="special.bk.progress.percent"></p>
                            </div>
                            <a class="close" v-on:click="_del_bk_image">×</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right" v-bind:class="unfold?'unfold':''">
                <div class="list">
                    <div class="title">
                        <div class="name"><p>专题文章</p><a v-on:click="_fold"></a></div>
                    </div>
                    <ul id="special-article-container">
                        <li class="special-article" v-bind:data-id="a.id" v-for="a in special.list"><p v-text="a.title"></p><a v-on:click="_remove_article(a.id)">×</a></li>
                    </ul>
                </div>
                <div class="selector">
                    <div class="keyword">
                        <input type="text" placeholder="输入关键词" v-model="search.keyword" v-on:keyup.enter="_search">
                        <a v-on:click="_search">搜索</a>
                    </div>
                    <div class="item">
                        <a v-for="a in search.list">
                            <p v-text="a.title"></p>
                            <span v-text="a.time"></span>
                            <em v-on:click="_select(a.id,$index)">+</em>
                        </a>
                    </div>
                </div>
            </div>
            <div class="confirm">
                <div><a><em class="yes" v-on:click="_confirm_special"></em><span>确定</span></a></div>
                <div><a><em class="no" v-on:click="_close"></em><span>取消</span></a></div>
            </div>
        </div>
    </div>
@stop
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Fvue.min.js"></script>
<script src="http://static.chuang.pro/imageuploader.min.js"></script>
<script src="/lib/sortable/js/Sortable.min.js"></script>
<script src="/lib/cropper/cropper.min.js"></script>
<script>
    var default_data = {
        total : '{{$total}}',
        list : JSON.parse('{!! json_encode_safe($list) !!}'),
        orderby : 'update_time'
    }
</script>
<script src="/js/admin/special.js?v3"></script>
@stop