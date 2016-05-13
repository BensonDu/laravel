<?php

namespace App\Http\Controllers\Account;

use App\Http\Model\AccountModel;
use App\Http\Model\CaptchaModel;
use App\Http\Controllers\Controller;
use App\Http\Model\GeetestModel;

class AccountController extends Controller
{
    public $request;

    public function __construct()
    {
        parent::__construct();
        $this->request = request();
        $this->rediect_syn();
    }
    /*
    |--------------------------------------------------------------------------
    | 设置用户登录session
    |--------------------------------------------------------------------------
    |
    | @param  string $user id
    | @return bool
    |
    */
    public function login($id)
    {
        session()->put('uid', $id);
    }
    /*
    |--------------------------------------------------------------------------
    | 注销返回主页
    |--------------------------------------------------------------------------
    */
    public function logout(){
        session()->flush();
        return redirect('/');
    }
    /*
    |--------------------------------------------------------------------------
    | 平台登录状态
    |--------------------------------------------------------------------------
    */
    public function status(){
        return $this->ApiOut(0,['login'=>!empty($_ENV['uid'])]);
    }
    /*
    |--------------------------------------------------------------------------
    | 用户是否存在
    |--------------------------------------------------------------------------
    */
    public function exist()
    {
        $username = $this->request->input('username');

        if(!AccountModel::is_exist($username)){
            return self::ApiOut(10001,'用户名不存在');
        }
        else{
            return self::ApiOut(0,'用户名已存在');
        }
    }
    /*
    |--------------------------------------------------------------------------
    | 发送验证码
    |--------------------------------------------------------------------------
    | @input $phone 手机号码
    | @input $username 用户名
    */
    public function captcha()
    {
        $username = $this->request->input('username');
        $phone    = $this->request->input('phone');

        //是否通过极验
        $gtaccess = session('gtaccess');
        if(!empty($gtaccess) && $gtaccess){
            session()->put('gtaccess',false);
        }
        else{
            return $this->ApiOut(10004,'图片验证码错误');
        }

        if(empty($phone) && !empty($username)){
            $info = AccountModel::get_user_info_by_username_or_mobilephone($username);
            if(isset($info->mobilephone) && strlen(trim($info->mobilephone)) == 11){
                $phone = $info->mobilephone;
            }
            else{
                return $this->ApiOut(10008,'用户信息错误');
            }
        }
        $ret = CaptchaModel::send($phone);
        return $this->ApiOut($ret['code'],$ret['ret']);
    }
    /*
    |--------------------------------------------------------------------------
    | 验证验证码
    |--------------------------------------------------------------------------
    */
    public function captcha_verify($phone,$captcha)
    {
        $ret = CaptchaModel::verify($phone,$captcha);
        return $ret['code'] == '0';
    }
    /*
    |--------------------------------------------------------------------------
    | 极验验证码起始
    |--------------------------------------------------------------------------
    */
    public function gtstart(){
        return GeetestModel::getToken();
    }
    /*
    |--------------------------------------------------------------------------
    | 极验验证码验证
    |--------------------------------------------------------------------------
    */
    public function gtverify(){
        $challenge   = request()->input('geetest_challenge');
        $validate    = request()->input('geetest_validate');
        $seccode     = request()->input('geetest_seccode');
        $ret = GeetestModel::validate($challenge,$validate,$seccode);
        session()->put('gtaccess',$ret);
        return self::ApiOut($ret ? 0 : 10001,'验证完成');
    }
    /*
    |--------------------------------------------------------------------------
    | 回调链接同步
    |--------------------------------------------------------------------------
    */
    private function rediect_syn(){
        $redirect = request()->input('redirect');
        view()->share('redirect','?redirect='.$redirect);
    }

}