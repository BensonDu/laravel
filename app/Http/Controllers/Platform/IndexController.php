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
        $domain = !empty($info->custom_domain) ? 'http://'.$info->custom_domain.'/' : 'http://'.$info->platform_domain.'.'.$_ENV['platform']['domain'].'/';
        return redirect($domain);
    }
}