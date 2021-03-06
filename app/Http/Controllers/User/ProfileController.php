<?php

namespace App\Http\Controllers\User;

use App\Http\Model\UserModel;

class ProfileController extends UserController
{

    public function __construct(){
        parent::__construct();
    }
    /*
     |--------------------------------------------------------------------------
     | 个人资料设置页
     |--------------------------------------------------------------------------
     */
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

        $data['base']['title'] = '个人资料-创之';
        return self::view('/user/profile',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | 个人资料设置保存
     |--------------------------------------------------------------------------
     */
    public static function post()
    {
        $request = request();
        $nickname   = $request->input('nickname');
        $avatar     = $request->input('avatar');
        $introduce  = $request->input('introduce');
        $slogan     = $request->input('slogan');

        if(empty(trim($nickname)))return self::ApiOut(20001,'昵称为空');

        if ( !preg_match('/^[\x7f-\xffa-zA-Z0-9]+$/',$nickname) ) return self::ApiOut(20001,'昵称包含非法字符');


        if(UserModel::nickname_exist($_ENV['uid'],$nickname))return self::ApiOut(20001,'昵称已存在');

        UserModel::save_profile($_ENV['uid'],$avatar,$nickname,$slogan,$introduce);

        return self::ApiOut(0,'/');
    }

}