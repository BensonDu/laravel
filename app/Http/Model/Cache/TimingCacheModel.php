<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/27
 * Time: 下午12:56
 */

namespace App\Http\Model\Cache;

use PRedis;

/*
|--------------------------------------------------------------------------
| 文章定时发布模块
|--------------------------------------------------------------------------
*/
class TimingCacheModel extends BaseModel
{
    /*
    |--------------------------------------------------------------------------
    | 添加文章定时发布到队列
    |--------------------------------------------------------------------------
    | 文章ID  string     $id
    | 发布时间 timestamp $time
    */
    public static function add($site_id,$id,$time){
        $list =  self::get(self::key(),true);
        $list = (!empty($list) && is_array($list)) ? $list : [];
        $list[$id] = [
            'site' => $site_id,
            'time' => $time
        ];
        return self::set(self::key(),$list,null);
    }
    /*
    |--------------------------------------------------------------------------
    | 清除文章定时发布
    |--------------------------------------------------------------------------
    | 文章ID  string     $id
    | 发布时间 timestamp $time
    */
    public static function clear($id){
        $list =  self::get(self::key(),true);
        if(is_array($list) && isset($list[$id])){
            $new = [];
            foreach ($list as $k => $v){
                if( $k != $id )$new[$k] = $v;
            }
            self::set(self::key(),$new,null);
        }
        return true;
    }
    /*
    |--------------------------------------------------------------------------
    | 获得到期 待发布 文章列表
    |--------------------------------------------------------------------------
    */
    public static function articles(){
        $list =  self::get(self::key(),true);
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
            if(!empty($ret)) self::set(self::key(),$new,null);
        }
        return $ret;
    }
    /*
    |--------------------------------------------------------------------------
    | 获得文章定时发布队列KEY
    |--------------------------------------------------------------------------
    */
    public static function key(){
        return config('cache.prefix').':'. config('cache.platform.timing.article.site');
    }

}