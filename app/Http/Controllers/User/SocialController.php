<?php

namespace App\Http\Controllers\User;

use App\Http\Model\UserModel;

class SocialController extends UserController
{

    public function __construct(){
        parent::__construct();
    }
    /*
     |--------------------------------------------------------------------------
     | 个人社交资料修改
     |--------------------------------------------------------------------------
     */
    public function index()
    {
        $data['active'] = 'social';
        $data['input'] = json_encode_safe([
            'id'        => self::$info->id,
            'wechat'    => self::$info->wechat,
            'email'     => self::$info->email,
            'weibo'     => self::$info->weibo
        ]);
        return self::view('/user/social',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | 个人社交资料保存
     |--------------------------------------------------------------------------
     */
    public function post()
    {
        $wechat = $this->request->input('wechat');
        $weibo  = $this->request->input('weibo');
        $email  = $this->request->input('email');
        UserModel::save_social($_ENV['uid'],$wechat,$weibo,$email);
        
        return self::ApiOut(0,'/');
    }

}