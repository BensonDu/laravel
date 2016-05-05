<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/8
 * Time: 下午7:40
 */

namespace App\Http\Controllers\User;

use App\Http\Model\ArticleUserModel;

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
        $article = ApiController::get_list(0,20,null);
        $data['list']   = $article['list'];
        $data['total']  = $article['total'];
        $data['route']  = $route;
        $data['base']['title'] = '个人文章管理-创之';
        return self::view('/user/edit',$data);
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
}