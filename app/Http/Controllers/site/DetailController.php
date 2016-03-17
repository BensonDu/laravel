<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/8
 * Time: 下午7:52
 */

namespace App\Http\Controllers\Site;


use App\Http\Model\ArticleSiteModel;
use App\Http\Model\ArticleSocialModel;

class DetailController extends SiteController
{
    public function __construct(){
        parent::__construct();
    }

    public function index($id){
        $info = ArticleSiteModel::get_artilce_detail($_ENV['site_id'],$id);

        if(empty($info))abort(404);

        $data['user'] = [
            'id'    => $info->user_id,
            'name'  => $info->nickname,
            'avatar'=>avatar($info->avatar)
        ];

        $data['article'] = [
            'title'     => $info->title,
            'summary'   => $info->summary,
            'content'   => $info->content,
            'tags'      => explode(' ',$info->tags),
            'time'      => date('Y年m月d日',strtotime($info->create_time)),
            'category'  => $info->category_name,
            'image'     => $info->image,
            'like'      => !empty($_ENV['uid']) ? !!ArticleSocialModel::check_is_like($id,$_ENV['uid']) : false,
            'favorite'  => !empty($_ENV['uid']) ? !!ArticleSocialModel::check_is_favorite($id,$_ENV['uid']) : false
        ];
        return self::view('site.detail',$data);
    }
}