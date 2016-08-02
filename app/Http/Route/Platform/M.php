<?php

/*
 |--------------------------------------------------------------------------
 | 平台移动设备路由
 |--------------------------------------------------------------------------
 */

//登录注册找回密码
Route::group(['middleware' => 'Device'], function () {
    //平台首页
    Route::get('/','Platform\IndexController@mobile' );
    Route::get('/account/login', 'Account\LoginController@mobileindex');
    Route::get('/account/regist', 'Account\RegistController@mobileindex');
    Route::get('/account/find', 'Account\FindController@mobileindex');
});
