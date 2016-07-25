<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/7/18
 * Time: 下午2:48
 */

namespace App\Http\Controllers\Platform\Admin;

use App\Http\Model\Option\PlatformModel;

class NavController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $data['admin_nav_top'] = [
            'name' => '平台设置',
            'class'=> 'category'
        ];
        view()->share($data);
    }
    /*
     |--------------------------------------------------------------------------
     | 导航管理 --> 首页
     |--------------------------------------------------------------------------
     */
    public static function index(){
        $data['sub_act'] = 'nav';
        $data['sites'] = PlatformModel::get_nav_site_list();
        return self::view('platform.admin.option.nav',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | 导航管理 --> 列表
     |--------------------------------------------------------------------------
     */
    public static function navlist(){
        $list = PlatformModel::get_nav_site_list();
        return self::ApiOut(0,$list);
    }
    /*
     |--------------------------------------------------------------------------
     | 导航管理 --> 保存: 排序、删除、添加
     |--------------------------------------------------------------------------
     */
    public static function save(){
        $order = request()->input('order');
        if (!is_array($order))return self::ApiOut('40001','请求错误');
        PlatformModel::set_nav_site_list($order);
        return self::ApiOut('0','保存成功');
    }
}