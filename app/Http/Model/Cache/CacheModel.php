<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/27
 * Time: 下午1:02
 */

namespace App\Http\Model\Cache;

class CacheModel
{
    /*
    |--------------------------------------------------------------------------
    | 清除文章缓存
    |--------------------------------------------------------------------------
    | 包含 文章列表缓存 | 文章数据缓存 | 渠道静态化文章
    |
    */
    public static function clear_article_cache($site_id,$article_id = null){
        SiteCacheModel::home_list_clear($site_id);
        if(!empty($article_id)){
            SiteCacheModel::m_article_view_clear($site_id,$article_id);
            SiteCacheModel::aritcle_del($site_id,$article_id);
            StaticWebModel::clear($site_id,$article_id);
        }
    }

}