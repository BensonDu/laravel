<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/9
 * Time: 下午6:57
 */

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Model\ArticleSiteModel;
use App\Http\Model\CategoryModel;
use App\Http\Model\SiteModel;

class SiteController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | 获得站点分类列表
     |--------------------------------------------------------------------------
     */
   public static function category(){
       $site_id = request('site_id');
       $list = CategoryModel::get_categories($site_id);
       return self::ApiOut(0,!empty($list) ? $list : []);
   }
    /*
    |--------------------------------------------------------------------------
    | 获得站点列表
    |--------------------------------------------------------------------------
    */
    public static function site(){
        $keyword = request('keyword');
        //获取站点列表
        $list = SiteModel::get_site_list(0,10,empty($keyword) ? null : $keyword);
        $ret = [];
        foreach ($list as $k => $v){
            $ret[$k]['id']      =   $v->id;
            $ret[$k]['name']    =   $v->name;
            $ret[$k]['link']    =   site_home($v->custom_domain,$v->platform_domain);
        }
        return self::ApiOut(0,$ret);
    }

}