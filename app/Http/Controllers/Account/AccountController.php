<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Site\SiteController;
use App\Http\Model\AccountModel;
use App\Http\Model\CaptchaModel;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public $request;

    public function __construct()
    {
        parent::__construct();
        $this->request = request();
        //账户对应页面显示 站点信息
        $site_info = SiteController::get_site_info();
        view()->share($site_info);
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
        $this->request->session()->put('uid', $id);
    }
    /*
    |--------------------------------------------------------------------------
    | 注销返回主页
    |--------------------------------------------------------------------------
    */
    public function logout(){
        $this->request->session()->flush();
        return redirect('/');
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

}