<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/27
 * Time: 下午12:56
 */

namespace App\Http\Model\Cache;


class SiteCacheModel extends BaseModel
{



    /*
    |--------------------------------------------------------------------------
    | 获取文章缓存
    |--------------------------------------------------------------------------
    */
    public static function aritcle_get($site_id,$id){
        return self::hget(self::key($site_id),$id,true);
    }
    /*
    |--------------------------------------------------------------------------
    | 设置文章缓存
    |--------------------------------------------------------------------------
    */
    public static function aritcle_set($site_id,$id,$data){
        return self::hset(self::key($site_id),$id,$data,600);
    }
    /*
    |--------------------------------------------------------------------------
    | 删除文章缓存
    |--------------------------------------------------------------------------
    */
    public static function aritcle_del($site_id,$id){
        return self::hdel(self::key($site_id),$id);
    }
    /*
    |--------------------------------------------------------------------------
    | 清除站点文章缓存
    |--------------------------------------------------------------------------
    */
    public static function article_clear($site_id){
        return self::del(self::key($site_id));
    }
    /*
    |--------------------------------------------------------------------------
    | 生成文章缓存Key
    |--------------------------------------------------------------------------
    */
    private static function key($site_id){
        return config('cache.prefix').':'. config('cache.site.article').':'.$site_id;
    }



    /*
    |--------------------------------------------------------------------------
    | 获取热榜缓存
    |--------------------------------------------------------------------------
    */
    public static function hot_get($site_id){
        return self::get(self::hot_key($site_id),true);
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
    | 首页文章列表缓存
    |--------------------------------------------------------------------------
    */
    public static function home_list_get($site_id,$skip,$take,$category){
        return self::hget(self::home_list_key($site_id),self::home_list_field($skip,$take,$category),true);
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
    | 获取首页文章列表 缓存清除
    |--------------------------------------------------------------------------
    */
    public static function home_list_clear($site_id){
        return self::del(self::home_list_key($site_id));
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
    | 子站M站文章视图缓存 获取
    |--------------------------------------------------------------------------
    */
    public static function m_article_view($site_id,$article_id){
        return self::hget(self::m_article_view_key($site_id),$article_id);
    }
    /*
    |--------------------------------------------------------------------------
    | 子站M站文章视图缓存 设置
    |--------------------------------------------------------------------------
    */
    public static function m_article_view_set($site_id,$article_id,$view){
        return self::hset(self::m_article_view_key($site_id),$article_id,$view,300);
    }
    /*
    |--------------------------------------------------------------------------
    | 子站M站文章视图缓存 删除
    |--------------------------------------------------------------------------
    */
    public static function m_article_view_clear($site_id,$article_id){
        return self::hdel(self::m_article_view_key($site_id),$article_id);
    }
    /*
    |--------------------------------------------------------------------------
    | 子站M站文章视图缓存 KEY
    |--------------------------------------------------------------------------
    */
    public static function m_article_view_key($site_id){
        return config('cache.prefix').':'.config('cache.site.view.m.article').':'.$site_id;
    }


    /*
    |--------------------------------------------------------------------------
    | 获取站点信息缓存
    |--------------------------------------------------------------------------
    */
    public static function site_info_get($site_id){
        return self::get(self::site_info_key($site_id),true);
    }
    /*
    |--------------------------------------------------------------------------
    | 设置站点信息缓存
    |--------------------------------------------------------------------------
    */
    public static function site_info_set($site_id,$data){
        return self::set(self::site_info_key($site_id),$data,600);
    }
    /*
    |--------------------------------------------------------------------------
    | 清除站点信息缓存
    |--------------------------------------------------------------------------
    */
    public static function site_info_clear($site_id){
        return self::del(self::site_info_key($site_id));
    }
    /*
    |--------------------------------------------------------------------------
    | 站点信息缓存 KEY
    |--------------------------------------------------------------------------
    */
    public static function site_info_key($site_id){
        return config('cache.prefix').':'.config('cache.site.info').':'.$site_id;
    }


    /*
    |--------------------------------------------------------------------------
    | 获取站点导航缓存
    |--------------------------------------------------------------------------
    */
    public static function site_nav_get($site_id){
        return self::get(self::site_nav_key($site_id),true);
    }
    /*
    |--------------------------------------------------------------------------
    | 设置站点导航缓存
    |--------------------------------------------------------------------------
    */
    public static function site_nav_set($site_id,$data){
        return self::set(self::site_nav_key($site_id),$data,600);
    }
    /*
    |--------------------------------------------------------------------------
    | 清除站点导航缓存
    |--------------------------------------------------------------------------
    */
    public static function site_nav_clear($site_id){
        return self::del(self::site_nav_key($site_id));
    }
    /*
    |--------------------------------------------------------------------------
    | 站点导航缓存 KEY
    |--------------------------------------------------------------------------
    */
    public static function site_nav_key($site_id){
        return config('cache.prefix').':'.config('cache.site.nav').':'.$site_id;
    }


    /*
    |--------------------------------------------------------------------------
    | 站点路由缓存 清除
    |--------------------------------------------------------------------------
    */
    public static function site_route_clear(){
        return self::del(self::site_route_key());
    }
    /*
    |--------------------------------------------------------------------------
    | 站点路由缓存 KEY
    |--------------------------------------------------------------------------
    */
    private static function site_route_key(){
        return config('site.platform_redis_prefix').':platform:'.config('site.platform_redis_routingtable_key');
    }
}