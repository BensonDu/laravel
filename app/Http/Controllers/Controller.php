<?php

namespace App\Http\Controllers;

use App\Http\Model\AccountModel;
use App\Http\Model\SiteModel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {

    }
    /*
    |--------------------------------------------------------------------------
    | 统一接口输出方法
    |--------------------------------------------------------------------------
    |
    */
    public static function ApiOut($code = 10001, $msg='', $data=[])
    {
        $ret['code'] = $code;
        if(is_array($msg) || is_object($msg)){
            $ret['data'] = $msg;
            $ret['msg'] = '';
        }
        else{
            $ret['msg'] = $msg;
            if(is_array($data)){
                $ret['data'] = $data;
            }
        }

        return response()->json($ret);
    }
    /*
    |--------------------------------------------------------------------------
    | 公共view 方法
    |--------------------------------------------------------------------------
    |
    */
    public static function view($path,$data = []){
        self::get_user_info();
        self::get_nav_url();
        return view($path,$data);
    }
    /*
    |--------------------------------------------------------------------------
    | 用户信息填充view
    |--------------------------------------------------------------------------
    |
    */
    public static function get_user_info(){
        $uid = $_ENV['uid'];
        /*SESSION中获取UID获得用户信息,传入VIEW*/
        if(!empty($uid)){
            $info = AccountModel::get_user_info_by_uid($uid);
        }
        $data = [
            'uid'       => $uid,
            'nickname'  => isset($info->nickname) ? $info->nickname : '',
            'avatar'    => isset($info->avatar) ? avatar($info->avatar) : '',
            //用户未登录,登录回调地址,过滤登录页;
            'url' => !empty($uid) ? user_url($uid) : $_ENV['platform']['home'].'/account/login'.((request()->path() != 'account/login' ? '?redirect='.urlencode(request()->url()) : ''))
        ];
        view()->share($data);
    }
    /*
    |--------------------------------------------------------------------------
    | 左全局导航 链接
    |--------------------------------------------------------------------------
    |
    */
    public static function get_nav_url(){
        $data['nav']['edit']     = $_ENV['platform']['home'].'/user/edit';
        $data['nav']['favorite'] = $_ENV['platform']['home'].'/user/favorite';
        $data['nav']['profile']  = $_ENV['platform']['home'].'/user/profile';
        $data['nav']['user']     = $_ENV['platform']['home'].'/user/';
        view()->share($data);
    }
    /*
    |--------------------------------------------------------------------------
    | 获取站点信息
    |--------------------------------------------------------------------------
    */
    public static function get_site_info(){
        $info = SiteModel::get_site_info($_ENV['site_id']);
        return view()->share(['site'=>$info]);
    }

}
