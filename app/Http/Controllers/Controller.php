<?php

namespace App\Http\Controllers;

use App\Http\Model\AccountModel;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(){

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
        $response = response()->json($ret);

        /*JSONP*/
        $callback = request()->input('callback');
        if(!empty($callback)) $response->setCallback($callback);

        return $response;
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
    | 公共view make 方法
    |--------------------------------------------------------------------------
    |
    */
    public static function make($path,$data = []){
        self::get_user_info();
        self::get_nav_url();
        return View::make($path,$data);
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
            'avatar'    => isset($info->avatar) ? avatar($info->avatar,200) : '',
            'url'       => !empty($uid) ? user_url($uid) : ''
        ];
        view()->share($data);
    }
    /*
    |--------------------------------------------------------------------------
    | 全局导航 链接
    |--------------------------------------------------------------------------
    |
    */
    public static function get_nav_url(){

        /*前往登录页回调参数,如果当前页为登录页,为空*/
        $request = request();
        $url     = $request->url();
        $seg     = $request->segment(1);
        $data['nav']['callback'] = $seg != 'account' ? '?redirect='.urlencode($url) : '';

        $data['nav']['home']     = $_ENV['platform']['home'];
        $data['nav']['edit']     = $_ENV['platform']['home'].'/user/edit';
        $data['nav']['favorite'] = $_ENV['platform']['home'].'/user/favorite';
        $data['nav']['profile']  = $_ENV['platform']['home'].'/user/profile';
        $data['nav']['user']     = $_ENV['platform']['home'].'/user/';
        view()->share($data);
    }
}
