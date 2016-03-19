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
use App\Http\Model\SiteSpecialModel;

class SpecialController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        view()->share(['admin_nav_top'=>[
            'name' => '专题管理',
            'class'=> 'special'
        ]]);
    }
    public function index(){
        return self::view('admin.special.index',$this->getlist(0,10));
    }
    /*
     |--------------------------------------------------------------------------
     | 专题列表 接口
     |--------------------------------------------------------------------------
     */
    public function specials(){
        $index      = intval(request()->input('index'));
        $keyword    = request()->input('keyword');
        $size       = intval(request()->input('size'));
        $order      = request()->input('order');
        $orderby    = request()->input('orderby');
        if(empty($index) || intval($size) > 50 || !in_array($order,['asc','desc']) || !in_array($orderby,['update_time','post_status']))return $this->ApiOut(40001,'Bat request');
        $keyword = !empty($keyword) ? $keyword : null;
        $skip = (intval($index)-1)*$size;
        $list = $this->getlist($skip,$size,$keyword,$orderby,$order);
        return $this->ApiOut(0,$list);
    }
    /*
     |--------------------------------------------------------------------------
     | 专题删除 接口
     |--------------------------------------------------------------------------
     */
    public function delete(){
        $request = request();
        $id = $request->input('id');
        if(empty($id)){
            return $this->ApiOut(40001,'请求错误');
        }
        SiteSpecialModel::special_delete($_ENV['site_id'],$id);
        return $this->ApiOut(0,'删除成功');
    }
    /*
     |--------------------------------------------------------------------------
     | 专题发布 接口
     |--------------------------------------------------------------------------
     */
    public function post(){
        $request = request();
        $id          = $request->input('id');
        $post_status = !!$request->input('post');
        if(empty($id)){
            return $this->ApiOut(40001,'请求错误');
        }
        SiteSpecialModel::special_update($_ENV['site_id'],$id,compact("post_status"));
        return $this->ApiOut(0,'更改发布状态成功');
    }
    /*
     |--------------------------------------------------------------------------
     | 专题更新 接口
     |--------------------------------------------------------------------------
     */
    public function save(){
        $request    = request();
        $id         = $request->input('id');
        $title      = $request->input('title');
        $summary    = $request->input('summary');
        $bg_image   = $request->input('bg_image');
        $image      = $request->input('image');
        $update_time= now();
        $list       = $request->input('list');
        if(empty($id) || empty($title) || empty($bg_image) || empty($image) || empty($list)) return $this->ApiOut(40001,'请求错误');
        $list   = trim(implode(' ',$list));
        SiteSpecialModel::special_update($_ENV['site_id'],$id,compact("title","summary","image","bg_image","update_time","list"));
        return $this->ApiOut(0,'更新成功');
    }
    /*
     |--------------------------------------------------------------------------
     | 专题添加 接口
     |--------------------------------------------------------------------------
     */
    public function add(){
        $request    = request();
        $title      = $request->input('title');
        $summary    = $request->input('summary');
        $bg_image   = $request->input('bg_image');
        $image      = $request->input('image');
        $list       = $request->input('list');
        if( empty($title) || empty($bg_image) || empty($image) || empty($list)) return $this->ApiOut(40001,'请求错误');
        $list   = trim(implode(' ',$list));
        SiteSpecialModel::special_add($_ENV['site_id'],compact("title","summary","image","bg_image","list"));
        return $this->ApiOut(0,'添加成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 获取专题信息 接口
    |--------------------------------------------------------------------------
    */
    public function info(){
        $id =  request()->input('id');
        if(empty($id))return $this->ApiOut(40001,'请求错误');
        $info = SiteSpecialModel::get_special_brief_info($_ENV['site_id'],$id,['id','title','summary','image','bg_image','list']);
        if(!isset($info->id))return $this->ApiOut(40001,'请求错误');
        $list =explode(' ',$info->list);
        $info->list = ArticleSiteModel::get_article_list_by_ids($_ENV['site_id'],$list,['id','title']);
        return $this->ApiOut(0,$info);
    }
    /*
    |--------------------------------------------------------------------------
    | 获取文章列表接口 接口
    |--------------------------------------------------------------------------
    */
    public function articles(){
        $keyword =  request()->input('keyword');
        $list = ArticleModel::get_articles($_ENV['site_id'],0,8,'desc',$keyword,1);
        $ret = [];
        foreach($list as $k => $v){
            $ret[$k]['title'] = $v->title;
            $ret[$k]['id']    = $v->article_id;
            $ret[$k]['time']  = $v->create_time;
            $ret[$k]['category']  = $v->category_name;
        }
        return $this->ApiOut(0 ,$ret);
    }
    /*
    |--------------------------------------------------------------------------
    | 获取文章列表基础方法 私有
    |--------------------------------------------------------------------------
    */
    private function getlist($skip,$take,$keyword = null,$orderby='update_time',$order='desc'){
        $list = SiteSpecialModel::get_special_list($_ENV['site_id'],$skip,$take,$keyword,null,0,$orderby,$order);
        $ret = [];
        foreach($list as $k => $v){
            $ret[$k]['title'] = $v->title;
            $ret[$k]['id']    = $v->id;
            $ret[$k]['post_status']=  $v->post_status == 1 ? 'now' : 'cancel';
            $ret[$k]['update_time']  = $v->update_time;
        }
        return [
            'list'  => $ret,
            'total' => SiteSpecialModel::get_special_count($_ENV['site_id'])
        ];
    }

}