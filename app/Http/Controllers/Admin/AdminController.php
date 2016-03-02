<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/22
 * Time: 下午6:17
 */
namespace App\Http\Controllers\Admin;

use App\Http\Model\SiteModel;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $site_info = self::get_site_info();
        view()->share($site_info);
    }
    public static function get_site_info(){
        $info = SiteModel::get_site_info($_ENV['site_id']);
        return ['site'=>$info];
    }
}