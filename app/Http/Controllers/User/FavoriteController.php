<?php

namespace App\Http\Controllers\User;

use App\Http\Model\ArticleUserModel;

class FavoriteController extends UserController
{

    public function __construct(){
        parent::__construct();
    }
    /*
     |--------------------------------------------------------------------------
     | 个人收藏页
     |--------------------------------------------------------------------------
     */
    public function index()
    {
        $data['active'] = 'home';
        $data['list']   = self::get_list($_ENV['uid']);
        $data['total']  = ArticleUserModel::get_favorite_article_count($_ENV['uid']);

        return self::view('/user/favorite',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | 个人收藏列表接口
     |--------------------------------------------------------------------------
     */
    public static function favorites(){
        $id    = request()->input('id');
        $index = request()->input('index');

        if(empty($index) || empty($id)) return self::ApiOut(40001,'请求错误');

        $skip = intval($index)*10;
        $list = self::get_list($id,$skip);

        return self::ApiOut(0,$list);
    }
    /*
     |--------------------------------------------------------------------------
     | 获取格式化列表
     |--------------------------------------------------------------------------
     */
    private static function get_list($id, $skip=0){
        $list = ArticleUserModel::get_favorite_article_list($id, $skip);
        foreach($list as $k =>$v){
            $tags = [];
            $tag = tag($v->tags);
            foreach($tag as $vv){
                $tags[] = [
                    'item'  => $vv,
                    'color' => rand_color()
                ];
            }
            $list[$k]->jump = $v->type == 1 ? 'http://'.$v->jump.'/'.$v->id : '/user/'.$v->jump.'/'.$v->id;
            $list[$k]->tags = $tags;
            $list[$k]->create_time = date('Y年m月d日',strtotime($v->create_time));
        }
        return $list;
    }
}