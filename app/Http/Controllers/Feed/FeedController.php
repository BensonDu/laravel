<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;
use App\Http\Model\ArticleSiteModel;
use App\Http\Model\SiteModel;

/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/11
 * Time: 上午9:45
 */
class FeedController extends Controller
{

    public $info;
    public function __construct()
    {
        parent::__construct();
        $this->site_info();
    }
    /*
    |--------------------------------------------------------------------------
    | 设置站点信息
    |--------------------------------------------------------------------------
    */
    private function site_info(){
        $this->info = SiteModel::get_site_info($_ENV['site_id']);
    }

    /*
    |--------------------------------------------------------------------------
    | 设置新闻列表
    |--------------------------------------------------------------------------
    */
    protected function site_articles(){
        return ArticleSiteModel::get_rss_article_list($_ENV['site_id']);
    }
    /*
    |--------------------------------------------------------------------------
    | RSS 输出
    |--------------------------------------------------------------------------
    */
    protected static function rss_out($feed){
        return response( iconv("UTF-8","UTF-8//IGNORE",$feed))->header('Content-Type',"text/xml");
    }

}