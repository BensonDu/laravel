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
        return view('/user/profile',$data);
    }
    public function post()
    {
        $nickname = $this->request->input('nickname'); 
        $avatar = $this->request->input('avatar'); 
        $introduce = $this->request->input('introduce');
        $slogan = $this->request->input('slogan');
        if(empty(trim($nickname))){
            return self::ApiOut(20001,'昵称为空');
        }
        UserModel::save_profile($_ENV['uid'],$avatar,$nickname,$slogan,$introduce);
        return self::ApiOut(0,'/');
    }

}