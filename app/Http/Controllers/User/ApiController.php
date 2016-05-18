<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/4/26
 * Time: 下午11:50
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Model\ArticleUserModel;
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
    | 获取文章信息
    |--------------------------------------------------------------------------
    |
    */
    public function article(){
        $article_id = request()->input('id');
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
            $ret['post_status'] = !!EditModel::article_existed_in_site($article_id) ? 1 : 0;
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
    | 获取文章列表 公共方法
    |--------------------------------------------------------------------------
    */
    public static function get_list($skip,$take,$post_status = null,$keyword = null){
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
    */
    public function save(){
        $request = request();
        $article_id = $request->input('id');
        $title      = $request->input('title');
        $summary    = $request->input('summary');
        $content    = $request->input('content');
        $image      = $request->input('image');
        $tags       = json_decode($request->input('tags'),1);
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
    /*
    |--------------------------------------------------------------------------
    | 删除文章
    |--------------------------------------------------------------------------
    */
    public function delete(){
        $article_id = request()->input('id');
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
    | 文章投稿到站点
    |--------------------------------------------------------------------------
    */
    public static function contribute(){
        $request = request();
        $id      = $request->input('id');
        $site_id = $request->input('site_id');
        $uid     = $_ENV['uid'];
        if(empty($id) || empty($site_id) || !SiteModel::check_site($site_id))return self::ApiOut(40001,'请求错误');
        //检查站点是否开启外部投稿
        $valid = SiteModel::get_site_id_list();
        if(!in_array($site_id, $valid))return self::ApiOut(40003,'站点已关闭外部投稿');

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

        $post_status = $type == 'cancel' ? 0 : (($type == 'time' && strtotime($time)>time()) ? 2 : 1);

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
                        'update'        => 'start'
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