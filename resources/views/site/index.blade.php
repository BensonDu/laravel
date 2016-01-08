@extends('layout.site')
@section('style')@parent  <link href="css/site.index.css" rel="stylesheet">
@stop
    @section('body')
    @parent
    <!--站点内容start-->
    <div id="site-content" class="site-content">

        <div class="star">
            <div id="star-album" class="album">
                <div class="nav prev"><em></em></div>
                <div class="item-container">
                    <a href="#">
                        <div class="image">
                            <img src="http://dn-t2ipo.qbox.me/v3/public/news-demo-2.png">
                        </div>
                        <div class="summary">
                            <h5>人物特写</h5>
                            <h2>差点被乔布斯搞砸的皮克斯,是如何将计算机带进电影业的</h2>
                            <h6>11-25 8:25</h6>
                        </div>
                    </a>
                    <a href="#">
                        <div class="image">
                            <img src="http://dn-t2ipo.qbox.me/v3/public/news-demo-1.png">
                        </div>
                        <div class="summary">
                            <h5>深度观点</h5>
                            <h2>库克说SurfaceBook不咋样,莫博士也说了他对iPad Por的看法</h2>
                            <h6>11-25 8:25</h6>
                        </div>
                    </a>
                    <a href="#">
                        <div class="image">
                            <img src="http://dn-t2ipo.qbox.me/v3/public/news-demo-3.png">
                        </div>
                        <div class="summary">
                            <h5>任务特写</h5>
                            <h2>特斯拉"空中升级"7.0,CEO亲自来华演示</h2>
                            <h6>11-25 8:25</h6>
                        </div>
                    </a>
                    <a href="#">
                        <div class="image">
                            <img src="http://dn-t2ipo.qbox.me/v3/public/news-demo-2.png">
                        </div>
                        <div class="summary">
                            <h5>人物特写</h5>
                            <h2>差点被乔布斯搞砸的皮克斯,是如何将计算机带进电影业的</h2>
                            <h6>11-25 8:25</h6>
                        </div>
                    </a>
                    <a href="#">
                        <div class="image">
                            <img src="http://dn-t2ipo.qbox.me/v3/public/news-demo-1.png">
                        </div>
                        <div class="summary">
                            <h5>深度观点</h5>
                            <h2>库克说SurfaceBook不咋样,莫博士也说了他对iPad Por的看法</h2>
                            <h6>11-25 8:25</h6>
                        </div>
                    </a>
                    <a href="#">
                        <div class="image">
                            <img src="http://dn-t2ipo.qbox.me/v3/public/news-demo-3.png">
                        </div>
                        <div class="summary">
                            <h5>任务特写</h5>
                            <h2>特斯拉"空中升级"7.0,CEO亲自来华演示</h2>
                            <h6>11-25 8:25</h6>
                        </div>
                    </a>
                </div>
                <div class="nav next"><em></em></div>
            </div>
        </div>

        <div class="filter">
            <div id="filter" class="filter-container">
                <div class="parent">
                    <div class="cate">
                        <a href="#" class="active"><span>全部</span></a>
                        <a href="#"><span>行业公司</span></a>
                        <a href="#"><span>人物特写</span></a>
                        <a href="#"><span>新闻快讯</span></a>
                        <a href="#"><span>深度观察</span></a>
                        <a href="#"><span>产品速报</span></a>
                    </div>
                    <div class="search">
                        <div><i></i><input type="text" placeholder="搜索关键词"></div>
                    </div>
                </div>
            </div>
        </div>
        <!--新闻列表start-->
        <div class="news">
            <div class="list-container">
                <div class="list">
                    <div class="info">
                        <div class="tags">
                            <a href="#" style="color: #fad53e">独角兽</a>
                            <a href="#" style="color: #81c683">美食</a>
                            <a href="#" style="color: #f57e16">创业大街</a>
                        </div>
                        <a class="title" href="#">天价众筹项目神秘夭折,支持者或赔夫又折兵</a>
                        <h5 class="summary">美基因检测公司为个体提供丰富的基因检测服务，1000人民币即可量身制定减肥计划。美 FDA 指出这些公司未获得许可证，对安全和有效性表示担忧.</h5>
                        <div class="social">
                            <a href="#" class="author"><img src="http://dn-t2ipo.qbox.me/v3/public/unknown-user.png"><span>李啸天</span></a>
                            <p class="time">发布于 23分钟前</p>
                            <a class="inter like"><em></em><span>赞 51</span></a>
                            <a class="inter collect"><em></em><span>收藏 36</span></a>
                        </div>
                    </div>
                    <div class="image">
                        <img src="http://dn-t2ipo.qbox.me/v3/public/news-list-2.png">
                    </div>
                </div>

                <div class="list">
                    <div class="info">
                        <div class="tags">
                            <a href="#" style="color: #f57e16">智能硬件</a>
                            <a href="#" style="color: #81c683">美食</a>
                            <a href="#" style="color: #b39ddb">创业大街</a>
                            <a href="#" style="color: #80cbc4">互联网社交</a>
                        </div>
                        <a class="title" href="#">Google+大改版，专注于热度最高的「社区」和「专辑」功能</a>
                        <h5 class="summary">Google+ 昨晚开始更新全新的 Google+，不仅在设计上重新设计，在产品上也更加简洁，专注于社区 (Communities) 和专辑 (Collections) ，因为这两个功能是 Google+ 忠实用户最喜欢和最频繁使用的两个功能。</h5>
                        <div class="social">
                            <a href="#" class="author"><img src="http://dn-t2ipo.qbox.me/v3/public/unknown-user.png"><span>李啸天</span></a>
                            <p class="time">发布于 23分钟前</p>
                            <a class="inter like"><em></em><span>赞 51</span></a>
                            <a class="inter collect"><em></em><span>收藏 36</span></a>
                        </div>
                    </div>
                    <div class="image">
                        <img src="http://dn-t2ipo.qbox.me/v3/public/news-list-1.png">
                    </div>
                </div>

                <div class="list">
                    <div class="info">
                        <div class="tags">
                            <a href="#" style="color: #f57e16">智能硬件</a>
                            <a href="#" style="color: #81c683">美食</a>
                            <a href="#" style="color: #b39ddb">创业大街</a>
                            <a href="#" style="color: #80cbc4">互联网社交</a>
                        </div>
                        <a class="title" href="#">Google+大改版，专注于热度最高的「社区」和「专 辑」功能</a>
                        <h5 class="summary">Google+ 昨晚开始更新全新的 Google+，不仅在设计上重新设计，在产品上也更加简洁，专注于社区 (Communities) 和专辑 (Collections) ，因为这两个功能是 Google+ 忠实用户最喜欢和最频繁使用的两个功能。</h5>
                        <div class="social">
                            <a href="#" class="author"><img src="http://dn-t2ipo.qbox.me/v3/public/unknown-user.png"><span>李啸天</span></a>
                            <p class="time">发布于 23分钟前</p>
                            <a class="inter like"><em></em><span>赞 51</span></a>
                            <a class="inter collect"><em></em><span>收藏 36</span></a>
                        </div>
                    </div>
                    <div class="image">
                        <img src="http://dn-t2ipo.qbox.me/v3/public/news-list-1.png">
                    </div>
                </div>

                <div class="list">
                    <div class="info">
                        <div class="tags">
                            <a href="#" style="color: #fad53e">独角兽</a>
                            <a href="#" style="color: #81c683">美食</a>
                            <a href="#" style="color: #f57e16">创业大街</a>
                        </div>
                        <a class="title" href="#">天价众筹项目神秘夭折,支持者或赔夫又折兵</a>
                        <h5 class="summary">美基因检测公司为个体提供丰富的基因检测服务，1000人民币即可量身制定减肥计划。美 FDA 指出这些公司未获得许可证，对安全和有效性表示担忧.</h5>
                        <div class="social">
                            <a href="#" class="author"><img src="http://dn-t2ipo.qbox.me/v3/public/unknown-user.png"><span>李啸天</span></a>
                            <p class="time">发布于 23分钟前</p>
                            <a class="inter like"><em></em><span>赞 51</span></a>
                            <a class="inter collect"><em></em><span>收藏 36</span></a>
                        </div>
                    </div>
                    <div class="image">
                        <img src="http://dn-t2ipo.qbox.me/v3/public/news-list-2.png">
                    </div>
                </div>

                <div class="list">
                    <div class="info">
                        <div class="tags">
                            <a href="#" style="color: #fad53e">独角兽</a>
                            <a href="#" style="color: #81c683">美食</a>
                            <a href="#" style="color: #f57e16">创业大街</a>
                        </div>
                        <a class="title" href="#">天价众筹项目神秘夭折,支持者或赔夫又折兵</a>
                        <h5 class="summary">美基因检测公司为个体提供丰富的基因检测服务，1000人民币即可量身制定减肥计划。美 FDA 指出这些公司未获得许可证，对安全和有效性表示担忧.</h5>
                        <div class="social">
                            <a href="#" class="author"><img src="http://dn-t2ipo.qbox.me/v3/public/unknown-user.png"><span>李啸天</span></a>
                            <p class="time">发布于 23分钟前</p>
                            <a class="inter like"><em></em><span>赞 51</span></a>
                            <a class="inter collect"><em></em><span>收藏 36</span></a>
                        </div>
                    </div>
                    <div class="image">
                        <img src="http://dn-t2ipo.qbox.me/v3/public/news-list-2.png">
                    </div>
                </div>

                <div class="list">
                    <div class="info">
                        <div class="tags">
                            <a href="#" style="color: #f57e16">智能硬件</a>
                            <a href="#" style="color: #81c683">美食</a>
                            <a href="#" style="color: #b39ddb">创业大街</a>
                            <a href="#" style="color: #80cbc4">互联网社交</a>
                        </div>
                        <a class="title" href="#">Google+大改版，专注于热度最高的「社区」和「专 辑」功能</a>
                        <h5 class="summary">Google+ 昨晚开始更新全新的 Google+，不仅在设计上重新设计，在产品上也更加简洁，专注于社区 (Communities) 和专辑 (Collections) ，因为这两个功能是 Google+ 忠实用户最喜欢和最频繁使用的两个功能。</h5>
                        <div class="social">
                            <a href="#" class="author"><img src="http://dn-t2ipo.qbox.me/v3/public/unknown-user.png"><span>李啸天</span></a>
                            <p class="time">发布于 23分钟前</p>
                            <a class="inter like"><em></em><span>赞 51</span></a>
                            <a class="inter collect"><em></em><span>收藏 36</span></a>
                        </div>
                    </div>
                    <div class="image">
                        <img src="http://dn-t2ipo.qbox.me/v3/public/news-list-1.png">
                    </div>
                </div>

                <a class="load-more">
                    <p>
                        <span>MORE</span><em></em>
                    </p>
                </a>
            </div>
            <!--新闻列表end-->
            <!--热榜start-->
            <div class="rank-container">
                <div class="parent">
                    <div class="title">
                        <h3>创见热榜</h3>
                        <span></span>
                    </div>
                    <div class="item">
                        <a href="#" class="rank"><b>N</b>o.01</a>
                        <h6><a href="#"class="author">李晓天</a><span class="time">11月23日 23:23</span></h6>
                        <a href="#" class="summary">飞碟说O2O不是让商家让利,浴室饮食街BAT: 外婆家,海底捞,和西贝就入股了口碑.</a>
                    </div>
                    <div class="item">
                        <a href="#" class="rank"><b>N</b>o.02</a>
                        <h6><a href="#"class="author">李晓天</a><span class="time">11月23日 23:23</span></h6>
                        <a href="#" class="summary">美团升级了一下手机,然后发布了国内互联网第一台微单.</a>
                    </div>
                    <div class="item">
                        <a href="#" class="rank"><b>N</b>o.03</a>
                        <h6><a href="#"class="author">李晓天</a><span class="time">11月23日 23:23</span></h6>
                        <a href="#" class="summary">飞碟说O2O不是让商家让利,浴室饮食街BAT: 外婆家,海底捞,和西贝就入股了口碑.</a>
                    </div>
                </div>
            </div>
            <!--热榜end-->
        </div>

    </div>
    <!--站点内容end-->
@stop
@section('script')@parent<script src="/js/site.index.js"></script>
@stop