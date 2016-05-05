<?php

namespace App\Http\Controllers\User;

use App\Http\Model\UserModel;

class PasswordController extends UserController
{
    public function __construct(){
        parent::__construct();
    }
    /*
     |--------------------------------------------------------------------------
     | 个人修改密码页
     |--------------------------------------------------------------------------
     */
    public function index()
    {
        $data['active'] = 'password';
        $data['base']['title'] = '密码重置-创之';
        return self::view('/user/password',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | 个人修改密码保存
     |--------------------------------------------------------------------------
     */
    public function post()
    {
        $password       = $this->request->input('password');
        $newpassword    = $this->request->input('newpassword');

        if(empty(trim($password)) || empty(trim($newpassword))){
            return self::ApiOut(20001,'请求错误');
        }

        if(!UserModel::change_password($_ENV['uid'],$password,$newpassword)){
            return self::ApiOut(10001,'密码错误');
        }
        
        return self::ApiOut(0,'/');
    }

}