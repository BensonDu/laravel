<?php

namespace App\Http\Controllers\User;

use App\Http\Model\UserModel;

class SocialController extends UserController
{

    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
        $data['active'] = 'social';
        $data['input'] = json_encode([
            'id'        => self::$info->id,
            'wechat'    => self::$info->wechat,
            'email'     => self::$info->email,
            'weibo'     => empty(self::$info->weibo)? 'http://weibo.com/' : self::$info->weibo
        ]);
        return self::view('/user/social',$data);
    }
    public function post()
    {
        $wechat = $this->request->input('wechat');
        $weibo = $this->request->input('weibo');
        $email = $this->request->input('email');
        UserModel::save_social($_ENV['uid'],$wechat,$weibo,$email);
        return self::ApiOut(0,'/');
    }

}