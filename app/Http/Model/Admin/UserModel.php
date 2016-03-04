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
    protected $table = 'site_auth_map';
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
    public static function get_user_role($site_id,$uid){
        $ret = DB::table('site_auth_map')->where('site_id',$site_id)->where('user_id',$uid)->where('deleted',0)->first();
        return isset($ret->role) ? $ret->role : null;
    }
    /*
    |--------------------------------------------------------------------------
    | 获取站点管理员列表
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  number $skip
    | @param  number $take
    | @param  string $order
    | @param  string $keyword
    | @param  string $orderby
    | @return array
    |
    */
    public static function get_site_users($site_id, $skip, $take, $order = 'desc', $keyword = null,$orderby = 'create_time'){
        $select = [
            'users.nickname',
            'users.id AS user_id',
            'site_auth_map.role',
            'site_auth_map.create_time'
        ];

        $query = DB::table('site_auth_map')
            ->leftJoin('users', 'site_auth_map.user_id', '=', 'users.id');

        if(empty($keyword)){
            $query->where('site_auth_map.site_id' ,$site_id)
            ->where('site_auth_map.deleted',0);
        }
        else{
            $query->where(function($query) use($site_id,$keyword){
                $query->where('site_auth_map.site_id' ,$site_id)
                    ->where('site_auth_map.deleted',0)
                    ->where('site_auth_map.user_id', 'LIKE', '%'.$keyword.'%');
            })
                ->orWhere(function($query) use($site_id,$keyword){
                    $query->where('site_auth_map.site_id' ,$site_id)
                        ->where('site_auth_map.deleted',0)
                        ->where('users.nickname', 'LIKE', '%'.$keyword.'%');
                });
        }

        return  $query->orderBy('site_auth_map.'.$orderby, $order)
            ->skip($skip)
            ->take($take)
            ->get($select);
    }
    /*
    |--------------------------------------------------------------------------
    | 获取站点管理员列表数量
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  number $skip
    | @param  number $take
    | @param  string $order
    | @param  string $keyword
    | @param  string $orderby
    | @return number
    |
    */
    public static function get_site_users_count($site_id, $keyword = null){

        $query = DB::table('site_auth_map')
            ->leftJoin('users', 'site_auth_map.user_id', '=', 'users.id');

        if(empty($keyword)){
            $query->where('site_auth_map.site_id' ,$site_id)
                ->where('site_auth_map.deleted',0);
        }
        else{
            $query->where(function($query) use($site_id,$keyword){
                $query->where('site_auth_map.site_id' ,$site_id)
                    ->where('site_auth_map.deleted',0)
                    ->where('site_auth_map.user_id', 'LIKE', '%'.$keyword.'%');
            })
                ->orWhere(function($query) use($site_id,$keyword){
                    $query->where('site_auth_map.site_id' ,$site_id)
                        ->where('site_auth_map.deleted',0)
                        ->where('users.nickname', 'LIKE', '%'.$keyword.'%');
                });
        }

        return  $query->count();
    }
    /*
    |--------------------------------------------------------------------------
    | 获取用户信息
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $user_id
    | @return bool
    |
    */
    public static function user_info($site_id, $user_id){
        $select = [
            'users.nickname',
            'users.avatar',
            'users.id AS user_id',
            'site_auth_map.role'
        ];
        return  DB::table('site_auth_map')
            ->leftJoin('users', 'site_auth_map.user_id', '=', 'users.id')
            ->where('site_auth_map.site_id' ,$site_id)
            ->where('site_auth_map.user_id' ,$user_id)
            ->where('site_auth_map.deleted',0)->first($select);
    }
    /*
    |--------------------------------------------------------------------------
    | 删除用户
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $user_id
    | @return bool
    |
    */
    public static function delete_user($site_id, $user_id){
        return  DB::table('site_auth_map')->where('site_id',$site_id)->where('user_id',$user_id)->update(['deleted' => 1]);
    }
    /*
    |--------------------------------------------------------------------------
    | 更新用户
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $user_id
    | @param  string $role
    | @return bool
    |
    */
    public static function update_user($site_id, $user_id, $role,$create_time = null){
        $update = ['role'=>$role];
        if(!is_null($create_time))$update['create_time'] = $create_time;
        return  DB::table('site_auth_map')->where('site_id',$site_id)->where('user_id',$user_id)->update($update);
    }
    /*
    |--------------------------------------------------------------------------
    | 搜索用户
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $keyword
    | @return array
    |
    */
    public static function search_user($keyword,$skip = 0, $take = 10){
        $select = ['nickname','avatar','id'];
        $query =   DB::table('users');
        $query->where(function($query) use($keyword){
            $query->where('id', 'LIKE', '%'.$keyword.'%');
        })
        ->orWhere(function($query) use($keyword){
            $query->where('nickname', 'LIKE', '%'.$keyword.'%');
        });
        return $query->skip($skip)
                ->take($take)
                ->get($select);
    }
    /*
    |--------------------------------------------------------------------------
    | 新增管理用户
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $user_id
    | @param  string $role
    | @return array
    |
    */
    public static function add_user($site_id,$user_id,$role){
        $ex =  DB::table('site_auth_map')->where('site_id',$site_id)->where('user_id',$user_id)->count();
        if($ex){
            return self::update_user($site_id,$user_id,$role,now());
        }
        else{
            $user = new UserModel();
            $user->site_id          = $site_id;
            $user->user_id          = $user_id;
            $user->role             = $role;
            $user->create_time      = now();
            return  $user->save();
        }
    }

}