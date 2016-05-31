<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/17
 * Time: 下午3:53
 */

namespace App\Http\Model;

use App\Http\Model\Cache\CacheModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ArticleSocialModel extends Model
{
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | 检查文章 是否为用户赞
    |--------------------------------------------------------------------------
    |
    | @param  string $article_id
    | @param  string $user_id
    | @return bool
    |
    */
    public static function check_is_like($article_id ,$user_id){
        return DB::table('user_like')
            ->where('article_id' ,$article_id)
            ->where('user_id',$user_id)
            ->where('type','1')
            ->where('valid',1)
            ->count();
    }
    /*
    |--------------------------------------------------------------------------
    | 检查文章 是否为用户收藏
    |--------------------------------------------------------------------------
    |
    | @param  string $article_id
    | @param  string $user_id
    | @return bool
    |
    */
    public static function check_is_favorite($article_id ,$user_id){
        return DB::table('user_favorite')
            ->where('article_id' ,$article_id)
            ->where('user_id',$user_id)
            ->where('type','1')
            ->where('valid',1)
            ->count();
    }
    /*
    |--------------------------------------------------------------------------
    | 用户收藏
    |--------------------------------------------------------------------------
    |
    | @param  string $article_id
    | @param  string $user_id
    | @param  string $type
    | @return bool
    |
    */
    public static function favorite($article_id ,$user_id){
        $like = DB::table('user_favorite')
            ->where('article_id' ,$article_id)
            ->where('user_id',$user_id)
            ->where('type','1')
            ->first();

        //添加||更新 用户收藏表记录
        if(isset($like->id)){
            $valid = !$like->valid;
            DB::table('user_favorite')
                ->where('article_id' ,$article_id)
                ->where('user_id',$user_id)
                ->where('type','1')
                ->update(['valid' => $valid ? 1 : 0]);
        }
        else{
            $valid = true;
            DB::table('user_favorite')->insert([
                ['article_id' => $article_id, 'user_id' => $user_id,'type' => '1','create_time'=>now()]
            ]);
        }

        $query = DB::table('articles_site')->where('id' ,$article_id)->first(['favorites']);
        $count = isset($query->favorites) ? $query->favorites : 0;
        $count = $valid ? $count+1 : $count-1;
        DB::table('articles_site')->where('id' ,$article_id)->update([
            'favorites' => $count
        ]);
        CacheModel::clear_article_cache($_ENV['site_id'],$article_id);
        return [
            'valid' => $valid ? 'FAV' : 'DISFAV',
            'count' => $count
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | 用户赞
    |--------------------------------------------------------------------------
    |
    | @param  string $article_id
    | @param  string $user_id
    | @param  string $type
    | @return bool
    |
    */
    public static function like($article_id ,$user_id){
        $like = DB::table('user_like')
            ->where('article_id' ,$article_id)
            ->where('user_id',$user_id)
            ->where('type','1')
            ->first();
        if(isset($like->id)){
            $valid = !$like->valid;
            DB::table('user_like')
                ->where('article_id' ,$article_id)
                ->where('user_id',$user_id)
                ->where('type','1')
                ->update(['valid' => $valid ? 1 : 0]);
        }
        else{
            $valid = true;
            DB::table('user_like')->insert([
                ['article_id' => $article_id, 'user_id' => $user_id,'type' => '1','create_time'=>now()]
            ]);
        }
        $query = DB::table('articles_site')->where('id' ,$article_id)->first(['likes']);
        $count = isset($query->likes) ? $query->likes : 0;
        $count = $valid ? $count+1 : $count-1;
        DB::table('articles_site')->where('id' ,$article_id)->update([
            'likes' => $count
        ]);
        CacheModel::clear_article_cache($_ENV['site_id'],$article_id);
        return [
            'valid' => $valid ? 'LIKE' : 'DISLIKE',
            'count' => $count
        ];
    }

}