<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/23
 * Time: 下午2:48
 */

namespace App\Http\Controllers\Admin;

use App\Http\Model\CategoryModel;

class CategoryController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        view()->share(['admin_nav_top'=>[
            'name' => '分类管理',
            'class'=> 'category'
        ]]);
    }
    /*
    |--------------------------------------------------------------------------
    | 分类管理首页
    |--------------------------------------------------------------------------
    */
    public function index(){
        return self::view('admin.category.index',[
            'list' => $this->listformat()
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | 分类列表 接口
    |--------------------------------------------------------------------------
    */
    public function categories(){
        return $this->ApiOut(0,$this->listformat());
    }
    /*
    |--------------------------------------------------------------------------
    | 分类列表格式化
    |--------------------------------------------------------------------------
    */
    private function listformat(){
        $list = CategoryModel::get_categorie_list($_ENV['site_id']);
        $show = [];
        $hide = [];
        foreach($list as $v){
            if($v->deleted == 0){
                $show[] = $v;
            }
            if($v->deleted == 1){
                $hide[] = $v;
            }
        }
        return [
            'show' => $show,
            'hide' => $hide
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | 分类列表排序保存 接口
    |--------------------------------------------------------------------------
    */
    public function ordersave(){
        $show =  request()->input('show');
        $hide =  request()->input('hide');
        $show = !!$show ? $show : [];
        $hide = !!$hide ? $hide : [];
        if((count($show) + count($hide))>5)return $this->ApiOut(40001,'请求错误');
        CategoryModel::order_save($_ENV['site_id'],$show,$hide);
        return $this->ApiOut(0,'保存成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 分类删除 接口
    |--------------------------------------------------------------------------
    */
    public function del(){
        $id =  request()->input('id');
        if(empty($id) || !!CategoryModel::get_category_related_article_count($_ENV['site_id'],$id))return $this->ApiOut(40001,'请求错误');
        CategoryModel::del_category($_ENV['site_id'],$id);
        return $this->ApiOut(0,'删除成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 分类删除关联文章 接口
    |--------------------------------------------------------------------------
    */
    public function delete(){
        $id     =  request()->input('id');
        $move   =  request()->input('move');
        if(empty($id) || empty($move) || !CategoryModel::category_owner($_ENV['site_id'],$move))return $this->ApiOut(40001,'请求错误');
        CategoryModel::article_transfer($_ENV['site_id'],$id,$move);
        CategoryModel::del_category($_ENV['site_id'],$id);
        return $this->ApiOut(0,'删除成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 精选修改 接口
    |--------------------------------------------------------------------------
    */
    public function edit(){
        $request = request();
        $id         = $request->input('id');
        $name       = trim($request->input('name'));
        if(!$id || !$name)return $this->ApiOut(40001,'名称为空');
        if(CategoryModel::category_exist($_ENV['site_id'],$name))return $this->ApiOut(40001,'分类已存在');
        CategoryModel::edit_category($_ENV['site_id'], $id, compact("name"));
        return $this->ApiOut(0,'保存成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 精选添加 接口
    |--------------------------------------------------------------------------
    */
    public function add(){
        $request = request();
        $name       = trim($request->input('name'));

        if(!$name)return $this->ApiOut(40001,'名称为空');
        if(CategoryModel::category_exist($_ENV['site_id'],$name))return $this->ApiOut(40001,'分类已存在');

        $max_order = CategoryModel::max_order($_ENV['site_id']);
        $max = isset($max_order->order) ? $max_order->order : 0;
        $count = CategoryModel::category_count($_ENV['site_id']);
        if($count >= 5)return $this->ApiOut(40001,'最多5个待选分类');
        CategoryModel::add_category($_ENV['site_id'],compact("name"),$max);
        return $this->ApiOut(0,'保存成功');
    }

}