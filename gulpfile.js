var elixir = require('laravel-elixir');

//关闭SourceMap
elixir.config.sourcemaps = false;
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    
    //基础JS 包含 jQuery VUE Base
    mix.scripts([
        '../lib/jquery/jquery-2.1.4.min.js', 
        '../lib/vue/vue.min.js',
        'base.js',
        'platform.nav.left.js',
        'baidu.js'
    ], 'public/js/base.js');
    
    //站点首页CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'site.mid.css',
        'site.index.css'
    ],'public/css/site.index.css');

    //站点首页JS
    mix.scripts([
        'site.base.js',
        'site.index.js',
        '../lib/imagelazyload/imagelazyload.js'
    ], 'public/js/site.index.js');


    //站点文章详情CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'site.mid.css',
        'public.content.css',
        'public.medium.editor.insert.plugin.css',
        'public.detail.css'
    ],'public/css/site.detail.css');

    //站点详情JS
    mix.scripts([
        'site.base.js',
        '../lib/geetest/gt.js',
        '../lib/geetest/geetest.js',
        '../lib/qrcode/qrcode.js',
        'site.detail.js'
    ], 'public/js/site.detail.js');

    //站点搜索CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'site.mid.css',
        'site.search.css'
    ],'public/css/site.search.css');

    //站点搜索JS
    mix.scripts([
        'site.base.js',
        'site.search.js'
    ], 'public/js/site.search.js');

    //站点标签CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'site.mid.css',
        'site.tag.css'
    ],'public/css/site.tag.css');

    //站点标签JS
    mix.scripts([
        'site.base.js',
        'site.tag.js'
    ], 'public/js/site.tag.js');

    //站点专题CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'site.mid.css',
        'site.special.css'
    ],'public/css/site.special.css');

    //站点专题JS
    mix.scripts([
        'site.base.js',
        'site.special.js'
    ], 'public/js/site.special.js');

    //站点专题详情JS
    mix.scripts([
        'site.base.js',
        'site.special.detail.js'
    ], 'public/js/site.special.detail.js');

    //站点管理 文章管理CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'admin.base.css',
        'public.medium.editor.css',
        'public.medium.editor.default.css',
        'bootstrap.datetimepicker.css',
        'public.font.awesome.css',
        'public.medium.editor.insert.plugin.css',
        'cropper.min.css',
        'public.content.css',
        'admin.article.css'
    ],'public/css/admin.article.css');

    //站点管理 文章管理JS
    mix.scripts([
        'admin/base.js',
        '../lib/qiniu/imageuploader.js',
        '../lib/medium/medium-editor.min.js',
        '../lib/medium/handlebars.runtime.min.js',
        '../lib/medium/jquery-sortable-min.js',
        '../lib/medium/jquery.cycle2.min.js',
        '../lib/medium/jquery.cycle2.center.min.js',
        '../lib/datetimepicker/js/moment.min.js',
        '../lib/datetimepicker/js/bootstrap-datetimepicker.min.js',
        '../lib/medium/medium-plugin.min.js',
        '../lib/cropper/cropper.js',
        'admin/article.js'
    ], 'public/js/admin.article.js');

    //站点管理 专题CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'cropper.min.css',
        'admin.base.css',
        'admin.special.css'
    ],'public/css/admin.special.css');

    //站点管理 专题管理JS
    mix.scripts([
        'admin/base.js',
        '../lib/qiniu/imageuploader.js',
        '../lib/sortable/Sortable.min.js',
        '../lib/cropper/cropper.js',
        'admin/special.js'
    ], 'public/js/admin.special.js');

    //站点管理 精选CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'admin.base.css',
        'cropper.min.css',
        'admin.star.css'
    ],'public/css/admin.star.css');

    //站点管理 精选管理JS
    mix.scripts([
        'admin/base.js',
        '../lib/qiniu/imageuploader.js',
        '../lib/sortable/Sortable.min.js',
        '../lib/cropper/cropper.js',
        'admin/star.js'
    ], 'public/js/admin.star.js');

    //站点管理 分类CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'admin.base.css',
        'admin.category.css'
    ],'public/css/admin.category.css');

    //站点管理 分类管理JS
    mix.scripts([
        'admin/base.js',
        '../lib/sortable/Sortable.min.js',
        'admin/category.js'
    ], 'public/js/admin.category.js');

    //站点管理 评论CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'admin.base.css',
        'admin.comment.css'
    ],'public/css/admin.comment.css');

    //站点管理 评论管理JS
    mix.scripts([
        'admin/base.js',
        'admin/comment.js'
    ], 'public/js/admin.comment.js');

    //站点管理 广告CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'admin.base.css',
        'bootstrap.datetimepicker.css',
        'public.font.awesome.css',
        'cropper.min.css',
        'admin.ad.css'
    ],'public/css/admin.ad.css');

    //站点管理 广告JS
    mix.scripts([
        '../lib/qiniu/imageuploader.js',
        '../lib/datetimepicker/js/moment.min.js',
        '../lib/datetimepicker/js/bootstrap-datetimepicker.min.js',
        '../lib/cropper/cropper.js',
        'admin/ad.js'
    ],'public/js/admin.ad.js');

    //站点管理 用户CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'admin.base.css',
        'admin.user.css'
    ],'public/css/admin.user.css');

    //站点管理 用户管理JS
    mix.scripts([
        'admin/base.js',
        'admin/user.js'
    ], 'public/js/admin.user.js');

    //站点管理 黑名单管理JS
    mix.scripts([
        'admin/base.js',
        'admin/blacklist.js'
    ], 'public/js/admin.blacklist.js');

    //站点管理 站点CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'cropper.min.css',
        'admin.base.css',
        'admin.site.css'
    ],'public/css/admin.site.css');

    //站点管理 站点设置JS
    mix.scripts([
        'admin/base.js',
        '../lib/qiniu/imageuploader.js',
        '../lib/cropper/cropper.js',
        'admin/site.js'
    ], 'public/js/admin.site.js');

    //账户CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'public.account.css'
    ],'public/css/public.account.css');

    //账户 登录JS
    mix.scripts([
        'account/base.js',
        'account/login.js'
    ], 'public/js/account.login.js');

    //账户 注册JS
    mix.scripts([
        'account/base.js',
        '../lib/geetest/gt.js',
        '../lib/geetest/geetest.js',
        'account/regist.js'
    ], 'public/js/account.regist.js');

    //账户 找回密码JS
    mix.scripts([
        'account/base.js',
        '../lib/geetest/gt.js',
        '../lib/geetest/geetest.js',
        'account/find.js'
    ], 'public/js/account.find.js');
    
    //用户文章中心CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'user.mid.css',
        'public.medium.editor.css',
        'public.medium.editor.default.css',
        'bootstrap.datetimepicker.css',
        'public.font.awesome.css',
        'public.medium.editor.insert.plugin.css',
        'user.edit.css',
        'cropper.min.css',
        'public.content.css'
    ],'public/css/user.edit.css');

    //用户文章中心JS
    mix.scripts([
        '../lib/qiniu/imageuploader.js',
        '../lib/medium/medium-editor.min.js',
        '../lib/medium/handlebars.runtime.min.js',
        '../lib/medium/jquery-sortable-min.js',
        '../lib/medium/jquery.cycle2.min.js',
        '../lib/medium/jquery.cycle2.center.min.js',
        '../lib/datetimepicker/js/moment.min.js',
        '../lib/datetimepicker/js/bootstrap-datetimepicker.min.js',
        '../lib/medium/medium-plugin.min.js',
        '../lib/cropper/cropper.js',
        'user.edit.js'
    ],'public/js/user.edit.js');

    //用户主页及收藏CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'user.mid.css',
        'user.index.css'
    ],'public/css/user.index.css');

    //用户主页 及 收藏JS
    mix.scripts([
        'user.index.js',
        '../lib/imagelazyload/imagelazyload.js'
    ], 'public/js/user.index.js');

    //用户修改密码、个人资料、社交信息CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'user.mid.css',
        'user.profile.css',
        'cropper.min.css'
    ],'public/css/user.profile.css');

    //用户个人资料JS
    mix.scripts([
        'user/base.js',
        '../lib/qiniu/imageuploader.js',
        '../lib/cropper/cropper.js',
        'user/profile.js'
    ], 'public/js/user.profile.js');

    //用户修改密码JS
    mix.scripts([
        'user/base.js',
        'user/password.js'
    ], 'public/js/user.password.js');

    //用户社交资料JS
    mix.scripts([
        'user/base.js',
        '../lib/qiniu/imageuploader.js',
        '../lib/cropper/cropper.js',
        'user/social.js'
    ], 'public/js/user.social.js');

    //渠道小知CSS
    mix.styles([
        'feed.xiaozhi.css'
    ],'public/css/feed.xiaozhi.css');
    
    //M 基础JS 包含 jQuery VUE Base
    mix.scripts([
        '../lib/jquery/jquery-2.1.4.min.js',
        '../lib/vue/vue.min.js',
        '../mobile/js/base.js',
        'baidu.js'
    ], 'public/mobile/js/base.js');

    //M 平台账户 CSS
    mix.styles([
        '../mobile/css/public.base.css',
        '../mobile/css/public.account.css'
    ],'public/mobile/css/platform.account.css');

    //账户 登录JS
    mix.scripts([
        'account/login.js'
    ], 'public/mobile/js/account.login.js');

    //账户 注册JS
    mix.scripts([
        '../lib/geetest/gt.js',
        '../lib/geetest/geetest.js',
        'account/regist.js'
    ], 'public/mobile/js/account.regist.js');

    //账户 找回密码JS
    mix.scripts([
        '../lib/geetest/gt.js',
        '../lib/geetest/geetest.js',
        'account/find.js'
    ], 'public/mobile/js/account.find.js');

    //M 首页 CSS
    mix.styles([
        '../mobile/css/public.base.css',
        '../mobile/css/site.index.css'
    ],'public/mobile/css/site.index.css');

    //M 首页JS
    mix.scripts([
        '../mobile/js/site.base.js',
        '../mobile/js/site.index.js',
        '../lib/imagelazyload/imagelazyload.js'
    ], 'public/mobile/js/site.index.js');

    //M 详情页 CSS
    mix.styles([
        '../mobile/css/public.base.css',
        '../mobile/css/public.detail.css'
    ],'public/mobile/css/site.detail.css');

    //M 详情JS
    mix.scripts([
        '../mobile/js/site.base.js',
        '../mobile/js/site.detail.js',
        '../lib/geetest/gt.js',
        '../lib/geetest/geetest.js'
    ], 'public/mobile/js/site.detail.js');

    //M 专题 CSS
    mix.styles([
        '../mobile/css/public.base.css',
        '../mobile/css/site.special.css'
    ],'public/mobile/css/site.special.css');

    //版本控制
    mix.version([
        'public/css/site.index.css',
        'public/css/site.detail.css',
        'public/css/site.search.css',
        'public/css/site.tag.css',
        'public/css/site.special.css',
        'public/css/admin.article.css',
        'public/css/admin.category.css',
        'public/css/admin.star.css',
        'public/css/admin.special.css',
        'public/css/admin.comment.css',
        'public/css/admin.ad.css',
        'public/css/admin.user.css',
        'public/css/admin.site.css',
        'public/css/public.account.css',
        'public/css/user.edit.css',
        'public/css/user.index.css',
        'public/css/user.profile.css',
        'public/css/feed.xiaozhi.css',
        'public/mobile/css/platform.account.css',
        'public/mobile/css/site.index.css',
        'public/mobile/css/site.detail.css',
        'public/mobile/css/site.special.css',

        'public/js/base.js',
        'public/js/site.index.js',
        'public/js/site.detail.js',
        'public/js/site.search.js',
        'public/js/site.tag.js',
        'public/js/site.special.js',
        'public/js/site.special.detail.js',
        'public/js/admin.article.js',
        'public/js/admin.special.js',
        'public/js/admin.star.js',
        'public/js/admin.category.js',
        'public/js/admin.comment.js',
        'public/js/admin.ad.js',
        'public/js/admin.user.js',
        'public/js/admin.blacklist.js',
        'public/js/admin.site.js',
        'public/js/account.login.js',
        'public/js/account.regist.js',
        'public/js/account.find.js',
        'public/js/user.edit.js',
        'public/js/user.index.js',
        'public/js/user.profile.js',
        'public/js/user.password.js',
        'public/js/user.social.js',
        'public/mobile/js/base.js',
        'public/mobile/js/account.login.js',
        'public/mobile/js/account.regist.js',
        'public/mobile/js/account.find.js',
        'public/mobile/js/site.detail.js',
        'public/mobile/js/site.index.js'
    ]);
    
});