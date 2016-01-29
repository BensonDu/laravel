<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Model\SiteModel;

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
        $site_info = self::get_site_info();
        view()->share($site_info);
    }
    public static function get_site_info(){
        $info = SiteModel::get_site_info($_ENV['site_id']);
        return ['site'=>$info];
    }

}