<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/20
 * Time: 下午4:48
 */

namespace App\Http\Model;

use App\Http\Model\Cache\SiteCacheModel;
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
        //获取缓存
        $cache = SiteCacheModel::site_info_get($id);
        if(!empty($cache)) return $cache;
        //数据库查询
        $info = SiteModel::where('id',$id)->first();
        //设置缓存
        if(isset($info->id)) SiteCacheModel::site_info_set($id,$info);

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
    public static function get_site_info_list($ids,$select = ['id','name','custom_domain','platform_domain']){
        if(is_array($ids)){
            return SiteModel::whereIn('site_info.id',$ids)->get($select);
        }
        else{
            return SiteModel::where('site_info.id',$ids)->first($select);
        }
    }
    /*
     |--------------------------------------------------------------------------
     | 更新站点信息
     |--------------------------------------------------------------------------
     */
    public static function update_site_info($id,$update){
        SiteModel::where('id',$id)->update($update);
        SiteCacheModel::site_info_clear($id);
        SiteCacheModel::site_nav_clear($id);
        return true;
    }
    /*
     |--------------------------------------------------------------------------
     | 获取站点导航列表 展示端
     |--------------------------------------------------------------------------
     */
    public static function site_nav_list($site_id){

        //获取缓存
        $cache = SiteCacheModel::site_nav_get($site_id);
        if(!empty($cache)) return $cache;

        $custom = DB::table('site_nav')->where('deleted',0)->where('site_id',$site_id)->where('display','1')->orderBy('id','asc')->get();
        $info   = SiteModel::where('id',$site_id)->first();
        $nav = [
            [
                'id'   => 'home',
                'link' => '/',
                'name' => $info->home
            ]
        ];
        //站点是否拥有专题
        if(!!SiteSpecialModel::get_special_count($_ENV['site_id'],0,1)){
            $nav[] = [
                'id'   => 'special',
                'link' => '/special',
                'name' => $info->special
            ];
        }
        if(!empty($custom)){
            foreach ($custom as $v){
                $nav[] = [
                    'id'   => '',
                    'link' => $v->link,
                    'name' => $v->name
                ];
            }
        }
        SiteCacheModel::site_nav_set($site_id, $nav);
        return $nav;
    }
    /*
     |--------------------------------------------------------------------------
     | 获取站点导航列表 管理端
     |--------------------------------------------------------------------------
     */
    public static function get_site_nav($site_id,$display = 1){
        $query = DB::table('site_nav')->where('deleted',0)->where('site_id',$site_id);
        if(!is_null($display)){
            $query->where('display',$display);
        }
        return $query->orderBy('id','asc')->get();
    }
    /*
     |--------------------------------------------------------------------------
     | 更新站点自定义导航
     |--------------------------------------------------------------------------
     */
    public static function update_site_nav($site_id,$id,$update){
        DB::table('site_nav')->where('deleted',0)->where('site_id',$site_id)->where('id',$id)->update($update);
        return SiteCacheModel::site_nav_clear($site_id);
    }
    /*
     |--------------------------------------------------------------------------
     | 获取站点导航总数
     |--------------------------------------------------------------------------
     */
    public static function get_site_nav_count($site_id){
        return DB::table('site_nav')->where('deleted',0)->where('site_id',$site_id)->count();
    }
    /*
     |--------------------------------------------------------------------------
     | 添加站点自定义导航
     |--------------------------------------------------------------------------
     */
    public static function add_site_nav($site_id,$name,$link,$display){
        DB::table('site_nav')->insert([
            'site_id'   => $site_id,
            'name'      => $name,
            'link'      => $link,
            'display'   => $display
        ]);
        SiteCacheModel::site_info_clear($site_id);
        SiteCacheModel::site_nav_clear($site_id);
        return true;
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
     | 获得用户对应站点 角色
     |--------------------------------------------------------------------------
     */
    public static function user_site_role($site_id,$user_id){
        $auth = DB::table('site_auth_map')->where('user_id',$user_id)->where('site_id',$site_id)->where('deleted',0)->first(['role']);
        return isset($auth->role) ? $auth->role : 0;
    }
    /*
     |--------------------------------------------------------------------------
     | 用户端->获取站点列表
     |--------------------------------------------------------------------------
     |
     | @param  array $keyword
     | @return array
     |
     */
    public static function get_site_list($skip = 0, $take = 10,$keyword = null,$except = [],$select = ['id','name','custom_domain','platform_domain']){
        $query =  DB::table('site_info')->whereNotIn('id',$except)->where('valid','1');
        if(!is_null($keyword)){
            $query->where('name', 'LIKE', '%'.$keyword.'%');
        }
        return $query->take($take)->skip($skip)->get($select);
    }
    /*
     |--------------------------------------------------------------------------
     | 站点管理->获取站点列表
     |--------------------------------------------------------------------------
     |
     */
    public static function admin_get_site_list($skip = 0, $take = 10,$keyword = null,$order = 'asc',$select = ['id','name','custom_domain','platform_domain','create_time','valid']){
        $query =  DB::table('site_info');
        if(!is_null($keyword)){
            $query->where('name', 'LIKE', '%'.$keyword.'%');
        }
        return $query->orderBy('id',$order)->take($take)->skip($skip)->get($select);
    }
    /*
     |--------------------------------------------------------------------------
     | 站点管理->获取站点总数
     |--------------------------------------------------------------------------
     |
     */
    public static function admin_get_site_count($keyword = null){
        $query =  DB::table('site_info');
        if(!is_null($keyword)){
            $query->where('name', 'LIKE', '%'.$keyword.'%');
        }
        return $query->count();
    }
    /*
     |--------------------------------------------------------------------------
     | 站点管理->添加站点
     |--------------------------------------------------------------------------
     |
     */
    public static function admin_add_site($platform,$domain,$name,$icp){
        $query = SiteModel::where('name',$name);
        if($platform){
            $query->orWhere('platform_domain',$domain);
        }
        else{
            $query->orWhere('custom_domain',$domain);
        }
        $exist = $query->count();
        if(!empty($exist))return false;
        $now = now();
        $insert = [
            'name'              => $name,
            'icp'               => $icp,
            'description'       => '创之媒体平台旗下子站点，创之媒体平台，发现垂直媒体的价值',
            'keywords'          => '创之;创见;创之新媒体平台;TECH2IPO;',
            'logo'              => 'http://qiniu.cdn-chuang.com/chuang-logo-circle.png',
            'slogan'            => '用心创作快乐',
            'update_time'       => $now,
            'create_time'       => $now,
            'favicon'           => 'http://qiniu.cdn-chuang.com/chuang.png',
            'mobile_logo'       => 'http://qiniu.cdn-chuang.com/platform-logo-m.png',
            'thirdparty_logo'   => 'http://qiniu.cdn-chuang.com/platform-logo-thirdparty.png'
        ];

        if($platform){
            $insert['platform_domain'] = $domain;
        }
        else{
            $insert['custom_domain']    =  $domain;
            $insert['mobile_domain']    =  'm.'.$domain;
        }
        SiteModel::insert($insert);
        SiteCacheModel::site_route_clear();
        return true;
    }
    /*
     |--------------------------------------------------------------------------
     | 获取站点 ID 列表
     |--------------------------------------------------------------------------
     |
     */
    public static function get_site_id_list($contribute = 1){
        $query = DB::table('site_info');
        if(!is_null($contribute)){
            $query->where('contribute',$contribute);
        }
        $list = $query->get(['id']);
        $ret = [];
        if(!empty($list)){
            foreach ($list as $v){
                $ret[] = $v->id;
            }
        }
        return $ret;
    }


}