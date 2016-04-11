<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/27
 * Time: 下午5:35
 */

namespace App\Http\Controllers\User;

use App\Http\Model\ArticleSocialModel;
use App\Http\Model\ArticleUserModel;

class DetailController extends UserController
{
    /*
     |--------------------------------------------------------------------------
     | 用户文章详情页
     |--------------------------------------------------------------------------
     */
    public function index($id,$article_id){

        $data = self::profile($id);

        $data['self'] = ($id == $_ENV['uid']);
        
        $article = ArticleUserModel::get_artilce_info($id,intval($article_id));

        if(empty($data) || empty($article))abort(404);

        $article->like      = !empty($_ENV['uid']) ? !!ArticleSocialModel::check_is_like($article_id,$_ENV['uid'],2) : false;
        $article->favorite  = !empty($_ENV['uid']) ? !!ArticleSocialModel::check_is_favorite($article_id,$_ENV['uid'],2) : false;
        $article->avatar    = avatar($article->avatar);
        $article->post_time = date('Y年m月d日',strtotime($article->post_time));
        $article->tags      = tag($article->tags);
        $image              = explode('?',$article->image);
        $article->image     = isset($image[0]) ? $image[0] : $article->image ;

        $data['article']    = $article;

        $data['base']['title'] = $article->title.' | '.$data['profile']['nickname'];
        
        return self::view('/user/detail',$data);
    }
}