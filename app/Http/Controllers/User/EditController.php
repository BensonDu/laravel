<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/8
 * Time: 下午7:40
 */

namespace App\Http\Controllers\User;

use App\Http\Model\ArticleUserModel;
use App\Http\Model\UserModel;

class EditController extends UserController
{
    private static $route_session_key = 'user_edit_route';

    /*
    |--------------------------------------------------------------------------
    | 用户文章管理中心首页
    |--------------------------------------------------------------------------
    |
    */
    public function index(){
        $route = session(self::$route_session_key);
        session()->forget(self::$route_session_key);
        $data = self::get_list(0,20,null);
        return self::view('/user/edit',['list'=>$data['list'],'total'=>$data['total'],'route'=>$route]);
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
        $user_id    = $_ENV['uid'];
        if(empty($article_id)){
            return self::ApiOut(40001,'请求错误');
        }
        $info =  ArticleUserModel::get_artilce_info($user_id,$article_id);
        $ret = [];
        if(isset($info->id)){
            $ret['id']          = $info->id;
            $ret['title']       = $info->title;
            $ret['summary']     = $info->summary;
            $ret['image']       = $info->image;
            $ret['content']     = $info->content;
            $ret['tags']        = empty($info->tags) ? [] : tag($info->tags);
            $ret['update_time'] = $info->update_time;
            return self::ApiOut(0,$ret);
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
        $request    = request();
        $index      = intval($request->input('index'));
        $keyword    = $request->input('keyword');
        $size       = intval($request->input('size'));
        $post_status= $request->input('type');
        if(empty($index) || empty($size) || !in_array($post_status,['all','pub','unpub']))return self::ApiOut(40001,'Bat request');
        $skip = (intval($index)-1)*$size;
        $keyword = empty($keyword) ? null : $keyword;
        $post_status = $post_status =='all' ? null : ($post_status == 'pub' ? 1 : 0);
        $data = self::get_list($skip,$size,$post_status,$keyword);
        return self::ApiOut(0,$data);
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
    | 获取文章列表
    |--------------------------------------------------------------------------
    |
    */
    private static function get_list($skip,$take,$post_status = null,$keyword = null){
        $all_post   = ArticleUserModel::get_user_site_post_article_list($_ENV['uid']);
        $filter     = is_null($post_status) ? null : ($post_status == 1 ? true : false);
        $list       = ArticleUserModel::get_articles($_ENV['uid'],$skip,$take,$keyword,$filter,$all_post);
        $data = [];
        $ret  = [];
        if(!empty($list)){
            foreach($list as $v){
                $index = substr($v->create_time, 0, 7);
                $v->create_time = date('m月d日', strtotime($v->create_time));
                $v->post_status = in_array($v->id,$all_post) ? 1 : 0;
                $data[$index][] = $v;
            }
            foreach ($data as $k => $v){
                //日期标示
                $ret[] = [
                    'title'=> $k
                ];
                //文章列表
                foreach ($v as $vv){
                    $ret[] = $vv;
                }
            }
        }
        return [
            'list' => $ret,
            'total' => ArticleUserModel::get_articles_count($_ENV['uid'],$keyword,$filter,$all_post)
        ];
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

}