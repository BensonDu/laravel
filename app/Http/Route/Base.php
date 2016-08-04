<?php
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
//七牛上传
Route::get('/qiniu/upload/token', 'Qiniu\UploadController@token');
