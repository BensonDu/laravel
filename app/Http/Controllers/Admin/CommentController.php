<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/23
 * Time: 下午2:48
 */

namespace App\Http\Controllers\Admin;

use App\Http\Model\CommentModel;

class CommentController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        view()->share(['admin_nav_top'=>[
            'name' => '评论管理',
            'class'=> 'comment'
        ]]);
    }
    /*
    |--------------------------------------------------------------------------
    | 评论管理首页
    |--------------------------------------------------------------------------
    */
    public function index(){
        $data['base']['title'] = '评论管理';
        $list = self::get_list(0,10,'desc');
        $data = array_merge($data,$list);
        return self::view('admin.comment.index',$data);
    }
    /*
    |--------------------------------------------------------------------------
    | 评论管理列表
    |--------------------------------------------------------------------------
    */
    public function comments(){
        $index      = intval(request()->input('index'));
        $site       = request()->input('site');
        $size       = intval(request()->input('size'));
        $order      = request()->input('order');
        if(empty($index) || intval($size) > 50 || !in_array($order,['asc','desc']))return $this->ApiOut(40001,'Bat request');
        $skip = (intval($index)-1)*$size;
        $data = self::get_list($skip,$size,$order,$site == 'insite');
        return self::ApiOut(0,$data);
    }
    /*
    |--------------------------------------------------------------------------
    | 获取评论列表 私有方法
    |--------------------------------------------------------------------------
    */
    private static function get_list($skip,$take,$order = 'desc',$inside = true){
        $list = CommentModel::getComments($_ENV['site_id'],$skip,$take,$order,$inside);
        foreach ($list as $k => $v){
            $list[$k]->link = !empty($v->custom_domain) ? 'http://'.$v->custom_domain.'/'.$v->article_id : 'http://'.$v->platform_domain.'.'.$_ENV['platform']['domain'].'/'.$v->article_id;
        }
        return  [
            'list' => $list,
            'total'=> CommentModel::getCommentsCount($_ENV['site_id'],$inside)
        ];
    }
}