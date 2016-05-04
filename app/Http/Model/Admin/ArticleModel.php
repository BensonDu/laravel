<?php

namespace App\Http\Model\Admin;

use App\Http\Model\ArticleBaseModel;
use App\Http\Model\Cache\CacheModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ArticleModel extends Model
{
    protected $table = 'articles_site';
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | 获取文章列表
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  number $skip
    | @param  number $take
    | @param  string $order desc | asc
    | @param  mix $keyword
    | @return array
    |
    */
   public static function get_articles($site_id, $skip, $take, $order = 'desc', $keyword = null,$post_status = 0,$orderby = 'create_time', $uid = null, $deleted = 0){
       $select = [
           'users.nickname',
           'users.id AS user_id',
           'site_auth_map.role',
           'article_category.id AS category_id',
           'article_category.name AS category_name',
           'articles_site.id AS article_id',
           'articles_site.title',
           'articles_site.image',
           'articles_site.create_time',
           'articles_site.post_status',
           'articles_site.post_time',
           'articles_site.create_time',
           'articles_site.contribute_status'
       ];

       $query = DB::table('articles_site')
                ->leftJoin('users', 'articles_site.author_id', '=', 'users.id')
                ->leftJoin('article_category', 'article_category.id', '=', 'articles_site.category')
                ->leftJoin('site_auth_map', function($join){
                    $join->on('site_auth_map.site_id', '=', 'articles_site.site_id');
                    $join->on('site_auth_map.user_id', '=', 'articles_site.author_id');
                });

       if(empty($keyword)){
           //我的文章
           if(!is_null($uid)){
               $query->where('articles_site.author_id' ,$uid);
           }
           $query->where('articles_site.site_id' ,$site_id)
               ->where('articles_site.deleted',$deleted);
           if(!is_null($post_status)){
               if($post_status != 0){
                   $query->where('articles_site.post_status' ,'>',0);
               }
               else{
                   $query->where('articles_site.post_status' ,0);
               }
           }
       }
       else{
           $query->where(function($query) use($site_id,$keyword,$post_status,$uid,$deleted){
               //我的文章
               if(!is_null($uid)){
                   $query->where('articles_site.author_id' ,$uid);
               }
               $query->where('articles_site.site_id' ,$site_id)
                   ->where('articles_site.deleted',$deleted)
                   ->where('articles_site.title', 'LIKE', '%'.$keyword.'%');
               if(!is_null($post_status)){
                   if($post_status != 0){
                       $query->where('articles_site.post_status' ,'>',0);
                   }
                   else{
                       $query->where('articles_site.post_status' ,0);
                   }
               }
           })
           ->orWhere(function($query) use($site_id,$keyword,$post_status,$uid,$deleted){
               //我的文章
               if(!is_null($uid)){
                   $query->where('articles_site.author_id' ,$uid);
               }
               $query->where('articles_site.site_id' ,$site_id)
                   ->where('articles_site.deleted',$deleted)
                   ->where('users.nickname', 'LIKE', '%'.$keyword.'%');
               if(!is_null($post_status)){
                   if($post_status != 0){
                       $query->where('articles_site.post_status' ,'>',0);
                   }
                   else{
                       $query->where('articles_site.post_status' ,0);
                   }

               }
           });
       }

       return  $query->orderBy('articles_site.contribute_status', 'asc')
           ->orderBy('articles_site.'.$orderby, $order)
           ->skip($skip)
           ->take($take)
           ->get($select);

   }
    /*
    |--------------------------------------------------------------------------
    | 获取未发表文章列表总数
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $keyword
    | @return number
    |
    */
    public static function get_articles_count($site_id,$keyword = null,$post_status = 0,$uid = null,$deleted = 0){
        $query = DB::table('articles_site')
            ->leftJoin('users', 'articles_site.author_id', '=', 'users.id');

        if(empty($keyword)){
            if(!is_null($uid)){
                $query->where('articles_site.author_id' ,$uid);
            }
            $query->where('articles_site.site_id' ,$site_id)
                ->where('articles_site.deleted',$deleted);
            if(!is_null($post_status)){
                if($post_status != 0){
                    $query->where('articles_site.post_status' ,'>',0);
                }
                else{
                    $query->where('articles_site.post_status' ,0);
                }
            }
        }
        else{
            $query->where(function($query) use($site_id,$keyword,$post_status,$uid,$deleted){
                //我的文章
                if(!is_null($uid)){
                    $query->where('articles_site.author_id' ,$uid);
                }
                $query->where('articles_site.site_id' ,$site_id)
                    ->where('articles_site.deleted',$deleted)
                    ->where('articles_site.title', 'LIKE', '%'.$keyword.'%');

                if(!is_null($post_status)){
                    if($post_status != 0){
                        $query->where('articles_site.post_status' ,'>',0);
                    }
                    else{
                        $query->where('articles_site.post_status' ,0);
                    }
                }

            })
                ->orWhere(function($query) use($site_id,$keyword,$post_status,$uid,$deleted){
                    //我的文章
                    if(!is_null($uid)){
                        $query->where('articles_site.author_id' ,$uid);
                    }
                    $query->where('articles_site.site_id' ,$site_id)
                        ->where('articles_site.deleted',$deleted)
                        ->where('users.nickname', 'LIKE', '%'.$keyword.'%');
                    if(!is_null($post_status)){
                        if($post_status != 0){
                            $query->where('articles_site.post_status' ,'>',0);
                        }
                        else{
                            $query->where('articles_site.post_status' ,0);
                        }
                    }
                });
        }


        return  $query->count();

    }
    /*
    |--------------------------------------------------------------------------
    | 获取文章基本信息
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $article_id
    | @return object
    |
    */
    public static function get_artcile_brief_info($site_id ,$article_id,$select = ['*']){
        return DB::table('articles_site')->where('site_id',$site_id)->where('id',$article_id)->first($select);
    }
    /*
    |--------------------------------------------------------------------------
    | 设置文章发布信息
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $article_id
    | @param  string $category
    | @param  string $type
    | @param  string $time Optional
    | @return object
    |
    */
    public static function save_article_post($site_id, $article_id, $category, $post_status, $time = 0){
        CacheModel::clear_article_cache($site_id,$article_id);
        $update = [
            'category'=>$category,
            'post_status'=>$post_status,
            'post_time'=>$time,
            'contribute_status' => 1
        ];
        return  DB::table('articles_site')->where('site_id',$site_id)->where('id',$article_id)->update($update);
    }
    /*
    |--------------------------------------------------------------------------
    | 批量发布文章
    |--------------------------------------------------------------------------
    | @param array $article_ids;
    |
    */
    public static function batch_article_post($ids){
        return (!empty($ids) && is_array($ids)) ? DB::table('articles_site')->whereIn('id',$ids)->where('deleted',0)->update(['post_status' => 1,'post_time'=>now()]) : null;
    }
    /*
    |--------------------------------------------------------------------------
    | 更新文章
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $article_id
    | @param  string $data
    | @return bool
    |
    */
    public static function update_article($site_id, $article_id, $data){
        CacheModel::clear_article_cache($site_id,$article_id);
        $info = [
            'title'     => $data['title'],
            'summary'   => $data['summary'],
            'content'   => ArticleBaseModel::filter_base64_image($data['content']),
            'tags'      => $data['tags'],
            'image'     => $data['image'],
            'contribute_status' => 1,
            'site_lock' => 1,
            'update_time'=> now()
        ];
        return  DB::table('articles_site')->where('site_id',$site_id)->where('id',$article_id)->update($info);
    }
    /*
    |--------------------------------------------------------------------------
    | 删除文章
    |--------------------------------------------------------------------------
    |
    | @param  string $article_id
    | @return bool
    |
    */
    public static function delete_article($site_id, $article_id, $deleted = 1){
        CacheModel::clear_article_cache($site_id,$article_id);
        return  DB::table('articles_site')->where('site_id',$site_id)->where('id',$article_id)->update(['deleted' => $deleted]);
    }
    /*
    |--------------------------------------------------------------------------
    | 待审核的文章数量
    |--------------------------------------------------------------------------
    | @param  string $site_id
    | @return number
    |
    */
    public static function contribute_article_count($site_id){
        return DB::table('articles_site')->where('site_id',$site_id)->where('deleted',0)->where('contribute_status',0)->count();
    }
    /*
    |--------------------------------------------------------------------------
    | 站点文章是否存在
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $article_id
    | @return bool
    |
    */
    public static function is_article_exist($site_id,$article_id){
        return !!DB::table('articles_site')->where('site_id',$site_id)->where('id',$article_id)->where('deleted',0)->count();
    }

}