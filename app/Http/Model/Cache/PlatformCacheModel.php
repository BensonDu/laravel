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
}