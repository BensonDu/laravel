<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/4/26
 * Time: 下午11:50
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Model\Cache\PlatformCacheModel;
use App\Http\Model\CategoryModel;
use App\Http\Model\SiteModel;
use App\Http\Model\User\EditModel;
use App\Http\Model\UserModel;

class ApiController  extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | 文章发布列表
    |--------------------------------------------------------------------------
    */
    public static function postlist(){
        $id = request()->input('id');
        if(empty($id))return self::ApiOut(40001,'Bad Request');
        $article = EditModel::article_brief_info($_ENV['uid'],$id);
        //文章不存在 请求错误
        if(!isset($article->id))return self::ApiOut(40001,'Bad Request');
        $site = self::article_sta_site($id,$article->hash);
        return self::ApiOut(0,$site);
    }
    /*
    |--------------------------------------------------------------------------
    | 文章投稿到站点
    |--------------------------------------------------------------------------
    */
    public static function contribute(){
        $request = request();
        $id      = $request->input('id');
        $site_id = $request->input('site_id');
        $uid     = $_ENV['uid'];
        if(empty($id) || empty($site_id) || !SiteModel::check_site($site_id))return self::ApiOut(40001,'Bad Request');
        $ret = EditModel::contribute($site_id,$id,$uid);
        return !!$ret ? self::ApiOut(0,'投稿成功') :  self::ApiOut(40001,'请求错误');
    }
    /*
    |--------------------------------------------------------------------------
    | 文章发布到站点
    |--------------------------------------------------------------------------
    */
    public static function postsite(){
        $request = request();
        $site_id            = $request->input('site_id');
        $user_article_id    = $request->input('id');
        $category           = $request->input('category');
        $type               = $request->input('type');
        $time               = $request->input('time');

        if(empty($user_article_id) || ($type !='cancel' && empty($category)) || empty($type) || ($type == 'time' && strtotime($time) <= time())){
            return self::ApiOut(40003,'请求错误');
        }

        //检查是否有站点权限
        if(!SiteModel::check_user_site_auth($site_id,$_ENV['uid'])) return self::ApiOut(40003,'权限不足');

        //检查文章分类
        if(CategoryModel::category_exist($site_id,$category)) return self::ApiOut(40004,'分类不存在');

        $post_status = $type == 'cancel' ? 0 : (time() > strtotime($time) ? 1 : 2);

        //发布文章 返回 站点文章 ID
        $new_id = EditModel::post($site_id,$_ENV['uid'],$user_article_id,$category,$post_status,$time);
        if(empty($new_id)) return self::ApiOut(40004,$new_id);

        //如果定时发布 推到 任务
        if($post_status == 2){
            PlatformCacheModel::timing_article($site_id,$new_id,$time);
        }
        //如果非定时发布 清除定时发布
        else{
            PlatformCacheModel::timing_clear($new_id);
        }

        return self::ApiOut(0,'发布成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 文章推送更新到站点
    |--------------------------------------------------------------------------
    */
    public static function pushsite(){
        $request = request();
        $site_id            = $request->input('site_id');
        $user_article_id    = $request->input('id');
        if(empty($site_id) || empty($user_article_id))return self::ApiOut(40003,'请求错误');
        //检查是否有站点权限
        if(!SiteModel::check_user_site_auth($site_id,$_ENV['uid'])) return self::ApiOut(40003,'权限不足');

        $push = EditModel::pushsite($site_id,$_ENV['uid'],$user_article_id);

        return $push ? self::ApiOut(0,'推送更新成功') : self::ApiOut(10001,'推送更新失败');
    }
    /*
    |--------------------------------------------------------------------------
    | 搜索站点 不包含 已经拥有权限站点 已存在 常用列表站点
    |--------------------------------------------------------------------------
    */
    public static function searchsite(){
        $keyword = request('keyword');
        //有权限的站点列表
        $auth    = UserModel::site_role_list($_ENV['uid']);
        //获取用户常用站点列表
        $current = UserModel::current_site($_ENV['uid']);
        $except  = array_merge($auth,$current);
        //获取站点列表
        $list = SiteModel::get_site_list(0,10,empty($keyword) ? null : $keyword,$except,['id','name']);
        return self::ApiOut(0,$list);
    }
    /*
    |--------------------------------------------------------------------------
    | 添加常用站点
    |--------------------------------------------------------------------------
    */
    public static function addsite(){
        $site_id = request('site_id');
        //有权限的站点列表
        $auth    = UserModel::site_role_list($_ENV['uid']);
        //获取用户常用站点列表
        $current = UserModel::current_site($_ENV['uid']);
        $except  = array_merge($auth,$current);
        if(in_array($site_id,$except))return  self::ApiOut(20001,'站点已添加');
        $current[] = $site_id;
        UserModel::update_current_site($_ENV['uid'],array_unique($current));
        return self::ApiOut(0,'站点添加成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 移除常用站点
    |--------------------------------------------------------------------------
    */
    public static function removesite(){
        $site_id = request('site_id');
        //获取用户常用站点列表
        $current = UserModel::current_site($_ENV['uid']);
        $new = [];
        if(in_array($site_id,$current)){
            foreach ($current as $v){
                if($v!=$site_id) $new[] = $v;
            }
            UserModel::update_current_site($_ENV['uid'],$new);
        }

        return self::ApiOut(0,'站点移除成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 文章站点发布状态列表
    |--------------------------------------------------------------------------
    */
    private static function article_sta_site($id,$hash){
        //获取文章在站点文章表中的所有有效列表
        $info    = EditModel::article_site_info($id);
        //获取该用户有权限的站点列表
        $auth    = UserModel::site_role_list($_ENV['uid']);
        //获取用户常用站点列表
        $current = UserModel::current_site($_ENV['uid']);
        $cur = [];
        //过滤常用站点 用户已拥有该站点 权限
        foreach ($current as $v){
            if(!in_array($v,$auth)){
                $cur[] = $v;
            }
        }
        //如果 常用站点包含拥有权限站点 更新常用站点
        if(count($cur)!= count($current))UserModel::update_current_site($id,$cur);
        $auth_list = [];
        $cont_list = [];
        $contributed_site = [];
        $posted_site      = [];
        if(!empty($info)){
            foreach ($info as $v){
                //遍历站点文章表  已有站点权限表部分
                if(in_array($v->site_id,$auth)){
                    $posted_site[] = $v->site_id;
                    $auth_list[] = [
                        'site_id'       => $v->site_id,
                        'name'          => $v->name,
                        'category'      => $v->category,
                        'post_time'     => $v->post_time,
                        //发布状态 : start初始 | time 定时发布 | cancel 未发布 | now 已发布
                        'post_status'   => ($v->deleted == 0) ? ($v->post_status == 2 ? 'time' : ($v->post_status == 0 ? 'cancel' : 'now')) : 'cancel',
                        'update'        => ($v->deleted == 1 || ($v->site_lock)) ?  'lock' : (($v->post_status >0 && $v->hash != $hash) ? 'enable' : 'disable')
                    ];
                }
                //常用投稿站点部分
                else{
                    if(in_array($v->site_id,$cur)){
                        $contributed_site[] = $v->site_id;
                        $cont_list[] = [
                            'site_id'       => $v->site_id,
                            'name'          => $v->name,
                            //投稿状态 : new 新文章 | auth 定时发布 | now 已发布 | refuse 已拒绝
                            'post_status'   => ($v->deleted == 0) ? (($v->contribute_status == 0 || $v->post_status == 2) ? 'auth' : ($v->post_status == 1 ? 'now' : 'refuse')) : 'refuse',
                            'update'        => 'disable'
                        ];
                    }
                }
            }
        }
        $site_map = [];
        if(!empty($auth) || !empty($cur)){
            $site_ids = array_merge($auth,$cur);
            $site_info_list = SiteModel::get_site_info_list($site_ids,['id','name']);
            foreach ($site_info_list as $v){
                $site_map[$v->id] = $v->name;
            }
        }
        if(!empty($auth)){
            foreach ($auth as $v){
                if(!in_array($v,$posted_site) && isset($site_map[$v])){
                    $auth_list[] = [
                        'site_id'       => $v,
                        'name'          => $site_map[$v],
                        'category'      => '',
                        'post_time'     => now(),
                        'post_status'   => 'start',
                        'update'        => 'disable'
                    ];
                }
            }
        }
        if(!empty($cur)){
            foreach ($cur as $v){
                if(!in_array($v,$contributed_site) && isset($site_map[$v])){
                    $cont_list[] = [
                        'site_id'       => $v,
                        'name'          => $site_map[$v],
                        'post_status'   => 'new',
                        'update'        => 'enable'
                    ];
                }
            }
        }
        $ret = [];
        if(!empty($auth_list))$ret['auth'] = $auth_list;
        if(!empty($cont_list))$ret['contribute'] = $cont_list;
        return $ret;
    }
}