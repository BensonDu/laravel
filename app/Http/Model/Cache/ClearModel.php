<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/27
 * Time: 下午1:02
 */

namespace App\Http\Model\Cache;

class ClearModel
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
        PlatformCacheModel::index_list_clear();
        if(!empty($article_id)){
            SiteCacheModel::m_article_view_del($site_id,$article_id);
            SiteCacheModel::aritcle_del($site_id,$article_id);
            StaticWebModel::clear($site_id,$article_id);
        }
    }
    /*
    |--------------------------------------------------------------------------
    | 清除站点缓存
    |--------------------------------------------------------------------------
    |
    */
    public static function clear_site_cache($site_id){

        //清除平台首页文章列表缓存
        PlatformCacheModel::index_list_clear();
        //清除站点信息缓存
        SiteCacheModel::site_info_clear($site_id);
        //清除站点文章列表缓存
        SiteCacheModel::home_list_clear($site_id);
        //清除M站文章页面缓存
        SiteCacheModel::m_article_view_clear($site_id);
        //清除站点导航缓存
        SiteCacheModel::site_nav_clear($site_id);
        //清除所有文章缓存
        SiteCacheModel::article_clear($site_id);
        //清除平台路由缓存
        SiteCacheModel::site_route_clear();
        //清除热榜文章缓存
        SiteCacheModel::hot_del($site_id);
        //清除站点静态化页面缓存
        StaticWebModel::clear_site_all($site_id);

    }

}