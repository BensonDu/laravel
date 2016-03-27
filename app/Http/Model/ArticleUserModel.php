<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/20
 * Time: 上午11:31
 */
namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ArticleUserModel extends Model
{

    protected $table = 'articles_user';
    public $timestamps = false;

   /*
    |--------------------------------------------------------------------------
    | 获取用户文章列表
    |--------------------------------------------------------------------------
    |
    | @param  string $user_id
    | @return array  $article_list
    |
    */
    public static function get_article($user_id,$select = ['*']){
        return ArticleUserModel::where('user_id' ,$user_id)->where('deleted',0)->orderBy('update_time', 'desc')->get($select);
    }
    /*
    |--------------------------------------------------------------------------
    | 获取用户文章总数
    |--------------------------------------------------------------------------
    |
    | @param  string $user_id
    | @return array  $article_list
    |
    */
    public static function get_article_count($user_id){
        return ArticleUserModel::where('user_id' ,$user_id)->where('deleted',0)->where('post_status',2)->count();
    }
    /*
    |--------------------------------------------------------------------------
    | 获取用户主页文章
    |--------------------------------------------------------------------------
    |
    | @param  string $user_id
    | @param  string $skip
    | @param  string $select
    | @return array  $article_list
    |
    */
    public static function get_home_article_list($user_id,$skip = 0){
        $select = '
        articles_user.id,
        articles_user.title,
        articles_user.summary,
        articles_user.tags,
        articles_user.image,
        articles_user.create_time,
        articles_user.favorites,
        articles_user.likes
        ';
        $list = ArticleUserModel::select(DB::raw($select))
            ->where('articles_user.user_id' ,$user_id)
            ->where('articles_user.deleted',0)
            ->where('articles_user.post_status',2)
            ->orderBy('articles_user.update_time', 'desc')
            ->take(10)->skip($skip)->get();

        return $list;
    }
    /*
    |--------------------------------------------------------------------------
    | 获取用户收藏文章
    |--------------------------------------------------------------------------
    |
    | @param  string $user_id
    | @param  string $skip
    | @param  string $select
    | @return array  $article_list
    |
    */
    public static function get_favorite_article_list($user_id,$skip = 0){
        $select_site = '
        articles_site.id,
        articles_site.title,
        articles_site.summary,
        articles_site.tags,
        articles_site.image,
        user_favorite.type,
        user_favorite.create_time,
        site_routing.custom_domain AS jump
        ';
        $site_list = DB::table('user_favorite')
            ->select(DB::raw($select_site))
            ->leftJoin('articles_site', function($join){
                $join->on('articles_site.id', '=', 'user_favorite.article_id');
            })
            ->leftJoin('site_routing', function($join){
                $join->on('articles_site.site_id', '=', 'site_routing.site_id');
            })
            ->where('user_favorite.valid', 1)
            ->where('user_favorite.type', 1)
            ->where('user_favorite.user_id', $user_id);
        ;
        $select_user = '
        articles_user.id,
        articles_user.title,
        articles_user.summary,
        articles_user.tags,
        articles_user.image,
        user_favorite.type AS type,
        user_favorite.create_time AS create_time,
        articles_user.user_id AS jump
        ';
        $user_list = DB::table('user_favorite')
            ->select(DB::raw($select_user))
            ->leftJoin('articles_user', function($join){
                $join->on('articles_user.id', '=', 'user_favorite.article_id');
            })
            ->where('user_favorite.valid', 1)
            ->where('user_favorite.type', 2)
            ->where('user_favorite.user_id', $user_id);
        ;
        $list = $site_list->unionAll($user_list)->take(10)->skip($skip)->get();
        usort($list, function($a, $b) {
            return strtotime($b->create_time) - strtotime($a->create_time);
        });
        return $list;
    }
    /*
    |--------------------------------------------------------------------------
    | 获取用户收藏文章总数
    |--------------------------------------------------------------------------
    |
    | @param  string $user_id
    | @param  string $skip
    | @param  string $select
    | @return array  $article_list
    |
    */
    public static function get_favorite_article_count($user_id){
        return DB::table('user_favorite')->where('user_favorite.valid', 1)->where('user_favorite.user_id', $user_id)->count();
    }
    /*
   |--------------------------------------------------------------------------
   | 获取用户文章信息
   |--------------------------------------------------------------------------
   |
   | @param  string $user_id
   | @param  string $ariticle_id
   | @return array  $article_list
   |
   */
    public static function get_artilce_info($user_id, $id, $select = ['*']){
        return ArticleUserModel::where('user_id' ,$user_id)->where('id',$id)->where('deleted',0)->first($select);
    }
   /*
    |--------------------------------------------------------------------------
    | 用户新建文章
    |--------------------------------------------------------------------------
    |
    | @param  string $username
    | @prarm  string $phone
    | @prarm  string $password
    | @return string  New id
    |
    */
    public static function new_article($user_id , $info){
        $article = new ArticleUserModel;

        $article->user_id       = $user_id;
        $article->title         = $info['title'];
        $article->summary       = $info['summary'];
        $article->content       = $info['content'];
        $article->image         = $info['image'];
        $article->tags          = tag($info['tags']);
        $article->post_status   = isset($info['post_status']) ? $info['post_status'] : 1;
        if(isset($info['post_time'])){
            $article->post_time     = $info['post_time'];
        }
        $article->create_time   = now();
        $article->update_time   = now();
        $article->save();

        return $article->id;
    }

  /*
   |--------------------------------------------------------------------------
   | 用户更新文章
   |--------------------------------------------------------------------------
   |
   | @param  string $username
   | @prarm  string $phone
   | @prarm  string $password
   | @return string  New id
   |
   */
    public static function update_article($user_id ,$article_id, $info){
        $article = ArticleUserModel::where('id' ,$article_id)->where('user_id',$user_id)->first();

        $article->title         = $info['title'];
        $article->summary       = $info['summary'];
        $article->content       = $info['content'];
        $article->image         = $info['image'];
        $article->tags          = tag($info['tags']);
        if(isset($info['post_status'])){
            $article->post_status   = $info['post_status'];
        }
        if(isset($info['post_time'])){
            $article->post_time     = $info['post_time'];
        }
        $article->update_time   = now();

        return $article->save();
    }
    /*
    |--------------------------------------------------------------------------
    | 投稿文章到站点
    |--------------------------------------------------------------------------
    |
    | @param  string $article_id 用户文章ID
    | @prarm  array  $site list  站点ID列表
    | @return bool
    |
    */
    public static function contribute_article($article_id,$sites){
        $article_user = ArticleUserModel::where('id' ,$article_id)->first();
        foreach($sites as $v){
            $article_site = new ArticleSiteModel;
            $article_site->site_id          = $v;
            $article_site->source_id        = $article_user->id;
            $article_site->author_id        = $article_user->user_id;
            $article_site->title            = $article_user->title;
            $article_site->summary          = $article_user->summary;
            $article_site->content          = $article_user->content;
            $article_site->image            = $article_user->image;
            $article_site->tags             = $article_user->tags;
            $article_site->post_status      = 0;
            $article_site->create_time      = now();
            $article_site->update_time      = now();
            $article_site->save();
        }
        return true;
    }
    /*
    |--------------------------------------------------------------------------
    | 投稿文章是否已经投稿
    |--------------------------------------------------------------------------
    |
    | @param  string $article_id 用户文章ID
    | @prarm  array  $site list  站点ID列表
    | @return bool
    |
    */
    public static function has_contributed($article_id,$site_id){
        return ArticleSiteModel::where('site_id',$site_id)->where('source_id',$article_id)->count();
    }
    /*
   |--------------------------------------------------------------------------
   | 文章所属权
   |--------------------------------------------------------------------------
   |
   | @param  string $article_id
   | @param  string $user_id
   | @return bool
   |
   */
    public static function own_article($user_id, $article_id){
        $ret = ArticleUserModel::where('id' ,$article_id)->where('user_id',$user_id)->where('deleted',0)->count();
        return $ret > 0;
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
    public static function delete_article($article_id){
        $article = ArticleUserModel::where('id' ,$article_id)->first();
        $article->deleted   = 1;
        return $article->save();
    }
    /*
    |--------------------------------------------------------------------------
    | 发布文章
    |--------------------------------------------------------------------------
    |
    | @param  string $article_id
    | @param  string $user_id
    | @return bool
    |
    */
    public static function post_article($user_id, $article_id,$post_status = 2){
        return ArticleUserModel::where('user_id' ,$user_id)->where('id',$article_id)->where('deleted',0)->update(['post_status'=>$post_status,'post_time'=>now()]);
    }

}