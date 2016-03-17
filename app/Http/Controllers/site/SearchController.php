<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/19
 * Time: 下午2:40
 */

namespace App\Http\Controllers\Site;


use App\Http\Model\ArticleSiteModel;

class SearchController extends SiteController
{
    public function index($keyword = null){
        $data['search'] = [
            'keyword'   => $keyword,
            'list'      => !empty($keyword) ? json_encode_safe($this->get_list($keyword)) : '[]',
            'total'     => !empty($keyword) ? ArticleSiteModel::search_article_count($_ENV['site_id'],$keyword) : 0
        ];

        return self::view('site.search',$data);
    }
    public function results($keyword = null){
        $index      = request()->input('index');
        $skip =intval($index)*10;
        $list = empty($keyword) ? [] : $this->get_list($keyword,$skip);
        return self::ApiOut(0,[
            'list'  => $list
        ]);
    }
    private function get_list($keyword,$skip = 0){
        return $this->format(ArticleSiteModel::search_article($_ENV['site_id'], $keyword, $skip),$keyword);
    }
    private function format($list ,$keyword){
        $ret = [];
        if(!empty($list)){
            foreach($list as $k => $v){
                $ret[$k]['create_time'] = date('Y-m-d',strtotime($v->create_time));
                $ret[$k]['article_id']   = $v->article_id;
                $ret[$k]['title']       = str_ireplace($keyword,'<b>'.$keyword.'</b>',$v->title);
                $ret[$k]['summary']     = str_ireplace($keyword,'<b>'.$keyword.'</b>',$v->summary);
                $ret[$k]['nickname']    = str_ireplace($keyword,'<b>'.$keyword.'</b>',$v->nickname);
                $ret[$k]['user_url']    = user_url($v->user_id);
            }
        }
        return $ret;
    }

}