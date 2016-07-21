<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/20
 * Time: 上午11:31
 */
namespace App\Http\Model;

use App\Http\Model\Cache\CacheModel;
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
    */
    public static function get_articles($user_id,$skip,$take,$keyword = null,$filter,$all_post){
        $select = [
            'articles_user.id',
            'articles_user.title',
            'articles_user.update_time',
            'articles_user.create_time'
        ];
        $query = ArticleUserModel::where('articles_user.deleted',0)
            ->where('articles_user.user_id',$user_id);
        if(!is_null($keyword)){
            $query->where('articles_user.title', 'LIKE', '%'.$keyword.'%');
        }
        if($filter == '1'){
            $query->whereIn('articles_user.id', $all_post);
        }
        if($filter == '0'){
            $query->whereNotIn('articles_user.id', $all_post);
        }
        return $query->take($take)->skip($skip)->orderBy('articles_user.create_time', 'desc')->get($select);
    }
    /*
    |--------------------------------------------------------------------------
    | 用户文章是否存在
    |--------------------------------------------------------------------------
   */
    public static function article_brief_info($uid,$id){
        return ArticleUserModel::where('.id',$id)->where('user_id',$uid)->where('deleted',0)->first(['id','hash']);
    }
    /*
    |--------------------------------------------------------------------------
    | 用户文章 在站点文章中是否存在
    |--------------------------------------------------------------------------
    */
    public static function article_existed_in_site($user_article_id){
        return DB::table('articles_site')->where('source_id',$user_article_id)->count();
    }
    /*
     |--------------------------------------------------------------------------
     | 获取用户文章列表总数
     |--------------------------------------------------------------------------
     */
    public static function get_articles_count($user_id,$keyword = null,$filter,$all_post){

        $query = ArticleUserModel::where('articles_user.deleted',0)
            ->where('articles_user.user_id',$user_id);
        if(!is_null($keyword)){
            $query->where('articles_user.title', 'LIKE', '%'.$keyword.'%');
        }
        if($filter == '1'){
            $query->whereIn('articles_user.id', $all_post);
        }
        if($filter == '0'){
            $query->whereNotIn('articles_user.id', $all_post);
        }
        return $query->count();
    }
    /*
     |--------------------------------------------------------------------------
     | 获取用户在站点所有发布的ID
     |--------------------------------------------------------------------------
     */
    public static function get_user_site_post_article_list($user_id){
        $list = DB::table('articles_site')->where('author_id',$user_id)->where('deleted',0)->where('post_status',1)->get(['source_id']);
        $ret = [];
        if(!empty($list)){
            foreach ($list as $v){
                if(!in_array($v->source_id,$ret)){
                    $ret[] = $v->source_id;
                }
            }
        }
        return $ret;
    }
    /*
    |--------------------------------------------------------------------------
    | 用户在 站点的文章发布状态 发布列表
    |--------------------------------------------------------------------------
    */
    public static function article_site_info($id){
        $select = [
            'site_info.name',
            'site_info.custom_domain',
            'site_info.platform_domain',
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
        return DB::table('articles_site')
            ->leftJoin('site_info', 'site_info.id', '=', 'articles_site.site_id')
            ->where('articles_site.source_id',$id)
            ->get($select);
    }
    /*
    |--------------------------------------------------------------------------
    | 获取用户主页文章
    |--------------------------------------------------------------------------
    */
    public static function get_home_article_list($user_id,$skip = 0){

        //选择字段
        $select = '
        articles_site.id,
        articles_site.title,
        articles_site.summary,
        articles_site.tags,
        articles_site.image,
        articles_site.post_time,
        articles_site.favorites,
        articles_site.likes,
        site_info.custom_domain,
        site_info.platform_domain
        ';

        $list = DB::table('articles_site')
            ->where('author_id' ,$user_id)
            ->where('deleted',0)
            ->where('post_status','1')
            ->groupBy('source_id')
            ->orderBy('post_time', 'asc')
            ->get(['id']);

        //总数
        $total  = count($list);
        //反转数组
        $desc   = array_reverse($list);
        //获取所需位置文章
        $need    = array_slice($desc,$skip,10);
        //文章IDS
        $ids = [];
        foreach ($need as $v){
            $ids[] = $v->id;
        }
        //文章详情
        $article = DB::table('articles_site')
            ->leftJoin('site_info','articles_site.site_id','=','site_info.id')
            ->select(DB::raw($select))
            ->whereIn('articles_site.id',$ids)
            ->get();
        //顺序校准
        $ret = [];
        foreach($ids as $k => $v){
            foreach($article as $vv){
                if($vv->id == $v){
                    $ret[$k] = $vv;
                }
            }
        }
        return [
            'total' => $total,
            'list'  => $ret
        ];
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
        site_info.custom_domain AS jump
        ';
        $site_list = DB::table('user_favorite')
            ->select(DB::raw($select_site))
            ->leftJoin('articles_site', function($join){
                $join->on('articles_site.id', '=', 'user_favorite.article_id');
            })
            ->leftJoin('site_info', function($join){
                $join->on('articles_site.site_id', '=', 'site_info.id');
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
   */
    public static function get_artilce_info($user_id, $article_id){
        return ArticleUserModel::where('deleted',0)->where('id',$article_id)->where('user_id',$user_id)->first(['id','title','image','summary','content','tags','update_time']);
    }
    /*
    |--------------------------------------------------------------------------
    | 获取用户文章在站点的发布状态
    |--------------------------------------------------------------------------
   */
    public static function get_article_site_post_info($article_id){
        return DB::table('articles_site')->where('source_id',$article_id)->get(['post_status','hash','deleted','site_lock']);
    }
    /*
    |--------------------------------------------------------------------------
    | 推送更新到站点
    |--------------------------------------------------------------------------
    */
    public static function pushsite($site_id, $uid, $user_article_id){
        $select = ['title','summary','content','tags','image','hash'];

        $info =  ArticleUserModel::where('id',$user_article_id)->where('user_id',$uid)->where('deleted',0)->first($select);
        //文章不存在
        if(!isset($info->title))return false;

        $site = DB::table('articles_site')->where('source_id',$user_article_id)->where('site_id',$site_id)->where('deleted',0)->first(['id','hash','site_lock']);

        if(!isset($site->id) || ($site->site_lock) || $site->hash == $info->hash)return false;
        //清除缓存
        CacheModel::clear_article_cache($site_id,$site->id);
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
        $article->content       = ArticleBaseModel::filter_base64_image($info['content']);
        $article->image         = $info['image'];
        $article->tags          = tag($info['tags']);
        $article->hash          = md5($article->title.$article->summary.$article->content.$article->tags.$article->image);
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
        $article->content       = ArticleBaseModel::filter_base64_image($info['content']);
        $article->image         = $info['image'];
        $article->tags          = tag($info['tags']);
        $article->hash          = md5($article->title.$article->summary.$article->content.$article->tags.$article->image);
        $article->update_time   = now();

        return $article->save();
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
}