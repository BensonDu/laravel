<?php
/**
 * 平台首页文章列表缓存
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/7/18
 * Time: 下午12:56
 */

namespace App\Http\Model\Cache;

class PlatformIndexCacheModel extends RedisModel
{
    /*
    |--------------------------------------------------------------------------
    | 首页文章列表缓存
    |--------------------------------------------------------------------------
    */
    public static function index_list_get($skip,$orderby,$width){
        return self::hget(self::index_list_key(),self::index_list_field($skip,$orderby,$width));
    }
    /*
    |--------------------------------------------------------------------------
    | 设置首页文章列表缓存
    |--------------------------------------------------------------------------
    */
    public static function index_list_set($skip,$orderby,$width,$data){
        return self::hset(self::index_list_key(),self::index_list_field($skip,$orderby,$width),$data,600);
    }
    /*
    |--------------------------------------------------------------------------
    | 获取首页文章列表 缓存清除
    |--------------------------------------------------------------------------
    */
    public static function index_list_clear(){
        return self::del(self::index_list_key());
    }
    /*
    |--------------------------------------------------------------------------
    | 获取首页文章列表 key
    |--------------------------------------------------------------------------
    */
    private static function index_list_key(){
        return config('cache.prefix').':'. config('cache.platform.article.index');
    }
    /*
    |--------------------------------------------------------------------------
    | 获取首页文章列表 field
    |--------------------------------------------------------------------------
    */
    private static function index_list_field($skip,$orderby,$width){
        return $skip.':'.$orderby.':'.$width;
    }
}