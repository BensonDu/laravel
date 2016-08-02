<?php

/*
 |--------------------------------------------------------------------------
 | 平台PC设备路由
 |--------------------------------------------------------------------------
 */

//登录、注册、找回密码
Route::group(['middleware' => 'Device'], function () {
    //平台首页
    Route::get('/','Platform\IndexController@index' );
    Route::get('/account/login', 'Account\LoginController@index');
    Route::get('/account/regist', 'Account\RegistController@index');
    Route::get('/account/find', 'Account\FindController@index');
});