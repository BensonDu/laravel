<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\AccountModel;

class UserModel extends Model
{
    protected $table = 'users';
    public $timestamps = false;

    public static function get_user_info_by_id($id)
    {
        $info = UserModel::where('id',$id)->get();
        return isset($info[0]) ? $info[0] :NULL;
    }
    public static function nickname_exist($nickname){
        return UserModel::where('nickname',$nickname)->where('deleted',0)->count();
    }
    public static function save_profile($id, $avatar, $nickname, $slogan, $introduce)
    {
        $user = UserModel::where('id',$id)->first();
        $user->avatar = $avatar;
        $user->nickname = $nickname;
        $user->slogan = $slogan;
        $user->introduce = $introduce;
        $user->update_time = now();
        $user->save();
        return true;
    }
    public static function save_social($id, $wechat, $weibo, $email)
    {
        $user = UserModel::where('id',$id)->first();
        $user->wechat = $wechat;
        $user->weibo = $weibo;
        $user->email = $email;
        $user->update_time = now();
        $user->save();
        return true;
    }
    public static function change_password($id, $password,$newpassword){
        $user = UserModel::where('id' ,$id)->first();
        if(AccountModel::encryption($user->salt,$password) != $user->password)return false;
        $salt = md5(time());
        $new =AccountModel::encryption($salt,$newpassword);
        $user->salt = $salt;
        $user->password = $new;
        $user->update_time = now();
        $user->save();
        return true;
    }
}