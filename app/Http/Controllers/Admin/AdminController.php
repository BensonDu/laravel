<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/22
 * Time: 下午6:17
 */
namespace App\Http\Controllers\Admin;

use App\Http\Model\Admin\ArticleModel;
use App\Http\Controllers\Controller;
use App\Http\Model\SiteModel;

class AdminController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    public static function view($path, $data = [])
    {
        $site = SiteModel::get_site_info($_ENV['domain']['id']);
        $data['site']                = $site;
        //待审核文章数量
        $data['uncontribute_article_num'] = ArticleModel::contribute_article_count($_ENV['domain']['id']);
        $data['base']['title']       = isset($data['base']['title']) ? $data['base']['title'] : $site->name;
        $data['base']['keywords']    = $site->keywords;
        $data['base']['description'] = $site->description;
        $data['base']['favicon']     = $site->favicon;
        return parent::view($path, $data);
    }
}