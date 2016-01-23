<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/8
 * Time: 下午7:40
 */

namespace App\Http\Controllers\User;

use App\Http\Model\ArticleUserModel;
use App\Http\Model\SiteModel;

class EditController extends UserController
{

    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $list = $this->sort_article_list();
        return view('/user/edit',['list'=>json_encode($list)]);
    }
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
                $info->tags = explode(' ',$info->tags);
            }
            return self::ApiOut(0,$info);
        }
        return self::ApiOut(40004,'Not found');
    }
    public function article_list(){
        $list = $this->sort_article_list();
        return self::ApiOut(0,$list);
    }
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
    public function test(){
        $list = $this->sort_article_list();
        return $list;
    }
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

    public function post(){
        $article_id = $this->request->input('id');
        $title      = $this->request->input('title');
        $summary    = $this->request->input('summary');
        $content    = $this->request->input('content');
        $image      = $this->request->input('image');
        $tags       = json_decode($this->request->input('tags'),1);
        $post_status= 2;
        $user_id    = $_ENV['uid'];
        if(empty($title) || empty($summary)){
            return self::ApiOut(40001,'请求错误');
        }
        //新建文章
        if(empty($article_id)){
            $id = ArticleUserModel::new_article($user_id,compact('title', 'summary', 'content', 'image', 'tags','post_status'));
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
            $ret = ArticleUserModel::update_article($user_id,$article_id,compact('title', 'summary', 'content', 'image', 'tags', 'post_status'));
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

    public function cancel(){
        $article_id = $this->request->input('id');
        if(empty($article_id)){
            return self::ApiOut(40001,'请求错误');
        }
        $ret = ArticleUserModel::cancel_post($article_id);
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

    public function contribute(){
        $article_id = $this->request->input('id');
        $title      = $this->request->input('title');
        $summary    = $this->request->input('summary');
        $content    = $this->request->input('content');
        $image      = $this->request->input('image');
        $tags       = json_decode($this->request->input('tags'),1);
        $post_status= 2;
        $sites      = json_decode($this->request->input('sites'));
        $user_id    = $_ENV['uid'];
        if(empty($title) || empty($summary) || empty($sites) || !SiteModel::check_site($sites)){
            return self::ApiOut(40001,'请求错误');
        }
        //新建文章 并投稿
        $id = $article_id;
        if(empty($article_id)){
            $id = ArticleUserModel::new_article($user_id,compact('title', 'summary', 'content', 'image', 'tags','post_status'));

        }
        //更新文章
        else{
            ArticleUserModel::update_article($user_id,$article_id,compact('title', 'summary', 'content', 'image', 'tags', 'post_status'));
        }
        //投稿
        $ret = ArticleUserModel::contribute_article($id,$sites);
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