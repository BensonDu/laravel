<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Model\UserModel;

class UserController extends Controller
{
    public  $request;
    public static $info = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->request = request();
        $data=self::profile($_ENV['uid']);
        $data['self'] = true;
        view()->share($data);
    }
    public static function profile($id)
    {
        $data = [];
        self::$info = UserModel::get_user_info_by_id($id);
        if(!empty(self::$info)){
            $data['id'] = $id;
            $data['profile'] = [
                'id'        => self::$info->id,
                'avatar'    => avatar(self::$info->avatar),
                'nickname'  => self::$info->nickname,
                'slogan'    => self::$info->slogan,
                'introduce' => self::$info->introduce,
                'email'     => self::$info->email,
                'weibo'     => self::$info->weibo,
                'wechat'    => self::$info->wechat
            ];
        }
        return $data;
    }

}