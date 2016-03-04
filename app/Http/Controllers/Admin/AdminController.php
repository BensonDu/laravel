<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/22
 * Time: 下午6:17
 */
namespace App\Http\Controllers\Admin;

use App\Http\Model\Admin\ArticleModel;
use App\Http\Model\SiteModel;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $site_info = self::get_site_info();
        $uncontribute_article_num = self::get_uncontribute_article_num();
        view()->share(array_merge($site_info,$uncontribute_article_num));
    }
    /*
     |--------------------------------------------------------------------------
     | 站点信息
     |--------------------------------------------------------------------------
     */
    public static function get_site_info(){
        $info = SiteModel::get_site_info($_ENV['site_id']);
        return ['site'=>$info];
    }
    /*
     |--------------------------------------------------------------------------
     | 待审核文章数量
     |--------------------------------------------------------------------------
     */
    public static function get_uncontribute_article_num(){
        return [
            'uncontribute_article_num' => ArticleModel::contribute_article_count($_ENV['site_id'])
        ];
    }
}