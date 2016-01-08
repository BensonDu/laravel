<?php

namespace App\Http\Controllers\User;

use App\Http\Model\AccountModel;
use App\Http\Model\UserModel;

class ProfileController extends UserController
{

    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
        $info = NULL;
        if(!empty($_ENV['uid'])) $info = UserModel::get_user_info_by_id($_ENV['uid']);
        if(is_null($info)) return redirect('/account/login?redirect='.urlencode($this->request->url()));
        $data['profile'] = json_encode([
                            'id'        => $info->id,
                            'nickname'  => $info->nickname,
                            'slogan'    => $info->slogan,
                            'introduce' => $info->introduce,
                            'avatar'    => avatar($info->avatar),
                            'username'  => $info->username
                         ]);
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