<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/8
 * Time: 下午7:40
 */

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Model\UserModel;

class IndexController extends Controller
{

    public function __construct(){
        parent::__construct();
    }

    public function index($id){
        $info = UserModel::get_user_info_by_id(intval($id));
        if(!$info)abort(404);
        $data['profile'] = [
            'avatar'    => avatar($info->avatar),
            'nickname'  => $info->nickname,
            'slogan'    => $info->slogan,
            'introduce' => $info->introduce,
            'email'     => $info->email,
            'weibo'     => $info->weibo,
            'wechat'    => $info->wechat
        ];
        return view('/user/index',$data);
    }

}