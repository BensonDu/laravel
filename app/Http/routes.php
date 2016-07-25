<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//设置请求域名 全局变量
$_ENV['request_is_mobile'] = is_mobile();
//请求HOST
$host = request()->server('HTTP_HOST');
//平台HOST
$base = config('site.platform_base');

/*
 |--------------------------------------------------------------------------
 | 子站路由
 |--------------------------------------------------------------------------
 */
if(!($host == $base || $host == 'm.'.$base)) {
    //设备跳转
    Route::group(['middleware' => 'Device'], function () {
        //PC站
        if(!$_ENV['request_is_mobile']){
            //站点首页
            Route::get('/', 'Site\IndexController@index');
            Route::get('/index/list', 'Site\IndexController@articles');
            //文章详情
            Route::get('/{id}', 'Site\DetailController@index');
            Route::get('/{id}.html', 'Site\DetailController@index');
            //站点专题页
            Route::get('/special', 'Site\SpecialController@index');
            Route::get('/special/{id}', 'Site\SpecialController@detail');
        }
        //M站
        else{
            //站点首页
            Route::get('/','Site\IndexController@mobile');
            Route::get('/index/list', 'Site\IndexController@mobilearticles');
            //文章详情
            Route::get('/{id}', 'Site\DetailController@mobile');
            Route::get('/{id}.html', 'Site\DetailController@mobile');
            //站点专题页
            Route::get('/special', 'Site\SpecialController@mobileindex');
            Route::get('/special/{id}', 'Site\SpecialController@mobiledetail');
        }
    });
    //搜索
    Route::get('/search', 'Site\SearchController@index');
    Route::get('/search/{keyword}', 'Site\SearchController@index');
    Route::get('/search/{keyword}/list', 'Site\SearchController@results');
    //标签
    Route::get('/tag/{tag}', 'Site\TagController@index');
    Route::get('/tag/{tag}/list', 'Site\TagController@tags');
    //注销
    Route::get('/account/logout', 'Account\AccountController@logout');
    //评论
    Route::get('/comment/comments', 'Comment\CommentController@comments');
    Route::get('/comment/like', 'Comment\CommentController@like');
    Route::get('/comment/hide', 'Comment\CommentController@hide');
    Route::get('/comment/delete', 'Comment\CommentController@delete');
    Route::get('/comment/submit', 'Comment\CommentController@submit');

    //渠道输出
    Route::get('/feed', 'Feed\IndexController@index');
    Route::get('/feed/toutiao', 'Feed\ToutiaoController@index');
    Route::get('/feed/toutiao/{id}', 'Feed\ToutiaoController@detail');
    Route::get('/feed/xiaozhi', 'Feed\XiaozhiController@index');
    Route::get('/feed/xiaozhi/{id}', 'Feed\XiaozhiController@detail');
    Route::get('/feed/flipboard', 'Feed\FlipboardController@index');
    Route::get('/feed/uc', 'Feed\UcController@index');
    Route::get('/feed/uc/{id}', 'Feed\UcController@detail');

    //站点管理
    Route::group(['middleware' => 'AdminAuth'], function () {
        //文章管理
        Route::get('/admin', 'Admin\ArticleController@unpub');
        Route::get('/admin/article', 'Admin\ArticleController@unpub');
        Route::get('/admin/article/unpub', 'Admin\ArticleController@unpub');
        Route::get('/admin/article/unpubs', 'Admin\ArticleController@unpubs');
        Route::get('/admin/article/post/info', 'Admin\ArticleController@postinfo');
        Route::get('/admin/article/post/save', 'Admin\ArticleController@postsave');
        Route::get('/admin/article/delete', 'Admin\ArticleController@delete');
        Route::get('/admin/article/destroy', 'Admin\ArticleController@destroy');
        Route::get('/admin/article/recovery', 'Admin\ArticleController@recovery');
        Route::get('/admin/article/pub', 'Admin\ArticleController@pub');
        Route::get('/admin/article/pubs', 'Admin\ArticleController@pubs');
        Route::get('/admin/article/mine', 'Admin\ArticleController@mine');
        Route::get('/admin/article/mines', 'Admin\ArticleController@mines');
        Route::get('/admin/article/recycle', 'Admin\ArticleController@recycle');
        Route::get('/admin/article/recycles', 'Admin\ArticleController@recycles');
        Route::get('/admin/article/info', 'Admin\ArticleController@info');
        Route::post('/admin/article/save', 'Admin\ArticleController@save');
        Route::get('/admin/article/filter/{id}', 'Admin\ArticleController@filter');
        //用户管理 成员管理
        Route::get('/admin/user', 'Admin\UserController@index');
        Route::get('/admin/user/users', 'Admin\UserController@users');
        Route::get('/admin/user/delete', 'Admin\UserController@delete');
        Route::get('/admin/user/info', 'Admin\UserController@info');
        Route::get('/admin/user/update', 'Admin\UserController@update');
        Route::get('/admin/user/search', 'Admin\UserController@search');
        Route::get('/admin/user/add', 'Admin\UserController@add');
        //用户管理 黑名单管理
        Route::get('/admin/user/blacklist', 'Admin\UserController@blacklist');
        Route::get('/admin/user/blacklist/users', 'Admin\UserController@blacklistusers');
        Route::get('/admin/user/blacklist/del', 'Admin\UserController@blacklistdel');
        Route::get('/admin/user/blacklist/add', 'Admin\UserController@blacklistadd');
        //精选管理
        Route::get('/admin/star', 'Admin\StarController@index');
        Route::get('/admin/star/list', 'Admin\StarController@starlist');
        Route::get('/admin/star/del', 'Admin\StarController@del');
        Route::get('/admin/star/save', 'Admin\StarController@save');
        Route::get('/admin/star/add', 'Admin\StarController@add');
        Route::get('/admin/star/info', 'Admin\StarController@info');
        Route::get('/admin/star/articles', 'Admin\StarController@articles');
        Route::get('/admin/star/specials', 'Admin\StarController@specials');
        Route::get('/admin/star/order/save', 'Admin\StarController@ordersave');
        //分类管理
        Route::get('/admin/category', 'Admin\CategoryController@index');
        Route::get('/admin/category/list', 'Admin\CategoryController@categories');
        Route::get('/admin/category/display', 'Admin\CategoryController@display');
        Route::get('/admin/category/edit', 'Admin\CategoryController@edit');
        Route::get('/admin/category/add', 'Admin\CategoryController@add');
        Route::get('/admin/category/order/save', 'Admin\CategoryController@ordersave');
        Route::get('/admin/category/delete', 'Admin\CategoryController@delete');
        //专题管理
        Route::get('/admin/special', 'Admin\SpecialController@index');
        Route::get('/admin/special/list', 'Admin\SpecialController@specials');
        Route::get('/admin/special/delete', 'Admin\SpecialController@delete');
        Route::get('/admin/special/articles', 'Admin\SpecialController@articles');
        Route::get('/admin/special/info', 'Admin\SpecialController@info');
        Route::get('/admin/special/save', 'Admin\SpecialController@save');
        Route::get('/admin/special/add', 'Admin\SpecialController@add');
        Route::get('/admin/special/post', 'Admin\SpecialController@post');
        //广告管理
        Route::get('/admin/ad', 'Admin\AdController@index');
        Route::get('/admin/ad/ads', 'Admin\AdController@ads');
        Route::get('/admin/ad/del', 'Admin\AdController@del');
        Route::post('/admin/ad/add', 'Admin\AdController@add');
        Route::post('/admin/ad/update', 'Admin\AdController@update');
        Route::get('/admin/ad/info', 'Admin\AdController@info');
        //评论管理
        Route::get('/admin/comment', 'Admin\CommentController@index');
        Route::get('/admin/comment/comments', 'Admin\CommentController@comments');
        Route::get('/admin/comment/del', 'Admin\CommentController@del');
        //站点管理
        Route::get('/admin/site', 'Admin\SiteController@index');
        Route::get('/admin/site/index', 'Admin\SiteController@index');
        Route::post('/admin/site/base', 'Admin\SiteController@base');
        Route::get('/admin/site/logo', 'Admin\SiteController@logo');
        Route::post('/admin/site/logo', 'Admin\SiteController@logosave');
        Route::get('/admin/site/social', 'Admin\SiteController@social');
        Route::post('/admin/site/social', 'Admin\SiteController@socialsave');
        Route::get('/admin/site/nav', 'Admin\SiteController@nav');
        Route::get('/admin/site/nav/list', 'Admin\SiteController@navlist');
        Route::post('/admin/site/nav/add', 'Admin\SiteController@navadd');
        Route::post('/admin/site/nav/update', 'Admin\SiteController@navupdate');
        Route::get('/admin/site/nav/del', 'Admin\SiteController@navdel');
        Route::get('/admin/site/others', 'Admin\SiteController@others');
        Route::post('/admin/site/others', 'Admin\SiteController@otherssave');
    });
}
/*
 |--------------------------------------------------------------------------
 | 平台路由
 |--------------------------------------------------------------------------
 */
else{
    //平台首页
    Route::get('/','Platform\IndexController@index' );
    //获取文章列表
    Route::get('/index/list','Platform\IndexController@articles' );
    //设备跳转
    Route::group(['middleware' => 'Device'], function () {
        //登录、注册、找回密码
        if (!$_ENV['request_is_mobile']) {
            Route::get('/account/login', 'Account\LoginController@index');
            Route::get('/account/regist', 'Account\RegistController@index');
            Route::get('/account/find', 'Account\FindController@index');
        } else {
            Route::get('/account/login', 'Account\LoginController@mobileindex');
            Route::get('/account/regist', 'Account\RegistController@mobileindex');
            Route::get('/account/find', 'Account\FindController@mobileindex');
        }
    });
    //登录表单
    Route::post('/account/login', 'Account\LoginController@post');
    //注册表单
    Route::post('/account/regist', 'Account\RegistController@post');
    //找回密码表单
    Route::post('/account/find', 'Account\FindController@post');
    //注销
    Route::get('/account/logout', 'Account\AccountController@logout');
    //平台登录状态
    Route::get('/account/status', 'Account\AccountController@status');
    //用户名检查
    Route::post('/account/exist', 'Account\AccountController@exist');
    //发送验证码
    Route::post('/account/captcha', 'Account\AccountController@captcha');

    Route::group(['middleware' => 'AdminAuth'], function () {
        //平台管理->站点管理
        Route::get('/admin', 'Platform\Admin\SiteController@index');
        Route::get('/admin/site', 'Platform\Admin\SiteController@index');
        Route::get('/admin/site/list', 'Platform\Admin\SiteController@sites');
        Route::get('/admin/site/open', 'Platform\Admin\SiteController@open');
        Route::post('/admin/site/add', 'Platform\Admin\SiteController@add');
        //平台管理->用户管理
        Route::get('/admin/user', 'Platform\Admin\UserController@index');
        Route::get('/admin/user/users', 'Platform\Admin\UserController@users');
        Route::get('/admin/user/delete', 'Platform\Admin\UserController@delete');
        Route::get('/admin/user/info', 'Platform\Admin\UserController@info');
        Route::get('/admin/user/search', 'Platform\Admin\UserController@search');
        Route::get('/admin/user/add', 'Platform\Admin\UserController@add');
        Route::get('/admin/user/update', 'Admin\UserController@update');
        //平台管理->平台设置
        Route::get('/admin/option', 'Platform\Admin\NavController@index');
        Route::get('/admin/option/nav', 'Platform\Admin\NavController@index');
        Route::get('/admin/option/nav/list', 'Platform\Admin\NavController@navlist');
        Route::post('/admin/option/nav/save', 'Platform\Admin\NavController@save');
    });
    // 用户
    Route::group(['middleware' => 'User'], function () {
        //个人主页
        Route::get('/user', 'User\IndexController@self');
        //我的收藏
        Route::get('/user/favorite', 'User\FavoriteController@index');
        Route::get('/user/favorites', 'User\FavoriteController@favorites');
        //个人资料设置
        Route::get('/user/profile', 'User\ProfileController@index');
        Route::post('/user/profile', 'User\ProfileController@post');
        //密码重置
        Route::get('/user/password', 'User\PasswordController@index');
        Route::post('/user/password', 'User\PasswordController@post');
        //社交资料
        Route::get('/user/social', 'User\SocialController@index');
        Route::post('/user/social', 'User\SocialController@post');
        //个人文章管理
        Route::get('/user/edit', 'User\EditController@index');
        Route::get('/user/edit/{id}', 'User\EditController@open');
        Route::get('/user/edit/create', 'User\EditController@create');
        //保存文章
        Route::post('/user/article/save', 'User\ApiController@save');
        //删除文章
        Route::get('/user/article/delete', 'User\ApiController@delete');
        //获取文章信息
        Route::get('/user/article', 'User\ApiController@article');
        //获取文章列表
        Route::get('/user/article/list', 'User\ApiController@articles');
        //推送更新到站点
        Route::get('/user/push/site', 'User\ApiController@pushsite');
        //发布到站点
        Route::post('/user/post/site', 'User\ApiController@postsite');
        //发布状态
        Route::get('/user/post/status', 'User\ApiController@poststatus');
        //投稿到站点
        Route::post('/user/contribute', 'User\ApiController@contribute');
        //投稿状态
        Route::get('/user/contribute/status', 'User\ApiController@contributestatus');
        //文章发布列表
        Route::get('/user/post/list', 'User\ApiController@postlist');
        //移除常用站点
        Route::get('/user/site/remove', 'User\ApiController@removesite');
        //添加常用站点
        Route::get('/user/site/add', 'User\ApiController@addsite');
        //搜索站点
        Route::get('/user/site/search', 'User\ApiController@searchsite');
    });
    //用户主页
    Route::get('/user/{id}', 'User\IndexController@index');
    //用户首页文章列表
    Route::get('/user/index/list', 'User\IndexController@articles');
}
/*
 |--------------------------------------------------------------------------
 | 公共路由
 |--------------------------------------------------------------------------
 */
//点赞收藏
Route::get('/social/like', 'Common\SocialController@like');
Route::get('/social/favorite', 'Common\SocialController@favorite');
//站点分类列表
Route::get('/site/category', 'Common\SiteController@category');
//站点列表
Route::get('/site/list', 'Common\SiteController@site');
//极验验证码
Route::get('/geetest/start', 'Common\GeetestController@start');
Route::post('/geetest/verify', 'Common\GeetestController@verify');
//临时 && 测试
Route::get('/temp/device', 'Temp\TempController@device');
//七牛上传
Route::get('/qiniu/upload/token', 'Qiniu\UploadController@token');
