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
Route::get('/', function(){
    return view('site/index');
});
Route::get('/special', function(){
    return view('site/special');
});
Route::get('/{id}', function(){
    return view('site/detail');
});
Route::get('/user/{id}', function(){
    return view('user/index');
});
Route::get('/user/edit', function(){
    return view('user/edit');
});
Route::get('/user/profile', function(){
    return view('user/profile');
});
Route::get('/user/password', function(){
    return view('user/password');
});
Route::get('/account/login', function(){
    return view('account/login');
});
Route::get('/account/regist', function(){
    return view('account/regist');
});
Route::get('/account/find', function(){
    return view('account/find');
});
//数据导入
Route::get('/import/auto', 'DataImport@auto');
