<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/8
 * Time: 下午7:52
 */

namespace App\Http\Controllers\Site;


use App\Http\Model\AdModel;
use App\Http\Model\ArticleSiteModel;
use App\Http\Model\ArticleSocialModel;
use App\Http\Model\Cache\PlatformCacheModel;
use App\Http\Model\Cache\SiteCacheModel;

class DetailController extends SiteController
{
    public function __construct(){
        parent::__construct();
    }

    public function index($id){
        $info = ArticleSiteModel::get_artilce_detail($_ENV['site_id'],$id);

        if(empty($info))abort(404);
        $tag = tag($info->tags);
        $data['base']['title']      = $info->title;
        $data['base']['keywords']   = implode(', ',$tag);
        $data['user'] = [
            'id'    => $info->user_id,
            'name'  => $info->nickname,
            'avatar'=>avatar($info->avatar)
        ];

        //广告
        $data['ad'] = AdModel::get_article_ad($_ENV['site_id']);

        $data['article'] = [
            'title'     => $info->title,
            'summary'   => $info->summary,
            'content'   => $info->content,
            'tags'      => $tag,
            'time'      => date('Y年m月d日',strtotime($info->post_time)),
            'category'  => $info->category_name,
            'image'     => image_crop($info->image,950),
            'like'      => !empty($_ENV['uid']) ? !!ArticleSocialModel::check_is_like($id,$_ENV['uid']) : false,
            'favorite'  => !empty($_ENV['uid']) ? !!ArticleSocialModel::check_is_favorite($id,$_ENV['uid']) : false
        ];
        //子站文章浏览总量+1
        PlatformCacheModel::site_article_view_increase($_ENV['site_id']);
        return self::view('site.detail',$data);
    }
    public function mobile($id){
        //调取缓存
        $cache = SiteCacheModel::m_article_view($_ENV['site_id'],$id);
        if(!empty($cache))return $cache;

        $info = ArticleSiteModel::get_artilce_detail($_ENV['site_id'],$id);

        if(empty($info))abort(404);
        $tag = tag($info->tags);
        $data['base']['title'] = $info->title;
        $data['base']['keywords']   = implode(', ',$tag);

        $data['user'] = [
            'id'    => $info->user_id,
            'name'  => $info->nickname,
            'avatar'=>avatar($info->avatar)
        ];

        $data['article'] = [
            'title'     => $info->title,
            'summary'   => $info->summary,
            'content'   => content_image_crop($info->content),
            'time'      => time_down(strtotime($info->post_time)),
            'category'  => $info->category_name,
            'image'     => image_crop($info->image,500),
            //微信分享头图
            'weixin'    => image_crop_custom($info->image,'?imageMogr2/gravity/Center/crop/300x300')
        ];

        $view = self::make('mobile.site.detail',$data);
        SiteCacheModel::m_article_view_set($_ENV['site_id'],$id,$view);

        return $view;
    }
}