<?php
/*
 |--------------------------------------------------------------------------
 | 子站PC设备路由
 |--------------------------------------------------------------------------
 */
Route::group(['middleware' => 'Device'], function () {
    //站点首页
    Route::get('/', 'Site\IndexController@index');
    Route::get('/index/list', 'Site\IndexController@articles');
    //文章详情
    Route::get('/{id}', 'Site\DetailController@index');
    Route::get('/{id}.html', 'Site\DetailController@index');
    //站点专题页
    Route::get('/special', 'Site\SpecialController@index');
    Route::get('/special/{id}', 'Site\SpecialController@detail');
});