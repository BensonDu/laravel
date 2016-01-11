<?php

namespace App\Http\Controllers\User;

use App\Http\Model\UserModel;

class PasswordController extends UserController
{
    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
        return view('/user/password');
    }
    public function post()
    {
        $password = $this->request->input('password');
        $newpassword = $this->request->input('newpassword');

        if(empty(trim($password)) || empty(trim($newpassword))){
            return self::ApiOut(20001,'请求错误');
        }
        if(!UserModel::change_password($_ENV['uid'],$password,$newpassword)){
            return self::ApiOut(10001,'密码错误');
        }
        return self::ApiOut(0,'/');
    }

}