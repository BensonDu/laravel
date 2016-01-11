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
        $data['input'] = json_encode([
                            'id'        => $this->info->id,
                            'nickname'  => $this->info->nickname,
                            'slogan'    => $this->info->slogan,
                            'introduce' => $this->info->introduce,
                            'avatar'    => avatar($this->info->avatar),
                            'username'  => $this->info->username
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