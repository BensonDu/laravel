<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/6/13
 * Time: 下午5:42
 */

namespace App\Http\Controllers\Platform;


use App\Http\Model\ArticleSiteModel;
use App\Http\Model\Cache\PlatformIndexCacheModel;
use App\Http\Model\Option\PlatformModel;
use App\Http\Model\SiteModel;

class IndexController extends PlatformController
{


    /*
     |--------------------------------------------------------------------------
     | 平台首页
     |--------------------------------------------------------------------------
     */
    public static function index(){

        //文章列表
        $data['articles']  = self::get_articles();
        //站点列表
        $data['sites']     = PlatformModel::get_nav_site_list();

        return self::view('platform.index',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | 平台 M 站首页
     |--------------------------------------------------------------------------
     */
    public static function mobile(){

        //文章列表
        $data['articles']  = self::get_articles();
        //站点列表
        $data['sites']     = PlatformModel::get_nav_site_list();

        return self::view('mobile.platform.index',$data);
    }

    /*
     |--------------------------------------------------------------------------
     | 平台文章列表接口
     |--------------------------------------------------------------------------
     */
    public static function articles(){
        $request = request();
        $orderby = $request->input('orderby') == 'new' ? 'new' : 'hot';
        $index   = $request->input('index');
        $skip    = intval($index)*20;
        return self::ApiOut('0',self::get_articles($skip,$orderby));
    }
    /*
     |--------------------------------------------------------------------------
     | 获取文章列表
     |--------------------------------------------------------------------------
     */
    private static function get_articles($skip = 0,$orderby = 'hot'){
        $ret = PlatformIndexCacheModel::index_list_get($skip,$orderby);
        if(empty($ret)){
            $ret =  self::format(ArticleSiteModel::get_platform_home_article_list($skip,$orderby));
            PlatformIndexCacheModel::index_list_set($skip,$orderby,$ret);
        };
        return  $ret;

    }
    /*
     |--------------------------------------------------------------------------
     | 列表格式化
     |--------------------------------------------------------------------------
     */
    private static function format($list){
        $site_ids = [];
        foreach ($list as $v){
            if(!in_array($v['site_id'],$site_ids))$site_ids[] = $v['site_id'];

        }
        $sites = SiteModel::get_site_info_list($site_ids);
        $site_info_map = [];
        foreach ($sites as $v){
            $site_info_map[$v->id] = [
                'link' => site_home($v->custom_domain,$v->platform_domain),
                'name' => $v->name
            ];
        }
        $ret = [];
        foreach ($list as $k => $v){
            $ret[] = [
                'id'        => $v['id'],
                'title'     => $v['title'],
                'summary'   => $v['summary'],
                'image'     => image_crop($v['image'],200),
                'site'      => $site_info_map[$v['site_id']],
                'user'      => [
                    'avatar' => avatar($v['avatar']),
                    'link'   => $_ENV['platform']['home'].'/user/'.$v['user_id'],
                    'name'   => $v['nickname']
                ],
                'time'      => time_down(strtotime($v['post_time'])),
                'rank'      => $v['rank'],
                'score'     => $v['score']
            ];
        }
        return $ret;
    }
}