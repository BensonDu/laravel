<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/6/29
 * Time: 下午12:56
 */

namespace App\Http\Model\Cache;

use PRedis;

class StartCacheModel extends RedisModel
{

    /*
    |--------------------------------------------------------------------------
    | 获得文章等待执行队列
    |--------------------------------------------------------------------------
    */
    public static function get_queue_list($article_id){
        $key = self::queue_key($article_id);
        $all = PRedis::hgetall($key);
        if(!empty($all) && is_array($all)){
            $all = array_map(function($v){
                return json_decode($v,1);
            },$all);
        }
        return $all;
    }
    /*
    |--------------------------------------------------------------------------
    | 清空文章等待执行队列
    |--------------------------------------------------------------------------
    */
    public static function clear_queue_list($article_id){
        $key = self::queue_key($article_id);
        return self::del($key);
    }
    /*
    |--------------------------------------------------------------------------
    | 获得等待执行队列中文章发布信息
    |--------------------------------------------------------------------------
    */
    public static function get_queue_info($article_id,$site_id){
        $key = self::queue_key($article_id);
        return json_decode(PRedis::hget($key,$site_id),1);
    }
    /*
    |--------------------------------------------------------------------------
    | 删除文章等待执行队列
    |--------------------------------------------------------------------------
    */
    public static function del_queue_list($article_id,$site_id){
        $key = self::queue_key($article_id);
        return self::hdel($key,$site_id);
    }
    /*
    |--------------------------------------------------------------------------
    | 设置任务到文章等待执行队列
    |--------------------------------------------------------------------------
    */
    public static function set_queue_list($article_id,$site_id,$post_status = 0,$post_time = 0,$category = null){
        $key = self::queue_key($article_id);
        PRedis::hset($key,$site_id,json_encode([
            'contribute'    => is_null($category),
            'category'      => is_null($category) ? 0 : $category,
            'post_status'   => $post_status,
            'post_time'     => $post_time
        ]));
        //总首发时长不超过一周,防止垃圾数据生成;
        PRedis::expire($key,60*60*24*7);
    }
    /*
    |--------------------------------------------------------------------------
    | 获得到期文章延迟执行
    |--------------------------------------------------------------------------
    */
    public static function get_excute_delay(){
        $key = self::excute_key();
        $all = PRedis::hgetall($key);
        $ret = [];
        $now = time();
        if(!empty($all)){
            foreach ($all as $k => $v){
                if(strtotime($v) <= $now){
                    $ret[] = $k;
                    self::del_excute_delay($k);
                }
            }
        }
        return $ret;
    }
    /*
    |--------------------------------------------------------------------------
    | 设置文章延迟执行
    |--------------------------------------------------------------------------
    */
    public static function set_excute_delay($article_id,$time){
        $key = self::excute_key();
        return PRedis::hset($key,$article_id,$time);
    }
    /*
    |--------------------------------------------------------------------------
    | 删除文章延迟执行
    |--------------------------------------------------------------------------
    */
    public static function del_excute_delay($article_id){
        $key = self::excute_key();
        return self::hdel($key,$article_id);
    }
    /*
    |--------------------------------------------------------------------------
    | 站点首发文章等待保鲜期过后执行发布队列KEY
    |--------------------------------------------------------------------------
    */
    public static function queue_key($article_id){
        return config('cache.prefix').':'.config('cache.platform.start.queue').':'.$article_id;
    }
    /*
    |--------------------------------------------------------------------------
    | 站点首发文章等待保鲜期过后发布队列KEY
    |--------------------------------------------------------------------------
    */
    public static function excute_key(){
        return config('cache.prefix').':'.config('cache.platform.start.excute');
    }

}