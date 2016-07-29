<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/23
 * Time: 下午2:48
 */

namespace App\Http\Controllers\Admin;


use App\Http\Model\Admin\ArticleModel;
use App\Http\Model\SiteSpecialModel;
use App\Http\Model\StarModel;

class StarController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        view()->share(['admin_nav_top'=>[
            'name' => '精选管理',
            'class'=> 'star'
        ]]);
    }
    /*
    |--------------------------------------------------------------------------
    | 精选管理首页
    |--------------------------------------------------------------------------
    */
    public function index(){
        $data['base']['title'] = '精选管理';
        $data['list'] = StarModel::get_star_list($_ENV['domain']['id']);
        return self::view('admin.star.index',$data);
    }
    /*
    |--------------------------------------------------------------------------
    | 精选列表 接口
    |--------------------------------------------------------------------------
    */
    public function starlist(){
        $list = StarModel::get_star_list($_ENV['domain']['id']);
        return $this->ApiOut(0,$list);
    }
    /*
    |--------------------------------------------------------------------------
    | 精选列表排序 接口
    |--------------------------------------------------------------------------
    */
    public function ordersave(){
        $order =  request()->input('order');
        $site_id = $_ENV['domain']['id'];
        if(empty($order) || !is_array($order) || !StarModel::check_star_auth($site_id,$order))return $this->ApiOut(40001,'请求错误');
        StarModel::order_save($_ENV['domain']['id'],$order);
        return $this->ApiOut(0,'保存成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 精选删除 接口
    |--------------------------------------------------------------------------
    */
    public function del(){
        $id =  request()->input('id');
        if(empty($id))return $this->ApiOut(40001,'请求错误');
        StarModel::del_star($_ENV['domain']['id'],$id);
        return $this->ApiOut(0,'删除成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 精选保存 接口
    |--------------------------------------------------------------------------
    */
    public function save(){
        $request = request();
        $id         = $request->input('id');
        $title      = $request->input('title');
        $category   = $request->input('category');
        $image      = $request->input('image');
        $type       = $request->input('type');
        $jump_info  = $request->input('jump_info');
        if($type == 'link'){
            $jump_info = url_fix($jump_info);
        }
        $update_time= now();
        if(!$id || !$title || !$category || !$image || !$this->check_jump($type,$jump_info))return $this->ApiOut(40001,'请求错误');
        StarModel::save_star($_ENV['domain']['id'], $id, compact("title","category","image","type","jump_info","update_time"));
        return $this->ApiOut(0,'保存成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 精选添加 接口
    |--------------------------------------------------------------------------
    */
    public function add(){
        $request = request();
        $title      = $request->input('title');
        $category   = $request->input('category');
        $image      = $request->input('image');
        $type       = $request->input('type');
        $jump_info  = $request->input('jump_info');
        if($type == 'link'){
            $jump_info = url_fix($jump_info);
        }
        if(!$title || !$category || !$image || !$this->check_jump($type,$jump_info))return $this->ApiOut(40001,'请求错误');
        $max_order = StarModel::max_order($_ENV['domain']['id']);
        $max = isset($max_order->order) ? $max_order->order : 0;
        $count = StarModel::star_count($_ENV['domain']['id']);
        if($count >= 8)return $this->ApiOut(40001,'超过最大精选数');
        StarModel::add_star($_ENV['domain']['id'], compact("title","category","image","type","jump_info"),$max);
        return $this->ApiOut(0,'保存成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 精选信息 接口
    |--------------------------------------------------------------------------
    */
    public function info(){
        $id =  request()->input('id');
        $site_id = $_ENV['domain']['id'];
        if(empty($id))return $this->ApiOut(40001,'请求错误');
        $info = StarModel::get_star_info($site_id,$id);
        if(isset($info->type)){
            if($info->type == 'article'){
                $info->article = ArticleModel::get_artcile_brief_info($site_id,$info->jump_info,['id','title','create_time']);
            }
            if($info->type == 'special'){
                $info->special = SiteSpecialModel::get_special_brief_info($site_id,$info->jump_info,['id','title','create_time']);
            }
            if($info->type == 'link'){
                $info->link = $info->jump_info;
            }
        }
        return $this->ApiOut(!!$info ? 0 : 10001, $info);
    }
    /*
    |--------------------------------------------------------------------------
    | 获取文章列表接口 接口
    |--------------------------------------------------------------------------
    */
    public function articles(){
        $keyword =  request()->input('keyword');
        $list = ArticleModel::get_articles($_ENV['domain']['id'],0,8,'desc',$keyword,1);
        $ret = [];
        foreach($list as $k => $v){
            $ret[$k]['title'] = $v->title;
            $ret[$k]['id']    = $v->article_id;
            $ret[$k]['time']  = $v->create_time;
            $ret[$k]['category']  = $v->category_name;
            $ret[$k]['image']  = $v->image;
        }
        return $this->ApiOut(0 ,$ret);
    }

    /*
    |--------------------------------------------------------------------------
    | 获取专题列表接口 接口
    |--------------------------------------------------------------------------
    */
    public function specials(){
        $keyword =  request()->input('keyword');
        $list = SiteSpecialModel::get_special_list($_ENV['domain']['id'],0,7,$keyword);
        $ret = [];
        foreach($list as $k => $v){
            $ret[$k]['title'] = $v->title;
            $ret[$k]['id']    = $v->id;
            $ret[$k]['time']  = $v->update_time;
            $ret[$k]['category']  = '';
            $ret[$k]['image']  = $v->image;
        }
        return $this->ApiOut(0 ,$ret);
    }
    /*
    |--------------------------------------------------------------------------
    | 检查 跳转类型
    |--------------------------------------------------------------------------
    */
    private function check_jump($type,$info){
        $site_id = $_ENV['domain']['id'];
        $ret = false;
        switch ($type){
            case 'link':
                $ret = true;
                break;
            case 'article':
                $ret = ArticleModel::is_article_exist($site_id,$info);
                break;
            case 'special':
                $ret = SiteSpecialModel::is_special_exist($site_id,$info);
                break;
            default:
        }
        return $ret;
    }
}