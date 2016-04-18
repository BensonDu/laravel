<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/23
 * Time: 下午2:48
 */

namespace App\Http\Controllers\Admin;

use App\Http\Model\Admin\ArticleModel;
use App\Http\Model\ArticleSiteModel;

class ArticleController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        view()->share(['admin_nav_top'=>[
            'name' => '文章管理',
            'class'=> 'article'
        ]]);
    }
    /*
     |--------------------------------------------------------------------------
     | 未发布文章
     |--------------------------------------------------------------------------
     */
    public function unpub(){
        $data['sub_active'] = 'unpub';
        $data['articles']   = $this->get_unpub_list(0,10);
        $data['categories'] = ArticleSiteModel::get_article_categories($_ENV['site_id']);
        return self::view('admin.article.unpub',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | 已发布文章
     |--------------------------------------------------------------------------
     */
    public function pub(){
        $data['sub_active'] = 'pub';
        $data['articles']   = $this->get_pub_list(0,10);
        $data['categories'] = ArticleSiteModel::get_article_categories($_ENV['site_id']);
        return self::view('admin.article.pub',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | 我的文章
     |--------------------------------------------------------------------------
     */
    public function mine(){
        $data['sub_active'] = 'mine';
        $data['articles']   = $this->get_mine_list(0,10);
        $data['categories'] = ArticleSiteModel::get_article_categories($_ENV['site_id']);
        return self::view('admin.article.mine',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | 回收站文章
     |--------------------------------------------------------------------------
     */
    public function recycle(){
        $data['sub_active'] = 'recycle';
        $data['articles']   = $this->get_recycle_list(0,10);
        $data['categories'] = ArticleSiteModel::get_article_categories($_ENV['site_id']);
        return self::view('admin.article.recycle',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | 获得未发布文章列表接口
     |--------------------------------------------------------------------------
     */
    public function unpubs(){
        $index      = intval(request()->input('index'));
        $keyword    = request()->input('keyword');
        $size       = intval(request()->input('size'));
        $order       = request()->input('order');
        if(empty($index) || intval($size) > 50 || !in_array($order,['asc','desc']))return $this->ApiOut(40001,'Bat request');
        $keyword = !empty($keyword) ? $keyword : null;
        $skip = (intval($index)-1)*$size;
        $list = $this->get_unpub_list($skip,$size,$order,$keyword);
        return $this->ApiOut(0,$list);
    }
    /*
     |--------------------------------------------------------------------------
     | 获得已发布文章列表接口
     |--------------------------------------------------------------------------
     */
    public function pubs(){
        $index      = intval(request()->input('index'));
        $keyword    = request()->input('keyword');
        $size       = intval(request()->input('size'));
        $order       = request()->input('order');
        if(empty($index) || intval($size) > 50 || !in_array($order,['asc','desc']))return $this->ApiOut(40001,'Bat request');
        $keyword = !empty($keyword) ? $keyword : null;
        $skip = (intval($index)-1)*$size;
        $list = $this->get_pub_list($skip,$size,$order,$keyword);
        return $this->ApiOut(0,$list);
    }
    /*
     |--------------------------------------------------------------------------
     | 获得我的文章列表接口
     |--------------------------------------------------------------------------
     */
    public function mines(){
        $index      = intval(request()->input('index'));
        $keyword    = request()->input('keyword');
        $size       = intval(request()->input('size'));
        $order      = request()->input('order');
        $orderby    = request()->input('orderby');
        if(empty($index) || intval($size) > 50 || !in_array($order,['asc','desc']) || !in_array($orderby,['create_time','post_status']))return $this->ApiOut(40001,'Bat request');
        $keyword = !empty($keyword) ? $keyword : null;
        $skip = (intval($index)-1)*$size;
        $list = $this->get_mine_list($skip,$size,$order,$keyword,$orderby);
        return $this->ApiOut(0,$list);
    }
    /*
     |--------------------------------------------------------------------------
     | 获得回收站章列表接口
     |--------------------------------------------------------------------------
     */
    public function recycles(){
        $index      = intval(request()->input('index'));
        $keyword    = request()->input('keyword');
        $size       = intval(request()->input('size'));
        $order      = request()->input('order');
        if(empty($index) || intval($size) > 50 || !in_array($order,['asc','desc']))return $this->ApiOut(40001,'Bat request');
        $keyword = !empty($keyword) ? $keyword : null;
        $skip = (intval($index)-1)*$size;
        $list = $this->get_recycle_list($skip,$size,$order,$keyword);
        return $this->ApiOut(0,$list);
    }
    /*
     |--------------------------------------------------------------------------
     | 删除文章
     |--------------------------------------------------------------------------
     */
    public function delete(){
        $request = request();
        $site_id    = $_ENV['site_id'];
        $article_id = $request->input('id');
        $info = $this->check_article_auth($article_id, null, 'array');
        if(empty($article_id) || empty($info)){
            return $this->ApiOut(40003,'权限不足');
        }
        //TODO 处理通知
        if($info->contribute_status == 0){

        }
        ArticleModel::delete_article($site_id,$article_id);
        return $this->ApiOut(0,'删除成功');
    }
    /*
     |--------------------------------------------------------------------------
     | 还原文章
     |--------------------------------------------------------------------------
     */
    public function recovery(){
        $request = request();
        $site_id    = $_ENV['site_id'];
        $article_id = $request->input('id');
        $info = $this->check_article_auth($article_id, null, 'array');
        if(empty($article_id) || empty($info)){
            return $this->ApiOut(40003,'权限不足');
        }
        //TODO 处理通知
        if($info->contribute_status == 0){

        }
        ArticleModel::delete_article($site_id,$article_id,0);
        return $this->ApiOut(0,'还原成功');
    }
    /*
     |--------------------------------------------------------------------------
     | 彻底删除文章
     |--------------------------------------------------------------------------
     */
    public function destroy(){
        $request = request();
        $site_id    = $_ENV['site_id'];
        $article_id = $request->input('id');
        $info = $this->check_article_auth($article_id, null, 'array');
        if(empty($article_id) || empty($info)){
            return $this->ApiOut(40003,'权限不足');
        }
        //TODO 处理通知
        if($info->contribute_status == 0){

        }
        ArticleModel::delete_article($site_id,$article_id, 2);
        return $this->ApiOut(0,'删除成功');
    }
     /*
     |--------------------------------------------------------------------------
     | 保存文章
     |--------------------------------------------------------------------------
     | @param  string $article_id
     | @param  string $title
     | @param  string $summary
     | @param  string $content
     | @param  string $image
     | @param  json $tags
     */
    public function save(){
        $request = request();
        $article_id = $request->input('id');
        $title      = $request->input('title');
        $summary    = $request->input('summary');
        $content    = $request->input('content');
        $image      = $request->input('image');
        $tags       = tag(json_decode($request->input('tags'),1));

        if(empty($article_id) ||empty($title) || !$this->check_article_auth($article_id)){
            return self::ApiOut(40001,'请求错误');
        }

        $ret = ArticleModel::update_article($_ENV['site_id'],$article_id,compact('title', 'summary', 'content', 'image', 'tags'));

        if($ret){
            return self::ApiOut(0,'保存成功');
        }
        else{
            return self::ApiOut(10001,'保存失败');
        }

    }
     /*
     |--------------------------------------------------------------------------
     | 获取文章信息
     |--------------------------------------------------------------------------
     | @param  string $_id
     |
     */
    public function info(){
        $article_id = request()->input('id');
        $site_id = $_ENV['site_id'];
        $info = $this->check_article_auth($article_id, null, 'array');
        if(empty($article_id) || empty($site_id) || empty($info)){
            return self::ApiOut(40001,'请求错误');
        }
        //TODO 处理通知
        if($info->contribute_status == 0){

        }

        if(isset($info->id)){

            $info->tags = tag(trim($info->tags));

            $ret = [
                'title'     => $info->title,
                'summary'   => $info->summary,
                'content'   => $info->content,
                'tags'      => $info->tags,
                'image'     => $info->image
            ];
            return self::ApiOut(0,$ret);
        }
        return self::ApiOut(40004,'Not found');
    }
    /*
     |--------------------------------------------------------------------------
     | 设置文章发布状态
     |--------------------------------------------------------------------------
     */
    public function postsave(){
        $request = request();
        $site_id    = $_ENV['site_id'];
        $article_id = $request->input('id');
        $category   = $request->input('category');
        $type       = $request->input('type');
        $time       = $request->input('time');

        $info = $this->check_article_auth($article_id, null, 'array');
        if(empty($article_id) || ($type !='cancel' && empty($category)) || empty($type) || empty($info)){
            return $this->ApiOut(40003,'权限不足');
        }
        //TODO 处理通知
        if($info->contribute_status == 0){

        }
        $post_status = $type == 'cancel' ? 0 : 1;
        $post_time = date('Y-m-d H:i:s', strtotime($time));
        ArticleModel::save_article_post($site_id,$article_id,$category,$post_status,$post_time);
        return $this->ApiOut(0,'Save Sussess');
    }
    /*
     |--------------------------------------------------------------------------
     | 获得文章发布状态
     |--------------------------------------------------------------------------
     */
    public function postinfo(){
        $article_id = request()->input('id');
        $info = $this->check_article_auth($article_id,null,'array');
        if(empty($info))return $this->ApiOut(40003,'No permission');
        $ret['category'] = $info->category;
        $ret['type'] = $info->post_status == 1 ? (time()>strtotime($info->post_time) ? 'now' : 'time') : 'cancel';
        $ret['time'] = ($ret['type'] == 'time' || $ret['type'] == 'now')  ? date('Y-m-d H:i',strtotime($info->post_time)) : null;
        //$ret['min'] = time()-strtotime($info->post_time);
        return $this->ApiOut(0,$ret);
    }
    /*
     |--------------------------------------------------------------------------
     | 检查是否有权限处理该文章
     |--------------------------------------------------------------------------
     | $param $article_id 文章 ID
     | $param $author_id  用户 ID   Default null
     | $param $ret_type   所需返回类型 Default bool Optional array
     | TODO 应该检查文章是否在当前站点下
     |
     */
    private function check_article_auth($article_id = null,$author_id = null,$ret_type = 'bool'){
        $ret  = null;
        $aid  = null;
        $info = null;
        if($ret_type == 'array' || is_null($author_id)){
            $info = ArticleModel::get_artcile_brief_info($_ENV['site_id'],$article_id);
        }
        if(is_null($author_id)){
            $aid = isset($info->author_id) ? $info->author_id : null;
        }
        else{
            $aid = $author_id;
        }
        if($_ENV['admin']['role'] > 1 || ($_ENV['admin']['role'] == 1 && $aid == $_ENV['uid'])){
            $ret = $ret_type == 'bool' ? true : $info;
        }

        return $ret;
    }
    /*
     |--------------------------------------------------------------------------
     | 获得已发布文章列表
     |--------------------------------------------------------------------------
     */
    private function get_pub_list($skip,$take,$order = 'desc' ,$keyword = null){
        return [
            'total' => ArticleModel::get_articles_count($_ENV['site_id'],$keyword,1),
            'list'  =>  $this->format(ArticleModel::get_articles($_ENV['site_id'], $skip,$take,$order,$keyword,1,'post_time'))
        ];
    }
    /*
     |--------------------------------------------------------------------------
     | 获得未发布文章列表
     |--------------------------------------------------------------------------
     */
    private function get_unpub_list($skip,$take,$order = 'desc' ,$keyword = null){
        return [
            'total' => ArticleModel::get_articles_count($_ENV['site_id'],$keyword),
            'list'  =>  $this->format(ArticleModel::get_articles($_ENV['site_id'], $skip,$take,$order,$keyword))
        ];
    }
    /*
     |--------------------------------------------------------------------------
     | 获得我的文章列表
     |--------------------------------------------------------------------------
     */
    private function get_mine_list($skip,$take, $order = 'desc' ,$keyword = null, $orderby = 'create_time'){
        $uid = $_ENV['uid'];
        return [
            'total' => ArticleModel::get_articles_count($_ENV['site_id'],$keyword,null,$uid),
            'list'  =>  $this->format(ArticleModel::get_articles($_ENV['site_id'], $skip,$take,$order,$keyword,null,$orderby,$uid))
        ];
    }
    /*
     |--------------------------------------------------------------------------
     | 获得回收站文章列表
     |--------------------------------------------------------------------------
     */
    private function get_recycle_list($skip,$take, $order = 'desc' ,$keyword = null){
        return [
            'total' => ArticleModel::get_articles_count($_ENV['site_id'],$keyword,null,null,1),
            'list'  =>  $this->format(ArticleModel::get_articles($_ENV['site_id'], $skip,$take,$order,$keyword,null,'create_time',null,1))
        ];
    }
    /*
     |--------------------------------------------------------------------------
     | 文章列表格式化
     |--------------------------------------------------------------------------
     */
    private function format($list){
        //TODO 最后权限判断获取角色ID
        $ret = [];
        if(!empty($list)){
            foreach($list as $k => $v){
                $ret[$k]['create_time'] = $v->create_time;
                $ret[$k]['post_time']   = $v->post_time;
                $ret[$k]['article_id']  = $this->check_article_auth($v->article_id,$v->user_id) ? $v->article_id : null;
                $ret[$k]['title']       = $v->title;
                $ret[$k]['nickname']    = $v->nickname;
                $ret[$k]['post_status'] = $this->post_status_format($v->post_status,$v->post_time);
                $ret[$k]['role']        = admin_role_map($v->role);
                $ret[$k]['new']         = $v->contribute_status < 1;
            }
        }
        return $ret;
    }
    /*
     |--------------------------------------------------------------------------
     | 获得文章发布状态
     |--------------------------------------------------------------------------
     */
    private function post_status_format($status, $time){
        return $status == 1 ? (time()>strtotime($time) ? 'now' : 'time') : 'cancel';
    }
    public function test(){
        return $this->get_list(0,10,'asc');
    }

}