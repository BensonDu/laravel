@extends('layout.site')
@section('style')@parent  <link href="css/site.special.css" rel="stylesheet">
@stop
@section('body')
    @parent
        <!--专题内容start-->
    <div id="background" class="background" style=" background: url(http://dn-t2ipo.qbox.me/v3/public/Max.png) no-repeat center; background-size: cover">
        <div class="filter"></div>
    </div>
    <div id="site-content" class="site-content">
        <div class="all">
            <a href="#" class="btn">
                <p>全部专题</p>
                <em></em>
            </a>
            <div class="list-container">
                <a href="#" class="list">
                    <h5>特斯拉如何改变世界</h5>
                    <p>2015年12月13日</p>
                </a>
                <a href="#" class="list">
                    <h5>小米为何惨败于华为</h5>
                    <p>2015年12月13日</p>
                </a>
                <a href="#" class="list">
                    <h5>为何智能家居遭受市场如此冷落</h5>
                    <p>2015年12月13日</p>
                </a>
                <a href="#" class="list">
                    <h5>新媒体时代的自媒体</h5>
                    <p>2015年12月13日</p>
                </a>
                <a href="#" class="list">
                    <h5>那些微博段子手</h5>
                    <p>2015年12月13日</p>
                </a>

            </div>
        </div>
        <div class="container">
            <div class="image">
                <img src="http://dn-t2ipo.qbox.me/v3/public/Tesla-logo-1.png">
            </div>
            <div class="title">
                <h1>特斯拉是如何改变世界的</h1>
            </div>
            <div class="summary">
                <p>硅谷创业奇人伊隆-马斯克(Elon Musk)被认为是乔布斯之后的下一个创新领袖，他在互联网支付（Paypal）、电动汽车（Tesla Motors）和太空探索（SpaceX）等三个迥然不同的领域创立了三家成功的公司。很多人都好奇：伊隆的思维方式和常人有那些不同？他是如何思考问题的？</p>
            </div>
            <div class="list">
                <div class="list-container">
                    <a href="#">
                        <div class="text">
                            <h5>马斯克Twitter找人,特斯拉无人驾驶更进一步</h5>
                            <p>美国东部时间11月20日，特斯拉 CEO 伊隆·马斯克在 Twitter 上表示，为了扩大特斯拉的无人驾驶研发团队，希望能找到一些“专家级的软件工程师”。</p>
                        </div>
                        <em></em>
                    </a>
                    <a href="#">
                        <div class="text">
                            <h5>为了提升性能和续航，特斯拉新款车可能会有些“与众不同</h5>
                            <p>如果 Model 3 要延续 Model S 的设计语言同时又降低风阻系数，那么如何有效兼顾两者将是特斯拉设计师和工程师面临的大课题。</p>
                        </div>
                        <em></em>
                    </a>
                    <a href="#">
                        <div class="text">
                            <h5>特斯拉自动驾驶功能在香港被禁，Musk称将会限制一部分功能</h5>
                            <p>前不久，香港交通部门已经禁止特斯拉车主在道路上使用自动变道以及自动转向等功能，这是因为半自动驾驶会分散驾驶员的注意力。</p>
                        </div>
                        <em></em>
                    </a>
                    <a href="#">
                        <div class="text">
                            <h5>马斯克Twitter找人,特斯拉无人驾驶更进一步</h5>
                            <p>美国东部时间11月20日，特斯拉 CEO 伊隆·马斯克在 Twitter 上表示，为了扩大特斯拉的无人驾驶研发团队，希望能找到一些“专家级的软件工程师”。</p>
                        </div>
                        <em></em>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--专题内容end-->
@stop
@section('script')
@parent<script src="/js/site.special.js"></script>
@stop