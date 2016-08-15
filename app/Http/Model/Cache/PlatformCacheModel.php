<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/27
 * Time: 下午12:56
 */

namespace App\Http\Model\Cache;

/*
|--------------------------------------------------------------------------
| 平台缓存模块
|--------------------------------------------------------------------------
*/
class PlatformCacheModel extends BaseModel
{

    /*
    |--------------------------------------------------------------------------
    | 文章浏览次数 +1  对于各个文章的访问计数 实时缓存 计划任务写入数据库
    |--------------------------------------------------------------------------
    */
    public static function article_view_increase($id){
        return self::hincrby(self::article_view_count_key(),$id);
    }
    /*
    |--------------------------------------------------------------------------
    | 返回文章缓存的浏览次数 列表
    |--------------------------------------------------------------------------
    */
    public static function article_view_list(){
        $key = self::article_view_count_key();
        $list = self::hgetall($key);
        self::del($key);
        $ret = [];
        if(!empty($list)){
            foreach ($list as $k => $v){
                if(!empty($v))$ret[$k] = $v;
            }
        }
        return $ret;
    }
    /*
    |--------------------------------------------------------------------------
    | 文章浏览计数 KEY
    |--------------------------------------------------------------------------
    */
    public static function article_view_count_key(){
        return config('cache.prefix').':'.config('cache.platform.article.view');
    }



    /*
    |--------------------------------------------------------------------------
    | 子站首页浏览总量 +1  对于各个站点首页访问量的统计
    |--------------------------------------------------------------------------
    */
    public static function site_home_view_increase($id){
        return self::incrby(self::site_home_view_count_key($id));
    }
    /*
    |--------------------------------------------------------------------------
    | 获得子站首页浏览总量
    |--------------------------------------------------------------------------
    */
    public static function site_home_view($id){
        return self::get(self::site_home_view_count_key($id));
    }
    /*
    |--------------------------------------------------------------------------
    | 子站首页浏览总量 KEY
    |--------------------------------------------------------------------------
    */
    public static function site_home_view_count_key($site_id){
        return config('cache.prefix').':'.config('cache.platform.view.home').':'.$site_id;
    }



    /*
    |--------------------------------------------------------------------------
    | 子站文章浏览总量 +1 对于各个站点所有文章访问量总和的统计
    |--------------------------------------------------------------------------
    */
    public static function site_article_view_increase($id){
        return self::incrby(self::site_article_view_count_key($id));
    }
    /*
    |--------------------------------------------------------------------------
    | 获得子站文章浏览总量
    |--------------------------------------------------------------------------
    */
    public static function site_article_view($id){
        return self::get(self::site_article_view_count_key($id));
    }
    /*
    |--------------------------------------------------------------------------
    | 子站文章浏览总量 KEY
    |--------------------------------------------------------------------------
    */
    public static function site_article_view_count_key($site_id){
        return config('cache.prefix').':'.config('cache.platform.view.article').':'.$site_id;
    }



    /*
    |--------------------------------------------------------------------------
    | 首页文章列表缓存
    |--------------------------------------------------------------------------
    */
    public static function index_list_get($skip,$orderby,$width){
        return self::hget(self::index_list_key(),self::index_list_field($skip,$orderby,$width),true);
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