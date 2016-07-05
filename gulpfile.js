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

    //站点首页CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'site.mid.css',
        'site.index.css'
    ],'public/dist/css/site.index.css');

    //站点文章详情CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'site.mid.css',
        'public.content.css',
        'public.medium.editor.insert.plugin.css',
        'public.detail.css'
    ],'public/dist/css/site.detail.css');

    //站点搜索CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'site.mid.css',
        'site.search.css'
    ],'public/dist/css/site.search.css');

    //站点标签CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'site.mid.css',
        'site.tag.css'
    ],'public/dist/css/site.tag.css');

    //站点专题CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'site.mid.css',
        'site.special.css'
    ],'public/dist/css/site.special.css');

    //站点管理 广告管理CSS
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
    ],'public/dist/css/admin.article.css');

    //站点管理 专题CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'admin.base.css',
        'admin.category.css'
    ],'public/dist/css/admin.category.css');

    //站点管理 精选CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'admin.base.css',
        'cropper.min.css',
        'admin.star.css'
    ],'public/dist/css/admin.star.css');

    //站点管理 分类CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'admin.base.css',
        'admin.star.css'
    ],'public/dist/css/admin.star.css');

    //站点管理 评论CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'admin.base.css',
        'admin.comment.css'
    ],'public/dist/css/admin.comment.css');

    //站点管理 广告CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'admin.base.css',
        'bootstrap.datetimepicker.css',
        'public.font.awesome.css',
        'cropper.min.css',
        'admin.ad.css'
    ],'public/dist/css/admin.ad.css');

    //站点管理 用户CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'admin.base.css',
        'admin.user.css'
    ],'public/dist/css/admin.user.css');

    //站点管理 站点CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'cropper.min.css',
        'admin.base.css',
        'admin.site.css'
    ],'public/dist/css/admin.site.css');

    //账户CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'public.account.css'
    ],'public/dist/css/public.account.css');
    
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
    ],'public/dist/css/user.edit.css');

    //用户主页及收藏CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'user.mid.css',
        'user.index.css'
    ],'public/dist/css/user.index.css');

    //用户修改密码、个人资料、社交信息CSS
    mix.styles([
        'public.base.css',
        'public.nav.left.css',
        'user.mid.css',
        'user.profile.css',
        'cropper.min.css'
    ],'public/dist/css/user.profile.css');

    //渠道小知CSS
    mix.styles([
        'feed.xiaozhi.css'
    ],'public/dist/css/feed.xiaozhi.css');

    //M 平台账户 CSS
    mix.styles([
        '../mobile/css/public.base.css',
        '../mobile/css/public.account.css'
    ],'public/dist/mobile/css/platform.account.css');

    //M 首页 CSS
    mix.styles([
        '../mobile/css/public.base.css',
        '../mobile/css/site.index.css'
    ],'public/dist/mobile/css/site.index.css');

    //M 详情页 CSS
    mix.styles([
        '../mobile/css/public.base.css',
        '../mobile/css/public.detail.css'
    ],'public/dist/mobile/css/site.detail.css');

    //M 专题 CSS
    mix.styles([
        '../mobile/css/public.base.css',
        '../mobile/css/site.special.css'
    ],'public/dist/mobile/css/site.special.css');
    
});