<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/27
 * Time: 下午1:02
 */

namespace App\Http\Model\Cache;

use PRedis;


class CacheModel
{
    /*
    |--------------------------------------------------------------------------
    | 获取String缓存
    |--------------------------------------------------------------------------
    */
    public static function get($key){
        $ret = null;
        if(self::exists($key))$ret = unserialize(PRedis::get($key));
        return $ret;
    }
    /*
    |--------------------------------------------------------------------------
    | 设置String缓存
    |--------------------------------------------------------------------------
    */
    public static function set($key,$data = [],$ttl = 300){
        return PRedis::setex($key,$ttl,serialize($data));
    }
    /*
    |--------------------------------------------------------------------------
    | 删除String缓存
    |--------------------------------------------------------------------------
    */
    public static function del($key){
        return PRedis::del($key);
    }
    /*
    |--------------------------------------------------------------------------
    | 缓存是否存在
    |--------------------------------------------------------------------------
    */
    public static function exists($key){
        return PRedis::exists($key);
    }
    /*
    |--------------------------------------------------------------------------
    | 设置缓存生存时间
    |--------------------------------------------------------------------------
    */
    public static function expire($key,$ttl){
        return PRedis::expire($key,$ttl);
    }
    /*
    |--------------------------------------------------------------------------
    | 选取缓存数据库
    |--------------------------------------------------------------------------
    */
    public static function select($index = 0){
        return PRedis::select($index);
    }
    /*
    |--------------------------------------------------------------------------
    | 设置Hash缓存
    |--------------------------------------------------------------------------
    */
    public static function hset($key,$field,$val,$ttl = null){
        PRedis::hset($key,$field,serialize($val));
        if(!is_null($ttl))self::expire($key,$ttl);
        return true;
    }
    /*
    |--------------------------------------------------------------------------
    | 获取Hash缓存
    |--------------------------------------------------------------------------
    */
    public static function hget($key,$field){
        return unserialize(PRedis::hget($key,$field));
    }
    /*
    |--------------------------------------------------------------------------
    | Hash缓存是否存在
    |--------------------------------------------------------------------------
    */
    public static function hexists($key,$field){
        return PRedis::hexists($key,$field);
    }

}