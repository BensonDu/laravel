<?php

namespace App\Http\Controllers\Account;


use App\Http\Model\AccountModel;


class FindController extends AccountController
{


    public function __construct(){
        parent::__construct();
    }
    /*
     |--------------------------------------------------------------------------
     | 找回密码页
     |--------------------------------------------------------------------------
     */
    public function index()
    {
        $data['base']['title'] = '找回密码-创之';
        return self::view('/account/find',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | M找回密码页
     |--------------------------------------------------------------------------
     */
    public function mobileindex()
    {
        $data['base']['title'] = '找回密码-创之';
        return self::view('mobile.platform.account.find',$data);
    }

    public function post()
    {
        $username = $this->request->input('username');
        $captcha = $this->request->input('captcha');
        $password = $this->request->input('password');

        $info = AccountModel::get_user_info_by_username_or_mobilephone($username);
        if(isset($info->mobilephone) && strlen(trim($info->mobilephone)) == 11){
            $phone = $info->mobilephone;
        }
        else{
            return self::ApiOut(20001,'用户手机号码错误');
        }
        if(empty(trim($username))){
            return self::ApiOut(20001,'请求错误');
        }
        if(!AccountModel::is_exist($username)){
            return self::ApiOut(20001,'用户不存在');
        }
        if(!$this->captcha_verify($phone,$captcha)){
            return self::ApiOut(20002,'验证码错误');
        }
        if(strlen($password) < 6){
            return self::ApiOut(20003,'密码格式错误');
        }
        AccountModel::password_reset($username,$password);
        return self::ApiOut(0);
    }


}