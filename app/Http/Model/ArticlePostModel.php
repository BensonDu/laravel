<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/6/30
 * Time: 下午3:45
 */

namespace App\Http\Model;


use App\Http\Model\Cache\CacheModel;
use App\Http\Model\Cache\StartCacheModel;
use App\Http\Model\Cache\TimingCacheModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ArticlePostModel extends Model
{

    protected $table = 'articles_user';
    public $timestamps = false;


    /*
    |--------------------------------------------------------------------------
    | 用户投稿文章
    |--------------------------------------------------------------------------
   */
    public static function contribute($id,$site_id,$start,$delay){
        //获取文章在站点的所有发布信息
        $postinfo   = self::postinfo($id,$site_id);

        //是否已有首发 可用首发  以及已经首发发布文章
        $hasstart   = $postinfo['hasstart'];
        //已有普通文章
        $hasarticle = $postinfo['hasarticle'];
        //首发站点
        $startid    = $postinfo['startid'];
        //首发发布
        $startpost  = $postinfo['startpost'];
        //是否处于保鲜期
        $infresh    = $postinfo['infresh'];
        //文章在该站点被删除
        $deleted    = $postinfo['deleted'];
        //当前时间
        $now        = now();
        //当前文章所在站点被删除
        if($deleted) return false;

        //删除文章 保鲜期过后待操作文章队列
        StartCacheModel::del_queue_list($id,$site_id);
        
        //站点表已存在该文章
        if(isset($postinfo['info']->id))return false;

        //已有未删除文章
        if($hasarticle){
            //没有首发文章
            if(!$hasstart){
                //已有普通文章 正常投稿
                self::normalpost($id,$site_id,0,$now,null,$start,$delay);
            }
            //已有首发文章
            else{
                //其它站点首发
                if($startid != $site_id){
                    //是否已经首发发布
                    if($startpost){
                        //处于保鲜期
                        if($infresh){
                            //更新保鲜期过后处理队列
                            StartCacheModel::set_queue_list($id,$site_id,0,$now);
                        }
                        //不在保鲜期
                        else{
                            //正常投稿操作
                            self::normalpost($id,$site_id,0,$now);
                        }
                    }
                    //没有发布
                    else{
                        //更新保鲜期过后处理队列
                        StartCacheModel::set_queue_list($id,$site_id,0,$now);
                    }
                }
            }
        }
        //没有任何文章
        else{
            self::normalpost($id,$site_id,0,$now,null,$start,$delay);
        }
        return true;
    }
    /*
    |--------------------------------------------------------------------------
    | 用户发布文章
    |--------------------------------------------------------------------------
   */
    public static function userpost($id,$site_id,$post_status,$post_time,$category,$start,$start_delay){

        //获取文章在站点的所有发布信息
        $postinfo   = self::postinfo($id,$site_id);
        //是否已有首发 可用首发  以及已经首发发布文章
        $hasstart   = $postinfo['hasstart'];
        //已有普通文章
        $hasarticle = $postinfo['hasarticle'];
        //首发站点
        $startid    = $postinfo['startid'];
        //首发发布
        $startpost  = $postinfo['startpost'];
        //是否处于保鲜期
        $infresh    = $postinfo['infresh'];
        //文章在该站点被删除
        $deleted    = $postinfo['deleted'];

        //当前文章所在站点被删除
        if($deleted) return false;
        //删除文章保鲜期过后待操作文章队列
        StartCacheModel::del_queue_list($id,$site_id);

        //已有未删除文章
        if($hasarticle){
            //没有首发文章
            if(!$hasstart){
                //已有普通文章 正常发布
                self::normalpost($id,$site_id,$post_status,$post_time,$category);
            }
            //已有首发文章
            else{
                //为当前发布站点
                if($startid == $site_id){
                    //是否已经首发发布
                    if($startpost){
                        //普通更新文章发布信息
                        self::normalpost($id,$site_id,$post_status,$post_time,$category);
                    }
                    else{
                        //立即发布
                        if($post_status == '1'){
                            //发布文章 设置保鲜期过后定时器
                            self::normalpost($id,$site_id,$post_status,$post_time,$category);
                        }
                        else{
                            //正常发布操作
                            self::normalpost($id,$site_id,$post_status,$post_time,$category);
                        }
                    }
                }
                //其它发布站点
                else{
                    //是否已经首发发布
                    if($startpost){
                        //处于保鲜期
                        if($infresh){
                            if($post_status == '0'){
                                //从文章等待保鲜期过后处理队列删除
                                StartCacheModel::del_queue_list($id,$site_id);
                            }
                            else{
                                //更新保鲜期过后处理队列
                                StartCacheModel::set_queue_list($id,$site_id,$post_status,$post_time,$category);
                            }
                        }
                        //不在保鲜期
                        else{
                            //正常发布操作
                            self::normalpost($id,$site_id,$post_status,$post_time,$category);
                        }
                    }
                    else{
                        //是否取消发布
                        if($post_status == '0'){
                            //从文章等待文章发布且保鲜期过后处理队列删除
                            StartCacheModel::del_queue_list($id,$site_id);
                        }
                        else{
                            //更新文章发布且保鲜期过后处理队列
                            StartCacheModel::set_queue_list($id,$site_id,$post_status,$post_time,$category);
                        }
                    }
                }
            }
        }
        //没有任何文章
        else{
            //首发文章
            if($start == '1'){
                self::normalpost($id,$site_id,$post_status,$post_time,$category,$start,$start_delay);
            }
            else{
                //普通正常发布
                self::normalpost($id,$site_id,$post_status,$post_time,$category);
            }
        }
        return true;
    }
    /*
    |--------------------------------------------------------------------------
    | 发布文章
    |--------------------------------------------------------------------------
   */
    public static function normalpost($id,$site_id,$post_status,$post_time,$category = null,$start = null,$start_delay = null){

        $site = DB::table('articles_site')->where('source_id',$id)->where('site_id',$site_id)->first(['id','start','start_delay','start_time']);
        $now  = now();

        //首发相关
        $startinfo = [];
        //首发相关信息写入 不可逆
        if(!is_null($start) && $start == '1' && !empty($start_delay) ){
            if(!(isset($site->id) && $site->start == '1')){
                $startinfo = [
                    'start'         => '1',
                    'start_delay'   => $start_delay
                ];
            }
        }
        //首发立即起效 立即发布 已经存在有效首发信息 或者 当前传入有效首发信息
        if($post_status == '1'){
            $excute_delay = 0;
            if(isset($site->start) && $site->start == '1' && !empty($site->start_delay)){
                $excute_delay = $site->start_delay;
            }
            if(!is_null($start) && $start == '1' && !empty($start_delay)){
                $excute_delay = $start_delay;
            }
            if(!!$excute_delay){
                //写入首发时间
                $startinfo['start_time'] = $now;
                //设置保鲜期过后任务
                StartCacheModel::set_excute_delay($id,date('Y-m-d H:i:s',time()+$excute_delay*60));
            }
        }
        //已存在该文章
        if(isset($site->id)){
            $update =  [
                'post_status'       => $post_status,
                'post_time'         => $post_status == '1' ? $now : $post_time,
                'contribute_status' => 1
            ];
            //分类信息
            if(!is_null($category))$update['category'] = $category;
            $update = array_merge($update,$startinfo);
            DB::table('articles_site')->where('id',$site->id)->update($update);
            $new_id = $site->id;
        }
        //不存在在站点新建文章
        else{
            $info = DB::table('articles_user')->where('id',$id)->first();
            $insert = [
                'site_id'           => $site_id,
                'source_id'         => $id,
                'author_id'         => $info->user_id,
                'title'             => $info->title,
                'summary'           => $info->summary,
                'content'           => $info->content,
                'tags'              => $info->tags,
                'image'             => $info->image,
                'hash'              => $info->hash,
                'post_status'       => $post_status,
                'contribute_status' => $post_status == '1' ? 1 : 0,
                'post_time'         => $post_status == '1' ? $now : $post_time,
                'create_time'       => $now,
                'update_time'       => $now
            ];
            //分类信息
            $insert['category'] = !is_null($category) ? $category :0 ;
            $insert = array_merge($insert,$startinfo);
            $new_id = DB::table('articles_site')->insertGetId($insert);
        }
        //定时发布设置定时器
        if($post_status == 2){
            TimingCacheModel::add($site_id,$new_id,$post_time);
        }
        //如果非定时发布 清除定时发布
        else{
            TimingCacheModel::clear($new_id);
        }
        //清除缓存
        CacheModel::clear_article_cache($site_id);
        return true;
    }
    /*
    |--------------------------------------------------------------------------
    | 定时发布文章
    |--------------------------------------------------------------------------
   */
    public static function timepost($site_article_id){
        $info = DB::table('articles_site')->where('id',$site_article_id)->first(['id','source_id','site_id']);
        if(isset($info->id)){
            self::normalpost($info->source_id,$info->site_id,1,now());
        }
    }
    /*
    |--------------------------------------------------------------------------
    | 站点管理员文章发布
    |--------------------------------------------------------------------------
   */
    public static function sitepost($site_article_id,$site_id,$category,$post_status,$post_time){
        $info = DB::table('articles_site')->where('id',$site_article_id)->where('site_id',$site_id)->first(['id','source_id','site_id','start','start_delay','start_time']);
        $update = [
            'category'          => $category,
            'post_status'       => $post_status,
            'post_time'         => $post_time,
            'contribute_status' => 1
        ];
        if(isset($info->id) && strtotime($info->start_time) <= 0 && $info->start == '1' && $post_status == '1'){
            //设置保鲜期过后任务
            StartCacheModel::set_excute_delay($info->source_id,date('Y-m-d H:i:s',time()+$info->start_delay*60));
            $update['start_time'] = now();
        }
        //如果定时发布 推到 任务
        if($post_status == 2){
            TimingCacheModel::add($site_id,$site_article_id,$post_time);
        }
        //如果非定时发布 清除定时发布
        else{
            TimingCacheModel::clear($site_article_id);
        }
        //清楚文章缓存
        CacheModel::clear_article_cache($site_id,$site_article_id);
        return DB::table('articles_site')->where('site_id',$site_id)->where('id',$site_article_id)->update($update);
    }
    /*
    |--------------------------------------------------------------------------
    | 文章发布信息及站点状态
    |--------------------------------------------------------------------------
   */
    public static function postinfo($id,$site_id){
        //获取用户文章在站点的列表
        $insite = ArticleSiteModel::get_user_article_in_site($id);
        //是否已有首发 可用首发  以及已经首发发布文章
        $ret['hasstart'] = false;
        //已有普通文章
        $ret['hasarticle'] = false;
        //首发站点;
        $ret['startid'] = 0;
        //首发发布
        $ret['startpost'] = false;
        //是否处于保鲜期
        $ret['infresh'] = false;
        //文章在该站点被删除
        $ret['deleted'] = false;
        //首发文章保鲜期
        $ret['delay'] = 0;
        //当前站点发布信息
        $ret['info'] = (object)[];
        if(!empty($insite)){
            foreach ($insite as $v){
                //文章在该站点被删除
                if($v->site_id == $site_id && ($v->deleted != '0'))$ret['deleted'] = true;
                //已有普通文章 未曾首发 未被删除
                if($v->deleted == '0' || ($v->deleted != '0' && strtotime($v->start_time) > 0) ){
                    $ret['hasarticle'] = true;
                }
                //已经设置首发
                if($v->start == '1'){
                    $ret['delay'] = $v->start_delay;
                }
                //已经首发发布
                if(strtotime($v->start_time) > 0){
                    $ret['hasstart'] = true;
                    $ret['startid']  = $v->site_id;
                    $ret['startpost'] = true;
                    //已经首发文章是否处于保鲜期
                    if(time() - strtotime($v->start_time) < intval($v->start_delay)*60){
                        $ret['infresh'] = true;
                    }
                    break;
                }
                //未首发发布 存在待首发文章
                if($v->deleted == '0' && $v->start == '1'){
                    $ret['hasstart'] = true;
                    $ret['startid']  = $v->site_id;
                    break;
                }
            }
            foreach ($insite as $v){
                //当前站点发布信息
                if($v->site_id == $site_id) $ret['info'] = $v;
            }
        }
        return $ret;
    }
    /*
    |--------------------------------------------------------------------------
    | 文章发布及投稿状态
    |--------------------------------------------------------------------------
   */
    public static function status($id,$site_id,$iscontribute = false){
        $post    = ArticlePostModel::postinfo($id,$site_id);
        //文章已被锁定
        if($post['deleted'])return false;
        //首发已关闭
        $startlock = $post['hasstart'] || $post['hasarticle'] || $post['startpost'];
        //首发站点信息
        $sitename = '';
        $sitelink = '';
        $sitedelay= $post['delay'];
        //文章是否已经首发 且超过保鲜期
        $site_post= $post['startpost'] && !$post['infresh'];

        if($startlock && !empty($post['startid'])){
            $startsite = SiteModel::get_site_info_list($post['startid']);
            if(isset($startsite->id)){
                $sitename = $startsite->name;
                $sitelink = site_home($startsite->custom_domain,$startsite->platform_domain);
            }
        }
        if(isset($post['info']->category)){
            $ret = [
                'start'         => $post['info']->start,
                'start_delay'   => $post['info']->start_delay,
                'site_name'     => $sitename,
                'site_link'     => $sitelink,
                'site_delay'    => $sitedelay,
                'site_post'     => $site_post,
                'start_lock'    => $startlock
            ];
            if(!$iscontribute){
                $ret['category']    = $post['info']->category;
                $ret['post_status'] = $post['info']->post_status == 1 ? 'now' : ($post['info']->post_status == 2 ? 'time' : 'cancel');
                $ret['post_time']   = $post['info']->post_time;
            }
        }
        else{
            $cache = StartCacheModel::get_queue_info($id,$site_id);
            if(!empty($cache) && !$cache['contribute']){
                $ret = [
                    'start'         => 0,
                    'start_delay'   => 30,
                    'site_name'     => $sitename,
                    'site_link'     => $sitelink,
                    'site_delay'    => $sitedelay,
                    'site_post'     => $site_post,
                    'start_lock'    => $startlock,
                ];
                if(!$iscontribute){
                    $ret['category']    = $cache['category'];
                    $ret['post_status'] = $cache['post_status'] == '1' ? 'now' : ($cache['post_status'] == 2 ? 'time' : 'cancel');
                    $ret['post_time']   = $cache['post_time'];
                }
            }
            else{
                $ret = [
                    'start'         => 0,
                    'start_delay'   => 30,
                    'site_name'     => $sitename,
                    'site_link'     => $sitelink,
                    'site_delay'    => $sitedelay,
                    'site_post'     => $site_post,
                    'start_lock'    => $startlock,
                ];
                if(!$iscontribute){
                    $ret['category']    = 0;
                    $ret['post_status'] = 'cancel';
                    $ret['post_time']   = now();
                }
            }

        }
        return $ret;
    }

}