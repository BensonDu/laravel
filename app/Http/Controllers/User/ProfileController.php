<?php

namespace App\Http\Controllers\User;

use App\Http\Model\UserModel;

class ProfileController extends UserController
{

    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
        $data['active'] = 'profile';
        $data['input'] = [
                            'id'        => self::$info->id,
                            'nickname'  => self::$info->nickname,
                            'slogan'    => self::$info->slogan,
                            'introduce' => self::$info->introduce,
                            'avatar'    => avatar(self::$info->avatar),
                            'username'  => self::$info->username
                         ];
        return self::view('/user/profile',$data);
    }
    public function post()
    {
        $nickname = $this->request->input('nickname'); 
        $avatar = $this->request->input('avatar'); 
        $introduce = $this->request->input('introduce');
        $slogan = $this->request->input('slogan');
        if(empty(trim($nickname)))return self::ApiOut(20001,'昵称为空');

        if ( !preg_match('/[\x4E00-\x9FA5\w]{1,20}/',$nickname) ) return self::ApiOut(20001,'包含非法字符');


        if(UserModel::nickname_exist($_ENV['uid'],$nickname))return self::ApiOut(20001,'昵称已存在');
        UserModel::save_profile($_ENV['uid'],$avatar,$nickname,$slogan,$introduce);
        return self::ApiOut(0,'/');
    }

}