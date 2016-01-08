<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AccountModel extends Model
{
    protected $table = 'users';
    public $timestamps = false;

   /*
   |--------------------------------------------------------------------------
   | 用户名或手机号码是否已存在
   |--------------------------------------------------------------------------
   |
   | @param  string $username OR $mobilephone
   | @return bool
   |
   */
    public static function is_exist($username)
    {
        return AccountModel::where('username',$username)->orwhere('mobilephone',$username)->count();
    }
    /*
    |--------------------------------------------------------------------------
    | 用户名或手机号码 对应密码验证
    |--------------------------------------------------------------------------
    |
    | @param  string $username OR $mobilephone
    | @prarm  string $password
    | 验证成功
    | @return array user info
    | 验证失败
    | @return bool  false
    |
    */
    public static function verify($username,$password)
    {

        $info = AccountModel::where('username',$username)->orwhere('mobilephone',$username)->take(1)->get();

        if(isset($info[0]) && $info[0]->password == self::encryption($info[0]->salt,$password))
        {
            return $info[0];
        }
        return FALSE;
    }
    /*
    |--------------------------------------------------------------------------
    | 用户注册 写入数据库
    |--------------------------------------------------------------------------
    |
    | @param  string $username
    | @prarm  string $phone
    | @prarm  string $password
    | @return string  New id
    |
    */
    public static function regist($username,$phone,$password)
    {
        $salt = md5(time().$username);
        $user = new AccountModel;
        $user->username = $username;
        $user->nickname = $username;
        $user->salt = $salt;
        $user->password = self::encryption($salt,$password);
        $user->mobilephone = $phone;
        $user->create_time = $user->update_time = date('Y-m-d H:i:s');
        $user->mobilephone_verified = 1;
        $user->save();
        $info = AccountModel::where('username',$username)->take(1)->get();
        return !empty($info) ? $info[0] : null;
    }
    /*
   |--------------------------------------------------------------------------
   | 用户重置密码
   |--------------------------------------------------------------------------
   |
   | @param  string $username
   | @prarm  string $password
   | @return bool
   |
   */
    public static function password_reset($username,$password)
    {
        $salt = md5(time().$username);
        $user = AccountModel::where('username',$username)->orwhere('mobilephone',$username)->first();
        $user->salt = $salt;
        $user->password = self::encryption($salt,$password);
        $user->update_time = date('Y-m-d H:i:s');
        $user->save();
        return true;
    }
    /*
    |--------------------------------------------------------------------------
    | 获取用户信息通过用户名或手机号码
    |--------------------------------------------------------------------------
    |
    | @param  string username OR mobile phone
    | @return string object user info
    |
    */
    public static function get_user_info_by_username_or_mobilephone($username)
    {
        $info = AccountModel::where('username',$username)->orwhere('mobilephone',$username)->take(1)->get();
        return isset($info[0]) ? $info[0] : NULL;
    }
    /*
    |--------------------------------------------------------------------------
    | 密码加密方法
    |--------------------------------------------------------------------------
    |
    | @param  string $password
    | @return string encrypted $password
    |
    */
    public static function encryption($salt, $password)
    {
        $h = $salt.$password;
        for($i = 0; $i<=512; $i++){
            $h = hash('sha512',$h,1);
        }
        return base64_encode($h);
    }
}