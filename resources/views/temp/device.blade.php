<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta id="viewport" name="viewport" content="initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,minimal-ui">
    <meta name="format-detection" content="telephone=no">
    <title>设备检测</title>
    <style>
        html, body, div, span, applet, object, iframe,
        h1, h2, h3, h4, h5, h6, p, blockquote, pre,
        a, abbr, acronym, address, big, cite, code,
        del, dfn, em, font, ins, kbd, bdi, q, s, samp,
        small, strike, strong, sub, sup, tt, var,
        dl, dt, dd, ol, ul, li,
        fieldset, form, label, legend,
        table, caption, tbody, tfoot, thead, tr, th, td {
            border: 0;
            margin: 0;
            outline: 0;
            padding: 0;
            font-size: 14px;
            font-style: inherit;
            font-weight: inherit;
            -webkit-tap-highlight-color: rgba(255,255,255,0);
        }
        *,
        *:before,
        *:after {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        img {
            border:0;
        }
        figure{
            margin: 5px 0;
        }

        li,a{
            overflow:hidden;
        }

        a{
            text-decoration:none;
            color: #333;
        }

        li{
            list-style-type:none;
            float:left;
        }

        i {
            font-style:normal;
        }
        html{
            display: block;
        }
        body{
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #555;
            font-size: 14px;
            font-family: "Lucida Grande","Microsoft YaHei",sans-serif;
        }
        .content{
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 60px 5%;
        }
        .content .img{
            width: 100%;
            text-align: center;
        }
        .content .img img{
            display: inline-block;
            width: 200px;
        }
        .content h3{
            font-size: 18px;
            line-height: 70px;
            text-align: center;
        }
        .content p,.content em,.content span,.content a{
            font-size: 16px;
            vertical-align: top;
            line-height: 34px;
        }
        .content em{
            font-weight: 900;
            margin-right: 10px;
        }
        .content span{
            color: #999;
        }
        .content a{
            font-size: 18px;
            color: #006dcc;
        }
        .content b{
            color: #f06292 ;
            font-weight: 100;
        }
    </style>
</head>
<body>
<div class="content">
    <div class="img">
        <img src="http://dn-noman.qbox.me/FjHi8wUlaVGCHUERTUnDkZDD_8-t" alt="吉祥物">
    </div>
    <h3>设备类型检测:</h3>
    <p><em>平台检测类型:</em><span>{{$type}}</span></p>
    <p><em>设备识别信息:</em><span>{!! $ua !!}</span></p>
    <p><em>问题反馈:</em><a href="mailto:geyi@pe.vc">geyi@pe.vc</a></p>
</div>
</body>
</html>
