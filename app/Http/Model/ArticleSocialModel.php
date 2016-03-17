<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/17
 * Time: 下午3:53
 */

namespace App\Http\Model;

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
    | @param  string $type
    | @return bool
    |
    */
    public static function check_is_like($article_id ,$user_id, $type = 1){
        return DB::table('user_like')
            ->where('article_id' ,$article_id)
            ->where('user_id',$user_id)
            ->where('type',$type)
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
    | @param  string $type
    | @return bool
    |
    */
    public static function check_is_favorite($article_id ,$user_id, $type = 1){
        return DB::table('user_favorite')
            ->where('article_id' ,$article_id)
            ->where('user_id',$user_id)
            ->where('type',$type)
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
    public static function favorite($article_id ,$user_id, $type = 1){
        $like = DB::table('user_favorite')
            ->where('article_id' ,$article_id)
            ->where('user_id',$user_id)
            ->where('type',$type)
            ->first();
        if(isset($like->id)){
            $valid = !$like->valid;
            DB::table('user_favorite')
                ->where('article_id' ,$article_id)
                ->where('user_id',$user_id)
                ->where('type',$type)
                ->update(['valid' => $valid ? 1 : 0]);
        }
        else{
            $valid = true;
            DB::table('user_favorite')->insert([
                ['article_id' => $article_id, 'user_id' => $user_id,'type' => $type,'create_time'=>now()]
            ]);
        }
        $table = $type == 1 ? 'articles_site' : 'articles_user';
        if($valid){
            DB::table($table)->where('id' ,$article_id)->increment('favorites', 1);
        }
        else{
            DB::table($table)->where('id' ,$article_id)->decrement('favorites', 1);
        }
        return $valid;
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
    public static function like($article_id ,$user_id, $type = 1){
        $like = DB::table('user_like')
            ->where('article_id' ,$article_id)
            ->where('user_id',$user_id)
            ->where('type',$type)
            ->first();
        if(isset($like->id)){
            $valid = !$like->valid;
            DB::table('user_like')
                ->where('article_id' ,$article_id)
                ->where('user_id',$user_id)
                ->where('type',$type)
                ->update(['valid' => $valid ? 1 : 0]);
        }
        else{
            $valid = true;
            DB::table('user_like')->insert([
                ['article_id' => $article_id, 'user_id' => $user_id,'type' => $type,'create_time'=>now()]
            ]);
        }
        $table = $type == 1 ? 'articles_site' : 'articles_user';
        if($valid){
            DB::table($table)->where('id' ,$article_id)->increment('likes', 1);
        }
        else{
            DB::table($table)->where('id' ,$article_id)->decrement('likes', 1);
        }
        return $valid;
    }

}