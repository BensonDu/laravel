<?php
/**
 * Created by PhpStorm.
 * Blacklist: Benson
 * Date: 16/2/27
 * Time: 下午10:32
 */

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BlacklistModel extends Model
{
    protected $table = 'site_blacklist';
    public $timestamps = false;
    
    /*
    |--------------------------------------------------------------------------
    | 获取站点黑名单列表
    |--------------------------------------------------------------------------
    */
    public static function get_site_blacklist($site_id, $skip, $take, $order = 'desc', $keyword = null,$orderby = 'time'){
        $select = [
            'users.nickname',
            'users.id AS user_id',
            'site_blacklist.time'
        ];

        $query = DB::table('site_blacklist')
            ->leftJoin('users', 'site_blacklist.user_id', '=', 'users.id');

        if(empty($keyword)){
            $query->where('site_blacklist.site_id' ,$site_id)
            ->where('site_blacklist.deleted',0);
        }
        else{
            $query->where(function($query) use($site_id,$keyword){
                $query->where('site_blacklist.site_id' ,$site_id)
                    ->where('site_blacklist.deleted',0)
                    ->where('site_blacklist.user_id', 'LIKE', '%'.$keyword.'%');
            })
                ->orWhere(function($query) use($site_id,$keyword){
                    $query->where('site_blacklist.site_id' ,$site_id)
                        ->where('site_blacklist.deleted',0)
                        ->where('users.nickname', 'LIKE', '%'.$keyword.'%');
                });
        }

        return  $query->orderBy('site_blacklist.'.$orderby, $order)
            ->skip($skip)
            ->take($take)
            ->get($select);
    }
    /*
    |--------------------------------------------------------------------------
    | 获取站点黑名单中用户数量
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
    public static function get_site_blacklist_count($site_id, $keyword = null){

        $query = DB::table('site_blacklist')
            ->leftJoin('users', 'site_blacklist.user_id', '=', 'users.id');

        if(empty($keyword)){
            $query->where('site_blacklist.site_id' ,$site_id)
                ->where('site_blacklist.deleted',0);
        }
        else{
            $query->where(function($query) use($site_id,$keyword){
                $query->where('site_blacklist.site_id' ,$site_id)
                    ->where('site_blacklist.deleted',0)
                    ->where('site_blacklist.user_id', 'LIKE', '%'.$keyword.'%');
            })
                ->orWhere(function($query) use($site_id,$keyword){
                    $query->where('site_blacklist.site_id' ,$site_id)
                        ->where('site_blacklist.deleted',0)
                        ->where('users.nickname', 'LIKE', '%'.$keyword.'%');
                });
        }

        return  $query->count();
    }
    /*
    |--------------------------------------------------------------------------
    | 移除黑名单
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $user_id
    | @return bool
    |
    */
    public static function delete_user($site_id, $user_id){
        return  DB::table('site_blacklist')->where('site_id',$site_id)->where('user_id',$user_id)->update(['deleted' => 1]);
    }
    /*
    |--------------------------------------------------------------------------
    | 添加用户到黑名单
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $user_id
    | @param  string $role
    | @return array
    |
    */
    public static function add_user($site_id,$user_id){
        $ex =  DB::table('site_blacklist')->where('site_id',$site_id)->where('user_id',$user_id)->count();
        if($ex){
            return  DB::table('site_blacklist')->where('site_id',$site_id)->where('user_id',$user_id)->update(['time'=>now(),'deleted'=>0]);
        }
        else{
            $user = new BlacklistModel();
            $user->site_id          = $site_id;
            $user->user_id          = $user_id;
            $user->time      = now();
            return  $user->save();
        }
    }
    /*
    |--------------------------------------------------------------------------
    | 用户是否包含在黑名单
    |--------------------------------------------------------------------------
    */
    public static function in_blacklist($site_id,$user_id){
        return BlacklistModel::where('site_id',$site_id)->where('user_id',$user_id)->where('deleted',0)->count();
    }

}