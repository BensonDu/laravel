<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/8
 * Time: 下午7:51
 */

namespace App\Http\Controllers\Site;


use App\Http\Model\ArticleSiteModel;
use App\Http\Model\SiteModel;
use App\Http\Model\SiteSpecialModel;

class SpecialController extends SiteController
{
    public function __construct(){
        parent::__construct();
    }
    /*
     |--------------------------------------------------------------------------
     | 专题首页
     |--------------------------------------------------------------------------
     */
    public function index(){
        $list = SiteSpecialModel::get_special_all($_ENV['site_id']);
        if(empty($list))abort(404);
        $info = SiteModel::get_site_info($_ENV['site_id']);
        $data['special'] = isset($info->special) ? $info->special : '';
        $data['list']   = $list;
        $data['active'] = 'special';
        $data['base']['title'] = $info->special;
        return self::view('/site/specials',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | 专题详情页
     |--------------------------------------------------------------------------
     */
    public function detail($id){
        //获取专题信息
        $data['info'] = SiteSpecialModel::get_special_brief_info($_ENV['site_id'],$id,['id','title','summary','image','bg_image','list']);
        if(empty($data['info']))abort(404);
        //导航Active
        $data['active'] = 'special';
        //获取专题文章列表
        $ids = explode(' ',$data['info']->list);
        $list = ArticleSiteModel::get_article_list_by_ids($_ENV['site_id'],$ids);
        $data['list'] = $list;
        //专题页标题
        $data['base']['title'] = $data['info']->title;
        return self::view('/site/special',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | M专题首页
     |--------------------------------------------------------------------------
     */
    public function mobileindex(){
        $list = SiteSpecialModel::get_special_all($_ENV['site_id']);
        if(empty($list))abort(404);
        $info = SiteModel::get_site_info($_ENV['site_id']);
        $data['special'] = isset($info->special) ? $info->special : '';
        $data['list']   = $list;
        $data['active'] = 'special';
        $data['base']['title'] = $info->special;
        return self::view('/mobile.site.specials',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | M专题详情页
     |--------------------------------------------------------------------------
     */
    public function mobiledetail($id){
        //获取专题信息
        $data['info'] = SiteSpecialModel::get_special_brief_info($_ENV['site_id'],$id,['id','title','summary','image','bg_image','list']);
        if(empty($data['info']))abort(404);
        //导航Active
        $data['active'] = 'special';
        //获取专题文章列表
        $ids = explode(' ',$data['info']->list);
        $list = ArticleSiteModel::get_article_list_by_ids($_ENV['site_id'],$ids);
        $data['list'] = $list;
        //专题页标题
        $data['base']['title'] = $data['info']->title;
        return self::view('mobile.site.special',$data);
    }
}