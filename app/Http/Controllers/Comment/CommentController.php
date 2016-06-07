<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/6/2
 * Time: 下午3:14
 */

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Model\CommentModel;
use App\Http\Model\SiteModel;
use App\Http\Model\UserModel;

class CommentController extends Controller{

    /*
     |--------------------------------------------------------------------------
     | 获取文章评论 回复
     |--------------------------------------------------------------------------
     */
    public static function comments(){
        $article_id = request()->input('id');
        $root       = intval(request()->input('root'));
        $orderby    = request()->input('orderby');
        $orderby    = 'new' == $orderby ? 'new' : 'hot';
        $order      = 'asc' == request()->input('order') ? 'asc' : 'desc';
        $root       = !empty($root) ? $root : 0;
        $list = self::format(CommentModel::getArticleComments($_ENV['site_id'],intval($_ENV['uid']),$article_id,$order),$root,$orderby);
        return self::ApiOut(0,$list);
    }
    /*
     |--------------------------------------------------------------------------
     | 评论赞
     |--------------------------------------------------------------------------
     */
    public static function like(){
        $id     = request()->input('id');
        $uid    = $_ENV['uid'];
        $valid  = request()->input('valid');

        if(empty($id) || empty($uid))return self::ApiOut(40001,'请求错误');

        CommentModel::likeComment($id,$uid,!!$valid ? 1 : 0);

        return self::ApiOut(0,'操作成功');
    }
    /*
     |--------------------------------------------------------------------------
     | 评论删除
     |--------------------------------------------------------------------------
     */
    public static function delete(){
        $id = request()->input('id');

        if(empty($id) || !CommentModel::checkCommentAuth($_ENV['uid'],$id))return self::ApiOut(40001,'请求错误');

        CommentModel::deleteComment($id);

        return self::ApiOut(0,'操作成功');
    }
    /*
     |--------------------------------------------------------------------------
     | 评论隐藏
     |--------------------------------------------------------------------------
     */
    public static function hide(){
        $id = request()->input('id');
        if(empty($id) || !isset($_ENV['admin']['role']) || $_ENV['admin']['role'] <= 1)return self::ApiOut(40001,'请求错误');
        CommentModel::hideComment($_ENV['site_id'],$id);
        return self::ApiOut(0,'操作成功');
    }
    /*
     |--------------------------------------------------------------------------
     | 评论 || 回复
     |--------------------------------------------------------------------------
     */
    public static function submit(){
        $request    = request();
        $uid        = $_ENV['uid'];
        $site_id    = $_ENV['site_id'];
        $article_id = $request->input('id');
        $content    = htmlspecialchars($request->input('content'));
        $parent         = $request->input('parent');
        $parent         = !empty($parent) ? $parent : 0;
        if(empty($uid) || empty($site_id) || strlen($content) < 5) return self::ApiOut(40001,'请求错误');

        //评论速率限制
        $comments =  CommentModel::getUserLatestComment($_ENV['site_id'],$_ENV['uid']);
        //3分钟内连续评论限制
        if(!empty($comments)){
            $latest = strtotime($comments[1]->time);
            $d = time()-$latest;
            if($d < 60*3) return self::ApiOut(40001,'请在 '.ceil(3-$d/60).' 分钟后评论');
        }
        //一天同一站点评论20条
        if(count($comments) == 20){
            $earliest = strtotime($comments[19]->time);
            if(time()-$earliest < 60*60*24) return self::ApiOut(40001,'超出一天评论限制数量');
        }

        CommentModel::addArticleComment($site_id,$article_id,$uid,$content,$parent);
        return self::ApiOut(0,'评论成功');
    }
    /*
     |--------------------------------------------------------------------------
     | 评论列表格式化
     |--------------------------------------------------------------------------
     */
    private static function format($list,$root,$orderby = 'hot'){

        $site = SiteModel::get_site_info($_ENV['site_id']);

        //站点开启评论
        if(empty($site->comment))return [];
        //站点开启站外评论
        $ex = !empty($site->comment_ex);
        //评论数
        $count = [];
        //用户列表
        $users = [];
        //父级对应用户表
        $id_user_map = [];
        foreach ($list as $v){
            //遍历出所有用户 ID 及父级评论 ID
            if($v->parent != '0' && !in_array($v->parent,$users))$users[] = $v->parent;
            if($v->user_id != '0' && !in_array($v->user_id,$users))$users[] = $v->user_id;

            //建立ID对应用户信息映射数组
            $id_user_map['id_'.$v->id] = $v->user_id;

            //筛选出拥有回复的评论ID及数量
            if(!isset($count['id_'.$v->root])) $count['id_'.$v->root] = 0;
            //根评论ID不为空
            if(!empty($v->root))$count['id_'.$v->root]++;
        }
        //用户信息映射
        $user_map = [];
        if(!empty($users)){
            $user_list = UserModel::get_user_list_by_ids($users);
            foreach ($user_list as $v){
                $user_map[$v->id]['id']         = $v->id;
                $user_map[$v->id]['avatar']     = $v->avatar;
                $user_map[$v->id]['nickname']   = $v->nickname;
            }
        }
        $ret = [];
        foreach ($list as $k => $v){
            if($v->root != $root)continue;
            $comment =[];
            $comment['id'] = $v->id;
            //站点ID
            $comment['site_id'] = $v->site_id;
            //站点首页
            $comment['site_home'] = !empty($v->custom_domain) ? 'http://'.$v->custom_domain.'/' : 'http://'.$v->platform_domain.'.'.$_ENV['platform']['domain'].'/';
            //站点名称
            $comment['site_name'] = $v->name;
            //是否已删除
            $comment['hide'] = 0;
            //是否赞
            $comment['like'] = intval($v->like);
            //正文
            $comment['content'] = $v->content;
            //时间
            $comment['time'] = time_down(strtotime($v->time));
            //点赞数
            $comment['like_count'] = $v->likes;
            //回复数
            $comment['reply_count'] = isset($count['id_'.$v->id]) ? $count['id_'.$v->id] : 0;
            //用户主页
            $comment['user_home'] = empty($v->hide) && isset($user_map[$v->user_id]['id']) ? $_ENV['platform']['home'].'/user/'.$user_map[$v->user_id]['id'] : '';
            //用户昵称
            $comment['nickname']  = isset($user_map[$v->user_id]['nickname']) ? $user_map[$v->user_id]['nickname'] : '';
            //用户头像
            $comment['avatar'] = isset($user_map[$v->user_id]['avatar']) ? $user_map[$v->user_id]['avatar'] : '';
            //被回复昵称
            $comment['replied_nickname'] = '';
            //被回复主页
            $comment['replied_home'] = '';
            if(isset($id_user_map['id_'.$v->parent]) && isset($user_map[$id_user_map['id_'.$v->parent]])){
                $comment['replied_nickname'] = $user_map[$id_user_map['id_'.$v->parent]]['nickname'];
                $comment['replied_home'] = $_ENV['platform']['home'].'/user/'.$user_map[$id_user_map['id_'.$v->parent]]['id'];
            };
            //评论已删除 或 被站点管理员隐藏 或关闭站外评论
            if(!empty($v->hide) || !empty($v->deleted) || (!$ex && $v->site_id != $_ENV['site_id'])){
                $comment['hide']     = 1;
                $comment['content']  = '评论已被删除';
                $comment['nickname'] = '用户信息已隐藏';
                $comment['avatar']   = 'http://dn-noman.qbox.me/question mark.png';
            }
            //如果评论被删除 且 没有回复不显示
            if($comment['hide'] && empty($comment['reply_count']))continue;
            $comment['rank'] = intval($comment['like_count'])*5+intval($comment['reply_count'])*10;
            $ret[] = $comment;
        }
        if($orderby =='hot'){
            usort($ret, function($a, $b) {
                return $b['rank'] - $a['rank'] ;
            });
        }
        return $ret;
    }

}