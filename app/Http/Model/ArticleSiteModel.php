<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/20
 * Time: 下午5:36
 */

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ArticleSiteModel extends Model
{
    protected $table = 'articles_site';
    public $timestamps = false;

   /*
    |--------------------------------------------------------------------------
    | 获取站点文章信息
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $ariticle_id
    | @return array  $article_list
    |
    */
    public static function get_artilce_detail($site_id,$id){
        $select = [
            'users.id AS user_id',
            'users.nickname',
            'users.avatar',
            'article_category.name AS category_name',
            'articles_site.id AS article_id',
            'articles_site.site_id',
            'articles_site.title',
            'articles_site.summary',
            'articles_site.content',
            'articles_site.tags',
            'articles_site.create_time',
            'articles_site.image'
        ];
        return DB::table('articles_site')
                ->leftJoin('users', 'articles_site.author_id', '=', 'users.id')
                ->leftJoin('article_category', 'article_category.id', '=', 'articles_site.category')
                ->where('articles_site.site_id' ,$site_id)
                ->where('articles_site.id',$id)
                ->where('articles_site.deleted',0)
                ->first($select);
    }
    /*
    |--------------------------------------------------------------------------
    | 获取站点热榜列表
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @return array
    |
    */
    public static function get_hot_list($id){
        $select = [
            'users.id AS user_id',
            'users.nickname',
            'articles_site.id AS article_id',
            'articles_site.title',
            'articles_site.summary',
            'articles_site.create_time',
            'articles_site.image'
        ];
        $items =  DB::table('articles_site')
            ->leftJoin('users', 'articles_site.author_id', '=', 'users.id')
            ->where('articles_site.site_id' ,$id)
            ->where('articles_site.deleted',0)
            ->orderBy('create_time','desc')
            ->take(3)
            ->get($select);

        $list = [];

        if(!empty($items)){
            foreach($items as $k => $v){
                $list[$k]['author']     = $v->nickname;
                $list[$k]['user_id']    = $v->user_id;
                $list[$k]['article_id'] = $v->article_id;
                $list[$k]['title']      = $v->title;
                $list[$k]['time']       = date('m-d H:i',strtotime($v->create_time));
            }
        }

        return $list;
    }

    /*
     |--------------------------------------------------------------------------
     | 获取站点文章类型列表
     |--------------------------------------------------------------------------
     |
     | @param  string $site_id
     | @return array  $article_list
     |
    */
    public static function get_article_categorys($id){
        $items =  DB::table('article_category')
            ->where('site_id' ,$id)
            ->orderBy('order','asc')
            ->take(5)
            ->get();
        $categorys = [];
        if(!empty($items)){
            foreach($items as $k => $v){
                $categorys[$k]['name']     = $v->name;
                $categorys[$k]['id']       = $v->id;
            }
        }
        array_unshift($categorys,[
            'id'    => 0,
            'name'  => '全部'
        ]);
        return $categorys;
    }
    /*
    |--------------------------------------------------------------------------
    | 获取站点主页文章
    |--------------------------------------------------------------------------
    |
    | @param  string $user_id
    | @param  string $skip
    | @param  string $select
    | @return array  $article_list
    |
    */
    public static function get_home_article_list($site_id,$skip = 0,$category = 0){
        $select = [
            'users.id AS user_id',
            'users.nickname',
            'users.avatar',
            'article_category.name AS category_name',
            'articles_site.id AS article_id',
            'articles_site.title',
            'articles_site.summary',
            'articles_site.tags',
            'articles_site.create_time',
            'articles_site.image'
        ];
        $ret =  DB::table('articles_site')
                ->leftJoin('users', 'articles_site.author_id', '=', 'users.id')
                ->leftJoin('article_category', 'article_category.id', '=', 'articles_site.category')
                ->where('articles_site.site_id' ,$site_id)
                ->where('articles_site.deleted',0);

        if(!!$category) $ret = $ret->where('category',$category);

        return $ret
            /* TODO
             * ->where('post_status',1)
             */
            ->orderBy('create_time', 'desc')
            ->take(15)
            ->skip($skip)
            ->get($select);
    }
    /*
    |--------------------------------------------------------------------------
    | 获取站点文章总数
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @return array  $article_list
    |
    */
    public static function get_article_count($site_id, $category = 0){
        $ret = ArticleSiteModel::where('site_id' ,$site_id)->where('deleted',0);

        if(!!$category) $ret = $ret->where('category',$category);

        return $ret
                /* TODO
                 * ->where('post_status',1)
                 */
                ->count();
    }
    /*
    |--------------------------------------------------------------------------
    | 关键词搜索文章
    |--------------------------------------------------------------------------
    |
    | @param  string $keyword
    | @return array  $article_list
    |
    */
    public static function search_article($site_id, $keyword, $skip, $take = 10){
        $select = [
            'users.id AS user_id',
            'users.nickname',
            'articles_site.id AS article_id',
            'articles_site.title',
            'articles_site.summary',
            'articles_site.create_time'
        ];
        return  DB::table('articles_site')
            ->leftJoin('users', 'articles_site.author_id', '=', 'users.id')
            ->where(function($query) use($site_id,$keyword){
                $query->where('articles_site.site_id' ,$site_id)
                ->where('articles_site.deleted',0)
                ->where('articles_site.title', 'LIKE', '%'.$keyword.'%');
            })
            ->orWhere(function($query) use($site_id,$keyword){
                $query->where('articles_site.site_id' ,$site_id)
                    ->where('articles_site.deleted',0)
                    ->where('users.nickname', 'LIKE', '%'.$keyword.'%');
            })
            ->orderBy('create_time', 'desc')
            ->take($take)
            ->skip($skip)
            ->get($select);
    }
    /*
    |--------------------------------------------------------------------------
    | 关键词搜索文章数量
    |--------------------------------------------------------------------------
    |
    | @param  string $tag
    | @return int  $count
    |
    */
    public static function search_article_count($site_id, $keyword){
        return  DB::table('articles_site')
            ->leftJoin('users', 'articles_site.author_id', '=', 'users.id')
            ->where(function($query) use($site_id,$keyword){
                $query->where('articles_site.site_id' ,$site_id)
                    ->where('articles_site.deleted',0)
                    ->where('articles_site.title', 'LIKE', '%'.$keyword.'%');
            })
            ->orWhere(function($query) use($site_id,$keyword){
                $query->where('articles_site.site_id' ,$site_id)
                    ->where('articles_site.deleted',0)
                    ->where('users.nickname', 'LIKE', '%'.$keyword.'%');
            })
            ->count();
    }
    /*
    |--------------------------------------------------------------------------
    | 标签对应文章
    |--------------------------------------------------------------------------
    |
    | @param  string $keyword
    | @return array  $article_list
    |
    */
    public static function tag_article($site_id, $tag, $skip = 0, $take = 10){
        $select = [
            'users.id AS user_id',
            'users.nickname',
            'users.avatar',
            'articles_site.id AS article_id',
            'articles_site.title',
            'articles_site.summary',
            'articles_site.tags',
            'articles_site.create_time',
            'articles_site.image'
        ];
        return DB::table('articles_site')
            ->leftJoin('users', 'articles_site.author_id', '=', 'users.id')
            ->where('articles_site.site_id' ,$site_id)
            ->where('articles_site.deleted',0)
            ->where('articles_site.tags', 'LIKE', '%'.$tag.'%')
            /* TODO
             * ->where('post_status',1)
             */
            ->orderBy('create_time', 'desc')
            ->take($take)
            ->skip($skip)
            ->get($select);
    }
    /*
    |--------------------------------------------------------------------------
    | 标签对应文章数量
    |--------------------------------------------------------------------------
    |
    | @param  string $keyword
    | @return int  $count
    |
    */
    public static function tag_article_count($site_id, $tag){
        return DB::table('articles_site')
            ->leftJoin('users', 'articles_site.author_id', '=', 'users.id')
            ->where('articles_site.site_id' ,$site_id)
            ->where('articles_site.deleted',0)
            ->where('articles_site.tags', 'LIKE', '%'.$tag.'%')
            /* TODO
             * ->where('post_status',1)
             */
            ->count();
    }

}