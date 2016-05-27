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
        $data['base']['title'] = '分类管理';
        $data['list']          = self::format();
        return self::view('admin.category.index',$data);
    }
    /*
    |--------------------------------------------------------------------------
    | 分类列表 接口
    |--------------------------------------------------------------------------
    */
    public function categories(){
        return $this->ApiOut(0,self::format());
    }
    /*
    |--------------------------------------------------------------------------
    | 分类列表格式化
    |--------------------------------------------------------------------------
    */
    private static function format(){
        $list = CategoryModel::get_category_list($_ENV['site_id']);
        $default    = [];
        $custom     = [];
        foreach($list as $v){
            if($v['id'] == '0'){
                $default = $v;
            }
            else{
                $custom[] = $v;
            }
        }
        return [
            'default'   => $default,
            'custom'    => $custom
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | 分类显示状态 接口
    |--------------------------------------------------------------------------
    */
    public function display(){
        $id =  request()->input('id');
        $deleted = request()->input('deleted') == '1' ? 1 : 0;
        if(empty($id))return $this->ApiOut(40001,'请求错误');
        CategoryModel::del_category($_ENV['site_id'],$id,$deleted);
        return $this->ApiOut(0,'操作成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 分类列表排序保存 接口
    |--------------------------------------------------------------------------
    */
    public function ordersave(){
        $order =  request()->input('order');
        $order = !!$order ? $order : [];
        if((count($order))>5)return $this->ApiOut(40001,'请求错误');
        CategoryModel::order_save($_ENV['site_id'],$order);
        return $this->ApiOut(0,'保存成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 分类删除关联文章 接口
    |--------------------------------------------------------------------------
    */
    public function delete(){
        $id     =  intval(request()->input('id'));
        $move   =  intval(request()->input('move'));

        if(($id == 0 && !CategoryModel::category_owner($_ENV['site_id'],$move)) || ($move != 0 && !CategoryModel::category_owner($_ENV['site_id'],$move)))return $this->ApiOut(40001,'请求错误');

        //默认分类转移
        if($id == 0){
            CategoryModel::article_transfer($_ENV['site_id'],$id,$move);
        }
        //常规分类删除
        else{
            CategoryModel::article_transfer($_ENV['site_id'],$id,$move);
            CategoryModel::del_category($_ENV['site_id'],$id);
        }
        
        return $this->ApiOut(0,'删除成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 分类名称修改 接口
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
    | 分类添加 接口
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