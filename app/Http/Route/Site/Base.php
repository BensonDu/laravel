<?php

/*
|--------------------------------------------------------------------------
| 子站基础路由
|--------------------------------------------------------------------------
*/

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