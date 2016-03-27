<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/8
 * Time: 下午7:40
 */

namespace App\Http\Controllers\User;

use App\Http\Model\ArticleSiteModel;
use App\Http\Model\ArticleUserModel;
use App\Http\Model\SiteModel;

class EditController extends UserController
{
    private static $route_session_key = 'user_edit_route';

    public function __construct(){
        parent::__construct();
    }
    /*
    |--------------------------------------------------------------------------
    | 用户文章管理中心首页
    |--------------------------------------------------------------------------
    |
    */
    public function index(){
        $route = session(self::$route_session_key);
        session()->forget(self::$route_session_key);
        $list = $this->sort_article_list();
        return self::view('/user/edit',['list'=>json_encode_safe($list),'route'=>$route]);
    }
    /*
    |--------------------------------------------------------------------------
    | 用户文章管理中心默认打开文章
    |--------------------------------------------------------------------------
    | 检查文章ID是否存在,存在写入route session 文章ID
    | 并重定向到管理中心首页
    */
    public function open($id){
        if(!empty($id) && ArticleUserModel::own_article($_ENV['uid'],$id))session([self::$route_session_key=>$id]);
        return redirect('/user/edit');
    }
    /*
    |--------------------------------------------------------------------------
    | 用户文章管理中心默认创建文章
    |--------------------------------------------------------------------------
    | 写入route session  create
    | 并重定向到管理中心首页
    */
    public function create(){
        session([self::$route_session_key=>'create']);
        return redirect('/user/edit');
    }
    /*
    |--------------------------------------------------------------------------
    | 获取文章信息 API
    |--------------------------------------------------------------------------
    | @param  string $article_id
    |
    */
    public function article(){
        $article_id = $this->request->input('id');
        $user_id = $_ENV['uid'];
        if(empty($article_id)){
            return self::ApiOut(40001,'请求错误');
        }
        $info =  ArticleUserModel::get_artilce_info($user_id,$article_id,['id','title','summary','content','tags','image','update_time','post_status']);
        if(isset($info->id)){
            if(empty(trim($info->tags))){
                $info->tags = [];
            }
            else{
                $info->tags = tag($info->tags);
            }
            return self::ApiOut(0,$info);
        }
        return self::ApiOut(40004,'Not found');
    }
    /*
    |--------------------------------------------------------------------------
    | 用户文章列表 API
    |--------------------------------------------------------------------------
    |
    */
    public function articles(){
        $list = $this->sort_article_list();
        return self::ApiOut(0,$list);
    }
    /*
    |--------------------------------------------------------------------------
    | 删除文章 API
    |--------------------------------------------------------------------------
    | @param  string $article_id
    */
    public function delete(){
        $article_id = $this->request->input('id');
        if(empty($article_id)){
            return self::ApiOut(40001,'请求错误');
        }
        $ret = ArticleUserModel::delete_article($article_id);
        if($ret){
            return self::ApiOut(0,'删除成功');
        }
        else{
            return self::ApiOut(10001,'删除失败');
        }
    }
    /*
    |--------------------------------------------------------------------------
    | 获取文章列表方法
    |--------------------------------------------------------------------------
    |
    */
    private function sort_article_list(){
        $list = ArticleUserModel::get_article($_ENV['uid'],['id','title','post_status','update_time']);
        $data = [];
        if(!empty($list)){
            foreach($list as $v){
                $index = substr($v->update_time, 0, 7);
                $v->update_time = date('m月d日', strtotime($v->update_time));
                $data[$index][] = $v;
            }
        }
        return $data;
    }
    /*
    |--------------------------------------------------------------------------
    | 保存文章 API
    |--------------------------------------------------------------------------
    | @param  string $article_id
    | @param  string $title
    | @param  string $summary
    | @param  string $content
    | @param  string $image
    | @param  json $tags
    */
    public function save(){
        $article_id = $this->request->input('id');
        $title      = $this->request->input('title');
        $summary    = $this->request->input('summary');
        $content    = $this->request->input('content');
        $image      = $this->request->input('image');
        $tags       = json_decode($this->request->input('tags'),1);
        $user_id    = $_ENV['uid'];
        if(empty($title)){
            return self::ApiOut(40001,'请求错误');
        }

        //新建文章
        if(empty($article_id)){
            $id = ArticleUserModel::new_article($user_id,compact('title', 'summary', 'content', 'image', 'tags'));
            if($id){
                return self::ApiOut(0,[
                    'id'    => $id,
                    'time'  =>now()
                ]);
            }
            else{
                return self::ApiOut(10001,'保存失败');
            }

        }
        //更新文章
        else{
            $ret = ArticleUserModel::update_article($user_id,$article_id,compact('title', 'summary', 'content', 'image', 'tags'));
            if($ret){
                return self::ApiOut(0,[
                    'id'    => $article_id,
                    'time'  =>now()
                ]);
            }
            else{
                return self::ApiOut(10001,'保存失败');
            }
        }
    }
    /*
    |--------------------------------------------------------------------------
    | 保存并发布文章 API
    |--------------------------------------------------------------------------
    | @param  string $article_id
    | @param  string $title
    | @param  string $summary
    | @param  string $content
    | @param  string $image
    | @param  json $tags
    */
    public function post(){
        $article_id = $this->request->input('id');
        $title      = $this->request->input('title');
        $summary    = $this->request->input('summary');
        $content    = $this->request->input('content');
        $image      = $this->request->input('image');
        $tags       = json_decode($this->request->input('tags'),1);
        $post_status= 2;
        $post_time = now();
        $user_id    = $_ENV['uid'];
        if(empty($title) || empty($summary)){
            return self::ApiOut(40001,'请求错误');
        }
        //新建文章
        if(empty($article_id)){
            $id = ArticleUserModel::new_article($user_id,compact('title', 'summary', 'content', 'image', 'tags','post_status','post_time'));
            if($id){
                return self::ApiOut(0,[
                    'id'    => $id,
                    'time'  =>now()
                ]);
            }
            else{
                return self::ApiOut(10001,'发布失败');
            }

        }
        //更新文章
        else{
            $ret = ArticleUserModel::update_article($user_id,$article_id,compact('title', 'summary', 'content', 'image', 'tags', 'post_status','post_time'));
            if($ret){
                return self::ApiOut(0,[
                    'id'    => $article_id,
                    'time'  =>now()
                ]);
            }
            else{
                return self::ApiOut(10001,'发布失败');
            }
        }

    }
    /*
    |--------------------------------------------------------------------------
    | 取消发布 API
    |--------------------------------------------------------------------------
    |
    */
    public function cancel(){
        $article_id = $this->request->input('id');
        $title      = $this->request->input('title');
        $summary    = $this->request->input('summary');
        $content    = $this->request->input('content');
        $image      = $this->request->input('image');
        $tags       = json_decode($this->request->input('tags'),1);
        $post_status= 1;
        $user_id    = $_ENV['uid'];
        if(empty($title) || empty($summary)){
            return self::ApiOut(40001,'请求错误');
        }
        $ret = ArticleUserModel::update_article($user_id,$article_id,compact('title', 'summary', 'content', 'image', 'tags', 'post_status'));
        if($ret){
            return self::ApiOut(0,[
                'id'    => $article_id,
                'time'  =>now()
            ]);
        }
        else{
            return self::ApiOut(10001,'操作失败');
        }
    }
    /*
    |--------------------------------------------------------------------------
    | 保存并投稿文章 API
    |--------------------------------------------------------------------------
    | @param  string $article_id
    | @param  string $title
    | @param  string $summary
    | @param  string $content
    | @param  string $image
    | @param  json $tags
    | @param  json $sites 投稿到的站点列表
    */
    public function contribute(){
        $article_id = $this->request->input('id');
        /*TODO 多站点投稿*/
        $sites      = ['1'];//json_decode($this->request->input('sites'),1);
        $user_id    = $_ENV['uid'];
        if(empty($article_id) || empty($sites)){
            return self::ApiOut(40001,'请求错误');
        }
        //新建文章 并投稿
        $id = $article_id;
        if(!ArticleUserModel::own_article($user_id,$id))return self::ApiOut(40002,'请求错误');
        if(ArticleUserModel::has_contributed($article_id,$sites[0]))return self::ApiOut(10002,'请勿重复投稿');
        //投稿
        $ret = ArticleUserModel::contribute_article($id,$sites);
        ArticleUserModel::post_article($user_id,$article_id);
        if($ret){
            return self::ApiOut(0,[
                'id'    => $id,
                'time'  =>now()
            ]);
        }
        else{
            return self::ApiOut(10001,'投稿失败');
        }

    }

}