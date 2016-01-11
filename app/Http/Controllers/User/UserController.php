<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Model\UserModel;

class UserController extends Controller
{
    public  $request,
            $info = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->request = request();
        if(!empty($_ENV['uid'])) $this->info = UserModel::get_user_info_by_id($_ENV['uid']);
        $data['profile'] = [
            'id'        => $this->info->id,
            'avatar'    => avatar($this->info->avatar),
            'nickname'  => $this->info->nickname,
            'slogan'    => $this->info->slogan,
            'introduce' => $this->info->introduce,
            'email'     => $this->info->email,
            'weibo'     => $this->info->weibo,
            'wechat'    => $this->info->wechat
        ];
        view()->share($data);
    }

}