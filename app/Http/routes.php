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
Route::get('/user/{id}/admin', function(){
    return view('user/admin');
});
//数据导入
Route::get('/import/auto', 'DataImport@auto');