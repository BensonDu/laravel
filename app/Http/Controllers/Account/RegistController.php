<?php

namespace App\Http\Controllers\Account;


use App\Http\Model\AccountModel;
use Illuminate\Support\Facades\Crypt;

class RegistController extends AccountController
{

    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $data['base']['title'] = '注册-创之';
        return self::view('/account/regist',$data);
    }
    public function post(){

        $username = $this->request->input('username');
        $phone = $this->request->input('phone');
        $captcha = $this->request->input('captcha');
        $password = $this->request->input('password');
        $redirect = $this->request->input('redirect');

        if(empty(trim($username)) || preg_match('/^\d+$/',$username) || !preg_match('/^[a-zA-Z0-9_]+$/',$username)){
            return self::ApiOut(20001,'用户名格式错误');
        }
        if(AccountModel::is_exist($username)){
            return self::ApiOut(20001,'用户已存在');
        }
        if(empty(trim($phone))){
            return self::ApiOut(20002,'手机号码错误');
        }
        if(AccountModel::is_exist($phone)){
            return self::ApiOut(20002,'手机号码已存在');
        }
        if(!$this->captcha_verify($phone,$captcha)){
            return self::ApiOut(20003,'验证码错误');
        }
        if(strlen($password) < 6){
            return self::ApiOut(20004,'密码需大于6位');
        }
        //注册 并登录
        $info = AccountModel::regist($username,$phone,$password);
        if(isset($info->id))$this->login($info->id);

        $site = !stristr($redirect, $_ENV['SITE_PLATFORM_BASE']);
        $sid = session()->getId();

        return self::ApiOut(0,['session'=>$sid,'site'=>$site]);

    }

}