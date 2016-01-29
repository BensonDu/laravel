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
            'articles_site.id AS site_id',
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

}