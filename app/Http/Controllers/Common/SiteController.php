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

class SiteController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | 获得站点分类列表
     |--------------------------------------------------------------------------
     */
   public static function category(){
       $site_id = request('site_id');
       $list = ArticleSiteModel::get_article_categories($site_id);
       return self::ApiOut(0,!empty($list) ? $list : []);
   }

}