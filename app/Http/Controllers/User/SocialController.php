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
        $data['input'] = json_encode([
            'id'        => $this->info->id,
            'wechat'    => $this->info->wechat,
            'email'     => $this->info->email,
            'weibo'     => empty($this->info->weibo)? 'http://weibo.com/' : $this->info->weibo
        ]);
        return view('/user/social',$data);
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