<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Model\SiteModel;
use App\Http\Model\SiteSpecialModel;
use Illuminate\Support\Facades\View;

/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/8
 * Time: 下午7:45
 */
class SiteController extends Controller
{
    public $request;

    public function __construct()
    {
        parent::__construct();
        $this->request = request();
    }
    public static function view($path, $data = [])
    {
        $site = SiteModel::get_site_info($_ENV['site_id']);
        $data['site']                = $site;
        $data['site']['id']          = $_ENV['site_id'];
        $data['base']['title']       = isset($data['base']['title']) ? $data['base']['title'] : $site->name.'-'.$site->slogan;
        $data['base']['keywords']    = isset($data['base']['keywords']) ? $data['base']['keywords'].', '.$site->keywords : $site->keywords;
        $data['base']['description'] = $site->description;
        $data['base']['favicon']     = $site->favicon;
        $data['site']['nav']         = SiteModel::site_nav_list($_ENV['site_id']);
        return parent::view($path, $data);
    }
    public static function make($path, $data = []){
        $site = SiteModel::get_site_info($_ENV['site_id']);
        $data['site']                = $site;
        $data['site']['id']          = $_ENV['site_id'];
        $data['site']['special']     = SiteSpecialModel::get_special_count($_ENV['site_id']);
        $data['base']['title']       = isset($data['base']['title']) ? $data['base']['title'] : $site->name.'-'.$site->slogan;
        $data['base']['keywords']    = isset($data['base']['keywords']) ? $data['base']['keywords'].', '.$site->keywords : $site->keywords;
        $data['base']['description'] = $site->description;
        $data['base']['favicon']     = $site->favicon;
        return View::make($path, $data);
    }

}