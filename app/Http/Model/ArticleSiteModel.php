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
use PRedis;

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
        DB::table('articles_site')
            ->where('articles_site.site_id' ,$site_id)
            ->where('articles_site.id',$id)
            ->where('articles_site.deleted',0)
            ->increment('views', 1);
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
        $rediskey = config('cache.prefix').':'. config('cache.site.hot').':'.$id;
        if(PRedis::exists($rediskey) && 1){
            return unserialize(PRedis::get($rediskey));
        }
        else{
            $select = [
                'articles_site.id AS article_id',
                'articles_site.title',
                'articles_site.summary',
                'articles_site.create_time',
                'articles_site.views',
                'articles_site.likes',
                'articles_site.favorites',
                'articles_site.image',
                'users.id AS user_id',
                'users.nickname'
            ];
            $items =  DB::table('articles_site')
                ->leftJoin('users', 'articles_site.author_id', '=', 'users.id')
                ->where('articles_site.site_id' ,$id)
                ->where('articles_site.deleted',0)
                ->where('articles_site.post_status',1)
                ->orderBy('create_time','desc')
                ->take(50)
                ->get($select);

            $list = [];
            foreach($items as $k => $v){
                $time = strtotime($v->create_time);
                $list[$k]['rank'] = self::hot_algorithm($time,$v->likes,$v->favorites,$v->views);
                $list[$k]['author']     = $v->nickname;
                $list[$k]['user_id']    = $v->user_id;
                $list[$k]['article_id'] = $v->article_id;
                $list[$k]['title']      = $v->title;
                $list[$k]['time']       = date('m-d H:i',$time);
            }
            usort($list, function($a, $b) {
                return $b['rank'] - $a['rank'] ;
            });
            $list = array_slice($list,0,5);
            if(!empty($list)){
                PRedis::set($rediskey,serialize($list));
                PRedis::expire($rediskey,300);
            }
            return $list;
        }

    }
    /*
     |--------------------------------------------------------------------------
     | 热榜排序算法
     |--------------------------------------------------------------------------
     |
     | @param  timestamp $time
     | @param  string $likes
     | @param  string $favorites
     | @param  string $views
     | @return number
     |
    */
    private static function hot_algorithm($t,$l,$f,$v){
        return floor(($l*30+$f*200+$v)/pow(ceil((time()-$t)/86400),1.2));
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
    public static function get_article_categories($id){
        $items =  DB::table('article_category')
            ->where('site_id' ,$id)
            ->where('deleted' ,0)
            ->orderBy('order','desc')
            ->take(5)
            ->get();
        $categories = [];
        if(!empty($items)){
            foreach($items as $k => $v){
                $categories[$k]['name']     = $v->name;
                $categories[$k]['id']       = $v->id;
            }
        }
        return $categories;
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
    public static function get_home_article_list($site_id,$skip = 0,$category = 0,$take = 15){
        $select = '
        users.id AS user_id,
        users.nickname,
        users.avatar,
        article_category.name AS category_name,
        articles_site.id AS article_id,
        articles_site.title,
        articles_site.summary,
        articles_site.tags,
        articles_site.create_time,
        articles_site.image,
        articles_site.favorites,
        articles_site.likes
        ';
        $ret =  DB::table('articles_site')
            ->select(DB::raw($select))
            ->leftJoin('users', 'articles_site.author_id', '=', 'users.id')
            ->leftJoin('article_category', 'article_category.id', '=', 'articles_site.category')
            ->where('articles_site.site_id' ,$site_id)
            ->where('articles_site.deleted',0);

        if(!!$category) $ret = $ret->where('articles_site.category',$category);

        return $ret
            ->where('articles_site.post_status',1)
            ->orderBy('articles_site.create_time', 'desc')
            ->take($take)
            ->skip($skip)
            ->get();
    }
    /*
    |--------------------------------------------------------------------------
    | 获取站点RSS文章
    |--------------------------------------------------------------------------
    |
    | @param  string $user_id
    | @param  string $skip
    | @param  string $select
    | @return array  $article_list
    |
    */
    public static function get_rss_article_list($site_id,$skip = 0,$take = 20){
        $select = '
        users.id AS user_id,
        users.nickname,
        article_category.name AS category_name,
        articles_site.id AS article_id,
        articles_site.title,
        articles_site.summary,
        articles_site.content,
        articles_site.create_time,
        articles_site.image
        ';
        $ret =  DB::table('articles_site')
            ->select(DB::raw($select))
            ->leftJoin('users', 'articles_site.author_id', '=', 'users.id')
            ->leftJoin('article_category', 'article_category.id', '=', 'articles_site.category')
            ->where('articles_site.site_id' ,$site_id)
            ->where('articles_site.deleted',0);

        return $ret
            ->where('articles_site.post_status',1)
            ->orderBy('articles_site.create_time', 'desc')
            ->take($take)
            ->skip($skip)
            ->get();
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
                ->where('articles_site.post_status',1)
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
            ->where('articles_site.post_status',1)
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
            ->where('articles_site.post_status',1)
            ->count();
    }

}