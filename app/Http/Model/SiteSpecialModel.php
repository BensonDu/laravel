<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/28
 * Time: 下午4:45
 */

namespace App\Http\Model;

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
    public static function get_special_info($site_id){
        return SiteSpecialModel::where('site_id',$site_id)->where('post_status' , 1)->where('deleted' , 0)->orderby('post_time','desc')->get();
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
        return SiteSpecialModel::where('deleted' , 0)->where('post_status' , 1)->where('site_id' , $site_id)->orderby('post_time','desc')->first();
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
    public static function get_special_brief_info($site_id,$special_id,$select=['*']){
        return SiteSpecialModel::where('deleted' , 0)->where('post_status' , 1)->where('site_id' , $site_id)->where('id' , $special_id)->first($select);
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
    | 站点专题列表
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $keyword
    | @return array
    |
    */
    public static function get_special_list($site_id,$skip,$take,$keyword,$post_status = 1,$deleted = 0){
        $select = [
            'title',
            'summary',
            'id',
            'image',
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

        return  $query->orderBy('update_time', 'desc')
            ->skip($skip)
            ->take($take)
            ->get($select);
    }

}