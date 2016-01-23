<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/20
 * Time: 上午11:31
 */
namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

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
        $article->tags          = implode(' ', $info['tags']);
        $article->post_status   = isset($info['post_status']) ? $info['post_status'] : 1;
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
        $article->tags          = implode(' ', $info['tags']);
        if(isset($info['post_status'])){
            $article->post_status   = $info['post_status'];
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
            $article_site->contribute_time  = now();
            $article_site->create_time      = now();
            $article_site->update_time      = now();
            $article_site->save();
        }
        return true;
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
   | 取消发布文章
   |--------------------------------------------------------------------------
   |
   | @param  string $article_id
   | @return bool
   |
   */
    public static function cancel_post($article_id){
        $article = ArticleUserModel::where('id' ,$article_id)->first();
        $article->post_status   = 1;
        return $article->save();
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

}