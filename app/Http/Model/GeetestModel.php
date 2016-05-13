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

    private static function init(){
        $id  = config('geetest.id');
        $key = config('geetest.key');
        self::$geetest = new \App\Libs\geetest\geetest($id,$key);
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
            return self::$geetest->success_validate($challenge, $validate, $seccode, session()->getId());
        }
        else{
            return self::$geetest->fail_validate($challenge, $validate, $seccode);
        }
    }

}