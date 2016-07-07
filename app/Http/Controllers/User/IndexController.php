<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/8
 * Time: 下午7:40
 */

namespace App\Http\Controllers\User;

use App\Http\Model\ArticleUserModel;

class IndexController extends UserController
{

    /*
     |--------------------------------------------------------------------------
     | 用户主页
     |--------------------------------------------------------------------------
     */
    public function index($id = null){

        if(is_null($id))abort(404);
        $data = UserController::profile($id);
        if(empty($data))abort(404);

        /*是否为本人主页*/
        $data['self']   = ($id == $_ENV['uid']) ? true : false;
        /*当前 Active 菜单*/
        $data['active'] = 'home';
        /*设置页面 title*/
        $data['base']['title'] = $data['self'] ? '我的个人主页-创之' : $data['profile']['nickname'].'的个人主页-创之';


        //文章列表
        $articles = ArticleUserModel::get_home_article_list($id);

        $data['list']   = self::get_list($articles['list']);

        $data['total']  = $articles['total'];

        return self::view('/user/index',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | 个人主页 -> 跳转到当前登录uid主页
     |--------------------------------------------------------------------------
     */
    public function self(){
        return redirect('/user/'.$_ENV['uid']);
    }
    /*
     |--------------------------------------------------------------------------
     | 文章列表接口
     |--------------------------------------------------------------------------
     */
    public static function articles(){
        $id    = request()->input('id');
        $index = request()->input('index');
        if(empty($index) || empty($id)){
            return self::ApiOut(40001,'请求错误');
        }
        $skip =intval($index)*10;
        //文章列表
        $articles = ArticleUserModel::get_home_article_list($id,$skip);
        $list = self::get_list($articles['list']);
        return self::ApiOut(0,$list);
    }
    /*
     |--------------------------------------------------------------------------
     | 私有方法 获取格式化文章列表
     |--------------------------------------------------------------------------
     */
    private static function get_list($list){

        foreach($list as $k =>$v){
            $tags = [];
            $tag = tag($v->tags);
            if(!empty($tag)){
                foreach($tag as $vv){
                    $tags[] = [
                        'item'  => $vv,
                        'color' => rand_color()
                    ];
                }
            }
            $list[$k]->image    = image_crop($v->image,200);
            $list[$k]->tags     = $tags;
            $list[$k]->domain   = !empty($v->custom_domain) ? 'http://'.$v->custom_domain : 'http://'.$v->platform_domain.'.'.$_ENV['platform']['domain'];
            $list[$k]->post_time= time_down(strtotime($v->post_time));
        }

        return $list;
    }

}