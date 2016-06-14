<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/6/13
 * Time: 下午5:42
 */

namespace App\Http\Controllers\Platform;


use App\Http\Model\SiteModel;

class IndexController extends PlatformController
{
    public static function index(){
        $recent = session('recent');
        $site = !empty($recent) ? $recent : 1;
        $info = SiteModel::get_site_info($site);
        $domain = site_home($info->custom_domain,$info->platform_domain);
        return redirect($domain);
    }
}