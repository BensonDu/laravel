<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/28
 * Time: 下午4:45
 */

namespace App\Http\Model;

use App\Http\Model\Cache\SiteCacheModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SiteSpecialModel extends Model
{
    protected $table = 'site_special';
    public $timestamps = false;

    /*
     |--------------------------------------------------------------------------
     | 获取站点专题列表
     |--------------------------------------------------------------------------
     |
     | @param  string $site_id
     | @param  string $special_id
     | @return array
     |
     */
    public static function get_special_all($site_id){
        return SiteSpecialModel::where('site_id',$site_id)->where('post_status' , 1)->where('deleted' , 0)->orderby('update_time','desc')->get();
    }
    /*
     |--------------------------------------------------------------------------
     | 获取站点最近专题
     |--------------------------------------------------------------------------
     |
     | @param  string $site_id
     | @return string $special_id
     |
     */
    public static function get_first_special($site_id){
        return SiteSpecialModel::where('deleted' , 0)->where('post_status' , 1)->where('site_id' , $site_id)->orderby('update_time','desc')->first();
    }
    /*
     |--------------------------------------------------------------------------
     | 获取站点主题文章列表
     |--------------------------------------------------------------------------
     |
     | @param  string $special_id
     | @return array
     |
     */
    public static function get_special_article_list($special_id){
        $list = DB::table('site_special_map')->leftJoin('articles_site', 'site_special_map.article_id', '=', 'articles_site.id')->where('site_special_map.special_id', '=', $special_id)->orderby('site_special_map.order','asc')->orderby('site_special_map.create_time','desc')->get(['articles_site.id','articles_site.title','articles_site.summary']);
        return $list;
    }
    /*
     |--------------------------------------------------------------------------
     | 获取专题基本信息
     |--------------------------------------------------------------------------
     |
     | @param  string $special_id
     | @return array
     |
     */
    public static function get_special_brief_info($site_id,$special_id,$select=['*'],$post_status = 1){
        $special = SiteSpecialModel::where('deleted' , 0)->where('site_id' , $site_id)->where('id' , $special_id);
        if(!is_null($post_status)){
            $special->where('post_status' , $post_status);
        }
        return $special->first($select);
    }
    /*
    |--------------------------------------------------------------------------
    | 站点专题是否存在
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $article_id
    | @return bool
    |
    */
    public static function is_special_exist($site_id,$special_id){
        return !!SiteSpecialModel::where('site_id',$site_id)->where('id',$special_id)->where('deleted',0)->count();
    }
    /*
    |--------------------------------------------------------------------------
    | 站点专题总数
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @return array
    |
    */
    public static function get_special_count($site_id,$deleted = 0,$post_status = null){
        $query = SiteSpecialModel::where('site_id' ,$site_id)->where('deleted',$deleted);
        if(!is_null($post_status)){
            $query->where('post_status',$post_status);
        }
        return $query->count();
    }
    /*
    |--------------------------------------------------------------------------
    | 站点专题删除
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @return array
    |
    */
    public static function special_delete($site_id,$id){
        SiteSpecialModel::where('site_id' ,$site_id)->where('id',$id)->update(['deleted' => 1]);
        return SiteCacheModel::site_nav_clear($site_id);
    }
    /*
    |--------------------------------------------------------------------------
    | 站点专题更新
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @return array
    |
    */
    public static function special_update($site_id,$id,$data){
        SiteSpecialModel::where('site_id' ,$site_id)->where('id',$id)->update($data);
        return SiteCacheModel::site_nav_clear($site_id);
    }
    /*
    |--------------------------------------------------------------------------
    | 站点专题新增
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @return array
    |
    */
    public static function special_add($site_id,$data){
        $special = new SiteSpecialModel();
        $special->site_id       = $site_id;
        $special->title         = $data['title'];
        $special->summary       = $data['summary'];
        $special->bg_image      = $data['bg_image'];
        $special->image         = $data['image'];
        $special->list          = $data['list'];
        $special->create_time   = now();
        $special->update_time   = now();
        $special->save();
        return SiteCacheModel::site_nav_clear($site_id);
    }
    /*
    |--------------------------------------------------------------------------
    | 站点专题列表
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $keyword
    | @return array
    |
    */
    public static function get_special_list($site_id,$skip,$take,$keyword,$post_status = 1,$deleted = 0,$orderby = 'update_time',$order = 'desc'){
        $select = [
            'title',
            'summary',
            'id',
            'image',
            'post_status',
            'create_time',
            'update_time'
        ];

        if(empty($keyword)){
            $query = SiteSpecialModel::where('site_id' ,$site_id)
                ->where('deleted',$deleted);
            if(!is_null($post_status)){
                $query->where('post_status' ,$post_status);
            }
        }
        else{
            $query = SiteSpecialModel::where(function($query) use($site_id,$keyword,$post_status,$deleted){
                $query->where('site_id' ,$site_id)
                    ->where('deleted',$deleted)
                    ->where('title', 'LIKE', '%'.$keyword.'%');
                if(!is_null($post_status)){
                    $query->where('post_status' ,$post_status);
                }
            });
        }

        return  $query->orderBy($orderby, $order)
            ->skip($skip)
            ->take($take)
            ->get($select);
    }

}