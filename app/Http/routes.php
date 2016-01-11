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
//站点专题页
Route::get('/special', 'Site\SpecialController@index');
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
    //个人资料设置
    Route::get('/user/profile', 'User\ProfileController@index');
    Route::post('/user/profile', 'User\ProfileController@post');
//测试
    Route::get('/user/test', 'User\ProfileController@test');
//密码重置
    Route::get('/user/password', 'User\PasswordController@index');
    Route::post('/user/password', 'User\PasswordController@post');
//社交资料
    Route::get('/user/social', 'User\SocialController@index');
    Route::post('/user/social', 'User\SocialController@post');
//个人文章管理
    Route::get('/user/edit', 'User\EditController@index');
//个人主页
});
Route::get('/user/{id}', 'User\IndexController@index');

//数据导入
Route::get('/import/auto', 'DataImport@auto');
