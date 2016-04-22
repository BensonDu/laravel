<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/4/22
 * Time: 下午6:07
 */

namespace App\Http\Controllers\Schedule;


use App\Http\Model\Admin\ArticleModel;
use App\Http\Model\Cache\CacheModel;
use App\Http\Model\Cache\PlatformCacheModel;

class ArticleController
{
    /*
     |--------------------------------------------------------------------------
     | 发布定时文章
     |--------------------------------------------------------------------------
     */
    public static function PostArticle(){
        $list = PlatformCacheModel::timed_article();
        $ids = [];
        //清除缓存
        if(!empty($list)){
            foreach ($list as $v){
                CacheModel::clear_article_cache($v['site'],$v['id']);
                $ids[] = $v['id'];
            }
        }
        return ArticleModel::batch_article_post($ids);
    }

}