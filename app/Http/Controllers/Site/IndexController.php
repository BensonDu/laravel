<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/8
 * Time: 下午7:48
 */

namespace App\Http\Controllers\Site;


use App\Http\Model\AdModel;
use App\Http\Model\ArticleSiteModel;
use App\Http\Model\Cache\PlatformCacheModel;
use App\Http\Model\CategoryModel;
use App\Http\Model\StarModel;

class IndexController extends SiteController
{

    public function __construct(){
        parent::__construct();
    }

    public function index(){

        $id = $_ENV['domain']['id'];
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
        //广告
        $data['ad'] = AdModel::get_home_ad($id);
        //浏览总量+1
        PlatformCacheModel::site_home_view_increase($_ENV['domain']['id']);
        return self::view('/site/index',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | M 站首页
     |--------------------------------------------------------------------------
     */
    public function mobile(){

        $id = $_ENV['domain']['id'];
        $data['active'] = 'home';

        //首页精选
        $data['stars']  = StarModel::get_star_list($id);
        //文章分类
        $data['categories'] = $this->get_categories();
        //文章列表
        $data['articles']  = [
            'total' => $this->get_articles_count($id),
            'list'  => $this->get_mobile_articles($id)
        ];

        return self::view('mobile.site.index',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | M 站文章列表 接口
     |--------------------------------------------------------------------------
     */
    public function mobilearticles(){
        $id = $_ENV['domain']['id'];
        $index      = request()->input('index');
        $category   = request()->input('category');
        $skip =intval($index)*15;
        return self::ApiOut(0,[
            'list'  => $this->get_mobile_articles($id,$skip,$category),
            'total' => $this->get_articles_count($id, $category),
        ]);
    }
    /*
     |--------------------------------------------------------------------------
     | 文章列表 接口
     |--------------------------------------------------------------------------
     */
    public function articles(){
        $id = $_ENV['domain']['id'];
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
     | 获得站点文章分类列表
     |--------------------------------------------------------------------------
     */
    private function get_categories(){
        return CategoryModel::get_categories($_ENV['domain']['id'],'全部');
    }
    private function get_articles($id, $skip = 0, $category = 0){
        $list = ArticleSiteModel::get_home_article_list($id, $skip,$category);

        //获取文章评论数
        $ids = [];

        foreach ($list as $v){
            if(!in_array($v->article_id,[])) $ids[] = $v->article_id;
        }

        $comments = ArticleSiteModel::get_articles_comment_count($_ENV['domain']['id'],$ids);

        foreach($list as $k =>$v){
            $tags = [];
            $tag = tag($v->tags);
            if(!empty($tag)){
                foreach($tag as $vv){
                    $tags[] = [
                        'item'  => $vv,
                        'color' => rand_color()
                    ];
                }
            }
            $list[$k]->comments = isset($comments[$v->article_id]) ? $comments[$v->article_id] : 0;
            $list[$k]->image    = image_crop($v->image,200);
            $list[$k]->avatar   = avatar($v->avatar);
            $list[$k]->user_url = user_url($v->user_id);
            $list[$k]->tags = $tags;
            $list[$k]->time = time_down(strtotime($v->post_time));
        }
        return $list;
    }
    private function get_mobile_articles($id, $skip = 0, $category = 0){
        $ret = [];
        $list = ArticleSiteModel::get_home_article_list($id, $skip,$category);
        $i = 0;
        foreach($list as $k =>$v){
            if(empty(trim($v->image)))continue;
            $ret[$i] = (object) [];
            $ret[$i]->article_id    = $v->article_id;
            $ret[$i]->title         = $v->title;
            $ret[$i]->summary       = $v->summary;
            $ret[$i]->image         = image_crop($v->image, 500);
            $ret[$i]->category_name = $v->category_name;
            $ret[$i]->time   = time_down(strtotime($v->post_time));
            $i++;
        }
        return $ret;
    }
    private function get_articles_count($id,$category = 0){
        return ArticleSiteModel::get_article_count($id,$category);
    }
}