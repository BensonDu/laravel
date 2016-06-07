<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/27
 * Time: 下午10:32
 */

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommentModel extends Model
{
    protected $table = 'comment';
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | 获取文章评论
    |--------------------------------------------------------------------------
    */
    public static function getArticleComments($site_id,$uid,$article_id,$order = 'desc'){
        $select = [
            'comment.id',
            'comment.site_id',
            'comment.user_id',
            'comment.article_id',
            'comment.parent',
            'comment.root',
            'comment.content',
            'comment.likes',
            'comment.time',
            'comment.deleted',
            'comment_hide.valid AS hide',
            'comment_like.valid AS like',
            'site_info.name',
            'site_routing.custom_domain',
            'site_routing.platform_domain'
        ];
        return CommentModel::leftJoin('site_info','site_info.id','=','comment.site_id')
            ->leftJoin('site_routing','site_routing.site_id','=','comment.site_id')
            ->leftJoin('comment_hide', function($join) use ($site_id){
                $join->on('comment_hide.comment_id', '=', 'comment.id');
                $join->on('comment_hide.site_id', '=', DB::raw($site_id));
            })
            ->leftJoin('comment_like', function($join) use ($uid){
                $join->on('comment_like.comment_id', '=', 'comment.id');
                $join->on('comment_like.user_id', '=', DB::raw($uid));
            })
            ->where('comment.article_id',$article_id)
            ->orderBy('comment.id',$order)
            ->get($select);
    }
    /*
    |--------------------------------------------------------------------------
    | 获取文章评论 管理端
    |--------------------------------------------------------------------------
    */
    public static function getComments($site_id, $skip, $take, $order = 'desc', $inside = true){
        $select = [
            'comment.id',
            'comment.user_id',
            'comment.content',
            'comment.time',
            'articles_site.id AS article_id',
            'users.nickname',
            'site_routing.custom_domain',
            'site_routing.platform_domain',
        ];
        $query = CommentModel::leftJoin('users','comment.user_id','=','users.id')
            ->leftJoin('articles_site','articles_site.source_id','=','comment.article_id')
            ->leftJoin('site_routing','site_routing.site_id','=','comment.site_id');
        if($inside){
            $query->where('comment.site_id',$site_id)
                ->where('comment.deleted','0')
                ->where('articles_site.site_id',$site_id);
        }
        else{
            $hide = DB::table('comment_hide')->where('site_id',$site_id)->where('valid','1')->get(['id']);
            $ids = [];
            foreach ($hide as $v){
                $ids[] = $v->id;
            }
            $query->whereNotIn('comment.id',$ids)->where('comment.site_id','!=',$site_id)->groupBy('comment.id');
        }
        return $query->orderBy('comment.time',$order)
            ->take($take)
            ->skip($skip)
            ->get($select);
    }
    /*
    |--------------------------------------------------------------------------
    | 获取文章评论总数 管理端
    |--------------------------------------------------------------------------
    */
    public static function getCommentsCount($site_id, $inside = true){

        $query = CommentModel::leftJoin('users','comment.user_id','=','users.id')
            ->leftJoin('articles_site','articles_site.source_id','=','comment.article_id');
        if($inside){
            $query->where('comment.site_id',$site_id)
                ->where('comment.deleted','0')
                ->where('articles_site.site_id',$site_id);;
        }
        else{
            $hide = DB::table('comment_hide')->where('site_id',$site_id)->where('valid','1')->get(['id']);
            $ids = [];
            foreach ($hide as $v){
                $ids[] = $v->id;
            }
            $query->whereNotIn('comment.id',$ids)->where('comment.site_id','!=',$site_id)->groupBy('comment.id');
        }
        return count($query->get(['comment.id']));
    }
    /*
    |--------------------------------------------------------------------------
    | 获取用户最近评论时间
    |--------------------------------------------------------------------------
    */
    public static function getUserLatestComment($site_id,$user_id){
        return CommentModel::where('user_id',$user_id)->where('site_id',$site_id)->orderBy('id','desc')->limit(20)->get();
    }
    /*
    |--------------------------------------------------------------------------
    | 添加文章评论
    |--------------------------------------------------------------------------
    */
    public static function addArticleComment($site_id,$article_id,$user_id,$content,$parent){
        $root = 0;
        if($parent != '0'){
            $p = CommentModel::where('id',$parent)->first();
            $root = !empty($p->root) ? $p->root : (!empty($p->id) ? $p->id : 0);
        }
        return CommentModel::insert([
            'site_id'       => $site_id,
            'article_id'    => $article_id,
            'user_id'       => $user_id,
            'content'       => $content,
            'parent'        => $parent,
            'root'          => $root,
            'time'          => now()
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | 赞评论
    |--------------------------------------------------------------------------
    */
    public static function likeComment($comment_id,$user_id,$valid){
        $like = DB::table('comment_like')->where('comment_id', $comment_id)->where('user_id',$user_id)->first();
        $query = DB::table('comment_like');
        if(isset($like->id)){
            $query->where('comment_id', $comment_id)->where('user_id',$user_id)->update(['valid'=>$valid]);
        }
        else{
            $query->insert([
                'comment_id'=>  $comment_id,
                'user_id'   =>  $user_id,
                'valid'     =>  $valid
            ]);
        }
        //点赞数同步到评论表
        if($valid == 1){
            CommentModel::where('id',$comment_id)->increment('likes');
        }
        else{
            $comment = CommentModel::where('id',$comment_id)->first();
            if(isset($comment->likes) && $comment->likes >0){
                CommentModel::where('id',$comment_id)->update(['likes'=>$comment->likes-1]);
            }
        }

    }
    /*
    |--------------------------------------------------------------------------
    | 隐藏评论
    |--------------------------------------------------------------------------
    */
    public static function hideComment($site_id,$comment_id,$valid =1){
        $hide = DB::table('comment_hide')->where('site_id',$site_id)->where('comment_id',$comment_id)->first();
        if(isset($hide->id)){
            DB::table('comment_hide')->where('site_id',$site_id)->where('comment_id',$comment_id)->update(['valid'=>$valid]);
        }
        else{
            DB::table('comment_hide')->insert([
                'site_id'   =>  $site_id,
                'comment_id'=>  $comment_id
            ]);
        }
    }
    /*
    |--------------------------------------------------------------------------
    | 删除评论
    |--------------------------------------------------------------------------
    */
    public static function deleteComment($comment_id,$deleted = 1){
        return CommentModel::where('id',$comment_id)->update(['deleted'=>$deleted]);
    }
    /*
    |--------------------------------------------------------------------------
    | 检查是否拥有管理该评论权限 评论对应站点管理员
    |--------------------------------------------------------------------------
    */
    public static function checkCommentAuth($user_id,$comment_id){
        $check = false;
        $comment = DB::table('comment')->where('id',$comment_id)->first();
        if(isset($comment->site_id)){
            $role = SiteModel::user_site_role($comment->site_id,$user_id);
            if($role > 1) $check = true;
        }
        return $check;
    }
}