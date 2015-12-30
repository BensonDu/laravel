@extends('layout.user')
@section('style')@parent  <link href="http://dn-t2ipo.qbox.me/v3/public/editor/medium-editor.min.css" rel="stylesheet">
    <link href="http://dn-t2ipo.qbox.me/v3/public/editor/default.min.css" rel="stylesheet">
    <link href="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Ffont-awesome.css?" rel="stylesheet">
    <link href="http://dn-t2ipo.qbox.me/v3/public/editor/medium-editor-insert-plugin.min.css" rel="stylesheet">
    <link href="/css/user.edit.css" rel="stylesheet">
    <link href="/css/public.content.css" rel="stylesheet">
@stop
@section('body')
<!--文章列表start-->
<div id="mid" class="mid">
    <div class="top">
        <h3>全部文章</h3>
        <a href="#" class="add"><em></em><span>撰写文章</span></a>
    </div>
    <div class="list">
        <p class="range">今天</p>
        <div class="item">
            <div class="title">
                <a href="#">黑色星期五网购狂欢,苹果用户最富安卓最穷</a>
            </div>
            <div class="handle">
                <p><span class="last">最后修改于: 12月21日</span> <span class="sta">未发布</span></p>
                <a href="#">删除</a>
            </div>
        </div>
        <div class="item">
            <div class="title">
                <a href="#">黑色星期五网购狂欢,苹果用户最富安卓最穷</a>
            </div>
            <div class="handle">
                <p><span class="last">最后修改于: 12月21日</span> <span class="sta publish">已发布</span></p>
                <a href="#">删除</a>
            </div>
        </div>
        <div class="item">
            <div class="title">
                <a href="#">黑色星期五网购狂欢,苹果用户最富安卓最穷</a>
            </div>
            <div class="handle">
                <p><span class="last">最后修改于: 12月21日</span> <span class="sta">未发布</span></p>
                <a href="#">删除</a>
            </div>
        </div>
        <div class="item">
            <div class="title">
                <a href="#">黑色星期五网购狂欢,苹果用户最富安卓最穷</a>
            </div>
            <div class="handle">
                <p><span class="last">最后修改于: 12月21日</span> <span class="sta publish">已发布</span></p>
                <a href="#">删除</a>
            </div>
        </div>
        <p class="range">一周之内</p>
        <div class="item">
            <div class="title">
                <a href="#">美感恩节黑色星期五网购额达 45 亿，移动端占 34%</a>
            </div>
            <div class="handle">
                <p><span class="last">最后修改于: 12月21日</span> <span class="sta">未发布</span></p>
                <a href="#">删除</a>
            </div>
        </div>
        <div class="item">
            <div class="title">
                <a href="#">黑色星期五网购狂欢,苹果用户最富安卓最穷</a>
            </div>
            <div class="handle">
                <p><span class="last">最后修改于: 12月21日</span> <span class="sta">未发布</span></p>
                <a href="#">删除</a>
            </div>
        </div>
        <div class="item">
            <div class="title">
                <a href="#">专访太库科技 CEO 黄海燕：中国孵化器如何迅速占领世界？</a>
            </div>
            <div class="handle">
                <p><span class="last">最后修改于: 12月21日</span> <span class="sta">未发布</span></p>
                <a href="#">删除</a>
            </div>
        </div>
        <div class="item">
            <div class="title">
                <a href="#">黑色星期五网购狂欢,苹果用户最富安卓最穷</a>
            </div>
            <div class="handle">
                <p><span class="last">最后修改于: 12月21日</span> <span class="sta">未发布</span></p>
                <a href="#">删除</a>
            </div>
        </div>
        <div class="item">
            <div class="title">
                <a href="#">专访太库科技 CEO 黄海燕：中国孵化器如何迅速占领世界？</a>
            </div>
            <div class="handle">
                <p><span class="last">最后修改于: 12月21日</span> <span class="sta">未发布</span></p>
                <a href="#">删除</a>
            </div>
        </div>
        <div class="item">
            <div class="title">
                <a href="#">黑色星期五网购狂欢,苹果用户最富安卓最穷</a>
            </div>
            <div class="handle">
                <p><span class="last">最后修改于: 12月21日</span> <span class="sta">未发布</span></p>
                <a href="#">删除</a>
            </div>
        </div>
        <div class="item">
            <div class="title">
                <a href="#">专访太库科技 CEO 黄海燕：中国孵化器如何迅速占领世界？</a>
            </div>
            <div class="handle">
                <p><span class="last">最后修改于: 12月21日</span> <span class="sta">未发布</span></p>
                <a href="#">删除</a>
            </div>
        </div>
    </div>
</div>
<!--文章列表end-->
<!--文章操作start-->
<div id="article-handle" class="article-handle">
    <div class="left">
        <p>最后修改时间: 11月21日 21:23:31</p>
    </div>
    <div class="right">
        <a class="save" href="#">保存</a>
        <a class="personal" href="#">发布到个人主页</a>
        <a class="contribute" href="#">投稿</a>
    </div>
</div>
<div class="site-list">
    <h3>投稿</h3>
    <div class="list">
        <a class="active" href="#"><em></em><span>TECH2IPO</span></a>
        <a href="#"><em></em><span>虎嗅网</span></a>
    </div>
    <a class="confirm" href="#">确定投稿</a>
    <a class="cancle" href="#">取消</a>
</div>
<!--文章操作end-->
<!--主体部分start-->
<div id="content" class="content">
    <div class="container">

        <div id="summary-switch" class="summary-container">
            <div  class="switch">
                <a href="#" class="summary active"><em></em></a>
                <a href="#" class="image"><em></em></a>
                <a href="#" class="tag"><em></em></a>
            </div>
            <div class="modules">
                <div class="summary">
                    <textarea maxlength="100" placeholder="摘要"></textarea>
                </div>
                <div class="image">
                    <div class="preview">
                        <div class="img-container">
                            <img src="http://dn-t2ipo.qbox.me/v3/public/img-click-upload-dark.png">
                            <span><p>98%</p></span>
                            <input type="file">
                        </div>
                    </div>
                    <div class="desc">
                        <p>文章配图需上传大于300 x 200像素且宽高比为 3:2 的图片,最大尺寸2M.</p>
                    </div>
                </div>
                <div class="tag">
                    <span>互联网金融<em>×</em></span>
                    <span>O2O<em>×</em></span>
                    <input type="text" placeholder="输入标签">
                </div>
            </div>
        </div>

        <div class="title">
            <input type="text" maxlength="40" placeholder="标题">
        </div>

        <div id="content-editor" class="editor medium">

        </div>

    </div>
</div>
<!--主体部分end-->
@stop
@section('script')@parent<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fjquery.ui.widget.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fjquery.iframe-transport.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fjquery.fileupload.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3/public/editor/medium-editor.min.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fhandlebars.runtime.min.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fjquery-sortable-min.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fjquery.cycle2.min.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fjquery.cycle2.center.min.js"></script>
<script src="http://dn-t2ipo.qbox.me/v3%2Fpublic%2Feditor%2Fmedium-editor-insert-plugin.min.js"></script>
<script src="/js/user.edit.js"></script>
@stop