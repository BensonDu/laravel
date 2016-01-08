<?php

namespace App\Http\Controllers\Account;


use App\Http\Model\AccountModel;

class RegistController extends AccountController
{

    public function __construct(){
        parent::__construct();
    }

    public function index(){
        return view('/account/regist');
    }
    public function post(){

        $username = $this->request->input('username');
        $phone = $this->request->input('phone');
        $captcha = $this->request->input('captcha');
        $password = $this->request->input('password');

        if(empty(trim($username))){
            return self::ApiOut(20001,'用户输入为空');
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

        return self::ApiOut(0,'/');

    }

}