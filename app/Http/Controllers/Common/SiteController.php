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

}