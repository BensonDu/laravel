<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/19
 * Time: 下午2:40
 */

namespace App\Http\Controllers\Platform;

use App\Http\Model\ArticleSiteModel;
use App\Http\Model\Option\PlatformModel;
use App\Http\Model\SiteModel;

class SearchController extends PlatformController
{
    /*
    |--------------------------------------------------------------------------
    | 平台搜索首页
    |--------------------------------------------------------------------------
    |
    */
    public function index(){
        //实际请求关键词
        $keyword = request('keyword');
        //用于搜索字符已识别通配符;
        $like = self::wildcard($keyword);
        //站点标题
        $data['base']['title'] = '搜索-'.$keyword;
        //站点列表
        $data['filter']['sites']= self::hotsites();
        //视图数据
        $data['search'] = [
            'keyword'   => $keyword,
            'list'      => !empty($like) ? $this->getlist($like,0,$keyword) : [],
            'total'     => !empty($like) ? ArticleSiteModel::search_article_count($like) : 0
        ];
        return self::view('platform.search',$data);
    }
    /*
    |--------------------------------------------------------------------------
    | 平台搜索结果-->接口
    |--------------------------------------------------------------------------
    |
    */
    public function results(){
        $request = request();
        //实际请求关键词
        $keyword = $request->input('keyword');
        //用于搜索字符已识别通配符;
        $like = self::wildcard($keyword);
        //请求分页
        $index   = $request->input('index');
        $skip =intval($index)*10;
        //站点列表
        $sites   = $request->input('sites');
        if(is_array($sites) && !empty($sites)){
            $safe = [];
            foreach ($sites as $v){
                $safe[] = intval($v);
            }
        }
        else{
            $sites = null;
        }
        return self::ApiOut(0,[
            'list'  => empty($like) ? [] : $this->getlist($like,$skip,urldecode($keyword),$sites),
            'total' => !empty($like) ? ArticleSiteModel::search_article_count($like,$sites) : 0
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | 获得列表
    |--------------------------------------------------------------------------
    |
    */
    private function getlist($keyword,$skip = 0,$origin,$sites = null){
        return self::format(ArticleSiteModel::search_article($keyword, $skip, 10, $sites),$origin);
    }
    /*
    |--------------------------------------------------------------------------
    | 搜索结果格式化
    |--------------------------------------------------------------------------
    |
    */
    private static function format($list,$keyword){
        $ret    = [];
        //关键词 包含空格 拆成关键词数组
        if(strpos($keyword," ")){
            $keyword = explode(" ",$keyword);
        }
        //去重结果
        $list = self::unique($list);

        foreach($list as $k => $v){

            //文章ID
            $ret[$k]['id']          = $v['id'];
            //格式化发布时间
            $ret[$k]['post_time']   = date('Y-m-d',strtotime($v['post_time']));
            //站点域名
            $ret[$k]['domain']      = site_home($v['custom_domain'],$v['platform_domain']);
            //站点名称
            $ret[$k]['name']        = $v['name'];
            //用户ID
            $ret[$k]['user_id']     = $v['user_id'];

            $title                  = $v['title'];
            $summary                = $v['summary'];
            $nickname               = $v['nickname'];
            if(!is_array($keyword)){
                $ret[$k]['title']       = str_ireplace($keyword,'<b>'.$keyword.'</b>',$title);
                $ret[$k]['summary']     = str_ireplace($keyword,'<b>'.$keyword.'</b>',$summary);
                $ret[$k]['nickname']    = str_ireplace($keyword,'<b>'.$keyword.'</b>',$nickname);
            }
            else{
                foreach ($keyword as $vv){
                    $title  = str_ireplace($vv,'<b>'.$vv.'</b>',$title);
                    $summary = str_ireplace($vv,'<b>'.$vv.'</b>',$summary);
                    $nickname = str_ireplace($vv,'<b>'.$vv.'</b>',$nickname);
                }
                $ret[$k]['title']       = $title;
                $ret[$k]['summary']     = $summary;
                $ret[$k]['nickname']    = $nickname;
            }
        }

        return $ret;
    }
    /*
    |--------------------------------------------------------------------------
    | 搜索结果去重
    |--------------------------------------------------------------------------
    |
    */
    private static function unique($list){
        $ret    = [];
        $list   = array_reverse($list);
        $source = [];
        foreach ($list as $v){
            if(in_array($v['source_id'],$source))continue;
            $ret[] = $v;
            $source[] = $v['source_id'];
        }
        return array_reverse($ret);
    }
    /*
    |--------------------------------------------------------------------------
    | 热门站点列表
    |--------------------------------------------------------------------------
    |
    */
    private static function hotsites(){
        $hot   = PlatformModel::get_nav_site_id_list();
        $sites = SiteModel::get_site_info_list(null,['id','name']);
        $retfront = [];
        $retback  = [];
        $hotsite  = [];
        foreach ($sites as $v){
            if(!in_array($v->id,$hot)){
                $retback[] = $v;
            }
            else{
                $hotsite[] = $v;
            }
        }
        foreach ($hot as $v){
            foreach ($hotsite as $vv){
                if($v == $vv['id'])$retfront[] = $vv;
            }
        }
        return array_merge($retfront,$retback);
    }
    /*
    |--------------------------------------------------------------------------
    | 关键词过滤 通配符识别
    |--------------------------------------------------------------------------
    */
    private static  function wildcard($k){
        $k = urldecode($k);
        $k = str_ireplace("*","\*",$k);
        $k = str_ireplace("%","\%",$k);
        $k = str_ireplace(" ","%",$k);
        $k = str_ireplace("_","\_",$k);
        return $k;
    }
}