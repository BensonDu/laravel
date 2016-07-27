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

class TagController extends PlatformController
{
    /*
    |--------------------------------------------------------------------------
    | 平台标签首页
    |--------------------------------------------------------------------------
    |
    */
    public function index($tag = null){
        //默认站点
        $default = request('site');
        //站点列表
        $data['filter']['sites']= self::hotsites();
        //视图数据
        $data = [
            'tag'       => $tag,
            'list'      => (!empty($tag) && empty($default)) ? self::getlist($tag) : [],
            'total'     => (!empty($tag) && empty($default)) ? ArticleSiteModel::tag_article_count($tag) : 0
        ];
        //站点列表
        $data['filter']['sites']= self::hotsites();
        $data['base']['title'] = '标签-'.$tag;
        return self::view('platform.tag',$data);
    }
    /*
    |--------------------------------------------------------------------------
    | 平台标签-->接口
    |--------------------------------------------------------------------------
    |
    */
    public function tags($tag = null){
        $request    = request();
        $index      = $request->input('index');
        $skip       = intval($index)*10;
        //站点列表
        $sites      = $request->input('sites');
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
            'total' => !empty($tag) ? ArticleSiteModel::tag_article_count($tag,$sites) : 0,
            'list'  => !empty($tag) ? self::getlist($tag,$skip,$sites) : []
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | 获得列表
    |--------------------------------------------------------------------------
    |
    */
    private static function getlist($tag,$skip = 0,$sites = null){
        return self::format(ArticleSiteModel::tag_article(self::filter($tag), $skip, 10, $sites));
    }
    /*
    |--------------------------------------------------------------------------
    | 搜索结果格式化
    |--------------------------------------------------------------------------
    |
    */
    private static function format($list){
        $list = self::unique($list);
        foreach($list as $k =>$v){
            $tags = [];
            $tag = tag($v['tags']);
            foreach($tag as $vv){
                $tags[] = [
                    'item'  => $vv,
                    'color' => rand_color()
                ];
            }
            //站点域名
            $list[$k]['domain']      = site_home($v['custom_domain'],$v['platform_domain']);
            //站点名称
            $list[$k]['name']        = $v['name'];
            $list[$k]['title']       = $v['title'];
            $list[$k]['summary']     = $v['summary'];
            $list[$k]['nickname']    = $v['nickname'];
            $list[$k]['avatar']      = avatar($v['avatar']);
            $list[$k]['user_id']     = $v['user_id'];
            $list[$k]['tags']        = $tags;
            $list[$k]['post_time']   = time_down(strtotime($v['post_time']));
        }
        return $list;
    }
    /*
    |--------------------------------------------------------------------------
    | 结果去重
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
    | 关键词过滤
    |--------------------------------------------------------------------------
    */
    private static  function filter($k){
        $k = str_ireplace("T@G","",$k);
        $k = str_ireplace("*","\*",$k);
        $k = str_ireplace("%","\%",$k);
        return $k;
    }

}