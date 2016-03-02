<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/27
 * Time: 下午10:32
 */

namespace App\Http\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserModel extends Model
{
    protected $table = 'articles_site';
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | 获取用户对应站点 身份ID
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  mix $user_id
    | @return number
    |
    */
    public static function get_user_role_($site_id,$uid){
        $ret = DB::table('site_auth_map')->where('site_id',$site_id)->where('user_id',$uid)->first();
        return isset($ret->role) ? $ret->role : null;
    }

}