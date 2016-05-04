<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/4/27
 * Time: 上午9:44
 */

namespace App\Http\Model\User;

use App\Http\Model\Cache\CacheModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditModel extends Model
{
    protected $table = 'articles_user';
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | 用户文章是否存在
    |--------------------------------------------------------------------------
    */
    public static function article_brief_info($uid,$id){
        return EditModel::where('.id',$id)->where('user_id',$uid)->where('deleted',0)->first(['id','hash']);
    }
    /*
    |--------------------------------------------------------------------------
    | 用户在 站点的文章发布状态 发布列表
    |--------------------------------------------------------------------------
    */
    public static function article_site_info($id){
        $select = [
            'site_info.name',
            'articles_site.id',
            'articles_site.site_id',
            'articles_site.post_status',
            'articles_site.category',
            'articles_site.post_time',
            'articles_site.update_time',
            'articles_site.site_lock',
            'articles_site.contribute_status',
            'articles_site.hash',
            'articles_site.deleted'
        ];
        return DB::table('articles_site')->leftJoin('site_info', 'site_info.id', '=', 'articles_site.site_id')->where('articles_site.source_id',$id)->get($select);
    }
    /*
    |--------------------------------------------------------------------------
    | 投稿到站点
    |--------------------------------------------------------------------------
    */
    public static function contribute($site_id,$id,$uid){
        $select = ['title','summary','content','tags','image','hash'];
        //站点是否存在该文章 存在:已经投稿
        $site = DB::table('articles_site')->where('source_id',$id)->where('site_id',$site_id)->count();
        if($site)return false;
        //文章是否存在
        $info = EditModel::where('id',$id)->where('user_id',$uid)->where('deleted',0)->first($select);
        if(!isset($info->title))return false;
        $now = now();
        return DB::table('articles_site')->insert([
            'site_id'           => $site_id,
            'source_id'        => $id,
            'author_id'         => $uid,
            'title'             => $info->title,
            'summary'           => $info->summary,
            'content'           => $info->content,
            'tags'              => $info->tags,
            'image'             => $info->image,
            'hash'              => $info->hash,
            'create_time'       => $now,
            'update_time'   => $now
        ]);
        
    }
    /*
    |--------------------------------------------------------------------------
    | 发布到站点
    |--------------------------------------------------------------------------
    */
    public static function post($site_id, $uid, $user_article_id, $category, $post_status, $time = 0){
        $select = ['title','summary','content','tags','image','hash'];

        $info =  EditModel::where('id',$user_article_id)->where('user_id',$uid)->where('deleted',0)->first($select);
        //文章不存在
        if(!isset($info->title))return false;

        $site = DB::table('articles_site')->where('source_id',$user_article_id)->where('site_id',$site_id)->first(['id','deleted','site_lock']);

        $now = now();
        //已经存在发布文章 更改发布信息
        if(isset($site->id)){
            //管理员已锁定
            if($site->deleted == '1' || $site->site_lock) return false;
            //清除缓存
            CacheModel::clear_article_cache($site_id,$site->id);
            DB::table('articles_site')->where('id',$site->id)->update(
                [
                    'category'          => $category,
                    'post_status'       => $post_status,
                    'post_time'         => $time,
                    'contribute_status' => 1
                ]
            );
            $last_id = $site->id;
        }
        //不存在 新建站点文章
        else{
            $last_id = DB::table('articles_site')->insertGetId(
                [
                    'site_id'           => $site_id,
                    'source_id'         => $user_article_id,
                    'author_id'         => $uid,
                    'title'             => $info->title,
                    'summary'           => $info->summary,
                    'content'           => $info->content,
                    'tags'              => $info->tags,
                    'image'             => $info->image,
                    'hash'              => $info->hash,
                    'category'          => $category,
                    'post_status'       => $post_status,
                    'contribute_status' => 1,
                    'post_time'         => $time,
                    'create_time'       => $now,
                    'update_time'       => $now
                ]
            );
        }
        return $last_id;
    }

    /*
    |--------------------------------------------------------------------------
    | 推送更新到站点
    |--------------------------------------------------------------------------
    */
    public static function pushsite($site_id, $uid, $user_article_id){
        $select = ['title','summary','content','tags','image','hash'];

        $info =  EditModel::where('id',$user_article_id)->where('user_id',$uid)->where('deleted',0)->first($select);
        //文章不存在
        if(!isset($info->title))return false;

        $site = DB::table('articles_site')->where('source_id',$user_article_id)->where('site_id',$site_id)->where('deleted',0)->first(['id','hash','site_lock']);

        if(!isset($site->id) || ($site->site_lock) || $site->hash == $info->hash)return false;

        $now = now();
        
        return DB::table('articles_site')->where('id',$site->id)->update(
            [
                'title'             => $info->title,
                'summary'           => $info->summary,
                'content'           => $info->content,
                'tags'              => $info->tags,
                'image'             => $info->image,
                'hash'              => $info->hash,
                'update_time'       => $now
            ]
        );

    }

}