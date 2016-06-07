<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserModel extends Model
{
    protected $table = 'users';
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | 获取用户信息
    |--------------------------------------------------------------------------
    */
    public static function get_user_info_by_id($id)
    {
        $info = UserModel::where('id',$id)->get();
        return isset($info[0]) ? $info[0] :NULL;
    }
    /*
    |--------------------------------------------------------------------------
    | 获取用户信息列表
    |--------------------------------------------------------------------------
    */
    public static function get_user_list_by_ids($ids,$select = ['id','nickname','avatar']){
        return UserModel::whereIn('id',$ids)->get($select);
    }
    /*
    |--------------------------------------------------------------------------
    | 昵称是否存在
    |--------------------------------------------------------------------------
    */
    public static function nickname_exist($uid,$nickname){
        $info = UserModel::where('nickname',$nickname)->where('deleted',0)->get();

        if(count($info) > 1)return true;
        if(count($info) == 1){
            return !($info[0]->id == $uid);
        }
        return false;
    }
    /*
    |--------------------------------------------------------------------------
    | 保存个人资料
    |--------------------------------------------------------------------------
    */
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
    /*
    |--------------------------------------------------------------------------
    | 保存社交资料
    |--------------------------------------------------------------------------
    */
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
    /*
    |--------------------------------------------------------------------------
    | 更改密码
    |--------------------------------------------------------------------------
    */
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
    /*
    |--------------------------------------------------------------------------
    | 用户拥有权限的站点列表
    |--------------------------------------------------------------------------
    */
    public static function site_role_list($id){
        $list = DB::table('site_auth_map')->where('deleted',0)->where('user_id',$id)->get(['site_id']);
        $ret = [];
        if(!empty($list)){
            foreach ($list as $v){
                $ret[] = $v->site_id;
            }
        }
        return $ret;
    }
    /*
    |--------------------------------------------------------------------------
    | 用户常用站点列表
    |--------------------------------------------------------------------------
    */
    public static function current_site($id){
        $info = UserModel::where('id',$id)->where('deleted',0)->first(['sites']);
        $ret = [];
        if(isset($info->sites) && !empty($info->sites)){
            $ret = explode(" ",$info->sites);
        }
        return $ret;
    }
    /*
    |--------------------------------------------------------------------------
    | 更新用户常用站点列表
    |--------------------------------------------------------------------------
    */
    public static function update_current_site($id,$current = []){
        return UserModel::where('id',$id)->update(['sites'=>implode(" ",$current)]);
    }
}