<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/7/22
 * Time: 下午10:32
 */

namespace App\Http\Model\Option;

use App\Http\Model\SiteModel;

class PlatformModel extends OptionModel
{
    //Option name;
    private static $name = [
        //平台首页-->左导航-->站点列表
        'site_list' => 'platform.nav.left.site.list'
    ];

    /*
     |--------------------------------------------------------------------------
     | 平台首页获得左导航站点列表
     |--------------------------------------------------------------------------
     */
    public static function get_nav_site_list(){
        $list   = self::get_nav_site_id_list();
        $sites  = SiteModel::get_site_info_list($list,['id','name','logo','custom_domain','platform_domain']);
        $ret    = [];
        foreach ($list as $v){
            foreach ($sites as $vv){
                if($v == $vv->id){
                    $ret[] = [
                        'id'    => $vv->id,
                        'name'  => $vv->name,
                        'logo'  => $vv->logo,
                        'link'  => site_home($vv->custom_domain,$vv->platform_domain)
                    ];
                }
            }
        }
        return $ret;
    }
    /*
     |--------------------------------------------------------------------------
     | 平台首页获得左导航站点ID列表
     |--------------------------------------------------------------------------
     */
    public static function get_nav_site_id_list(){
        return self::get_option(self::$name['site_list']);
    }
    /*
     |--------------------------------------------------------------------------
     | 平台首页设置左导航站点列表
     |--------------------------------------------------------------------------
     */
    public static function set_nav_site_list($list){
        return self::set_option(self::$name['site_list'],$list);
    }


}