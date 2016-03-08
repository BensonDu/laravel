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
//站点首页
Route::get('/', 'Site\IndexController@index');
Route::get('/index/list', 'Site\IndexController@articles');
Route::get('/test', 'Admin\StarController@test');
//搜索
Route::get('/search', 'Site\SearchController@index');
Route::get('/search/{keyword}', 'Site\SearchController@index');
Route::get('/search/{keyword}/list', 'Site\SearchController@results');
//标签
Route::get('/tag/{tag}', 'Site\TagController@index');
Route::get('/tag/{tag}/list', 'Site\TagController@tags');
//站点专题页
Route::get('/special', 'Site\SpecialController@home');
Route::get('/special/{id}', 'Site\SpecialController@index');
//文章详情
Route::get('/{id}', 'Site\DetailController@index');

//登录页面
Route::get('/account/login', 'Account\LoginController@index');
Route::post('/account/login','Account\LoginController@post');
//注册页面
Route::get('/account/regist', 'Account\RegistController@index');
Route::post('/account/regist', 'Account\RegistController@post');
//注销
Route::get('/account/logout', 'Account\AccountController@logout');
//用户名检查
Route::post('/account/exist','Account\AccountController@exist');
//发送验证码
Route::post('/account/captcha','Account\AccountController@captcha');
//找回密码页面
Route::get('/account/find', 'Account\FindController@index');
Route::post('/account/find', 'Account\FindController@post');

Route::group(['middleware' => 'User'], function () {
    //个人主页
    Route::get('/user', 'User\IndexController@self');
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
    Route::get('/user/article/list', 'User\EditController@article_list');
    //发布个人主页
    Route::post('/user/post', 'User\EditController@post');
    Route::post('/user/post/cancel', 'User\EditController@cancel');
    //投稿到站点
    Route::post('/user/contribute', 'User\EditController@contribute');
});
//用户主页
Route::get('/user/{id}', 'User\IndexController@index');
//用户首页文章列表
Route::get('/user/index/list', 'User\IndexController@article_list');
//用户文章详情
Route::get('/user/{id}/{articleid}', 'User\DetailController@index');

//站点管理
Route::group(['middleware' => 'AdminAuth'], function (){
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
});

//数据导入
Route::get('/import/auto', 'DataImport@auto');
