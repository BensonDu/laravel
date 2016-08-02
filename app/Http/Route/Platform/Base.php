<?php

/*
 |--------------------------------------------------------------------------
 | 平台路由
 |--------------------------------------------------------------------------
 */
//获取文章列表
Route::get('/index/list','Platform\IndexController@articles' );
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

//搜索
Route::get('/search', 'Platform\SearchController@index');
Route::get('/search/results', 'Platform\SearchController@results');
//标签
Route::get('/tag/{tag}', 'Platform\TagController@index');
Route::get('/tag/{tag}/list', 'Platform\TagController@tags');

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
