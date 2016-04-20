<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/27
 * Time: 下午12:56
 */

namespace App\Http\Model\Cache;


class SiteCacheModel extends RedisModel
{
    /*
    |--------------------------------------------------------------------------
    | 文章缓存是否存在
    |--------------------------------------------------------------------------
    */
    public static function article_exists($site_id,$id){
        return self::exists(self::key($site_id,$id));
    }
    /*
    |--------------------------------------------------------------------------
    | 获取文章缓存
    |--------------------------------------------------------------------------
    */
    public static function aritcle_get($site_id,$id){
        return self::get(self::key($site_id,$id));
    }
    /*
    |--------------------------------------------------------------------------
    | 设置文章缓存
    |--------------------------------------------------------------------------
    */
    public static function aritcle_set($site_id,$id,$data){
        return self::set(self::key($site_id,$id),$data,600);
    }
    /*
    |--------------------------------------------------------------------------
    | 删除文章缓存
    |--------------------------------------------------------------------------
    */
    public static function aritcle_del($site_id,$id){
        return self::del(self::key($site_id,$id));
    }
    /*
    |--------------------------------------------------------------------------
    | 生成文章缓存Key
    |--------------------------------------------------------------------------
    */
    private static function key($site_id,$id){
        return config('cache.prefix').':'. config('cache.site.article').':'.$site_id.':'.$id;
    }
    /*
    |--------------------------------------------------------------------------
    | 获取热榜缓存
    |--------------------------------------------------------------------------
    */
    public static function hot_get($site_id){
        return self::get(self::hot_key($site_id));
    }
    /*
    |--------------------------------------------------------------------------
    | 设置热榜缓存
    |--------------------------------------------------------------------------
    */
    public static function hot_set($site_id,$data){
        return self::set(self::hot_key($site_id),$data,300);
    }
    /*
    |--------------------------------------------------------------------------
    | 获取热榜缓存 Key
    |--------------------------------------------------------------------------
    */
    private static function hot_key($site_id){
        return config('cache.prefix').':'. config('cache.site.hot').':'.$site_id;
    }
    /*
    |--------------------------------------------------------------------------
    | 首页文章列表缓存 是否存在
    |--------------------------------------------------------------------------
    */
    public static function home_list_exists($site_id,$skip,$take,$category){
        return self::hexists(self::home_list_key($site_id),self::home_list_field($skip,$take,$category));
    }
    /*
    |--------------------------------------------------------------------------
    | 首页文章列表缓存
    |--------------------------------------------------------------------------
    */
    public static function home_list_get($site_id,$skip,$take,$category){
        return self::hget(self::home_list_key($site_id),self::home_list_field($skip,$take,$category));
    }
    /*
    |--------------------------------------------------------------------------
    | 设置首页文章列表缓存
    |--------------------------------------------------------------------------
    */
    public static function home_list_set($site_id,$skip,$take,$category,$data){
        return self::hset(self::home_list_key($site_id),self::home_list_field($skip,$take,$category),$data,600);
    }
    /*
    |--------------------------------------------------------------------------
    | 获取首页文章列表 key
    |--------------------------------------------------------------------------
    */
    private static function home_list_key($site_id){
        return config('cache.prefix').':'. config('cache.site.home').':'.$site_id;
    }
    /*
    |--------------------------------------------------------------------------
    | 获取首页文章列表 field
    |--------------------------------------------------------------------------
    */
    private static function home_list_field($skip,$take,$category){
        return $skip.':'.$take.':'.$category;
    }
    /*
    |--------------------------------------------------------------------------
    | 获取首页文章列表 缓存清除
    |--------------------------------------------------------------------------
    */
    public static function home_list_clear($site_id){
        return self::del(self::home_list_key($site_id));
    }
}