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
//子站路由
if(!strpos(' '.request()->server('HTTP_HOST'),config('site.platform_base'))) {

    //设备跳转
    Route::group(['middleware' => 'Device'], function () {
        //PC站
        if(!is_mobile()){
            //站点首页
            Route::get('/', 'Site\IndexController@index');
            Route::get('/index/list', 'Site\IndexController@articles');
            //文章详情
            Route::get('/{id}', 'Site\DetailController@index');
            Route::get('/{id}.html', 'Site\DetailController@index');
        }
        //M站
        else{
            //站点首页
            Route::get('/','Site\IndexController@mobile');
            Route::get('/index/list', 'Site\IndexController@mobilearticles');
            //文章详情
            Route::get('/{id}', 'Site\DetailController@mobile');
            Route::get('/{id}.html', 'Site\DetailController@mobile');
        }
    });
    //站点专题页
    Route::get('/special', 'Site\SpecialController@home');
    Route::get('/special/{id}', 'Site\SpecialController@index');
    //搜索
    Route::get('/search', 'Site\SearchController@index');
    Route::get('/search/{keyword}', 'Site\SearchController@index');
    Route::get('/search/{keyword}/list', 'Site\SearchController@results');
    //标签
    Route::get('/tag/{tag}', 'Site\TagController@index');
    Route::get('/tag/{tag}/list', 'Site\TagController@tags');

    //登录页面
    Route::get('/account/login', function(){
        return redirect($_ENV['platform']['home'].request()->server('REQUEST_URI'));
    });
    //注册页面
    Route::get('/account/regist', function(){
        return redirect($_ENV['platform']['home'].request()->server('REQUEST_URI'));
    });
    //找回密码页面
    Route::get('/account/find', function(){
        return redirect($_ENV['platform']['home'].request()->server('REQUEST_URI'));
    });
    //注销
    Route::get('/account/logout', 'Account\AccountController@logout');
    //点赞收藏
    Route::get('/social/like', 'Common\SocialController@like');
    Route::get('/social/favorite', 'Common\SocialController@favorite');

    //渠道输出
    Route::get('/feed', 'Feed\IndexController@index');
    Route::get('/feed/toutiao', 'Feed\ToutiaoController@index');
    Route::get('/feed/toutiao/{id}', 'Feed\ToutiaoController@detail');

    Route::get('/feed/xiaozhi', 'Feed\XiaozhiController@index');
    Route::get('/feed/xiaozhi/{id}', 'Feed\XiaozhiController@detail');

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
        //用户管理
        Route::get('/admin/user', 'Admin\UserController@index');
        Route::get('/admin/user/users', 'Admin\UserController@users');
        Route::get('/admin/user/delete', 'Admin\UserController@delete');
        Route::get('/admin/user/info', 'Admin\UserController@info');
        Route::get('/admin/user/update', 'Admin\UserController@update');
        Route::get('/admin/user/search', 'Admin\UserController@search');
        Route::get('/admin/user/add', 'Admin\UserController@add');
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
        Route::get('/admin/category/order/save', 'Admin\CategoryController@ordersave');
        Route::get('/admin/category/del', 'Admin\CategoryController@del');
        Route::get('/admin/category/delete', 'Admin\CategoryController@delete');
        Route::get('/admin/category/edit', 'Admin\CategoryController@edit');
        Route::get('/admin/category/add', 'Admin\CategoryController@add');
        //专题管理
        Route::get('/admin/special', 'Admin\SpecialController@index');
        Route::get('/admin/special/list', 'Admin\SpecialController@specials');
        Route::get('/admin/special/delete', 'Admin\SpecialController@delete');
        Route::get('/admin/special/articles', 'Admin\SpecialController@articles');
        Route::get('/admin/special/info', 'Admin\SpecialController@info');
        Route::get('/admin/special/save', 'Admin\SpecialController@save');
        Route::get('/admin/special/add', 'Admin\SpecialController@add');
        Route::get('/admin/special/post', 'Admin\SpecialController@post');
    });
}
//平台部分
else{
    //平台首页
    Route::get('/', function(){
        return redirect('http://crababy.com');
    });
    //点赞收藏
    Route::get('/social/like', 'Common\SocialController@like');
    Route::get('/social/favorite', 'Common\SocialController@favorite');
    //登录页面
    Route::get('/account/login', 'Account\LoginController@index');
    Route::post('/account/login', 'Account\LoginController@post');
    //注册页面
    Route::get('/account/regist', 'Account\RegistController@index');
    Route::post('/account/regist', 'Account\RegistController@post');
    //注销
    Route::get('/account/logout', 'Account\AccountController@logout');
    //用户名检查
    Route::post('/account/exist', 'Account\AccountController@exist');
    //发送验证码
    Route::post('/account/captcha', 'Account\AccountController@captcha');
    //找回密码页面
    Route::get('/account/find', 'Account\FindController@index');
    Route::post('/account/find', 'Account\FindController@post');
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
        Route::post('/user/save', 'User\EditController@save');
        //删除文章
        Route::get('/user/article/delete', 'User\EditController@delete');
        //获取文章信息
        Route::get('/user/article', 'User\EditController@article');
        //获取文章列表
        Route::get('/user/article/list', 'User\EditController@articles');
        //发布个人主页
        Route::post('/user/post', 'User\EditController@post');
        Route::post('/user/post/cancel', 'User\EditController@cancel');
        //投稿到站点
        Route::get('/user/contribute', 'User\EditController@contribute');
    });
    //用户主页
    Route::get('/user/{id}', 'User\IndexController@index');
    //用户首页文章列表
    Route::get('/user/index/list', 'User\IndexController@articles');
    //用户文章详情
    Route::get('/user/{id}/{articleid}', 'User\DetailController@index');
}

//数据导入
Route::get('/import/auto', 'DataImport@auto');
//七牛上传
Route::get('/qiniu/upload/token', 'Qiniu\UploadController@token');
