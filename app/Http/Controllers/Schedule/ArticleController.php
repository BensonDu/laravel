<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/4/22
 * Time: 下午6:07
 */

namespace App\Http\Controllers\Schedule;


use App\Http\Controllers\Controller;
use App\Http\Model\ArticlePostModel;
use App\Http\Model\Cache\CacheModel;
use App\Http\Model\Cache\PlatformCacheModel;
use App\Http\Model\Cache\StartCacheModel;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | 发布定时文章
     |--------------------------------------------------------------------------
     */
    public static function PostArticle(){
        $list = PlatformCacheModel::timed_article();
        //清除缓存
        if(!empty($list)){
            foreach ($list as $v){
                CacheModel::clear_article_cache($v['site'],$v['id']);
                ArticlePostModel::timepost($v['id']);
            }
        }
    }
    /*
     |--------------------------------------------------------------------------
     | 文章浏览次数落地记录进文章表
     |--------------------------------------------------------------------------
     */
    public static function ArticleViewCount(){
        $list = PlatformCacheModel::article_view_list();
        foreach ($list as $k => $v){
            DB::table('articles_site')->where('id', $k)->increment('views', intval($v));
        }
        return true;
    }
    /*
     |--------------------------------------------------------------------------
     | 执行保鲜期到期相关文章列表操作
     |--------------------------------------------------------------------------
     */
    public static function ExcuteArticle(){
        $list = StartCacheModel::get_excute_delay();
        if(!empty($list)){
            foreach ($list as $v){
                //等待保鲜期后发布的文章
                $articles = StartCacheModel::get_queue_list($v);
                if(!empty($articles)){
                    foreach ($articles as $kk => $vv){
                        if(!$vv['contribute']){
                            ArticlePostModel::normalpost($v,$kk,$vv['post_status'],$vv['post_time'],$vv['category']);
                        }
                        else{
                            ArticlePostModel::normalpost($v,$kk,0,now(),0);
                        }
                    }
                    StartCacheModel::clear_queue_list($v);
                }
            }
        }
    }
}