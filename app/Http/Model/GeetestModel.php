<?php
namespace App\Http\Model;

/*
|--------------------------------------------------------------------------
| 极验验证码Model
|--------------------------------------------------------------------------
*/

class GeetestModel{

    private static $geetest;
    private static $session = 'gtserver';
    private static $access  = 'gtaccess';

    private static function init(){
        $id  = config('geetest.id');
        $key = config('geetest.key');
        self::$geetest = new \App\Libs\geetest\geetest($id,$key);
    }
    /*
    |--------------------------------------------------------------------------
    |  检测验证状态 通过-->重置
    |--------------------------------------------------------------------------
    */
    public static function verify(){
        $gtaccess = session(self::$access);
        if(!empty($gtaccess) && $gtaccess){
            session()->put(self::$access,false);
            return true;
        }
        else{
            return false;
        }
    }
    /*
    |--------------------------------------------------------------------------
    |  获得验证 Token
    |--------------------------------------------------------------------------
    */
    public static function getToken(){
        self::init();
        $sid = session()->getId();
        $status = self::$geetest->pre_process($sid);
        session()->put(self::$session,$status);
        return self::$geetest->get_response_str();
    }
    /*
    |--------------------------------------------------------------------------
    | 校验验证
    |--------------------------------------------------------------------------
    */
    public static function validate($challenge, $validate, $seccode){
        self::init();
        if(session(self::$session)){
            $ret = self::$geetest->success_validate($challenge, $validate, $seccode, session()->getId());
        }
        else{
            $ret = self::$geetest->fail_validate($challenge, $validate, $seccode);
        }
        session()->put(self::$access,$ret);
        return $ret;
    }

}