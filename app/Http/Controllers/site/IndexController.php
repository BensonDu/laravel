<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/8
 * Time: 下午7:48
 */

namespace App\Http\Controllers\Site;


use App\Http\Model\ArticleSiteModel;
use App\Http\Model\StarModel;

class IndexController extends SiteController
{

    public function __construct(){
        parent::__construct();
    }

    public function index(){

        $id = $_ENV['site_id'];
        $data['active'] = 'home';

        //首页精选
        $data['stars']  = StarModel::get_star_list($id);
        //热榜
        $data['hot']    = ArticleSiteModel::get_hot_list($id);
        //文章分类
        $data['categories'] = $this->get_categories();
        //文章列表
        $data['articles']  = [
            'total' => $this->get_articles_count($id),
            'list'  => $this->get_articles($id)
        ];

        return self::view('/site/index',$data);
    }
    public function articles(){
        $id = $_ENV['site_id'];
        $index      = request()->input('index');
        $category   = request()->input('category');
        $skip =intval($index)*15;
        return self::ApiOut(0,[
            'list'  => $this->get_articles($id,$skip,$category),
            'total' => $this->get_articles_count($id, $category),
        ]);
    }
    /*
     |--------------------------------------------------------------------------
     | 获得站点文章分类列表 首页增加 [全部] 分类
     |--------------------------------------------------------------------------
     */
    private function get_categories(){
        $categories = ArticleSiteModel::get_article_categories($_ENV['site_id']);
        array_unshift($categories,[
            'id'    => 0,
            'name'  => '全部'
        ]);
        return $categories;
    }
    private function get_articles($id, $skip = 0, $category = 0){
        $list = ArticleSiteModel::get_home_article_list($id, $skip,$category);
        foreach($list as $k =>$v){
            $tags = [];
            //老版JSON储存标签
            if(substr($v->tags,0,1) == '['){
                $json = json_decode($v->tags,1);
                if(!empty($json)){
                    foreach($json as $vv){
                        $tags[] = [
                            'item'  => $vv,
                            'color' => rand_color()
                        ];
                    }
                }
            }
            else{
                foreach(explode(' ',$v->tags) as $vv){
                    $tags[] = [
                        'item'  => $vv,
                        'color' => rand_color()
                    ];
                }
            }
            $list[$k]->avatar   = avatar($v->avatar);
            $list[$k]->user_url = user_url($v->user_id);
            $list[$k]->tags = $tags;
            $list[$k]->create_time = time_down(strtotime($v->create_time));
        }
        return $list;
    }
    private function get_articles_count($id,$category = 0){
        return ArticleSiteModel::get_article_count($id,$category);
    }
    public function test(){
        return ArticleSiteModel::get_hot_list_($_ENV['site_id']);
    }
}