<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/27
 * Time: 下午12:56
 */

namespace App\Http\Model\Cache;


class PlatformCacheModel extends RedisModel
{
    /*
    |--------------------------------------------------------------------------
    | 添加文章定时发布到队列
    |--------------------------------------------------------------------------
    | 文章ID  string     $id
    | 发布时间 timestamp $time
    */
    public static function timing_article($site_id,$id,$time){
        $list =  self::get(self::timing_article_key());
        $list = (!empty($list) && is_array($list)) ? $list : [];
        $list[$id] = [
            'site' => $site_id,
            'time' => $time
        ];
        return self::set(self::timing_article_key(),$list,null);
    }
    /*
    |--------------------------------------------------------------------------
    | 清除文章定时发布
    |--------------------------------------------------------------------------
    | 文章ID  string     $id
    | 发布时间 timestamp $time
    */
    public static function timing_clear($id){
        $list =  self::get(self::timing_article_key());
        if(is_array($list) && isset($list[$id])){
            $new = [];
            foreach ($list as $k => $v){
                if( $k != $id )$new[$k] = $v;
            }
            self::set(self::timing_article_key(),$new,null);
        }
        return true;
    }
    /*
    |--------------------------------------------------------------------------
    | 获得到期 待发布 文章列表
    |--------------------------------------------------------------------------
    */
    public static function timed_article(){
        $list =  self::get(self::timing_article_key());
        $ret = [];
        $new = [];
        $now = time();
        if(!empty($list) && is_array($list)){
            foreach ($list as $k => $v){
                if(strtotime($v['time']) <= $now){
                    $ret[]   = [
                        'site' => $v['site'],
                        'id'   => $k,
                    ];
                }
                else{
                    $new[$k] = $v;
                }
            }
            if(!empty($ret)) self::set(self::timing_article_key(),$new,null);
        }
        return $ret;
    }
    /*
    |--------------------------------------------------------------------------
    | 获得文章定时发布队列KEY
    |--------------------------------------------------------------------------
    */
    public static function timing_article_key(){
        return config('cache.prefix').':'. config('cache.platform.timing.article.site');
    }
    /*
    |--------------------------------------------------------------------------
    | 文章浏览次数 +1
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
}