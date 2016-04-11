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
        /*登录用户部分*/
        if(!empty($_ENV['uid'])){
            self::set_profile($_ENV['uid']);
            self::set_view();
        }
    }
    /*
     |--------------------------------------------------------------------------
     | 设置用户资料
     |--------------------------------------------------------------------------
     */
    private static function set_profile($id){
        self::$info = UserModel::get_user_info_by_id($id);
    }
    /*
     |--------------------------------------------------------------------------
     | 设置用户登录 View
     |--------------------------------------------------------------------------
     */
    private static function set_view(){
        $data = [
            'id'        => self::$info->id,
            'profile'   => [
                'id'        => self::$info->id,
                'avatar'    => avatar(self::$info->avatar),
                'nickname'  => self::$info->nickname,
                'slogan'    => self::$info->slogan,
                'introduce' => self::$info->introduce,
                'email'     => self::$info->email,
                'weibo'     => self::$info->weibo,
                'wechat'    => self::$info->wechat
            ],
            'self' => true
        ];

        return view()->share($data);
    }
    /*
     |--------------------------------------------------------------------------
     | 获取用户资料
     |--------------------------------------------------------------------------
     */
    public static function profile($id){
        $data = [];
        $info = UserModel::get_user_info_by_id($id);
        if(!empty($info)){
            $data['id'] = $id;
            $data['profile'] = [
                'id'        => $info->id,
                'avatar'    => avatar($info->avatar),
                'nickname'  => $info->nickname,
                'slogan'    => $info->slogan,
                'introduce' => $info->introduce,
                'email'     => $info->email,
                'weibo'     => $info->weibo,
                'wechat'    => $info->wechat
            ];
            
        }
        return $data;
    }

}