<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/20
 * Time: 下午4:48
 */

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use PRedis;
use Illuminate\Support\Facades\DB;

class SiteModel extends Model
{
    protected $table = 'site_info';
    public $timestamps = false;

   /*
    |--------------------------------------------------------------------------
    | 检查site id 是否存在
    |--------------------------------------------------------------------------
    |
    | @param  string | int | array $site
    | @return bool
    |
    */
    public static function check_site($site){
        $where = is_array($site) ? $site : [$site];
        $site = SiteModel::whereIn('id', $where)->count();
        return count($where) == $site;
    }
    /*
     |--------------------------------------------------------------------------
     | 获取站点信息
     |--------------------------------------------------------------------------
     |
     | @param  string $site_id
     | @return bool
     |
     */
    public static function get_site_info($id){
        $rediskey = config('cache.prefix').':'. config('cache.site.info').':'.$id;
        if(PRedis::exists($rediskey)){
            return unserialize(PRedis::get($rediskey));
        }
        $info = SiteModel::leftJoin('site_routing','site_info.id', '=', 'site_routing.site_id')
            ->where('site_info.id',$id)->first();
        if(isset($info->id)){
            PRedis::set($rediskey,serialize($info));
            PRedis::expire($rediskey,600);
        }
        return $info;
    }
    /*
     |--------------------------------------------------------------------------
     | 获取站点信息
     |--------------------------------------------------------------------------
     |
     | @param  array $site_ids
     | @return array
     |
     */
    public static function get_site_info_list($ids,$select = ['*']){
        return (is_array($ids) && !empty($ids)) ? SiteModel::whereIn('id',$ids)->get($select) : [];
    }
    /*
     |--------------------------------------------------------------------------
     | 用户是否有站点权限
     |--------------------------------------------------------------------------
     |
     | @param  array $user_id
     | @param  array $site_id
     | @return bool
     |
     */
    public static function check_user_site_auth($site_id, $user_id){
        $auth = DB::table('site_auth_map')->where('user_id',$user_id)->where('site_id',$site_id)->where('deleted',0)->first(['role']);
        return (isset($auth->role) && $auth->role > 0);
    }
    /*
     |--------------------------------------------------------------------------
     | 获取站点列表
     |--------------------------------------------------------------------------
     |
     | @param  array $keyword
     | @return array
     |
     */
    public static function get_site_list($skip = 0, $take = 10,$keyword = null,$except = [],$select = ['*']){
        $query =  DB::table('site_info')->whereNotIn('id',$except);
        if(!is_null($keyword)){
            $query->where('name', 'LIKE', '%'.$keyword.'%');
        }
        return $query->take($take)->skip($skip)->get($select);
    }


}