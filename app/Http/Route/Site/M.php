<?php

/*
 |--------------------------------------------------------------------------
 | 子站移动设备路由
 |--------------------------------------------------------------------------
 */

Route::group(['middleware' => 'Device'], function () {
    //站点首页
    Route::get('/','Site\IndexController@mobile');
    Route::get('/index/list', 'Site\IndexController@mobilearticles');
    //文章详情
    Route::get('/{id}', 'Site\DetailController@mobile');
    Route::get('/{id}.html', 'Site\DetailController@mobile');
    //站点专题页
    Route::get('/special', 'Site\SpecialController@mobileindex');
    Route::get('/special/{id}', 'Site\SpecialController@mobiledetail');
});

