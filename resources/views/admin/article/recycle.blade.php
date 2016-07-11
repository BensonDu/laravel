@extends('admin.article.layout')
@section('table')
    <table>
        <thead>
        <tr>
            <th><span>标题</span></th>
            <th><span>作者</span></th>
            <th><span>身份</span></th>
            <th class="orderable" v-bind:class="orderby == 'create_time' ? order : ''" v-on:click="_order('create_time')"><span>创建时间</span><em></em></th>
            <th><span>操作</span></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="a in list">
            <td class="title"><a v-text="a.title"></a></td>
            <td v-text="a.nickname"></td>
            <td v-text="a.role"></td>
            <td v-text="a.create_time"></td>
            <td class="handle">
                <div v-bind:class="!a.article_id ? 'hide' : ''">
                    <a class="recovery" v-on:click="_recovery(a.article_id)"><em></em><span>还原</span></a>
                    <a class="del" v-on:click="_del(a.article_id)"><em></em><span>删除</span></a>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
@stop

@section('pop')
    <div id="pop-container" class="pop-container" v-bind:class="display">
        <div class="post">
            <div class="header">
                <h3><em></em><span>发布</span></h3>
                <a v-on:click="_close">×</a>
            </div>
            <div class="category" v-bind:class="{ 'active': post.category.active }" v-on:click="_category_display">
                <h5>文章分类</h5>
                <p><span v-text="post.category.text"></span><em></em></p>
                <ul>
                    <li v-for="c in post.category.list" v-text="c.name" v-on:click="_category_select(c.id,c.name)"></li>
                </ul>
            </div>
            <div class="type">
                <ul>
                    <li v-bind:class="{ 'active': post.type.val == 'now' }" v-on:click="_type_select('now')">
                        <p><em class="flash"></em></p>
                        <span>立即发布</span>
                    </li>
                    <li v-bind:class="{ 'active': post.type.val == 'time' }" v-on:click="_type_select('time')">
                        <p><em class="clock"></em></p>
                        <span>定时发布</span>
                    </li>
                    <li v-bind:class="{ 'active': post.type.val == 'cancel' }" v-on:click="_type_select('cancel')">
                        <p><em class="cancel"></em></p>
                        <span>暂不发布</span>
                    </li>
                </ul>
            </div>

            <div class="time" v-bind:class="{ 'active': post.type.val == 'time' }">
                <div class="picked">
                    <p v-text="post.type.time"></p>
                </div>
                <div id="datetimepicker" class="picker-container">
                </div>
            </div>

            <div class="confirm">
                <div><a v-on:click="_confirm_post"><em class="yes"></em><span>确定</span></a></div>
                <div><a v-on:click="_close"><em class="no"></em><span>取消</span></a></div>
            </div>
        </div>
        <div class="article">
            <div class="header">
                <h3><em></em><span>编辑</span></h3>
                <a v-on:click="_close">×</a>
            </div>
            <div class="left">
                <div class="title">
                    <div class="name"><p>标题</p></div>
                    <div class="textarea">
                        <textarea maxlength="50" placeholder="输入标题" v-model="article.title"></textarea>
                    </div>
                </div>
                <div class="image">
                    <div class="name"><p>文章配图</p></div>
                    <div class="preview">
                        <div class="img" v-bind:class="article.image.progress.active ? 'loading' : ''">
                            <img v-bind:src="!article.image.val ? 'http://dn-noman.qbox.me/default-upload-image.png' : article.image.val">
                            <input type="file" accept="image/*" v-on:change="_upload_image" v-el:image>
                            <div class="process">
                                <p v-text="article.image.progress.percent"></p>
                            </div>
                            <a class="close" v-on:click="_del_image">×</a>
                        </div>
                    </div>
                </div>
                <div class="summary">
                    <div class="name"><p>摘要</p></div>
                    <div class="textarea">
                        <textarea maxlength="100" placeholder="输入摘要" v-model="article.summary"></textarea>
                    </div>
                </div>
                <div class="tag">
                    <div class="name"><p>标签</p><span>5个以内</span></div>
                    <div class="tags">
                        <span v-for="tag in article.tag.items" track-by="$index"><p v-text="tag"></p><em v-on:click = _tag_del>×</em></span>
                        <input v-model = "article.tag.input" v-on:keydown="_tag_keydown($event)"  type="text" placeholder="输入标签(Enter键选中)">
                    </div>
                </div>
            </div>
            <div class="right">
                <div id="content-editor" class="editor medium">
                </div>
            </div>
            <div class="confirm">
                <div><a><em class="yes" v-on:click="_confirm_article"></em><span>确定</span></a></div>
                <div><a><em class="no" v-on:click="_close"></em><span>取消</span></a></div>
            </div>
        </div>
    </div>
@stop

@section('script-article')
<script>
    (function () {
        this.total      = '{{$articles['total']}}';
        this.list       = JSON.parse('{!! json_encode_safe($articles['list']) !!}');
        this.category   = JSON.parse('{!! json_encode_safe($categories) !!}');
        this.orderby    = 'create_time';
        this.api        = {
            get_list : '/admin/article/recycles',
            del_article : '/admin/article/destroy',
            get_article_info : '/admin/article/info',
            save_article : '/admin/article/save',
            recovery_article : '/admin/article/recovery',
            get_post_info : '/admin/article/post/info',
            save_post : '/admin/article/post/save'
        };
    }).call(define('data'));
</script>
@stop